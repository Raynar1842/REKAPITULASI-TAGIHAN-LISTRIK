<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rekapitulasi Tagihan Listrik RT FORMADIKA</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full font-sans antialiased text-slate-100 flex items-center justify-center p-4 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-teal-900 via-slate-900 to-slate-950">

    <div class="w-full max-w-md space-y-6">
        
        <!-- BRANDING HEADER -->
        <div class="text-center space-y-3">
            <div class="inline-flex p-3.5 bg-teal-500/20 backdrop-blur-xl rounded-2xl border border-teal-400/30 text-yellow-400 shadow-xl shadow-teal-900/50">
                <i data-lucide="zap" class="w-10 h-10 animate-bounce"></i>
            </div>
            
            <div>
                <span class="inline-block px-2.5 py-0.5 bg-yellow-400/20 text-yellow-300 border border-yellow-400/30 text-[11px] font-extrabold uppercase tracking-wider rounded-md">
                    FORMADIKA
                </span>
                <h1 class="text-2xl font-extrabold tracking-tight text-white mt-1">
                    REKAPITULASI TAGIHAN LISTRIK
                </h1>
                <p class="text-xs text-teal-200/80 mt-1 font-medium">
                    Sekretariat: Kentolan Lor, Guwosari, Pajangan, Bantul
                </p>
            </div>
        </div>

        <!-- LOGIN CARD -->
        <div class="bg-slate-900/90 backdrop-blur-2xl border border-slate-800 p-6 sm:p-8 rounded-3xl shadow-2xl shadow-black/80 space-y-6">
            
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i data-lucide="lock" class="w-5 h-5 text-teal-400"></i>
                    <span>Masuk ke Sistem</span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">Pilih wilayah RT dan masukkan akun Anda untuk melanjutkan.</p>
            </div>

            <!-- ERROR ALERT -->
            @if ($errors->has('login_error'))
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 p-3.5 rounded-xl text-xs flex items-center gap-2.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-400"></i>
                    <span>{{ $errors->first('login_error') }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 p-3.5 rounded-xl text-xs flex items-center gap-2.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0 text-emerald-400"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- LOGIN FORM -->
            <form action="{{ url('/login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- PILIH WILAYAH RT DROPDOWN -->
                <div class="space-y-1.5">
                    <label for="rt" class="block text-xs font-bold text-teal-300 uppercase tracking-wider flex items-center justify-between">
                        <span>Pilih Wilayah RT</span>
                        <span class="text-[10px] text-slate-400 font-normal">Akses Wilayah</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-teal-400">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </div>
                        <select id="rt" 
                                name="rt" 
                                class="w-full pl-10 pr-8 py-3 bg-slate-800/90 border border-teal-500/40 rounded-xl text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all appearance-none cursor-pointer">
                            <option value="RT 01">RT 01 - Kentolan Lor</option>
                            <option value="RT 02">RT 02 - Kentolan Lor</option>
                            <option value="RT 03">RT 03 - Kentolan Lor</option>
                            <option value="RT 04" selected class="text-yellow-300 font-extrabold">RT 04 - FORMADIKA (Utama)</option>
                            <option value="RT 05">RT 05 - Kentolan Lor</option>
                            <option value="RT 06">RT 06 - Kentolan Lor</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Username or Email Input -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Username / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input type="text" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', 'admin') }}" 
                               required 
                               placeholder="Masukkan Username / Email"
                               class="w-full pl-10 pr-4 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-sm font-medium text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5" x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               value="admin"
                               required 
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-3 bg-slate-800/80 border border-slate-700 rounded-xl text-sm font-medium text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">
                        
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white">
                            <i data-lucide="eye" x-show="!showPassword" class="w-4 h-4"></i>
                            <i data-lucide="eye-off" x-show="showPassword" class="w-4 h-4" x-cloak></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-teal-900/40 hover:shadow-teal-700/50 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                    <span>Masuk ke Dashboard</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <!-- DEMO ACCOUNTS HELPER BOX -->
            <div class="bg-teal-950/60 border border-teal-800/50 p-3.5 rounded-xl space-y-2 text-xs">
                <div class="text-teal-300 font-bold flex items-center gap-1.5">
                    <i data-lucide="users" class="w-3.5 h-3.5 text-yellow-400"></i>
                    <span>Pilihan Akun Login (Klik untuk Isi Otomatis):</span>
                </div>
                <div class="grid grid-cols-3 gap-1.5 pt-1">
                    <button type="button" 
                            onclick="fillUser('admin', 'admin')"
                            class="p-2 bg-slate-800 hover:bg-teal-800/60 border border-slate-700 rounded-lg text-center transition-colors">
                        <div class="text-[11px] font-extrabold text-amber-300">Admin</div>
                        <div class="text-[10px] text-slate-400 font-mono">admin/admin</div>
                    </button>

                    <button type="button" 
                            onclick="fillUser('petugas', 'petugas')"
                            class="p-2 bg-slate-800 hover:bg-teal-800/60 border border-slate-700 rounded-lg text-center transition-colors">
                        <div class="text-[11px] font-extrabold text-emerald-300">Petugas</div>
                        <div class="text-[10px] text-slate-400 font-mono">petugas/petugas</div>
                    </button>

                    <button type="button" 
                            onclick="fillUser('warga', 'warga')"
                            class="p-2 bg-slate-800 hover:bg-teal-800/60 border border-slate-700 rounded-lg text-center transition-colors">
                        <div class="text-[11px] font-extrabold text-blue-300">Warga</div>
                        <div class="text-[10px] text-slate-400 font-mono">warga/warga</div>
                    </button>
                </div>
            </div>

        </div>

        <!-- FOOTER CREDITS -->
        <p class="text-center text-xs text-slate-500 font-medium">
            Sistem Rekapitulasi Tagihan Listrik RT FORMADIKA &copy; 2026
        </p>

    </div>

    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });

        function fillUser(username, password) {
            document.getElementById('email').value = username;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
