<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $film['title'] }} - Detail Film</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<div class="container">
    <h1>{{ $film['title'] }}</h1>

    <div class="film-details">
        <img src="{{ $film['poster_url'] }}" alt="Poster {{ $film['title'] }}" style="width:200px;float:right;margin-left:20px;">

        <p><strong>Sinopsis:</strong> {{ $film['synopsis'] }}</p>
        <p><strong>Tahun Rilis:</strong> {{ $film['release_year'] }}</p>
        <p><strong>Durasi:</strong> {{ $film['duration'] }} menit</p>
        <p><strong>Sutradara:</strong> {{ $film['director'] }}</p>
        <p><strong>Rating Rata-rata:</strong> {{ $rating_avg }}</p>
        <p><strong>Total Review:</strong> {{ $total_reviews }}</p>

        <p><strong>Genre:</strong>
            @foreach($film['genres'] as $genre)
                {{ $genre['name'] }}@if(!$loop->last), @endif
            @endforeach
        </p>

        <p><strong>Pemeran:</strong>
            @foreach($film['characters'] as $character)
                {{ $character['name'] }}@if(!$loop->last), @endif
            @endforeach
        </p>
    </div>

    <hr>

    @if($userReview)
        <p>Rating: {{ $userReview['rating'] }}</p>
        <p>Comment: {{ $userReview['comment'] ?? 'Tidak ada komentar' }}</p>

        <!-- Tambahkan tombol edit jika pengguna sudah membuat review -->
        <a href="{{ route('reviews.edit', $userReview['id']) }}" class="btn-edit">Edit Review</a>

        <form action="{{ route('reviews.destroy', $userReview['id']) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus review ini?')" class="btn-delete">Hapus Review</button>
        </form>
    @else
        <p>Anda belum memberikan review untuk film ini.</p>
        <!-- Tombol untuk membuat review -->
        <a href="{{ route('reviews.create', ['film_id' => $film['id']]) }}" class="btn-edit">Buat Review</a>
    @endif

    <hr>

    <h3>Ulasan Lainnya</h3>
    @php
        $otherReviews = collect($film['reviews'])->filter(function($r) {
            return $r['user_id'] != session('user_id');
        });
    @endphp

    @if($otherReviews->isEmpty())
        <p>Tidak ada ulasan lain untuk film ini.</p>
    @else
        @foreach($otherReviews as $review)
            <div class="review">
                <p><strong>User ID {{ $review['user_id'] }}:</strong></p>
                <p>Rating: {{ $review['rating'] }}</p>
                <p>Comment: {{ $review['comment'] ?? 'Tidak ada komentar' }}</p>
            </div>
        @endforeach
    @endif

    <br><br>
    <a href="http://localhost:5000/films" class="btn-edit">← Kembali ke Daftar Film</a>
</div>
</body>
</html>
