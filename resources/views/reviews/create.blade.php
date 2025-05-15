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

        <input type="hidden" name="film_id" value="{{ $filmId }}">

        <div class="form-group">
            <label for="rating">Rating (1-10)</label>
            <input type="number" name="rating" id="rating" min="1" max="10" required value="{{ old('rating') }}">
        </div>

        <div class="form-group">
            <label for="comment">Komentar</label>
            <textarea name="comment" id="comment" rows="4" required>{{ old('comment') }}</textarea>
        </div>

        <input type="hidden" name="film_id" value="{{ $filmId }}">

        <div class="form-actions">
            <input type="submit" value="Simpan">
            <a href="{{ route('films.show', ['id' => $filmId]) }}" class="back">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
