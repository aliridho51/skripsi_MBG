<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem MBG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/animations.css') }}?v={{ time() }}" rel="stylesheet">
</head>

<body class="login-bg-animate h-screen flex items-center justify-center overflow-hidden relative">

    <!-- Decorative floating shapes -->
    <div class="absolute top-20 left-20 w-32 h-32 bg-blue-200/20 rounded-full blur-xl" style="animation: particleFloat1 18s ease-in-out infinite;"></div>
    <div class="absolute bottom-20 right-20 w-40 h-40 bg-indigo-200/20 rounded-full blur-xl" style="animation: particleFloat2 14s ease-in-out infinite;"></div>
    <div class="absolute top-1/3 right-1/4 w-24 h-24 bg-purple-200/15 rounded-full blur-lg" style="animation: particleFloat1 20s ease-in-out infinite reverse;"></div>

    <div class="bg-white/80 backdrop-blur-lg p-8 rounded-2xl shadow-xl w-full max-w-md border border-white/50 login-card-glow animate-fade-in-scale relative z-10">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg login-icon-float">
                <i class="fas fa-utensils text-3xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 animate-fade-in-up delay-1">Sistem Distribusi MBG</h1>
            <p class="text-slate-500 text-sm mt-2 animate-fade-in-up delay-2">Silakan masuk ke akun Anda</p>
        </div>

        @if($errors->any())
            <div
                class="alert-animate bg-red-50 text-red-600 p-3 rounded-lg text-sm font-medium mb-6 border border-red-200 text-center flex items-center justify-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                Email atau Password salah!
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-5 animate-fade-in-up delay-3">
                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                <div class="relative group">
                    <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400 transition-colors group-focus-within:text-blue-500"></i>
                    <input type="email" name="email" required
                        class="input-animate w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition"
                        placeholder="contoh@mbg.com">
                </div>
            </div>

            <div class="mb-8 animate-fade-in-up delay-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi</label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-4 top-3.5 text-slate-400 transition-colors group-focus-within:text-blue-500"></i>
                    <input type="password" name="password" required id="passwordField"
                        class="input-animate w-full pl-11 pr-12 py-3 bg-slate-50/50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-3.5 text-slate-400 hover:text-blue-500 transition">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="animate-fade-in-up delay-5">
                <button type="submit"
                    class="btn-animate btn-ripple w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg flex justify-center items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sistem
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400 animate-fade-in delay-6">
            <i class="fas fa-shield-alt mr-1"></i>
            Akses terbatas hanya untuk Admin, Kurir, dan Sekolah Terdaftar.
        </div>
    </div>

    <script>
        function togglePassword() {
            const field = document.getElementById('passwordField');
            const icon = document.getElementById('toggleIcon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>