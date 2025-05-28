<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>FilmKu</title>
    @vite(['resources/css/app.css'])
</head>

<body>
<x-navbar />

@if(session('success'))
    <div class="alert alert-success mx-16 mt-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mx-16 mt-4">{{ session('error') }}</div>
@endif
@if(session('message'))
    <div class="alert alert-info mx-16 mt-4">{{ session('message') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger mx-16 mt-4">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="px-16 py-8 mt-16">
    <h1 class="text-4xl font-bold">
        Selamat datang {{ session('user_name') ?? 'Pengunjung' }} di FilmKu
    </h1>
    <p class="mt-4 text-lg">Temukan film favoritmu di sini!</p>
</div>

{{-- Filter Section Dropdown --}}
<div class="px-16 mb-8">
    <form method="GET" action="{{ route('welcome') }}" class="space-y-4">
        <div class="flex space-x-6">
            <div class="flex-[0.2]">
                <label for="genre" class="block text-xl font-semibold mb-1">Filter Genre</label>
                <select name="genre" id="genre" onchange="this.form.submit()" class="w-full border px-4 py-3 rounded text-lg">
                    <option value="">Semua Genre</option>
                    @foreach ($genres as $genreItem)
                        <option value="{{ $genreItem['name'] }}" {{ request('genre') == $genreItem['name'] ? 'selected' : '' }}>
                            {{ $genreItem['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-[0.3]">
                <label for="cast" class="block text-xl font-semibold mb-1">Filter Cast</label>
                <select name="cast" id="cast" onchange="this.form.submit()" class="w-full border px-4 py-3 rounded text-lg">
                    <option value="">Semua Cast</option>
                    @foreach ($casts as $castItem)
                        <option value="{{ $castItem['name'] }}" {{ request('cast') == $castItem['name'] ? 'selected' : '' }}>
                            {{ $castItem['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('welcome') }}" class="inline-block text-yellow-600 hover:underline">Reset Filter</a>
        </div>
    </form>
</div>

{{-- Film List --}}
<div class="px-16 py-8">
    <div class="grid grid-cols-4 gap-4">
        @foreach ($films as $movie)
            @php
                $poster = $movie['poster_url'] ?? 'https://i.pinimg.com/236x/56/2e/be/562ebed9cd49b9a09baa35eddfe86b00.jpg';
            @endphp

            <a href="{{ route('films.show', $movie['id']) }}" class="block hover:shadow-lg transition">
                <div class="rounded overflow-hidden shadow-lg bg-white" style="height: 400px">
                    <img class="w-full h-64 object-cover" src="{{ $poster }}" alt="{{ $movie['title'] }}" />
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">{{ $movie['title'] }}</div>
                        @foreach ($movie['genres'] as $genre)
                            <p class="text-gray-700 text-base">{{ $genre['name'] }}</p>
                        @endforeach
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
</body>
</html>
