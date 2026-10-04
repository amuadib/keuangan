<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ isset($title) ? $title . ' - ' : '' }}{{ App\Models\Setting::getValue('nama_aplikasi', 'Sistem Keuangan') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>
            @media print {
                body { background-color: white; }
                nav { display: none !important; }
            }
        </style>
        <script>
            function applyTheme() {
                if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
            
            applyTheme();

            // Matikan dark mode secara paksa saat mencetak dokumen
            window.addEventListener('beforeprint', () => {
                document.documentElement.classList.remove('dark');
            });

            // Kembalikan ke mode sebelumnya setelah selesai mencetak
            window.addEventListener('afterprint', () => {
                applyTheme();
            });
        </script>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased text-gray-900 dark:text-gray-100 transition-colors duration-200">
        
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 print:hidden shadow-sm transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="font-extrabold text-xl text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition">
                                {{ App\Models\Setting::getValue('nama_aplikasi', 'Sistem Keuangan') }}
                            </a>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('transactions') }}" class="{{ request()->routeIs('transactions') ? 'border-indigo-500 text-gray-900 dark:text-white dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Transaksi & Laporan
                            </a>
                            <a href="{{ route('receipt') }}" class="{{ request()->routeIs('receipt') ? 'border-indigo-500 text-gray-900 dark:text-white dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Kwitansi
                            </a>
                            <a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'border-indigo-500 text-gray-900 dark:text-white dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Kategori Laporan
                            </a>
                            <a href="{{ route('recipients') }}" class="{{ request()->routeIs('recipients') ? 'border-indigo-500 text-gray-900 dark:text-white dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Penerima
                            </a>
                            <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'border-indigo-500 text-gray-900 dark:text-white dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Pengaturan
                            </a>
                            <a href="{{ route('users') }}" class="{{ request()->routeIs('users') ? 'border-indigo-500 text-gray-900 dark:text-white dark:border-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Pengguna
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2" x-data="{ 
                        darkMode: document.documentElement.classList.contains('dark'),
                        toggle() {
                            this.darkMode = !this.darkMode;
                            if (this.darkMode) {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('color-theme', 'dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('color-theme', 'light');
                            }
                        }
                    }">
                        <button @click="toggle()" type="button" class="cursor-pointer text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 focus:outline-none rounded-lg text-sm p-2">
                            <svg x-show="!darkMode" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="cursor-pointer border border-red-200 dark:border-red-900 px-2 py-1 rounded bg-red-50 dark:bg-red-900/30 text-sm font-medium text-red-500 dark:text-red-400 hover:text-white dark:hover:text-white hover:bg-red-700 dark:hover:bg-red-600 transition-colors">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
