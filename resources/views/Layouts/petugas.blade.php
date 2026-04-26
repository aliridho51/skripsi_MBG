<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Petugas - MBG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal text-slate-800">

    <div class="flex h-screen overflow-hidden">

        <!-- Mobile overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

        <aside id="sidebar" class="w-64 bg-slate-900 text-white flex flex-col shadow-xl fixed inset-y-0 left-0 z-50 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="p-6 flex items-center justify-center border-b border-slate-700">
                <i class="fas fa-motorcycle text-2xl mr-3 text-blue-500"></i>
                <span class="text-2xl font-bold font-sans tracking-tight">MBG Petugas</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('petugas.dashboard') }}"
                    class="block px-4 py-3 rounded-lg transition {{ request()->routeIs('petugas.dashboard') ? 'bg-blue-600 text-white shadow-lg font-bold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-home mr-3 w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('petugas.riwayat') }}"
                    class="block px-4 py-3 rounded-lg transition {{ request()->routeIs('petugas.riwayat') ? 'bg-blue-600 text-white shadow-lg font-bold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-history mr-3 w-5 text-center"></i> Riwayat Pengiriman
                </a>
                <a href="{{ route('petugas.profil') }}"
                    class="block px-4 py-3 rounded-lg transition {{ request()->routeIs('petugas.profil') ? 'bg-blue-600 text-white shadow-lg font-bold' : 'text-gray-400 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-user-circle mr-3 w-5 text-center"></i> Petugas Pengirim
                </a>
            </nav>

            <div class="p-4 border-t border-slate-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left block px-4 py-3 text-red-400 hover:bg-slate-800 hover:text-red-300 rounded-lg transition font-medium">
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col w-full">
            <header class="bg-white shadow-sm flex justify-between items-center p-4 lg:px-8 border-b border-gray-200">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4 md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800">
                        @yield('header_title')
                    </h2>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Kurir Petugas</p>
                    </div>
                    <div
                        class="w-10 h-10 bg-blue-600 border-2 border-white rounded-full flex items-center justify-center text-white font-bold shadow-md uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 lg:p-10">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

</html>

