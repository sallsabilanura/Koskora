<x-app-layout>
    @section('header_title', 'Dashboard Overview')

    <div class="space-y-6 md:space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
            <!-- Total Rooms -->
            <div class="stat-card flex items-center justify-between">
                <div class="min-w-0">
                    <div class="stat-title uppercase tracking-wider font-semibold truncate">Total Rooms</div>
                    <div class="stat-value">{{ $totalRooms }}</div>
                </div>
                <div class="p-2.5 md:p-3 bg-blue-50 text-blue-600 rounded-xl flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>

            <!-- Available Rooms -->
            <div class="stat-card flex items-center justify-between">
                <div class="min-w-0">
                    <div class="stat-title uppercase tracking-wider font-semibold text-emerald-600 truncate">Available</div>
                    <div class="stat-value">{{ $availableRooms }}</div>
                </div>
                <div class="p-2.5 md:p-3 bg-emerald-50 text-emerald-600 rounded-xl flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Total Tenants -->
            <div class="stat-card flex items-center justify-between">
                <div class="min-w-0">
                    <div class="stat-title uppercase tracking-wider font-semibold truncate">Active Tenants</div>
                    <div class="stat-value">{{ $totalTenants }}</div>
                </div>
                <div class="p-2.5 md:p-3 bg-purple-50 text-purple-600 rounded-xl flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="stat-card flex items-center justify-between">
                <div class="min-w-0">
                    <div class="stat-title uppercase tracking-wider font-semibold text-blue-600 truncate">Revenue</div>
                    <div class="stat-value text-base md:text-2xl">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="p-2.5 md:p-3 bg-blue-50 text-blue-600 rounded-xl flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Announcements -->
            <div class="stat-card flex items-center justify-between col-span-2 lg:col-span-4">
                <div class="min-w-0">
                    <div class="stat-title uppercase tracking-wider font-semibold text-indigo-600 truncate">Announcements</div>
                    <div class="stat-value">{{ $announcementsCount }}</div>
                </div>
                <div class="p-2.5 md:p-3 bg-indigo-50 text-indigo-600 rounded-xl flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-4 md:px-6 py-4 md:py-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-base md:text-lg font-bold text-slate-800">Recent Payments</h3>
                <a href="{{ route('rent-payments.index') }}" class="text-xs md:text-sm font-semibold text-blue-600 hover:text-blue-700">View All</a>
            </div>

            <!-- Desktop Table -->
            <div class="overflow-x-auto desktop-table">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tenant</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Room</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Amount</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($recentPayments as $payment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $payment->tenants->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $payment->room->room_number }}</td>
                                <td class="px-6 py-4 text-slate-500 text-sm">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-slate-800">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500 italic">
                                    No recent payments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="mobile-cards p-3">
                @forelse($recentPayments as $payment)
                    <div class="mobile-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-bold text-slate-800 text-sm">{{ $payment->tenants->name }}</div>
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <div>
                                <div class="text-slate-400 font-medium mb-0.5">Room</div>
                                <div class="font-bold text-slate-700">{{ $payment->room->room_number }}</div>
                            </div>
                            <div>
                                <div class="text-slate-400 font-medium mb-0.5">Date</div>
                                <div class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-slate-400 font-medium mb-0.5">Amount</div>
                                <div class="font-bold text-brand-blue">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 italic text-sm">
                        No recent payments found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
