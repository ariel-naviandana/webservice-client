<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h2>Dashboard</h2>

    <p>Selamat Datang, {{ session('user_name') }}!</p>

    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn-logout">Logout</button>
    </form>
    <br><br>
    <a href="{{ route('reviews.index') }}" class="btn-detail">Lihat Review Film</a>
</div>
</body>
</html>
