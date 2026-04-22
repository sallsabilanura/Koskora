<x-app-layout>
    @section('header_title', 'Room Details')

    <div class="max-w-6xl mx-auto space-y-8">
        {{-- Breadcrumb --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                @if($room->property_name)
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $room->property_name }}</p>
                @endif
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Kamar {{ $room->room_number }}</h2>
            </div>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('rooms.edit', $room->id) }}" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-900 transition-all shadow-lg shadow-slate-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('rooms.index') }}" class="px-5 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            {{-- Gallery Grid --}}
            <div class="space-y-4">
                @if($room->picture && count($room->picture) > 0)
                    {{-- Main Featured Image --}}
                    <div class="aspect-[16/10] rounded-[2rem] overflow-hidden border border-slate-100 shadow-2xl relative group">
                        <img id="main-picture" src="{{ asset('storage/' . $room->picture[0]) }}" class="w-full h-full object-cover transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-none"></div>
                    </div>

                    {{-- Thumbnails --}}
                    <div class="grid grid-cols-4 md:grid-cols-5 gap-3">
                        @foreach($room->picture as $index => $img)
                            <div class="aspect-square rounded-2xl overflow-hidden border-2 {{ $index == 0 ? 'border-blue-500' : 'border-transparent' }} cursor-pointer hover:border-blue-300 transition-all thumbnail-card" onclick="changeMainImage('{{ asset('storage/' . $img) }}', this)">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="aspect-[16/10] rounded-[2rem] bg-slate-50 flex flex-col items-center justify-center border-4 border-dashed border-slate-100 italic text-slate-400 space-y-3">
                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-bold">Belum ada foto untuk kamar ini.</span>
                    </div>
                @endif
            </div>

            {{-- Details Info --}}
            <div class="space-y-6">
                {{-- Status & Type --}}
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-100 border border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-4 py-1.5 bg-blue-50 text-blue-700 text-xs font-black uppercase tracking-widest rounded-full border border-blue-100">
                            {{ $room->room_type }}
                        </span>
                        @php
                            $statusClasses = [
                                'available'   => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'occupied'    => 'bg-rose-100 text-rose-700 border-rose-200',
                                'maintenance' => 'bg-amber-100 text-amber-700 border-amber-200',
                            ];
                        @endphp
                        <div class="flex items-center space-x-2">
                            @if($room->gender == 'putra')
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-full uppercase tracking-widest border border-blue-100">♂ Putra</span>
                            @elseif($room->gender == 'putri')
                                <span class="px-3 py-1 bg-pink-50 text-pink-700 text-[10px] font-black rounded-full uppercase tracking-widest border border-pink-100">♀ Putri</span>
                            @else
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 text-[10px] font-black rounded-full uppercase tracking-widest border border-purple-100">⚤ Gabungan</span>
                            @endif
                            <span class="px-3 py-1 {{ $statusClasses[$room->status] ?? 'bg-slate-100 border-slate-200' }} text-[10px] font-black rounded-full uppercase tracking-widest border">
                                {{ $room->status }}
                            </span>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="mb-6">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Sewa</div>
                        <div class="text-4xl font-black text-slate-800 tracking-tight">
                            Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-lg font-normal text-slate-400">/bulan</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($room->description)
                    <div class="pt-5 border-t border-slate-50">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tentang Kamar</div>
                        <div class="text-slate-600 leading-relaxed font-medium whitespace-pre-line text-sm">{{ $room->description }}</div>
                    </div>
                    @endif

                    {{-- Assets --}}
                    @if($room->assets->count() > 0)
                        <div class="pt-5 border-t border-slate-50">
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Fasilitas & Aset</div>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($room->assets as $asset)
                                    <div class="flex items-center p-3 bg-slate-50 rounded-2xl border border-slate-100 space-x-3">
                                        <div class="w-8 h-8 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-500 flex-shrink-0">
                                            @if($asset->icon)
                                                <i class="{{ $asset->icon }} text-xs"></i>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">{{ $asset->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Booking Button --}}
                    @if($room->status == 'available' && !auth()->user()->isAdmin())
                        <div class="pt-6">
                            <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl font-black text-lg hover:from-blue-700 hover:to-blue-800 shadow-2xl shadow-blue-100 transition-all flex items-center justify-center gap-3 group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                SEWA SEKARANG
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============= REVIEWS SECTION ============= --}}
        @php
            $reviews = $room->reviews()->with('user')->latest()->get();
            $avgRating = $reviews->avg('rating') ?? 0;
            $totalReviews = $reviews->count();
        @endphp

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-100 overflow-hidden">
            {{-- Reviews Header --}}
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Ulasan Penghuni</h3>
                    <p class="text-sm text-slate-400 font-medium mt-0.5">{{ $totalReviews }} ulasan dari penghuni</p>
                </div>
                @if($totalReviews > 0)
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-2xl px-5 py-3">
                        <div class="text-3xl font-black text-amber-600">{{ number_format($avgRating, 1) }}</div>
                        <div>
                            <div class="flex items-center gap-0.5 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Rating</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Reviews List --}}
            <div class="divide-y divide-slate-50">
                @forelse($reviews as $review)
                    <div class="px-8 py-6 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start gap-4">
                            {{-- Avatar --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-black text-white text-sm shadow-sm">
                                @if($review->is_anonymous)
                                    ?
                                @else
                                    {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm">
                                            {{ $review->is_anonymous ? 'Penghuni Anonim' : ($review->user->name ?? 'Penghuni') }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-slate-600 leading-relaxed font-medium">{{ $review->comment }}</p>
                                @else
                                    <p class="text-sm text-slate-400 italic">Tidak ada komentar.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-8 py-16 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <p class="text-slate-400 font-bold text-sm">Belum ada ulasan untuk kamar ini.</p>
                        <p class="text-slate-300 text-xs mt-1">Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                @endforelse
            </div>

            {{-- Write a Review (for tenants who have rented this room) --}}
            @auth
                @php
                    $user = auth()->user();
                    $tenant = $user->tenant ?? null;
                    $hasRentedThis = $tenant ? \App\Models\Rental::where('tenant_id', $tenant->id)->where('room_id', $room->id)->exists() : false;
                    $myReview = $tenant ? $room->reviews()->where('user_id', $user->id)->first() : null;
                @endphp
                @if($hasRentedThis && !$user->isAdmin())
                    <div class="px-8 py-8 bg-slate-50/50 border-t border-slate-100">
                        <h4 class="text-base font-black text-slate-800 mb-6 tracking-tight">
                            {{ $myReview ? 'Edit Ulasan Anda' : 'Tulis Ulasan' }}
                        </h4>
                        <form action="{{ route('room-reviews.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">

                            {{-- Star Rating --}}
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Rating</label>
                                <div class="flex items-center gap-2" id="star-rating-group">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ ($myReview && $myReview->rating == $i) ? 'checked' : '' }}>
                                            <svg class="w-8 h-8 transition-all duration-150 star-icon {{ ($myReview && $myReview->rating >= $i) ? 'text-amber-400' : 'text-slate-200' }} hover:text-amber-400 peer-checked:text-amber-400" fill="currentColor" viewBox="0 0 20 20" data-value="{{ $i }}"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            {{-- Comment --}}
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Komentar (Opsional)</label>
                                <textarea name="comment" rows="3" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all text-sm font-medium text-slate-700 resize-none" placeholder="Bagikan pengalaman Anda menghuni kamar ini...">{{ old('comment', $myReview?->comment) }}</textarea>
                            </div>

                            {{-- Anonymous --}}
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_anonymous" value="1" class="sr-only peer" {{ ($myReview && $myReview->is_anonymous) ? 'checked' : '' }}>
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                                <span class="text-sm font-bold text-slate-600">Kirim sebagai anonim</span>
                            </div>

                            <button type="submit" class="px-8 py-3 bg-slate-800 text-white rounded-xl font-black text-sm hover:bg-blue-600 transition-all shadow-sm">
                                {{ $myReview ? 'Update Ulasan' : 'Kirim Ulasan' }}
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <script>
        function changeMainImage(url, thumb) {
            document.getElementById('main-picture').src = url;
            const cards = document.querySelectorAll('.thumbnail-card');
            cards.forEach(c => {
                c.classList.remove('border-blue-500');
                c.classList.add('border-transparent');
            });
            thumb.classList.remove('border-transparent');
            thumb.classList.add('border-blue-500');
        }

        // Interactive star rating
        const stars = document.querySelectorAll('.star-icon');
        const radios = document.querySelectorAll('input[name="rating"]');

        radios.forEach((radio, idx) => {
            radio.addEventListener('change', () => {
                stars.forEach((star, sIdx) => {
                    if (sIdx <= idx) {
                        star.classList.remove('text-slate-200');
                        star.classList.add('text-amber-400');
                    } else {
                        star.classList.remove('text-amber-400');
                        star.classList.add('text-slate-200');
                    }
                });
            });
        });
    </script>
</x-app-layout>
