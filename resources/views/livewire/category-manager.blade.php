<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8 font-sans">
    <div class="bg-white shadow-xl sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Kelola Kategori Laporan</h2>
        
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="saveCategory" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-8 space-y-4">
            <h3 class="text-lg font-medium text-gray-900">{{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Laporan</label>
                    <input type="text" wire:model="name" required placeholder="Contoh: Laporan Keuangan Masjid" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Unik (singkatan)</label>
                    <input type="text" wire:model="code" required placeholder="Contoh: masjid" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                @if($categoryId)
                    <button type="button" wire:click="cancelEdit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </button>
                @endif
                <button type="submit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    {{ $categoryId ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                </button>
            </div>
        </form>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Kategori</h3>
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Nama Laporan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">{{ $category->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button wire:click="editCategory({{ $category->id }})" class="cursor-pointer text-yellow-600 hover:text-yellow-900 mr-2 border border-yellow-200 px-2 py-1 rounded bg-yellow-50">Edit</button>
                                <button wire:click="deleteCategory({{ $category->id }})" class="cursor-pointer text-red-600 hover:text-red-900 mr-2 border border-red-200 px-2 py-1 rounded bg-red-50" onclick="confirm('Yakin ingin menghapus kategori ini?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada kategori laporan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
