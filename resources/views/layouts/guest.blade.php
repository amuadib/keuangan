<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ App\Models\Setting::getValue('nama_aplikasi', 'Sistem Keuangan') }} - Login</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-100 font-sans antialiased text-gray-900 flex items-center justify-center min-h-screen">
        <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 flex justify-center">
                <span class="font-extrabold text-2xl text-indigo-600">{{ App\Models\Setting::getValue('nama_aplikasi', 'Sistem Keuangan') }}</span>
            </div>
            {{ $slot }}
        </div>
        @livewireScripts
    </body>
</html>
