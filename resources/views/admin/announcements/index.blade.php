<x-app-layout>
    @section('header_title', 'Announcements')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Pusat Informasi</h2>
                <p class="text-slate-500 text-[13px] mt-0.5">Kelola berita, pembaruan, dan informasi penting untuk penghuni.</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                <i class="fas fa-bullhorn text-[10px]"></i>
                Buat Pengumuman
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="badge badge-green w-full justify-start p-3 rounded-xl border border-emerald-100">
                <i class="fas fa-check-circle mr-2"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== TABLE SECTION ===== --}}
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Informasi & Konten</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $item)
                        <tr>
                            <td>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-brand-light text-brand flex items-center justify-center font-bold text-xs mt-0.5">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 truncate max-w-md">{{ $item->title }}</div>
                                        <div class="text-[11px] text-slate-500 line-clamp-1 mb-1">{{ $item->content }}</div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $item->created_at->format('d M Y, H:i') }}</span>
                                            <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                            <span class="text-[10px] font-bold text-brand uppercase tracking-tighter">Target: {{ ucfirst($item->target_role) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $typeBadge = [
                                        'info' => 'badge-purple',
                                        'update' => 'badge-green',
                                        'warning' => 'badge-amber',
                                        'danger' => 'badge-red',
                                    ][$item->type] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $typeBadge }}">{{ ucfirst($item->type) }}</span>
                            </td>
                            <td class="text-center">
                                @if($item->is_active)
                                    <span class="badge badge-green !bg-emerald-50 !text-emerald-600 border-emerald-100">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                        Published
                                    </span>
                                @else
                                    <span class="badge badge-gray">Draft</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.announcements.edit', $item->id) }}" class="nav-icon-btn" title="Edit">
                                        <i class="fas fa-edit text-[11px]"></i>
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="nav-icon-btn hover:!text-red-600 hover:!bg-red-50">
                                            <i class="fas fa-trash-alt text-[11px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-comment-slash"></i></div>
                                    <p>Belum ada pengumuman yang diterbitkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="py-2">
            {!! $announcements->links() !!}
        </div>
    </div>
</x-app-layout>
