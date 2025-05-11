<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Review</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h2>Daftar Review Film</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('dashboard') }}" class="btn-detail">« Kembali ke Dashboard</a>
    <a href="{{ route('reviews.create') }}" class="btn-tambah">+ Tambah Review</a>

    <div class="gallery">
        @forelse ($reviews as $review)
            <div class="gallery-item">
                <h3>{{ $review['film']['title'] ?? 'Film Tidak Diketahui' }}</h3>
                <p class="gallery-info">Rating: {{ $review['rating'] }}</p>
                <p class="gallery-info">Komentar: {{ $review['comment'] }}</p>
                <p class="gallery-info">Reviewer: {{ $review['user']['name'] ?? '-' }}</p>

                <div class="btn-group">
                    <a href="{{ route('reviews.edit', $review['id']) }}" class="btn-edit">Edit</a>
                    <form action="{{ route('reviews.destroy', $review['id']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p>Tidak ada review yang tersedia.</p>
        @endforelse
    </div>
</div>
</body>
</html>
