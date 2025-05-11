<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h2>Edit Review</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reviews.update', $review['id']) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="film_id">Pilih Film</label>
            <select name="film_id" id="film_id" required>
                <option value="">-- Pilih Film --</option>
                @foreach ($films as $film)
                    <option value="{{ $film['id'] }}" {{ $film['id'] == $review['film_id'] ? 'selected' : '' }}>
                        {{ $film['title'] }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="rating">Rating (1-10)</label>
            <input type="number" name="rating" id="rating" min="1" max="10" value="{{ $review['rating'] }}" required>
        </div>

        <div class="form-group">
            <label for="comment">Komentar</label>
            <textarea name="comment" id="comment" rows="4">{{ $review['comment'] }}</textarea>
        </div>

        <div class="form-checkbox">
            <input type="checkbox" name="is_critic" id="is_critic" value="1" {{ $review['is_critic'] ? 'checked' : '' }}>
            <label for="is_critic">Reviewer adalah kritikus</label>
        </div>

        <div class="form-actions">
            <input type="submit" value="Simpan Perubahan"><br>
            <a href="{{ route('reviews.index') }}" class="back">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
