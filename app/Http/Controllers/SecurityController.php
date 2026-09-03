<?php

namespace App\Http\Controllers;

use App\Models\SecurityReport;
use App\Models\SecurityShift;
use App\Models\RentPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SecurityController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $todayShift = SecurityShift::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $recentReports = SecurityReport::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $myAttendances = \App\Models\SecurityAttendance::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        $lastAttendance = $myAttendances->first();
        $nextType = (!$lastAttendance || $lastAttendance->type === 'out') ? 'in' : 'out';

        return view('security.dashboard', compact('todayShift', 'recentReports', 'myAttendances', 'nextType'));
    }


    public function report()
    {
        $user = auth()->user();
        $todayShift = SecurityShift::where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->first();

        if (!$todayShift) {
            return redirect()->route('security.dashboard')->with('error', 'Anda tidak memiliki jadwal shift hari ini untuk membuat laporan.');
        }

        return view('security.report', compact('todayShift'));
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'incident_date' => 'required|date',
            'attachment' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['location', 'title', 'description', 'incident_date']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('security_attachments', 'public');
            $data['attachment'] = $path;
        }

        SecurityReport::create($data);

        return redirect()->route('security.dashboard')->with('success', 'Laporan keamanan berhasil dikirim.');
    }

    public function shifts()
    {
        $shifts = SecurityShift::where('user_id', auth()->id())
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get();

        return view('security.shifts', compact('shifts'));
    }

    // Admin Methods
    public function adminIndex(Request $request)
    {
        $adminUser    = auth()->user();
        $isSuperAdmin = $adminUser->isSuperAdmin();

        $search = $request->query('search');
        $month  = $request->query('month', now()->format('Y-m'));

        $securityStaff = User::where('role', 'security')
            ->when(!$isSuperAdmin, function ($q) use ($adminUser) {
                $q->where('district', $adminUser->district ?? 'NOT_SET');
            })
            ->when($search, function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->get();

        $staffIds = $securityStaff->pluck('id');

        $allReports = SecurityReport::with('user')
            ->whereIn('user_id', $staffIds)
            ->latest()->get();

        $rawShifts = SecurityShift::with('user')
            ->whereIn('user_id', $staffIds)
            ->orderBy('user_id')->orderBy('date')->get();

        $compactShifts = [];

        if ($rawShifts->count() > 0) {
            $currentGroup = null;
            foreach ($rawShifts as $shift) {
                $key = $shift->user_id . '-' . $shift->location . '-' . $shift->start_time . '-' . $shift->end_time;

                if (!$currentGroup || $currentGroup['key'] !== $key || Carbon::parse($currentGroup['end_date'])->addDay()->toDateString() !== $shift->date) {
                    if ($currentGroup) $compactShifts[] = $currentGroup;
                    $currentGroup = [
                        'key'        => $key,
                        'user'       => $shift->user,
                        'location'   => $shift->location,
                        'start_time' => $shift->start_time,
                        'end_time'   => $shift->end_time,
                        'start_date' => $shift->date,
                        'end_date'   => $shift->date,
                    ];
                } else {
                    $currentGroup['end_date'] = $shift->date;
                }
            }
            $compactShifts[] = $currentGroup;
        }

        // Properties: scope to admin district if applicable
        $propertiesQuery = \App\Models\Room::select('property_name')->distinct();
        if (!$isSuperAdmin) {
            $propertiesQuery->where('district', $adminUser->district ?? 'NOT_SET');
        }
        $properties = $propertiesQuery->pluck('property_name');

        // Filter attendances by month, search, and district-scoped staff
        $rawAttendances = \App\Models\SecurityAttendance::with('user')
            ->whereHas('user', function($q) use ($search, $isSuperAdmin, $adminUser) {
                if ($search) $q->where('name', 'like', '%' . $search . '%');
                if (!$isSuperAdmin) $q->where('district', $adminUser->district ?? 'NOT_SET');
            })
            ->where('created_at', 'like', $month . '%')
            ->latest()
            ->get();

        $allAttendances = [];
        foreach ($rawAttendances as $att) {
            $date   = $att->created_at->toDateString();
            $userId = $att->user_id;
            $key    = $userId . '-' . $date;

            if (!isset($allAttendances[$key])) {
                $allAttendances[$key] = [
                    'user'     => $att->user,
                    'date'     => $date,
                    'location' => $att->location,
                    'in'       => null,
                    'out'      => null,
                ];
            }

            if ($att->type === 'in' && !$allAttendances[$key]['in']) {
                $allAttendances[$key]['in'] = $att;
            } elseif ($att->type === 'out' && !$allAttendances[$key]['out']) {
                $allAttendances[$key]['out'] = $att;
            }
        }

        return view('admin.security.index', compact('securityStaff', 'allReports', 'compactShifts', 'properties', 'allAttendances'));
    }

    public function adminStoreShift(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Loop through each date in the range
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            SecurityShift::create([
                'user_id' => $request->user_id,
                'location' => $request->location,
                'date' => $currentDate->toDateString(),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
            $currentDate->addDay();
        }

        return redirect()->back()->with('success', 'Jadwal shift berhasil ditambahkan untuk periode tersebut.');
    }

    public function adminStoreStaff(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $admin = auth()->user();

        \App\Models\User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => \Illuminate\Support\Facades\Hash::make($request->password),
            'role'              => 'security',
            'district'          => $admin->district, // Inherit district from registering admin
            'email_verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Akun Satpam baru berhasil dibuat.');
    }

    public function attendance()
    {
        $user = auth()->user();
        $today = now()->toDateString();
        
        $todayShift = SecurityShift::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$todayShift) {
            return redirect()->route('security.dashboard')->with('error', 'Anda tidak memiliki jadwal shift hari ini (' . $today . '). Silakan hubungi Admin untuk dibuatkan jadwal.');
        }

        // Determine if it's check-in or check-out
        $lastAttendance = \App\Models\SecurityAttendance::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->latest()
            ->first();
        
        $nextType = (!$lastAttendance || $lastAttendance->type === 'out') ? 'in' : 'out';

        return view('security.attendance', compact('todayShift', 'nextType'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'image_data' => 'required|string',
            'type' => 'required|in:in,out',
        ]);

        try {
            $imgData = $request->image_data;
            
            if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $typeMatch)) {
                $imgData = substr($imgData, strpos($imgData, ',') + 1);
                $ext = strtolower($typeMatch[1]);

                $imgData = str_replace(' ', '+', $imgData);
                $data = base64_decode($imgData);

                $fileName = 'security_attendances/' . uniqid() . '.' . $ext;
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $data);

                \App\Models\SecurityAttendance::create([
                    'user_id' => auth()->id(),
                    'image' => $fileName,
                    'type' => $request->type,
                    'location' => $request->location,
                    'attendance_time' => now(),
                    'note' => $request->note,
                ]);

                // Logic for status message and voice
                $todayShift = SecurityShift::where('user_id', auth()->id())
                    ->whereDate('date', now()->toDateString())
                    ->first();
                
                $isLate = false;
                $isEarly = false;
                $timeStr = now()->format('H:i');
                $msg = 'Absensi Berhasil pada pukul ' . $timeStr;

                if ($todayShift) {
                    $now = now();
                    if ($request->type === 'in') {
                        $startTime = Carbon::createFromFormat('H:i:s', $todayShift->start_time);
                        if ($now->greaterThan($startTime)) {
                            $isLate = true;
                            $msg = 'Anda datang terlambat pada pukul ' . $timeStr . ', jangan lupa absen pulang.';
                        } else {
                            $msg = 'Anda datang pada pukul ' . $timeStr . ', jangan lupa absen pulang.';
                        }
                    } else {
                        $endTime = Carbon::createFromFormat('H:i:s', $todayShift->end_time);
                        if ($now->lessThan($endTime)) {
                            $isEarly = true;
                            $msg = 'Anda pulang lebih awal pada pukul ' . $timeStr . '.';
                        } else {
                            $msg = 'Anda pulang pada pukul ' . $timeStr . ', terima kasih.';
                        }
                    }
                }

                return redirect()->route('security.dashboard')->with([
                    'success' => $msg,
                    'play_success_sound' => true,
                    'is_late' => $isLate,
                    'is_early' => $isEarly,
                    'attendance_type' => $request->type
                ]);
            } else {
                throw new \Exception('Data gambar tidak valid.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses absensi: ' . $e->getMessage());
        }
    }
}
