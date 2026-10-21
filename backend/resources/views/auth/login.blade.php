<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PinjamIn System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative p-4" 
      style="background-image: url('{{ asset('assets/img/backgroundlogin.jpg') }}');">

    <!-- Dark Overlay / Vignette Halus -->
    <div class="absolute inset-0 bg-emerald-950/20 backdrop-blur-[1px]"></div>

    <!-- Card Login Container -->
    <div class="relative w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20 transition-all duration-300">
        
        <!-- Header Logo & Judul -->
        <div class="text-center mb-6">
            <img src="{{ asset('assets/img/pinjaminlogo.svg') }}" alt="Logo PinjamIn" class="w-24 h-24 mx-auto mb-2 drop-shadow-sm">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">PinjamIn</h2>
            <p class="text-xs text-gray-500 mt-1">Nggak usah beli, PinjamIn aja</p>
        </div>

        <!-- Alert Session Success -->
        @if(session('success'))
            <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3.5 py-2.5 rounded-xl text-xs flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 10 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Alert Session Error -->
        @if(session('error'))
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 px-3.5 py-2.5 rounded-xl text-xs flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 10 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">EMAIL</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="pinjamin@email.com"
                       class="w-full px-4 py-2.5 bg-gray-50/80 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                @error('email')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-[11px] font-bold text-gray-600 tracking-wider uppercase mb-1.5">PASSWORD</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full px-4 py-2.5 bg-gray-50/80 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-xl text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all">
                @error('password')
                    <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs py-3 rounded-xl shadow-lg shadow-emerald-600/20 transition-all duration-200 mt-2">
                Masuk ke Sistem
            </button>
        </form>

    </div>

</body>
</html>