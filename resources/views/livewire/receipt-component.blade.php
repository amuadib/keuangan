<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-8 font-sans">
    
    @if(!$showPrintView)
    <div class="bg-white shadow-xl sm:rounded-lg p-6 print:hidden">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Buat Kwitansi</h2>
        
        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="saveReceipt" class="space-y-4 mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kwitansi No</label>
                    <input type="text" wire:model="receipt_number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sudah terima dari</label>
                    <input type="text" wire:model="received_from" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div x-data="{
                    rawAmount: @entangle('amount').live,
                    get formatted() {
                        if (!this.rawAmount) return '';
                        return new Intl.NumberFormat('id-ID').format(this.rawAmount);
                    },
                    set formatted(value) {
                        this.rawAmount = value.replace(/\D/g, '');
                    }
                }">
                    <label class="block text-sm font-medium text-gray-700">Jumlah Uang (Rp)</label>
                    <input type="text" x-model="formatted" required placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-right">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Terbilang</label>
                    <span class="block w-full py-2 px-3">{{ $amount_words ?: 'Nol rupiah' }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Untuk pembayaran</label>
                    <input type="text" wire:model="for_payment" required placeholder="Contoh: HONOR OP DAPODIK SEPTEMBER 2026" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat</label>
                        <input type="text" wire:model="place" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" wire:model="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                    <input type="text" wire:model="receiver_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Kwitansi
                </button>
            </div>
        </form>
        
        <hr class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Daftar Kwitansi</h3>
            <button wire:click="showPrint" class="cursor-pointer inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                🖨️ Cetak Terpilih
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                            Pilih
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Kwitansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Untuk Pembayaran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($receipts as $receipt)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <input type="checkbox" wire:model="selectedReceiptIds" value="{{ $receipt->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $receipt->receipt_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Carbon\Carbon::parse($receipt->date)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($receipt->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $receipt->for_payment }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                            <button wire:click="deleteReceipt({{ $receipt->id }})" class="cursor-pointer text-red-600 hover:text-red-900 mr-2 border border-red-200 px-2 py-1 rounded bg-red-50" onclick="confirm('Yakin hapus kwitansi ini?') || event.stopImmediatePropagation()">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($showPrintView)
    <div class="mb-4 print:hidden">
        <button wire:click="hidePrint" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            &larr; Kembali
        </button>
        <button onclick="window.print()" class="ml-4 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-base font-bold rounded-md text-white bg-green-600 hover:bg-green-700">
            🖨️ Cetak Semua Kwitansi
        </button>
    </div>

    @foreach($selectedReceiptsToPrint as $selectedReceipt)
    {{-- Kwitansi Print View --}}
    <div class="bg-white p-6 sm:p-10 w-full max-w-4xl mx-auto border border-black text-black print:p-8 print:m-0 print:border-black mb-8" style="font-family: Arial, sans-serif; page-break-after: always; break-after: page;">
        <table class="w-full text-sm sm:text-base leading-relaxed mb-6">
            <tr>
                <td class="w-[25%] font-semibold pb-8 align-top">Kwitansi No</td>
                <td class="w-4 font-semibold pb-8 align-top">:</td>
                <td class="pb-8 font-bold align-top">{{ $selectedReceipt->receipt_number }}</td>
            </tr>
            <tr>
                <td class="w-[25%] pb-1 align-top">Sudah terima dari</td>
                <td class="w-4 pb-1 align-top">:</td>
                <td class="pb-1 align-top">{{ $selectedReceipt->received_from }}</td>
            </tr>
            <tr>
                <td class="w-[25%] pb-1 align-top">Uang sejumlah</td>
                <td class="w-4 pb-1 align-top">:</td>
                <td class="pb-1 align-top">{{ $selectedReceipt->amount_words }}</td>
            </tr>
            <tr>
                <td class="w-[25%] pb-6 align-top">Untuk pembayaran</td>
                <td class="w-4 pb-6 align-top">:</td>
                <td class="pb-6 align-top">{{ $selectedReceipt->for_payment }}</td>
            </tr>
        </table>

        <div class="flex justify-between items-start mt-4">
            <div class="bg-gray-800 text-white font-bold text-lg px-4 py-1 flex items-center print:bg-gray-800 print:text-white" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                <span class="mr-12 italic text-gray-200">Rp</span> 
                <span>{{ number_format($selectedReceipt->amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="text-left text-sm sm:text-base mr-8">
                <p class="mb-1">{{ $selectedReceipt->place }}, {{ Carbon\Carbon::parse($selectedReceipt->date)->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="mb-16">Penerima</p>
                <p class="font-bold underline">{{ strtoupper($selectedReceipt->receiver_name) }}</p>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
