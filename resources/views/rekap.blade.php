<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rekapitulasi Tagihan Listrik RT - FORMADIKA</title>
    
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
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SheetJS (XLSX Export) -->
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js (Defer) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full font-sans text-slate-800 antialiased selection:bg-teal-500 selection:text-white bg-slate-100 flex flex-col min-h-screen"
      x-data="appData()" 
      x-init="initApp()">

    <!-- MAIN CONTAINER -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 space-y-6">

        <!-- HEADER CARD -->
        <header class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900 text-white shadow-xl border border-teal-700/30">
            <!-- Decorative Background Pattern -->
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <i data-lucide="zap" class="w-72 h-72 text-teal-300"></i>
            </div>
            <div class="absolute right-1/3 top-0 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative p-5 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- Brand / Title Info -->
                <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-teal-500/20 backdrop-blur-md rounded-xl border border-teal-400/30 text-teal-300 shadow-inner">
                            <i data-lucide="zap" class="w-7 h-7 sm:w-8 sm:h-8 text-yellow-400 animate-bounce"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-yellow-400/20 text-yellow-300 border border-yellow-400/30 text-[11px] font-extrabold uppercase tracking-wider rounded-md"
                                  x-text="settings.app_subtitle">
                            </span>
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold tracking-tight text-white leading-tight"
                                x-text="settings.app_title">
                            </h1>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-teal-100/80 flex items-center gap-1.5 font-medium pl-1">
                        <i data-lucide="map-pin" class="w-4 h-4 text-teal-400 shrink-0"></i>
                        <span x-text="settings.app_address"></span>
                    </p>
                </div>

                <!-- Metadata Badges & User Profile Logout -->
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <div class="bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/15 flex items-center space-x-2">
                        <i data-lucide="users" class="w-4 h-4 text-teal-300"></i>
                        <div>
                            <div class="text-[10px] text-teal-200 font-semibold uppercase tracking-wider">WILAYAH</div>
                            <div class="text-xs sm:text-sm font-extrabold text-yellow-300">{{ session('selected_rt', 'RT 04') }}</div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/15 flex items-center space-x-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-teal-300"></i>
                        <div>
                            <div class="text-[10px] text-teal-200 font-semibold uppercase tracking-wider">PERIODE BULAN</div>
                            <div class="text-xs sm:text-sm font-extrabold text-yellow-300" x-text="settings.app_periode"></div>
                        </div>
                    </div>

                    <!-- User Profile Badge & Logout -->
                    <div class="bg-slate-900/60 backdrop-blur-md px-3 py-1.5 rounded-xl border border-teal-500/30 flex items-center space-x-2.5 shadow-inner">
                        <div class="flex items-center space-x-2 text-xs">
                            <div class="w-7 h-7 rounded-lg bg-teal-500/20 border border-teal-400/30 flex items-center justify-center font-extrabold text-teal-300">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-[9px] text-teal-300 font-bold uppercase flex items-center gap-1">
                                    <span>PERAN:</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase"
                                          :class="userRole === 'admin' ? 'bg-amber-500/30 text-amber-300 border border-amber-500/40' : (userRole === 'petugas' ? 'bg-emerald-500/30 text-emerald-300 border border-emerald-500/40' : 'bg-blue-500/30 text-blue-300 border border-blue-500/40')"
                                          x-text="userRole.toUpperCase()"></span>
                                </div>
                                <div class="text-xs font-extrabold text-white">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    title="Keluar / Logout" 
                                    class="px-2.5 py-1 bg-rose-500/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 rounded-lg text-xs font-extrabold transition-all flex items-center gap-1">
                                <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- WARGA VIEW-ONLY NOTICE BANNER -->
        <template x-if="isWarga">
            <div class="bg-blue-900/80 border border-blue-600/40 text-blue-100 p-4 rounded-2xl flex items-center justify-between shadow-md">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-blue-500/20 rounded-xl border border-blue-400/30 text-blue-300">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="font-extrabold text-sm text-white">Mode Warga (Read-Only)</div>
                        <div class="text-xs text-blue-200/80">Anda masuk sebagai Warga RT. Anda dapat melihat rekap data tagihan dan mengunduh Excel, namun pencatatan status lunas hanya dapat dilakukan oleh Petugas atau Admin.</div>
                    </div>
                </div>
                <span class="hidden sm:inline-block px-3 py-1 bg-blue-500/20 text-blue-300 text-xs font-bold rounded-lg border border-blue-400/30">
                    Akses Melihat Data
                </span>
            </div>
        </template>

        <!-- STATS DASHBOARD SUMMARY CARDS -->
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            <!-- Card 1: Total Warga -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Warga</span>
                    <div class="p-2 bg-slate-100 rounded-xl text-slate-600">
                        <i data-lucide="user-check" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <div class="text-xl sm:text-2xl font-extrabold text-slate-900" x-text="wargas.length"></div>
                    <div class="text-xs text-slate-500 font-medium">Orang</div>
                </div>
            </div>

            <!-- Card 2: Total Tagihan Keseluruhan -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Tagihan</span>
                    <div class="p-2 bg-teal-50 text-teal-600 rounded-xl">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="text-lg sm:text-xl font-extrabold text-slate-900" x-text="formatRupiah(totalTagihan)"></div>
                    <div class="text-[11px] text-slate-400 font-medium" x-text="'Tagihan ' + currentRt"></div>
                </div>
            </div>

            <!-- Card 3: Jumlah Diterima (Lunas) -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-emerald-100 hover:shadow-md transition-shadow bg-gradient-to-br from-white to-emerald-50/30">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Telah Diterima</span>
                    <div class="p-2 bg-emerald-100 text-emerald-700 rounded-xl">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="text-lg sm:text-xl font-extrabold text-emerald-600" x-text="formatRupiah(totalDiterima)"></div>
                    <div class="text-[11px] text-emerald-700 font-medium flex items-center justify-between mt-1">
                        <span x-text="totalLunasCount + ' / ' + wargas.length + ' Warga Lunas'"></span>
                        <span class="font-bold" x-text="persentaseLunas + '%'"></span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Sisa Tagihan (Belum Bayar) -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-rose-100 hover:shadow-md transition-shadow bg-gradient-to-br from-white to-rose-50/30">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Belum Diterima</span>
                    <div class="p-2 bg-rose-100 text-rose-700 rounded-xl">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="text-lg sm:text-xl font-extrabold text-rose-600" x-text="formatRupiah(sisaTagihan)"></div>
                    <div class="text-[11px] text-rose-700 font-medium mt-1" x-text="(wargas.length - totalLunasCount) + ' Warga Belum Bayar'"></div>
                </div>
            </div>
        </section>

        <!-- PROGRESS BAR COLLECTION -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold">
                <span class="text-slate-700 flex items-center gap-1.5">
                    <i data-lucide="trending-up" class="w-4 h-4 text-teal-600"></i>
                    <span>Progress Pembayaran Bulan Ini</span>
                </span>
                <span class="text-teal-700 font-extrabold" x-text="persentaseLunas + '% Terkumpul'"></span>
            </div>
            <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden p-0.5 border border-slate-200">
                <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-full rounded-full transition-all duration-500 ease-out shadow-sm"
                     :style="'width: ' + persentaseLunas + '%'"></div>
            </div>
        </div>

        <!-- CONTROL TOOLBAR: SEARCH & ACTION BUTTONS -->
        <section class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Real-Time Search Column -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Cari nama warga..." 
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                
                <!-- Clear Search Button -->
                <button x-show="searchQuery.length > 0" 
                        @click="searchQuery = ''" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Action Buttons Group -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <!-- App Settings Button -->
                <button @click="openAppSettingsModal = true" 
                        class="px-3.5 py-2.5 bg-slate-900 hover:bg-slate-950 text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-1.5 shadow-sm active:scale-95 border border-slate-700">
                    <i data-lucide="settings" class="w-4 h-4 text-yellow-400"></i>
                    <span>Pengaturan Aplikasi</span>
                </button>

                <!-- Add Warga Button (Admin & Petugas Only) -->
                <button @click="openAddWargaModal = true" 
                        :disabled="!canManagePayments"
                        :class="!canManagePayments ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-slate-800 hover:bg-slate-900 active:scale-95'"
                        class="px-3.5 py-2.5 text-white rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5 shadow-sm">
                    <i data-lucide="user-plus" class="w-4 h-4 text-teal-400"></i>
                    <span>Tambah Warga</span>
                </button>

                <!-- Export Excel Button (All Roles) -->
                <button @click="exportToExcel()" 
                        class="px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5 shadow-sm hover:shadow-emerald-600/20 active:scale-95">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Export Excel</span>
                </button>

                <!-- Reset Bulanan Button (Admin & Petugas Only) -->
                <button @click="confirmResetBulanan()" 
                        :disabled="!canManagePayments"
                        :class="!canManagePayments ? 'opacity-50 cursor-not-allowed bg-rose-400' : 'bg-rose-600 hover:bg-rose-700 active:scale-95'"
                        class="px-3.5 py-2.5 text-white rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5 shadow-sm hover:shadow-rose-600/20">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    <span>Reset Bulanan</span>
                </button>
            </div>
        </section>

        <!-- WARGA DATA TABLE CARD -->
        <section class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] sm:text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 w-12 text-center">NO</th>
                            <th class="py-3.5 px-4">NAMA WARGA</th>
                            <th class="py-3.5 px-4">NO. REK PLN</th>
                            <th class="py-3.5 px-4 text-right">TAGIHAN</th>
                            <th class="py-3.5 px-4 text-center">STATUS</th>
                            <th class="py-3.5 px-4 text-center w-28">AKSI (LUNAS)</th>
                            <th class="py-3.5 px-4 text-center w-28" x-show="canManagePayments">KELOLA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        <template x-for="(w, index) in filteredWarga" :key="w.no">
                            <tr class="transition-colors hover:bg-slate-50/80"
                                :class="w.lunas ? 'bg-emerald-50/30' : ''">
                                
                                <!-- NO -->
                                <td class="py-3.5 px-4 font-bold text-slate-400 text-center" x-text="w.no"></td>
                                
                                <!-- NAMA -->
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2">
                                        <span x-text="w.nama"></span>
                                    </div>
                                </td>

                                <!-- NO. REK -->
                                <td class="py-3.5 px-4 font-mono text-slate-600 text-xs">
                                    <span class="px-2 py-1 bg-slate-100 rounded-md border border-slate-200" x-text="w.rek"></span>
                                </td>

                                <!-- TAGIHAN -->
                                <td class="py-3.5 px-4 text-right font-extrabold text-slate-900" x-text="formatRupiah(w.tagihan)"></td>

                                <!-- STATUS BADGE -->
                                <td class="py-3.5 px-4 text-center">
                                    <template x-if="w.lunas">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                            <i data-lucide="check" class="w-3.5 h-3.5 mr-1 text-emerald-600"></i>
                                            LUNAS
                                        </span>
                                    </template>
                                    <template x-if="!w.lunas">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 shadow-sm">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 mr-1 text-rose-600"></i>
                                            BELUM BAYAR
                                        </span>
                                    </template>
                                </td>

                                <!-- AKSI (CHECKBOX EXTRA LARGE & TOUCH FRIENDLY FOR MOBILE) -->
                                <td class="py-3.5 px-4 text-center">
                                    <label class="inline-flex items-center justify-center p-2 rounded-xl transition-colors touch-manipulation"
                                           :class="canManagePayments ? 'cursor-pointer hover:bg-slate-200/50' : 'cursor-not-allowed opacity-60'">
                                        <input type="checkbox" 
                                               :checked="w.lunas" 
                                               :disabled="!canManagePayments"
                                               @change="toggleStatus(w)"
                                               class="w-6 h-6 sm:w-7 sm:h-7 text-emerald-600 rounded-lg border-2 border-slate-300 focus:ring-2 focus:ring-emerald-500 transition cursor-pointer accent-emerald-600">
                                    </label>
                                </td>

                                <!-- KELOLA (EDIT & HAPUS BUTTONS FOR ADMIN & PETUGAS) -->
                                <td class="py-3.5 px-4 text-center" x-show="canManagePayments">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <!-- Edit Button -->
                                        <button @click="openEditWargaModal(w)" 
                                                title="Edit Data Warga"
                                                class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg transition-colors shadow-sm active:scale-95">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>

                                        <!-- Hapus Button -->
                                        <button @click="confirmDeleteWarga(w)" 
                                                title="Hapus Warga"
                                                class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg transition-colors shadow-sm active:scale-95">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- EMPTY STATE IF SEARCH RESULTS ARE EMPTY -->
                        <tr x-show="filteredWarga.length === 0">
                            <td :colspan="canManagePayments ? 7 : 6" class="py-10 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i data-lucide="search-x" class="w-10 h-10 text-slate-300"></i>
                                    <p class="font-medium text-slate-600">Warga tidak ditemukan</p>
                                    <p class="text-xs text-slate-400">Coba ubah kata kunci pencarian Anda</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>

                    <!-- TABLE FOOTER (BARIS TOTAL) -->
                    <tfoot class="bg-slate-800 text-white font-extrabold text-xs sm:text-sm border-t-2 border-slate-700">
                        <!-- Row Total Overall -->
                        <tr>
                            <td colspan="3" class="py-3.5 px-4 text-right uppercase tracking-wider text-slate-300">
                                JUMLAH TAGIHAN (TOTAL KESELURUHAN):
                            </td>
                            <td class="py-3.5 px-4 text-right text-yellow-400 text-base" x-text="formatRupiah(totalTagihan)"></td>
                            <td :colspan="canManagePayments ? 3 : 2" class="py-3.5 px-4 text-slate-400 text-xs font-medium">
                                (<span x-text="wargas.length"></span> Warga <span x-text="currentRt"></span>)
                            </td>
                        </tr>

                        <!-- Row Total Diterima -->
                        <tr class="bg-slate-900 border-t border-slate-700/60">
                            <td colspan="3" class="py-3.5 px-4 text-right uppercase tracking-wider text-emerald-400">
                                JUMLAH DITERIMA (TOTAL LUNAS):
                            </td>
                            <td class="py-3.5 px-4 text-right text-emerald-400 text-base font-extrabold" x-text="formatRupiah(totalDiterima)"></td>
                            <td :colspan="canManagePayments ? 3 : 2" class="py-3.5 px-4 text-emerald-300 text-xs font-semibold">
                                <span x-text="totalLunasCount + ' Warga Sudah Lunas'"></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </main>

    <!-- FOOTER CREDITS -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-6 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center space-y-2">
            <p class="font-semibold text-slate-300">
                Sistem Rekapitulasi Tagihan Listrik <span x-text="currentRt"></span> <span x-text="settings.app_subtitle"></span> &copy; 2026
            </p>
            <p class="text-slate-500" x-text="settings.app_address"></p>
        </div>
    </footer>


    <!-- MODAL: PENGATURAN APLIKASI (APP SETTINGS MODAL) -->
    <div x-show="openAppSettingsModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full overflow-hidden border border-slate-200 my-8 space-y-0">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-slate-950 via-teal-950 to-slate-900 text-white p-5 sm:p-6 flex items-center justify-between border-b border-teal-800/40">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-yellow-400/20 text-yellow-400 rounded-xl border border-yellow-400/30">
                        <i data-lucide="settings" class="w-6 h-6 animate-spin" style="animation-duration: 10s;"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-white">Pengaturan Aplikasi</h3>
                        <p class="text-xs text-teal-200/80 font-medium">Kelola identitas, koneksi cloud, akun admin & hak akses.</p>
                    </div>
                </div>
                <button @click="openAppSettingsModal = false" class="text-slate-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Settings Tabs Navigation -->
            <div class="bg-slate-100 border-b border-slate-200 px-6 pt-3 flex flex-wrap gap-2 text-xs font-bold">
                <button @click="settingsTab = 'identity'" 
                        :class="settingsTab === 'identity' ? 'bg-white text-teal-800 border-t-2 border-teal-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-4 py-2.5 rounded-t-xl transition-all flex items-center gap-1.5">
                    <i data-lucide="sliders" class="w-4 h-4 text-teal-600"></i>
                    <span>Identitas & Periode</span>
                </button>

                <button @click="settingsTab = 'google_sheets'" 
                        :class="settingsTab === 'google_sheets' ? 'bg-white text-teal-800 border-t-2 border-teal-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-4 py-2.5 rounded-t-xl transition-all flex items-center gap-1.5">
                    <i data-lucide="sheet" class="w-4 h-4 text-emerald-600"></i>
                    <span>Google Sheets</span>
                </button>

                <button @click="settingsTab = 'account'" 
                        :class="settingsTab === 'account' ? 'bg-white text-teal-800 border-t-2 border-teal-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-4 py-2.5 rounded-t-xl transition-all flex items-center gap-1.5">
                    <i data-lucide="key-round" class="w-4 h-4 text-amber-600"></i>
                    <span>Akun Admin & Password</span>
                </button>

                <button @click="settingsTab = 'roles'" 
                        :class="settingsTab === 'roles' ? 'bg-white text-teal-800 border-t-2 border-teal-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-4 py-2.5 rounded-t-xl transition-all flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600"></i>
                    <span>Hak Akses Peran</span>
                </button>
            </div>

            <!-- Modal Content Area -->
            <div class="p-6 max-h-[70vh] overflow-y-auto text-xs sm:text-sm">
                
                <!-- NOTICE FOR NON-ADMIN USERS -->
                <template x-if="!isAdmin">
                    <div class="bg-amber-500/10 border border-amber-500/30 text-amber-900 p-4 rounded-xl mb-4 text-xs space-y-1">
                        <div class="font-bold flex items-center gap-1.5 text-amber-800">
                            <i data-lucide="lock" class="w-4 h-4 text-amber-600"></i>
                            <span x-text="userRole === 'petugas' ? 'Akses Pengaturan Terbatas (Peran Petugas)' : 'Akses Read-Only (Peran Warga)'"></span>
                        </div>
                        <p class="text-slate-600">
                            <span x-show="userRole === 'petugas'">Sebagai Petugas Penagih, Anda memiliki wewenang untuk menceklis status tagihan dan menambah/mengedit warga. Pengaturan aplikasi & ganti password akun hanya diperuntukkan bagi <strong>Admin Utama</strong>.</span>
                            <span x-show="isWarga">Sebagai Warga, Anda memiliki hak akses melihat data rekapitulasi tagihan. Pengubahan data atau pengaturan hanya dapat dilakukan oleh Petugas / Admin.</span>
                        </p>
                    </div>
                </template>

                <!-- TAB 1: IDENTITAS & PERIODE -->
                <div x-show="settingsTab === 'identity'" class="space-y-4">
                    <div class="bg-teal-50 border border-teal-200 p-3.5 rounded-xl text-teal-900 text-xs">
                        <i data-lucide="info" class="w-4 h-4 inline mr-1 text-teal-600"></i>
                        <span>Ubah judul header, sub-judul organisasi, alamat sekretariat, dan periode bulan tagihan.</span>
                    </div>

                    <form @submit.prevent="saveAppSettings()" class="space-y-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Judul Aplikasi Header</label>
                            <input type="text" 
                                   x-model="settingsForm.app_title" 
                                   :disabled="!isAdmin"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-teal-500 disabled:opacity-60">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Sub-Judul / Nama Organisasi</label>
                            <input type="text" 
                                   x-model="settingsForm.app_subtitle" 
                                   :disabled="!isAdmin"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-teal-500 disabled:opacity-60">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Alamat Sekretariat</label>
                            <input type="text" 
                                   x-model="settingsForm.app_address" 
                                   :disabled="!isAdmin"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:ring-2 focus:ring-teal-500 disabled:opacity-60">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Periode Bulan & Tahun</label>
                            <input type="text" 
                                   x-model="settingsForm.app_periode" 
                                   :disabled="!isAdmin"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold text-yellow-700 focus:ring-2 focus:ring-teal-500 disabled:opacity-60">
                        </div>

                        <div class="pt-3 flex justify-end" x-show="isAdmin">
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 shadow-md">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span>Simpan Identitas</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: GOOGLE SHEETS -->
                <div x-show="settingsTab === 'google_sheets'" class="space-y-4">
                    <div class="bg-teal-50/70 border border-teal-200 p-4 rounded-xl space-y-3">
                        <label class="block font-bold text-teal-900 text-xs uppercase tracking-wider">
                            URL Web App Google Apps Script:
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="url" 
                                   x-model="settingsForm.google_sheets_url" 
                                   :disabled="!isAdmin"
                                   placeholder="https://script.google.com/macros/s/.../exec"
                                   class="flex-1 px-3.5 py-2.5 bg-white border border-teal-300 rounded-xl text-xs font-mono text-slate-800 focus:ring-2 focus:ring-teal-500 disabled:opacity-60">
                            <button @click="saveAppSettings()" 
                                    x-show="isAdmin"
                                    class="px-4 py-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold rounded-xl text-xs shrink-0 shadow-sm">
                                Simpan URL
                            </button>
                        </div>
                        <p class="text-[11px] text-teal-700">
                            * Jika URL diisi, data status centang warga akan otomatis tersinkron ke Google Sheets Cloud!
                        </p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-xs uppercase">Kode Google Apps Script (`doGet` & `doPost`)</h4>
                            <button @click="copyAppsScriptCode()" 
                                    class="px-3 py-1 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold flex items-center gap-1">
                                <i data-lucide="copy" class="w-3.5 h-3.5 text-teal-400"></i>
                                <span>Salin Kode</span>
                            </button>
                        </div>
                        <pre class="bg-slate-900 text-teal-300 p-4 rounded-xl text-xs font-mono overflow-x-auto max-h-40 border border-slate-800" x-text="appsScriptSnippet"></pre>
                    </div>
                </div>

                <!-- TAB 3: AKUN ADMIN & PASSWORD -->
                <div x-show="settingsTab === 'account'" class="space-y-4">
                    <div class="bg-amber-50 border border-amber-200 p-3.5 rounded-xl text-amber-900 text-xs">
                        <i data-lucide="shield-alert" class="w-4 h-4 inline mr-1 text-amber-600"></i>
                        <span>Ubah Username Admin dan Password login Anda di sini.</span>
                    </div>

                    <form @submit.prevent="saveAccountSettings()" class="space-y-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Username Admin Baru</label>
                            <input type="text" 
                                   x-model="accountForm.username" 
                                   :disabled="!isAdmin"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold text-slate-900 focus:ring-2 focus:ring-teal-500 disabled:opacity-60">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Email Admin</label>
                            <input type="email" 
                                   x-model="accountForm.email" 
                                   :disabled="!isAdmin"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium text-slate-900 focus:ring-2 focus:ring-teal-500 disabled:opacity-60">
                        </div>

                        <div class="border-t border-slate-200 pt-3 space-y-3" x-show="isAdmin">
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Password Saat Ini (Wajib Verifikasi)</label>
                                <input type="password" 
                                       x-model="accountForm.current_password" 
                                       placeholder="Masukkan password lama untuk konfirmasi"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium focus:ring-2 focus:ring-teal-500">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Password Baru (Kosongkan jika tidak diganti)</label>
                                <input type="password" 
                                       x-model="accountForm.new_password" 
                                       placeholder="Masukkan password baru"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-medium focus:ring-2 focus:ring-teal-500">
                            </div>
                        </div>

                        <div class="pt-3 flex justify-end" x-show="isAdmin">
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 shadow-md">
                                <i data-lucide="key" class="w-4 h-4"></i>
                                <span>Simpan Akun & Password</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 4: HAK AKSES PERAN PENGGUNA -->
                <div x-show="settingsTab === 'roles'" class="space-y-4">
                    <div class="space-y-3 text-xs">
                        <h4 class="font-extrabold text-slate-900 text-sm">Hak Akses & Matriks Peran Pengguna (RBAC):</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <!-- Card Admin -->
                            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-amber-900 uppercase">1. Admin (Utama)</span>
                                    <span class="px-2 py-0.5 bg-amber-600 text-white rounded text-[10px] font-bold">FULL ACCESS</span>
                                </div>
                                <ul class="list-disc list-inside text-amber-800 space-y-1">
                                    <li>Menceklis status lunas/belum.</li>
                                    <li>Edit & Hapus data warga.</li>
                                    <li>Pengaturan Aplikasi & Password.</li>
                                    <li>Akun: <strong class="font-mono">admin / admin</strong>.</li>
                                </ul>
                            </div>

                            <!-- Card Petugas -->
                            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-emerald-900 uppercase">2. Petugas Penagih</span>
                                    <span class="px-2 py-0.5 bg-emerald-600 text-white rounded text-[10px] font-bold">AKSES PETUGAS</span>
                                </div>
                                <ul class="list-disc list-inside text-emerald-800 space-y-1">
                                    <li>Menceklis status lunas/belum warga.</li>
                                    <li>Edit & Hapus data warga.</li>
                                    <li>Tambah warga & Reset bulanan.</li>
                                    <li>Akun: <strong class="font-mono">petugas / petugas</strong>.</li>
                                </ul>
                            </div>

                            <!-- Card Warga -->
                            <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-blue-900 uppercase">3. Warga (Viewer)</span>
                                    <span class="px-2 py-0.5 bg-blue-600 text-white rounded text-[10px] font-bold">READ-ONLY</span>
                                </div>
                                <ul class="list-disc list-inside text-blue-800 space-y-1">
                                    <li>Melihat rekap tagihan & statistik.</li>
                                    <li>Pencarian real-time & Export Excel.</li>
                                    <li>Edit/Hapus & pengaturan DIBATASI.</li>
                                    <li>Akun: <strong class="font-mono">warga / warga</strong>.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="bg-slate-100 px-6 py-4 flex justify-between items-center border-t border-slate-200">
                <span class="text-xs text-slate-500 font-medium" 
                      x-text="userRole === 'admin' ? 'Role Active: Admin (Full Access)' : (userRole === 'petugas' ? 'Role Active: Petugas (Payment Access)' : 'Role Active: Warga (Read-Only)')"></span>
                <button @click="openAppSettingsModal = false" 
                        class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT WARGA -->
    <div x-show="showEditWargaModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200">
            <div class="bg-gradient-to-r from-amber-900 to-slate-900 text-white p-5 flex items-center justify-between">
                <h3 class="text-base font-bold flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-amber-400"></i>
                    <span>Edit Data Warga</span>
                </h3>
                <button @click="showEditWargaModal = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form @submit.prevent="saveEditWarga()" class="p-6 space-y-4 text-xs sm:text-sm">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Warga</label>
                    <input type="text" 
                           x-model="editWargaForm.nama" 
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 font-semibold text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">No. Rekening PLN</label>
                    <input type="text" 
                           x-model="editWargaForm.rek" 
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 font-mono text-slate-900">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nominal Tagihan (Rp)</label>
                    <input type="number" 
                           x-model.number="editWargaForm.tagihan" 
                           required
                           min="0"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 font-bold text-slate-900">
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" 
                           id="edit_lunas"
                           x-model="editWargaForm.lunas" 
                           class="w-5 h-5 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500 accent-emerald-600">
                    <label for="edit_lunas" class="font-bold text-slate-800 cursor-pointer">Status Pembayaran LUNAS</label>
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="showEditWargaModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: TAMBAH WARGA -->
    <div x-show="openAddWargaModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200">
            <div class="bg-slate-900 text-white p-5 flex items-center justify-between">
                <h3 class="text-base font-bold flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5 text-teal-400"></i>
                    <span>Tambah Data Warga RT</span>
                </h3>
                <button @click="openAddWargaModal = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form @submit.prevent="saveNewWarga()" class="p-6 space-y-4 text-xs sm:text-sm">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Warga</label>
                    <input type="text" 
                           x-model="newWarga.nama" 
                           required
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 font-semibold">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">No. Rekening PLN</label>
                    <input type="text" 
                           x-model="newWarga.rek" 
                           required
                           placeholder="Contoh: 521030999888"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 font-mono">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nominal Tagihan (Rp)</label>
                    <input type="number" 
                           x-model.number="newWarga.tagihan" 
                           required
                           min="0"
                           placeholder="Contoh: 45000"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 font-bold">
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="openAddWargaModal = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-teal-700 hover:bg-teal-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>


    <!-- ALPINE.JS APPLICATION LOGIC -->
    <script>
        function appData() {
            return {
                initialWargas: [
                    { no: 1, nama: "AGUS SENJOYO", rek: "521031153794", tagihan: 29000, lunas: false },
                    { no: 2, nama: "AMATJUMALI", rek: "521030282735", tagihan: 50000, lunas: true },
                    { no: 3, nama: "AMAT WAKIT", rek: "521030289557", tagihan: 41000, lunas: false },
                    { no: 4, nama: "DIDIK SUTARNO", rek: "521031160904", tagihan: 122000, lunas: true },
                    { no: 5, nama: "DOLAH KOMARI", rek: "521030953431", tagihan: 24000, lunas: false },
                    { no: 6, nama: "EFENDI", rek: "521031208923", tagihan: 88000, lunas: true },
                    { no: 7, nama: "M ZAINURI", rek: "521030823515", tagihan: 51000, lunas: false },
                    { no: 8, nama: "PAIMIN", rek: "521030283425", tagihan: 19000, lunas: true },
                    { no: 9, nama: "PENDI", rek: "521030283409", tagihan: 36000, lunas: true },
                    { no: 10, nama: "SARINDI", rek: "521031050740", tagihan: 47000, lunas: false },
                    { no: 11, nama: "SOMODIMEJO/KAMIJAN", rek: "521031090865", tagihan: 49000, lunas: false },
                    { no: 12, nama: "SOMODIMEJO", rek: "521030289532", tagihan: 78000, lunas: false },
                    { no: 13, nama: "SUDARMAN", rek: "521031036658", tagihan: 53000, lunas: false },
                    { no: 14, nama: "UMAT MUH SUPRIYANTO", rek: "521030852058", tagihan: 40000, lunas: true },
                    { no: 15, nama: "TUMPRADIYONO", rek: "521031377731", tagihan: 31000, lunas: true },
                    { no: 16, nama: "WAHYUDI", rek: "521031427914", tagihan: 27000, lunas: true },
                    { no: 17, nama: "SARJILAH", rek: "521031537564", tagihan: 36000, lunas: false },
                    { no: 18, nama: "M MUNAJI", rek: "521031630599", tagihan: 22000, lunas: true }
                ],

                wargas: [],
                searchQuery: '',
                isLoading: false,
                openAppSettingsModal: false,
                openAddWargaModal: false,
                showEditWargaModal: false,
                editingWargaNo: null,
                settingsTab: 'identity',
                currentRt: '{{ session('selected_rt', 'RT 04') }}',
                userRole: '{{ Auth::user()->role ?? 'admin' }}',
                isAdmin: {{ Auth::user()->isAdmin() ? 'true' : 'false' }},
                canManagePayments: {{ Auth::user()->canManagePayments() ? 'true' : 'false' }},
                isWarga: {{ Auth::user()->isWarga() ? 'true' : 'false' }},

                settings: {
                    app_title: 'REKAPITULASI TAGIHAN LISTRIK',
                    app_subtitle: 'FORMADIKA',
                    app_address: 'Sekretariat: Kentolan Lor, Guwosari, Pajangan, Bantul',
                    app_periode: 'AGUSTUS 2026',
                    google_sheets_url: ''
                },

                settingsForm: {
                    app_title: '',
                    app_subtitle: '',
                    app_address: '',
                    app_periode: '',
                    google_sheets_url: ''
                },

                accountForm: {
                    username: '',
                    email: '',
                    current_password: '',
                    new_password: ''
                },
                
                newWarga: {
                    nama: '',
                    rek: '',
                    tagihan: 0
                },

                editWargaForm: {
                    no: null,
                    nama: '',
                    rek: '',
                    tagihan: 0,
                    lunas: false
                },

                appsScriptSnippet: `function doGet(e) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  var data = sheet.getDataRange().getValues();
  if (data.length <= 1) return responseJSON({status:"success", data:[]});
  var result = [];
  for (var i = 1; i < data.length; i++) {
    if(!data[i][0]) continue;
    result.push({
      no: Number(data[i][0]),
      nama: String(data[i][1]),
      rek: String(data[i][2]),
      tagihan: Number(data[i][3]),
      lunas: data[i][4] === true || String(data[i][4]).toLowerCase() === "true"
    });
  }
  return responseJSON({status:"success", data:result});
}

function doPost(e) {
  var contents = JSON.parse(e.postData.contents);
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
  if (contents.action === "update_status") {
    var data = sheet.getDataRange().getValues();
    for (var i = 1; i < data.length; i++) {
      if (Number(data[i][0]) === Number(contents.no)) {
        sheet.getRange(i + 1, 5).setValue(contents.lunas);
        return responseJSON({status:"success"});
      }
    }
  }
  if (contents.action === "reset_all") {
    var lastRow = sheet.getLastRow();
    if (lastRow > 1) sheet.getRange(2, 5, lastRow - 1, 1).setValue(false);
    return responseJSON({status:"success"});
  }
  return responseJSON({status:"error"});
}

function responseJSON(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj)).setMimeType(ContentService.MimeType.JSON);
}`,

                // FILTERED WARGA SEARCH COMPUTED
                get filteredWarga() {
                    if (!this.searchQuery.trim()) {
                        return this.wargas;
                    }
                    const q = this.searchQuery.toLowerCase();
                    return this.wargas.filter(w => 
                        w.nama.toLowerCase().includes(q) || 
                        w.rek.includes(q)
                    );
                },

                get totalTagihan() {
                    return this.wargas.reduce((sum, item) => sum + (Number(item.tagihan) || 0), 0);
                },

                get totalDiterima() {
                    return this.wargas.reduce((sum, item) => item.lunas ? sum + (Number(item.tagihan) || 0) : sum, 0);
                },

                get sisaTagihan() {
                    return this.totalTagihan - this.totalDiterima;
                },

                get totalLunasCount() {
                    return this.wargas.filter(item => item.lunas).length;
                },

                get persentaseLunas() {
                    if (this.wargas.length === 0) return 0;
                    return Math.round((this.totalLunasCount / this.wargas.length) * 100);
                },

                // INITIALIZE APP
                async initApp() {
                    await this.loadAppSettings();
                    await this.loadData();
                    
                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                },

                // LOAD APP SETTINGS FROM API
                async loadAppSettings() {
                    try {
                        const res = await fetch('/api/settings');
                        const json = await res.json();
                        if (json.status === 'success') {
                            this.settings = { ...this.settings, ...json.data };
                            this.settingsForm = { ...this.settings };
                            if (json.user) {
                                this.userRole = json.user.role;
                                this.isAdmin = json.user.is_admin;
                                this.canManagePayments = json.user.role === 'admin' || json.user.role === 'petugas';
                                this.isWarga = json.user.role === 'warga' || json.user.role === 'viewer';
                                this.accountForm.username = json.user.name;
                                this.accountForm.email = json.user.email;
                            }
                        }
                    } catch (e) {
                        console.log('App settings load error', e);
                    }
                },

                // SAVE APP IDENTITY SETTINGS
                async saveAppSettings() {
                    if (!this.isAdmin) {
                        Swal.fire('Akses Ditolak', 'Hanya Admin Utama yang dapat menyimpan pengaturan aplikasi.', 'error');
                        return;
                    }

                    try {
                        const res = await fetch('/api/settings', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.settingsForm)
                        });
                        const json = await res.json();
                        if (json.status === 'success') {
                            this.settings = { ...this.settingsForm };
                            Swal.fire('Berhasil', 'Pengaturan aplikasi berhasil disimpan.', 'success');
                        } else {
                            Swal.fire('Gagal', json.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Gagal menyimpan pengaturan.', 'error');
                    }
                },

                // SAVE ACCOUNT CREDENTIALS & PASSWORD
                async saveAccountSettings() {
                    if (!this.isAdmin) {
                        Swal.fire('Akses Ditolak', 'Hanya Admin Utama yang dapat mengubah password.', 'error');
                        return;
                    }

                    try {
                        const res = await fetch('/api/settings/account', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.accountForm)
                        });
                        const json = await res.json();
                        if (json.status === 'success') {
                            this.accountForm.current_password = '';
                            this.accountForm.new_password = '';
                            Swal.fire('Berhasil', 'Username/Password Admin berhasil diperbarui!', 'success');
                        } else {
                            Swal.fire('Gagal', json.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Gagal memperbarui akun admin.', 'error');
                    }
                },

                // LOAD WARGA DATA
                async loadData() {
                    this.isLoading = true;

                    if (this.settings.google_sheets_url) {
                        try {
                            const res = await fetch(this.settings.google_sheets_url);
                            const json = await res.json();
                            if (json.status === 'success' && Array.isArray(json.data) && json.data.length > 0) {
                                this.wargas = json.data;
                                this.saveLocalCache();
                                this.isLoading = false;
                                return;
                            }
                        } catch (err) {}
                    }

                    try {
                        const res = await fetch('/api/warga');
                        const json = await res.json();
                        if (json.status === 'success' && Array.isArray(json.data) && json.data.length > 0) {
                            this.wargas = json.data;
                            this.saveLocalCache();
                            this.isLoading = false;
                            return;
                        }
                    } catch (err) {}

                    const cached = localStorage.getItem('warga_data_cache');
                    if (cached) {
                        try {
                            this.wargas = JSON.parse(cached);
                            this.isLoading = false;
                            return;
                        } catch(e) {}
                    }

                    this.wargas = [...this.initialWargas];
                    this.saveLocalCache();
                    this.isLoading = false;
                },

                // TOGGLE STATUS (ADMIN & PETUGAS ONLY)
                async toggleStatus(warga) {
                    if (!this.canManagePayments) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Mode Warga (Read-Only)',
                            text: 'Warga hanya dapat melihat rekap data. Pencatatan status lunas hanya dapat dilakukan oleh Petugas atau Admin.',
                            timer: 3500
                        });
                        return;
                    }

                    warga.lunas = !warga.lunas;
                    this.saveLocalCache();
                    this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });

                    fetch('/api/warga/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ no: warga.no, lunas: warga.lunas })
                    });

                    if (this.settings.google_sheets_url) {
                        fetch(this.settings.google_sheets_url, {
                            method: 'POST',
                            mode: 'no-cors',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ action: 'update_status', no: warga.no, lunas: warga.lunas })
                        });
                    }
                },

                // OPEN EDIT WARGA MODAL
                openEditWargaModal(w) {
                    if (!this.canManagePayments) return;

                    this.editingWargaNo = w.no;
                    this.editWargaForm = {
                        no: w.no,
                        nama: w.nama,
                        rek: w.rek,
                        tagihan: w.tagihan,
                        lunas: w.lunas
                    };
                    this.showEditWargaModal = true;
                    this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                },

                // SAVE EDIT WARGA
                async saveEditWarga() {
                    if (!this.canManagePayments) return;

                    const target = this.wargas.find(item => item.no === this.editingWargaNo);
                    if (target) {
                        target.nama = this.editWargaForm.nama.toUpperCase();
                        target.rek = this.editWargaForm.rek;
                        target.tagihan = Number(this.editWargaForm.tagihan) || 0;
                        target.lunas = this.editWargaForm.lunas;
                        this.saveLocalCache();
                    }

                    fetch(`/api/warga/${this.editingWargaNo}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.editWargaForm)
                    });

                    this.showEditWargaModal = false;
                    Swal.fire('Berhasil', 'Data warga berhasil diperbarui.', 'success');
                    this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                },

                // CONFIRM DELETE WARGA
                confirmDeleteWarga(w) {
                    if (!this.canManagePayments) return;

                    Swal.fire({
                        title: 'Hapus Data Warga?',
                        html: `Apakah Anda yakin ingin menghapus data warga <strong>${w.nama}</strong> (${w.rek})?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus Data',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            this.wargas = this.wargas.filter(item => item.no !== w.no);
                            this.wargas.forEach((item, index) => item.no = index + 1);
                            this.saveLocalCache();

                            fetch(`/api/warga/${w.no}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });

                            Swal.fire('Terhapus!', 'Data warga telah berhasil dihapus.', 'success');
                            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                        }
                    });
                },

                // RESET BULANAN (ADMIN & PETUGAS ONLY)
                confirmResetBulanan() {
                    if (!this.canManagePayments) {
                        Swal.fire('Akses Ditolak', 'Hanya Petugas atau Admin yang dapat mereset tagihan.', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Reset Pembayaran Bulanan?',
                        text: 'Semua status warga akan diubah kembali menjadi "BELUM BAYAR" secara massal.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Reset Semua!',
                        cancelButtonText: 'Batal'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            this.wargas.forEach(w => w.lunas = false);
                            this.saveLocalCache();

                            fetch('/api/warga/reset', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });

                            if (this.settings.google_sheets_url) {
                                fetch(this.settings.google_sheets_url, {
                                    method: 'POST',
                                    mode: 'no-cors',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ action: 'reset_all' })
                                });
                            }

                            Swal.fire('Berhasil!', 'Semua status warga telah direset menjadi BELUM BAYAR.', 'success');
                            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                        }
                    });
                },

                // EXPORT EXCEL (ALL ROLES)
                exportToExcel() {
                    try {
                        const exportData = this.filteredWarga.map(w => ({
                            "NO": w.no,
                            "NAMA WARGA": w.nama,
                            "NO. REK PLN": w.rek,
                            "TAGIHAN (RP)": w.tagihan,
                            "STATUS PEMBAYARAN": w.lunas ? "LUNAS" : "BELUM BAYAR"
                        }));

                        exportData.push({});
                        exportData.push({ "NAMA WARGA": "JUMLAH TAGIHAN (TOTAL KESELURUHAN)", "TAGIHAN (RP)": this.totalTagihan });
                        exportData.push({ "NAMA WARGA": "JUMLAH DITERIMA (TOTAL LUNAS)", "TAGIHAN (RP)": this.totalDiterima });
                        exportData.push({ "NAMA WARGA": "SISA TAGIHAN (BELUM BAYAR)", "TAGIHAN (RP)": this.sisaTagihan });

                        const worksheet = XLSX.utils.json_to_sheet(exportData);
                        const workbook = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(workbook, worksheet, `Rekap Tagihan ${this.currentRt}`);

                        XLSX.writeFile(workbook, `Rekap_Tagihan_Listrik_${this.currentRt.replace(/\s+/g, '_')}_${this.settings.app_periode.replace(/\s+/g, '_')}.xlsx`);

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'File Excel Berhasil Diunduh!',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } catch (error) {
                        Swal.fire('Error', 'Gagal membuat file Excel: ' + error.message, 'error');
                    }
                },

                // SAVE NEW WARGA (ADMIN & PETUGAS ONLY)
                async saveNewWarga() {
                    if (!this.canManagePayments) {
                        Swal.fire('Akses Ditolak', 'Hanya Petugas atau Admin yang dapat menambah warga.', 'error');
                        return;
                    }
                    if (!this.newWarga.nama || !this.newWarga.rek) return;

                    const newNo = this.wargas.length + 1;
                    const item = {
                        no: newNo,
                        nama: this.newWarga.nama.toUpperCase(),
                        rek: this.newWarga.rek,
                        tagihan: Number(this.newWarga.tagihan) || 0,
                        lunas: false
                    };

                    this.wargas.push(item);
                    this.saveLocalCache();

                    fetch('/api/warga/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(item)
                    });

                    this.openAddWargaModal = false;
                    this.newWarga = { nama: '', rek: '', tagihan: 0 };
                    Swal.fire('Berhasil', 'Warga baru telah ditambahkan.', 'success');
                    this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                },

                copyAppsScriptCode() {
                    navigator.clipboard.writeText(this.appsScriptSnippet);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Kode berhasil disalin ke clipboard!',
                        showConfirmButton: false,
                        timer: 2500
                    });
                },

                saveLocalCache() {
                    localStorage.setItem('warga_data_cache', JSON.stringify(this.wargas));
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0
                    }).format(number || 0);
                }
            };
        }
    </script>
</body>
</html>
