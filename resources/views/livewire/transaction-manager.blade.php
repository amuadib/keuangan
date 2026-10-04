<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8 font-sans">
    <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 transition-colors duration-200">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">Manajemen Transaksi</h2>
        
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-end mb-8 bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-lg border border-indigo-100 dark:border-indigo-800/50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-1 w-full md:w-auto">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Laporan</label>
                    <select wire:model.live="reportType" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->code }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                    <select wire:model.live="month" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->locale('id')->monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                    <select wire:model.live="year" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (range(date('Y') - 5, date('Y') + 5) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 md:mt-0 md:ml-6">
                <a href="{{ route('report', ['reportType' => $reportType, 'month' => $month, 'year' => $year]) }}" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-green-600 hover:bg-green-700 w-full md:w-auto text-center">
                    🖨️ Cetak Laporan Bulanan
                </a>
            </div>
        </div>

        <form wire:submit.prevent="saveTransaction" class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700 mb-8 space-y-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $editingId ? 'Edit Transaksi' : 'Tambah Transaksi Baru' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                    <input type="date" wire:model="date" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Uraian</label>
                    <input type="text" wire:model="description" required placeholder="Contoh: Honor OP, Bayar Wifi..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penerima (Opsional)</label>
                    <select wire:model="recipient_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Penerima --</option>
                        @foreach($recipients as $recipient)
                            <option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                    <select wire:model="type" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="income">Masuk</option>
                        <option value="expense">Keluar</option>
                    </select>
                </div>
                <div x-data="{
                    rawAmount: @entangle('amount'),
                    get formatted() {
                        if (!this.rawAmount) return '';
                        return new Intl.NumberFormat('id-ID').format(this.rawAmount);
                    },
                    set formatted(value) {
                        this.rawAmount = value.replace(/\D/g, '');
                    }
                }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah (Rp)</label>
                    <input type="text" x-model="formatted" required placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right">
                </div>
            </div>
            <div class="flex justify-end space-x-2">
                @if($editingId)
                    <button type="button" wire:click="cancelEdit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Batal
                    </button>
                @endif
                <button type="submit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    {{ $editingId ? 'Perbarui Transaksi' : 'Simpan Transaksi' }}
                </button>
            </div>
        </form>

        {{-- Transactions List --}}
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Daftar Transaksi</h3>
            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uraian</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Penerima</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah (Rp)</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ Carbon\Carbon::parse($trx->date)->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $trx->description }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $trx->recipient ? $trx->recipient->name : '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($trx->type == 'income' || $trx->type == 'initial_balance')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300">Masuk</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300">Keluar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('receipt', ['for_payment' => $trx->description, 'amount' => $trx->amount, 'date' => $trx->date, 'receiver_name' => $trx->recipient ? $trx->recipient->name : null]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-4 font-bold border border-indigo-200 dark:border-indigo-800 px-2 py-1 rounded bg-indigo-50 dark:bg-indigo-900/30">Buat Kwitansi</a>
                                <button wire:click="editTransaction({{ $trx->id }})" class="cursor-pointer text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 mr-2 border border-yellow-200 dark:border-yellow-800 px-2 py-1 rounded bg-yellow-50 dark:bg-yellow-900/30">Edit</button>
                                <button wire:click="deleteTransaction({{ $trx->id }})" class="cursor-pointer text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 mr-2 border border-red-200 dark:border-red-800 px-2 py-1 rounded bg-red-50 dark:bg-red-900/30" onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada transaksi di bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
