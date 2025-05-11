<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Review</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Daftar Review Film</h1>

    <a href="{{ route('reviews.create') }}" class="btn btn-primary mb-3">+ Tambah Review</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Film</th>
            <th>Rating</th>
            <th>Komentar</th>
            <th>Reviewer</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($reviews as $review)
            <tr>
                <td>{{ $review['film']['title'] ?? '-' }}</td>
                <td>{{ $review['rating'] }}</td>
                <td>{{ $review['comment'] }}</td>
                <td>{{ $review['user']['name'] ?? '-' }}</td>
                <td>
                    <a href="{{ route('reviews.edit', $review['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('reviews.destroy', $review['id']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
