<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8 font-sans">
    <div class="bg-white shadow-xl sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Manajemen Transaksi</h2>
        
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-end mb-8 bg-indigo-50 p-4 rounded-lg border border-indigo-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-1 w-full md:w-auto">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Laporan</label>
                    <select wire:model.live="reportType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->code }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bulan</label>
                    <select wire:model.live="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">{{ Carbon\Carbon::create()->month($m)->locale('id')->monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun</label>
                    <select wire:model.live="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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

        <form wire:submit.prevent="saveTransaction" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-8 space-y-4">
            <h3 class="text-lg font-medium text-gray-900">Tambah Transaksi Baru</h3>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" wire:model="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Uraian</label>
                    <input type="text" wire:model="description" required placeholder="Contoh: Honor OP, Bayar Wifi..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Penerima (Opsional)</label>
                    <select wire:model="recipient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Penerima --</option>
                        @foreach($recipients as $recipient)
                            <option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis</label>
                    <select wire:model="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="income">Pemasukan (Masuk)</option>
                        <option value="expense">Pengeluaran (Keluar)</option>
                        <option value="initial_balance">Saldo Awal</option>
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
                    <label class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                    <input type="text" x-model="formatted" required placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Simpan Transaksi
                </button>
            </div>
        </form>

        {{-- Transactions List --}}
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Transaksi</h3>
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah (Rp)</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ Carbon\Carbon::parse($trx->date)->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $trx->description }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $trx->recipient ? $trx->recipient->name : '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($trx->type == 'income' || $trx->type == 'initial_balance')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Masuk</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 text-right">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('receipt', ['for_payment' => $trx->description, 'amount' => $trx->amount, 'date' => $trx->date, 'receiver_name' => $trx->recipient ? $trx->recipient->name : null]) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold border border-indigo-200 px-2 py-1 rounded bg-indigo-50">Buat Kwitansi</a>
                                <button wire:click="deleteTransaction({{ $trx->id }})" class="text-red-600 hover:text-red-900" onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada transaksi di bulan ini.</td>
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
