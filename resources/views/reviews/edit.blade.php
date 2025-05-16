<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container2">
    <h2>Edit Review</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reviews.update', $review->id) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="film_id" value="{{ $review->film_id }}">

        <div class="form-group">
            <label for="rating">Rating (1-10)</label>
            <input type="number" name="rating" id="rating" min="1" max="10" value="{{ $review->rating }}" required>
        </div>

        <div class="form-group">
            <label for="comment">Komentar</label>
            <textarea name="comment" id="comment" rows="4" required>{{ $review->comment }}</textarea>
        </div>

        <input type="hidden" name="film_id" value="{{ $review->film_id }}">

        <div class="form-actions">
            <input type="submit" value="Simpan Perubahan"><br>
            <a href="{{ route('films.show', ['id' => $review->film_id]) }}" class="back">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
