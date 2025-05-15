<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Film</title>
    @vite(['resources/css/app.css'])
</head>

<body>
    <x-navbar />

    <div class="px-16 py-8 mt-16">
        <h1 class="text-4xl font-bold">
            Selamat datang {{ session('user_name') ?? 'Pengunjung' }} di Film
        </h1>
        <p class="mt-4 text-lg">Temukan film favoritmu di sini!</p>
    </div>

    {{-- Filter Section --}}
    <div class="px-16">
        <h2 class="text-2xl font-bold mb-2">Filter Genre</h2>
        <div class="flex gap-2 flex-wrap mb-6">
            @foreach ($genres as $genreItem)
                @php
                    $isActive = request('genre') == $genreItem;
                    $url = $isActive
                        ? request()->fullUrlWithQuery(['genre' => null])
                        : request()->fullUrlWithQuery(['genre' => $genreItem]);
                @endphp
                <a href="{{ $url }}"
                    class="px-4 py-2 rounded-full border {{ $isActive ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }}">
                    {{ $genreItem }}
                </a>
            @endforeach
            <a href="{{ route('welcome') }}"
                class="px-4 py-2 rounded-full border bg-white text-gray-700">Reset</a>
        </div>

        <h2 class="text-2xl font-bold mb-2">Filter Cast</h2>
        <div class="flex gap-2 flex-wrap mb-8">
            @foreach ($casts as $castItem)
                @php
                    $isActive = request('cast') == $castItem;
                    $url = $isActive
                        ? request()->fullUrlWithQuery(['cast' => null])
                        : request()->fullUrlWithQuery(['cast' => $castItem]);
                @endphp
                <a href="{{ $url }}"
                    class="px-4 py-2 rounded-full border {{ $isActive ? 'bg-green-500 text-white' : 'bg-white text-gray-700' }}">
                    {{ $castItem }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Film List --}}
    <div class="px-16 py-8">
        <div class="grid grid-cols-4 gap-4">
            @php use Illuminate\Support\Str; @endphp

            @foreach ($films as $movie)
                @php
                    $poster = !empty($movie['poster_url'])
                        ? (Str::startsWith($movie['poster_url'], 'http')
                            ? $movie['poster_url']
                            : 'https://your-base-url.com/images/' . $movie['poster_url'])
                        : 'https://via.placeholder.com/300x450?text=No+Image';
                @endphp

                <a href="{{ route('films.show', $movie['id']) }}" class="block hover:shadow-lg transition">
                    <div class="rounded overflow-hidden shadow-lg bg-white">
                        <img class="w-full h-64 object-cover" src="{{ $poster }}" alt="{{ $movie['title'] }}" />
                        <div class="px-6 py-4">
                            <div class="font-bold text-xl mb-2">{{ $movie['title'] }}</div>
                            <p class="text-gray-700 text-base">{{ $movie['genre'] ?? 'Tanpa genre' }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</body>

</html>
