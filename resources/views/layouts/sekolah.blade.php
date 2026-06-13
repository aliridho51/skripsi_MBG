<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Sekolah - MBG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Background Particles -->
    <div class="bg-particle bg-particle-1" style="top: 15%; right: 3%;"></div>
    <div class="bg-particle bg-particle-2" style="bottom: 20%; left: 8%;"></div>

    <div class="flex h-screen overflow-hidden">

        <!-- Mobile overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden modal-overlay-animate" onclick="toggleSidebar()"></div>

        <aside id="sidebar" class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="p-6 flex items-center justify-center border-b border-slate-700">
                <img src="{{ asset('logo_mbg.svg') }}" alt="Logo MBG" class="w-8 h-8 mr-3">
                <span class="text-xl font-bold font-sans sidebar-logo">MBG Sekolah</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('sekolah.dashboard') }}"
                    class="sidebar-nav-link block px-4 py-2 rounded {{ request()->routeIs('sekolah.dashboard') ? 'bg-blue-600 text-white sidebar-active-link' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-bullhorn mr-2 w-5 text-center"></i> Info Kuota
                </a>
                <a href="{{ route('sekolah.tracking') }}"
                    class="sidebar-nav-link block px-4 py-2 rounded {{ request()->routeIs('sekolah.tracking') ? 'bg-blue-600 text-white sidebar-active-link' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-map-marked-alt mr-2 w-5 text-center"></i> Lacak Pengiriman
                </a>
                <a href="{{ route('sekolah.konfirmasi') }}"
                    class="sidebar-nav-link block px-4 py-2 rounded {{ request()->routeIs('sekolah.konfirmasi') ? 'bg-blue-600 text-white sidebar-active-link' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-clipboard-check mr-2 w-5 text-center"></i> Konfirmasi Terima
                </a>
                <a href="{{ route('sekolah.riwayat') }}"
                    class="sidebar-nav-link block px-4 py-2 rounded {{ request()->routeIs('sekolah.riwayat') ? 'bg-blue-600 text-white sidebar-active-link' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-history mr-2 w-5 text-center"></i> Rekap Riwayat
                </a>
                <div class="my-3 border-t border-slate-700"></div>
                <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Lainnya</p>
                <a href="{{ route('sekolah.pengembalian-ompreng') }}"
                    class="sidebar-nav-link block px-4 py-2 rounded {{ request()->routeIs('sekolah.pengembalian-ompreng') ? 'bg-blue-600 text-white sidebar-active-link' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-box-open mr-2 w-5 text-center"></i> Pengembalian Ompreng
                </a>
                <a href="{{ route('sekolah.kritik-saran') }}"
                    class="sidebar-nav-link block px-4 py-2 rounded {{ request()->routeIs('sekolah.kritik-saran') ? 'bg-blue-600 text-white sidebar-active-link' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-comments mr-2 w-5 text-center"></i> Kritik & Saran
                </a>
                <a href="{{ route('sekolah.profil') }}"
                    class="sidebar-nav-link block px-4 py-2 rounded {{ request()->routeIs('sekolah.profil') ? 'bg-blue-600 text-white sidebar-active-link' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fas fa-building mr-2 w-5 text-center"></i> Profil Sekolah
                </a>
            </nav>


            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-left block px-4 py-3 text-red-400 hover:bg-slate-800 hover:text-red-300 rounded-lg transition font-medium btn-ripple">
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col w-full">
            <header class="bg-white shadow flex justify-between items-center p-4 header-animate">
                <div class="flex items-center">
                    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4 md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('header_title')
                    </h2>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block animate-fade-in">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-600">Penerima MBG</p>
                    </div>
                    <div
                        class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold uppercase avatar-animate">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div
                        class="alert-animate bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                        <i class="fas fa-check-circle text-xl mr-3"></i>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

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

        // Ripple effect for buttons
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-ripple');
            if (!btn) return;

            const circle = document.createElement('span');
            const diameter = Math.max(btn.clientWidth, btn.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${e.clientX - btn.getBoundingClientRect().left - radius}px`;
            circle.style.top = `${e.clientY - btn.getBoundingClientRect().top - radius}px`;
            circle.classList.add('ripple-effect');

            const existing = btn.querySelector('.ripple-effect');
            if (existing) existing.remove();
            btn.appendChild(circle);
        });
    </script>
</body>

</html>