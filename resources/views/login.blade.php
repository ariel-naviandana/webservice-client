<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h2>Login</h2>

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

@if(session('message'))
    <script>
        alert("{{ e(session('message')) }}");
    </script>
@endif
</body>
</html>
