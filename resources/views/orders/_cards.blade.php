@foreach($orders as $order)
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-blue-500 transition-colors shadow-sm hover:shadow-xl hover:shadow-blue-900/10 group">
        <div class="relative h-48 w-full">
            @if($order->showtime->movie->poster)
                <img src="{{ Storage::url($order->showtime->movie->poster) }}" alt="Poster" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
            @else
                <div class="w-full h-full bg-slate-100"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-90"></div>
            <div class="absolute bottom-4 left-4 right-4">
                <h3 class="text-xl font-bold text-white truncate drop-shadow-md">{{ $order->showtime->movie->title }}</h3>
                <p class="text-sm text-blue-300 font-medium drop-shadow-md">{{ \Carbon\Carbon::parse($order->showtime->start_time)->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Studio</p>
                    <p class="text-slate-900 font-medium">{{ $order->showtime->studio->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Status</p>
                    <span class="{{ $order->status == 'pending' ? 'text-orange-500' : ($order->status == 'failed' ? 'text-red-500' : 'text-green-600') }} font-bold uppercase text-sm">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
            <div class="mb-6">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Seats</p>
                <p class="text-slate-700 font-medium truncate">{{ $order->seats->pluck('seat_number')->implode(', ') }}</p>
            </div>
            @if($order->status == 'pending')
                <a href="{{ route('orders.pay', $order->id) }}" class="block w-full text-center bg-white border border-slate-300 hover:border-orange-500 hover:bg-orange-50 hover:text-orange-700 text-slate-800 font-semibold py-2 rounded-lg transition-colors shadow-sm">
                    Pay Ticket
                </a>
            @else
                <a href="{{ route('orders.show', $order->id) }}" class="block w-full text-center bg-white border border-slate-300 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700 text-slate-800 font-semibold py-2 rounded-lg transition-colors shadow-sm">
                    View e-Ticket
                </a>
            @endif
        </div>
    </div>
@endforeach
