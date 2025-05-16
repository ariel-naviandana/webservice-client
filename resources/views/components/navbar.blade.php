<nav class="px-16 flex justify-between items-center py-4 bg-yellow-300 shadow-md fixed w-full top-0">
    <a href="{{ route('welcome') }}" class="text-2xl font-bold">
        Film
    </a>

    <div class="relative">
        @if (session('user_id'))
            {{-- Jika user sudah login, tampilkan tombol dropdown --}}
            <button id="accountBtn" class="bg-green-400 px-4 py-2 cursor-pointer hover:bg-green-500 text-black">
                {{ session('user_name') ?? 'Akun' }}
            </button>

            <div id="dropdownMenu"
                class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded shadow-md z-50">
                <a href="{{ url('edit-profile') }}" class="block px-4 py-2 hover:bg-gray-100">Edit Profil</a>
                {{-- Tambahkan logout jika perlu --}}
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        @else
            {{-- Jika user belum login, arahkan ke halaman login --}}
            <a href="{{ route('login_form') }}" class="bg-green-400 px-4 py-2 hover:bg-green-500 text-black">
                Akun
            </a>
        @endif
    </div>
</nav>

@if (session('user_id'))
<script>
    const btn = document.getElementById('accountBtn');
    const menu = document.getElementById('dropdownMenu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    window.addEventListener('click', function (e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
@endif
