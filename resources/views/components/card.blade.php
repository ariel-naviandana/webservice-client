@props(['posterurl', 'title', 'genre', 'id'])

<div class="bg-white shadow-lg rounded-lg p-4">
    <img src="{{ $posterurl }}" alt="{{ $title }}" class="w-full h-60 object-cover rounded-t-lg">
    <div class="p-4">
        <h2 class="text-xl font-bold mb-2">{{ $title }}</h2>
        <div class="bg-yellow-100 wrap-normal">
            <p class="text-gray-700 mb-4">{{ $genre }}</p>
        </div>
        {{-- @if ($casts->isNotEmpty())
            @foreach ($casts as $cast)
                <li>{{ $cast->name }} as {{ $cast->character_name }}</li>
            @endforeach
        @endif --}}
        <a href="{{ route('watchlist.add', $id) }}"
            class="bg-blue-500 hover:bg-blue-800 text-white px-4 py-2 rounded-md">Tambah ke Watchlist</a>
    </div>
</div>
