<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Profil</title>
    @vite(['resources/css/app.css'])
</head>

<body>
    <x-navbar />

    <div class="px-16 py-8 mt-16">
        <h1 class="text-4xl font-bold">Edit Profil</h1>
    </div>

    <div class="px-16 py-8">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="/edit" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}"
                    class="bg-white mt-1 block w-full rounded-md shadow-sm px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}"
                    class="bg-white mt-1 block w-full rounded-md shadow-sm px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="bg-white mt-1 block w-full rounded-md shadow-sm px-3 py-2 pr-10">
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600">
                        👁
                    </button>
                </div>
            </div>

            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Simpan</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>

</html>
