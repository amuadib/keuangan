<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8 font-sans">
    
    <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 print:hidden transition-colors duration-200">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">Kelola Pengguna Aplikasi</h2>
        
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 dark:bg-green-900/40 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 dark:bg-red-900/40 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="saveUser" class="space-y-4 mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                    <input type="text" wire:model="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" wire:model="email" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password {{ $isEditMode ? '(Kosongkan jika tidak ingin mengubah)' : '' }}</label>
                    <input type="password" wire:model="password" {{ $isEditMode ? '' : 'required' }} minlength="6" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password') <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="flex justify-end gap-2 mt-4">
                @if($isEditMode)
                <button type="button" wire:click="resetInputFields" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
                @endif
                <button type="submit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $isEditMode ? 'Simpan Perubahan' : 'Tambah Pengguna' }}
                </button>
            </div>
        </form>
        
        <hr class="mb-6 border-gray-200 dark:border-gray-700">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Daftar Pengguna</h3>
        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="editUser({{ $user->id }})" class="cursor-pointer text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 mr-2 border border-yellow-200 dark:border-yellow-800 px-2 py-1 rounded bg-yellow-50 dark:bg-yellow-900/30">Edit</button>
                            <button wire:click="deleteUser({{ $user->id }})" class="cursor-pointer text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 mr-2 border border-red-200 dark:border-red-800 px-2 py-1 rounded bg-red-50 dark:bg-red-900/30" onclick="confirm('Yakin ingin menghapus pengguna ini?') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
