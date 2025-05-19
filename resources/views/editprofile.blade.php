<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    @vite(['resources/css/app.css'])
    <style>
        .profile-pic {
            height: 120px;
            width: 120px;
            object-fit: cover;
            border-radius: 9999px;
            cursor: pointer;
            border: 4px solid #ccc;
            transition: border-color 0.3s;
        }

        .profile-pic:hover {
            border-color:#f5c518;
        }

        input[type="file"] {
            display: none;
        }
    </style>
</head>

<body>
    <x-navbar />

    <div class="min-h-screen flex flex-col items-center justify-start pt-24 px-4">
        <h1 class="text-4xl font-bold mb-8">Edit Profil</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-6 w-full max-w-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="/edit" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 py-6 w-full max-w-lg space-y-6">
            @csrf

            <!-- FOTO PROFIL -->
            <div class="flex justify-center">
                <label for="photo">
                    <img id="preview" src="{{ session('user_photo') ? asset('storage/' . session('user_photo')) : 'https://i.pinimg.com/236x/56/2e/be/562ebed9cd49b9a09baa35eddfe86b00.jpg' }}"
                        alt="Foto Profil" class="profile-pic mx-auto">
                </label>
                <input type="file" name="photo" id="photo" accept="image/*" onchange="previewImage(event)">
            </div>

            <!-- NAMA -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name"
                    value="{{ session('user_name') }}"
                    class="mt-1 block w-full rounded-md shadow-sm px-3 py-2 border border-gray-300"
                    required>
            </div>

            <!-- EMAIL -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                    value="{{ session('user_email') }}"
                    class="mt-1 block w-full rounded-md shadow-sm px-3 py-2 border border-gray-300"
                    required>
            </div>

            <!-- PASSWORD -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full rounded-md shadow-sm px-3 py-2 pr-10 border border-gray-300">
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600">
                        👁
                    </button>
                </div>
            </div>

            <!-- TOMBOL -->
            <div class="text-right">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }

        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function () {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
            }

            if (input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
