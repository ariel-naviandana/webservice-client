<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Watchlist</title>
    @vite(['resources/css/app.css'])
</head>

<body>
    <x-navbar />

    <div class="flex justify-between px-16 py-8 mt-16">
        <h1 class="text-4xl font-bold">Watchlist</h1>
        <div class="flex items-center">
            <a href="" class="text-lg text-gray-500 hover:text-gray-700">Home</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="" class="text-lg text-gray-500 hover:text-gray-700">Watchlist</a>
        </div>
    </div>

    <div class="px-16 py-8">
        <div class="grid grid-cols-4 gap-4">
            @foreach ($watchlist as $movie)
                <x-card :posterurl="$movie->film->poster_url" :title="$movie->film->title" :genre="$movie->film->genre" :id="$movie->film->id" />
            @endforeach
        </div>
    </div>
</body>

</html>
