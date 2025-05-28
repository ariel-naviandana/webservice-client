<!DOCTYPE html>
<html>
<head>
    <title>Login FilmKu</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="login-page">
<div class="containerlogin">
    <h1 style="color:#e4b70d">FilmKu</h1>
    <h2>Login</h2>

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

    <form action="{{ route('login_process') }}" method="POST">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>

    <a href="{{ route('register_form') }}" class="btn-detail">Register Here</a>
</div>
</body>
</html>
