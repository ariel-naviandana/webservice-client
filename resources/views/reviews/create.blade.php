<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buat Review</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h2>Buat Review</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reviews.store') }}">
        @csrf

        <div class="form-group">
            <label for="film_id">Pilih Film</label>
            <select name="film_id" id="film_id" required>
                <option value="">-- Pilih Film --</option>
                @foreach ($films as $film)
                    <option value="{{ $film['id'] }}">{{ $film['title'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="rating">Rating (1-10)</label>
            <input type="number" name="rating" id="rating" min="1" max="10" required>
        </div>

        <div class="form-group">
            <label for="comment">Komentar</label>
            <textarea name="comment" id="comment" rows="4"></textarea>
        </div>

        <div class="form-checkbox">
            <input type="checkbox" name="is_critic" id="is_critic" value="1">
            <label for="is_critic">Reviewer adalah kritikus</label>
        </div>

        <div class="form-actions">
            <input type="submit" value="Simpan">
            <a href="{{ route('reviews.index') }}" class="back">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
