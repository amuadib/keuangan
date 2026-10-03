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
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        
        <nav class="bg-white border-b border-gray-200 print:hidden shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="font-extrabold text-xl text-indigo-600 hover:text-indigo-800 transition">
                                {{ App\Models\Setting::getValue('nama_aplikasi', 'Sistem Keuangan') }}
                            </a>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('transactions') }}" class="{{ request()->routeIs('transactions') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Transaksi & Laporan
                            </a>
                            <a href="{{ route('receipt') }}" class="{{ request()->routeIs('receipt') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Kwitansi
                            </a>
                            <a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Kategori Laporan
                            </a>
                            <a href="{{ route('recipients') }}" class="{{ request()->routeIs('recipients') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Penerima
                            </a>
                            <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Pengaturan
                            </a>
                            <a href="{{ route('users') }}" class="{{ request()->routeIs('users') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Pengguna
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="cursor-pointer border border-red-200 px-2 py-1 rounded bg-red-50 text-sm font-medium text-red-500 hover:text-white hover:bg-red-700">
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
