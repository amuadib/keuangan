<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8 font-sans">
    <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 transition-colors duration-200">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">Kelola Pengaturan (Settings)</h2>
        
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 dark:bg-green-900/40 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="saveSetting" class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700 mb-8 space-y-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $settingId ? 'Edit Pengaturan' : 'Tambah Pengaturan Baru' }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kunci (Key)</label>
                    <input type="text" wire:model="key" required placeholder="Contoh: ketua_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('key') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nilai (Value)</label>
                    <input type="text" wire:model="value" placeholder="Contoh: Budi Santoso, S.Pd" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                @if($settingId)
                    <button type="button" wire:click="cancelEdit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Batal
                    </button>
                @endif
                <button type="submit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    {{ $settingId ? 'Simpan Perubahan' : 'Tambah Pengaturan' }}
                </button>
            </div>
        </form>

        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Daftar Pengaturan</h3>
            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Kunci (Key)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Nilai (Value)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($settings as $setting)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 font-mono">{{ $setting->key }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 font-bold">{{ $setting->value }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button wire:click="editSetting({{ $setting->id }})" class="cursor-pointer text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 mr-2 border border-yellow-200 dark:border-yellow-800 px-2 py-1 rounded bg-yellow-50 dark:bg-yellow-900/30">Edit</button>
                                <button wire:click="deleteSetting({{ $setting->id }})" class="cursor-pointer text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 mr-2 border border-red-200 dark:border-red-800 px-2 py-1 rounded bg-red-50 dark:bg-red-900/30" onclick="confirm('Yakin ingin menghapus pengaturan ini?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada pengaturan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
