@props(['posterurl', 'title', 'genre', 'id'])

<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="aspect-[2/3] w-full">
        <img src="{{ $posterurl }}" alt="{{ $title }}" class="object-cover w-full h-full">
    </div>
    <div class="p-4">
        <h2 class="text-lg font-bold mb-1 truncate" title="{{ $title }}">{{ $title }}</h2>
        <p class="text-sm text-gray-600 mb-3 truncate" title="{{ $genre }}">{{ $genre }}</p>
        <a href="{{ route('watchlist.add', $id) }}"
           class="bg-blue-500 hover:bg-blue-700 text-white text-sm px-3 py-2 rounded-md">
            Tambah ke Watchlist
        </a>
    </div>
</div>
