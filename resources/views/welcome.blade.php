<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Film</title>
    @vite(['resources/css/app.css'])
</head>

<body>
    <x-navbar />

    <div class="px-16 py-8 mt-16">
        <h1 class="text-4xl font-bold">Selamat datang di Film</h1>
        <p class="mt-4 text-lg">Temukan film favoritmu di sini!</p>
    </div>

    <div class="px-16 py-8">
        <div class="grid grid-cols-4 gap-4">
            @foreach ($films as $movie)
                <x-card :posterurl="$movie['poster_url']" :title="$movie['title']" :genre="$movie['title']" :id="$movie['id']" />
            @endforeach
        </div>
    </div>
</body>

</html>
