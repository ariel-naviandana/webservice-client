<nav class="px-16 flex justify-between items-center py-4 bg-yellow-300 shadow-md fixed w-full top-0">
    <a href="/" class="text-2xl font-bold">
        Film
    </a>
    <div class="relative">
        <button id="accountBtn" class="bg-green-400 px-4 py-2 cursor-pointer hover:bg-green-500">Akun</button>
        <div id="dropdownMenu"
            class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-300 rounded shadow-md z-50">
            <a href="watchlist" class="block px-4 py-2 hover:bg-gray-100">Watchlist</a>
            <a href="edit-profile" class="block px-4 py-2 hover:bg-gray-100">Edit Profil</a>
        </div>
    </div>
</nav>

<script>
    const btn = document.getElementById('accountBtn');
    const menu = document.getElementById('dropdownMenu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    window.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
