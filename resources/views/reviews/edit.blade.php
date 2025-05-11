<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Edit Review</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reviews.update', $review['id']) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="film_id" class="form-label">Film</label>
            <select name="film_id" id="film_id" class="form-select" required>
                <option value="">-- Pilih Film --</option>
                @foreach ($films as $film)
                    <option value="{{ $film['id'] }}" {{ $film['id'] == $review['film_id'] ? 'selected' : '' }}>{{ $film['title'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Rating (1-10)</label>
            <input type="number" name="rating" id="rating" class="form-control" min="1" max="10" value="{{ $review['rating'] }}" required>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Komentar</label>
            <textarea name="comment" id="comment" class="form-control">{{ $review['comment'] }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_critic" id="is_critic" class="form-check-input" {{ $review['is_critic'] ? 'checked' : '' }}>
            <label for="is_critic" class="form-check-label">Reviewer adalah kritikus</label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
