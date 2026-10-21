<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PinjamIn System</title>
    <!-- Memuat Tailwind CSS melalui CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#059669] min-h-screen flex items-center justify-center p-4 antialiased relative overflow-hidden" 
      style="background-image: radial-gradient(#047857 2px, transparent 2px); background-size: 24px 24px;">

    <!-- Card Container Login -->
    <div class="bg-[#F8FAFC]/95 backdrop-blur-md p-8 rounded-3xl shadow-2xl w-full max-w-sm border border-white/40 text-gray-800 space-y-5 relative z-10">
        
        <!-- Header Logo & Title PinjamIn -->
        <div class="text-center space-y-2">
            <div class="w-16 h-16 bg-[#00AA55] rounded-2xl mx-auto flex items-center justify-center p-2.5 shadow-lg shadow-emerald-600/30">
                <img src="{{ asset('assets/img/pinjaminlogo.svg') }}" alt="Logo PinjamIn" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">PinjamIn</h1>
            <p class="text-xs text-gray-500 font-medium">Nggak usah beli, Pinjamin aja</p>
        </div>

        <!-- Alert Success Logout / Session -->
        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs flex items-center gap-2 font-medium">
                <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Alert Error Session -->
        @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Alert Error Validasi Input -->
        @if($errors->any())
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-medium space-y-1">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Input Email -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">EMAIL</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="pinjamin@email.com"
                       class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#00AA55] transition">
            </div>

            <!-- Input Password -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">PASSWORD</label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-[#00AA55] transition">
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                    class="w-full bg-[#00AA55] hover:bg-[#00994D] text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 text-xs transition duration-200">
                Masuk ke Sistem
            </button>
        </form>

    </div>

</body>
</html>