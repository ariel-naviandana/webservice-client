<!DOCTYPE html>
<html>
<head>
    <title>Daftar Film</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h2>Dashboard - Daftar Film</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('message'))
        <div class="alert alert-info">{{ session('message') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
