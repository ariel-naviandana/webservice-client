<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h2>Dashboard - Daftar Film</h2>

    <p>Selamat Datang, {{ session('user_name') }}!</p>
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn-delete">Logout</button>
    </form>
    <br><br>

    <div class="gallery">
        @foreach ($films as $film)
            <div class="gallery-item">
                <h3>{{ $film['title'] }}</h3>
                <a href="{{ route('films.show', $film['id']) }}" class="btn-detail">Lihat Detail</a>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
