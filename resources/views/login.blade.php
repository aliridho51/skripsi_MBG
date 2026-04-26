<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem MBG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-slate-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md border border-slate-200">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                <i class="fas fa-utensils text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Sistem Distribusi MBG</h1>
            <p class="text-slate-500 text-sm mt-1">Silakan masuk ke akun Anda</p>
        </div>

        @if($errors->any())
            <div
                class="bg-red-50 text-red-600 p-3 rounded-lg text-sm font-medium mb-6 border border-red-200 text-center animate-pulse">
                Email atau Password salah!
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400"></i>
                    <input type="email" name="email" required
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="contoh@mbg.com">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-3.5 text-slate-400"></i>
                    <input type="password" name="password" required
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition flex justify-center items-center">
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sistem
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400">
            Akses terbatas hanya untuk Admin, Kurir, dan Sekolah Terdaftar.
        </div>
    </div>

</body>

</html>