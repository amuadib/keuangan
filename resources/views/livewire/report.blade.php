<div class="w-full bg-white font-sans text-black print:p-0 print:m-0">
    <div class="max-w-5xl mx-auto py-8 px-4 print:py-0 print:px-0">
        
        <div class="mb-6 print:hidden">
            <a href="{{ route('transactions') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                &larr; Kembali ke Transaksi
            </a>
            <button onclick="window.print()" class="ml-4 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-base font-bold rounded-md text-white bg-green-600 hover:bg-green-700">
                🖨️ Cetak Laporan
            </button>
        </div>

        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold uppercase" style="font-family: Arial, sans-serif;">
                LAPORAN KEUANGAN {{ strtoupper(str_replace(['LAPORAN KEUANGAN ', 'Laporan Keuangan '], '', $category->name ?? $reportType)) }}
            </h1>
            <p class="text-md uppercase">
                {{ Carbon\Carbon::create()->month((int) $month)->locale('id')->monthName }} {{ $year }}
            </p>
        </div>

        <table class="w-full border-collapse border border-black mb-10 text-sm" style="font-family: Arial, sans-serif;">
            <thead>
                <tr>
                    <th class="border border-black px-2 py-1 text-center font-bold w-10">NO</th>
                    <th class="border border-black px-2 py-1 text-center font-bold w-24">TANGGAL</th>
                    <th class="border border-black px-2 py-1 text-center font-bold">URAIAN</th>
                    <th class="border border-black px-2 py-1 text-center font-bold w-32">MASUK</th>
                    <th class="border border-black px-2 py-1 text-center font-bold w-32">KELUAR</th>
                    <th class="border border-black px-2 py-1 text-center font-bold w-32">SALDO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentBalance = $previousBalance;
                    $totalIncome = 0;
                    $totalExpense = 0;
                    $no = 1;
                @endphp
                
                @if($previousBalance > 0)
                <tr>
                    <td class="border border-black px-2 py-1 text-right">{{ $no++ }}</td>
                    <td class="border border-black px-2 py-1 text-center">{{ Carbon\Carbon::create((int) $year, (int) $month, 1)->format('d-m-Y') }}</td>
                    <td class="border border-black px-2 py-1">{{ $reportType == 'wifi' ? 'SALDO BULAN LALU' : 'SALDO SEBELUMNYA' }}</td>
                    <td class="border border-black px-2 py-1">
                        <div class="flex justify-between"><span>Rp</span> <span>{{ number_format($previousBalance, 0, ',', '.') }}</span></div>
                    </td>
                    <td class="border border-black px-2 py-1">
                        <div class="flex justify-between"><span>Rp</span> <span>-</span></div>
                    </td>
                    <td class="border border-black px-2 py-1">
                        <div class="flex justify-between"><span>Rp</span> <span>{{ number_format($currentBalance, 0, ',', '.') }}</span></div>
                    </td>
                </tr>
                @php
                    $totalIncome += $previousBalance;
                @endphp
                @endif

                @foreach($transactions as $trx)
                    @php
                        if ($trx->type == 'income' || $trx->type == 'initial_balance') {
                            $currentBalance += $trx->amount;
                            $totalIncome += $trx->amount;
                        } else {
                            $currentBalance -= $trx->amount;
                            $totalExpense += $trx->amount;
                        }
                    @endphp
                    <tr>
                        <td class="border border-black px-2 py-1 text-right">{{ $no++ }}</td>
                        <td class="border border-black px-2 py-1 text-center">{{ Carbon\Carbon::parse($trx->date)->format('d-m-Y') }}</td>
                        <td class="border border-black px-2 py-1">{{ $trx->description }}</td>
                        <td class="border border-black px-2 py-1">
                            <div class="flex justify-between">
                                <span>Rp</span> 
                                <span>{{ ($trx->type == 'income' || $trx->type == 'initial_balance') && $trx->amount > 0 ? number_format($trx->amount, 0, ',', '.') : '-' }}</span>
                            </div>
                        </td>
                        <td class="border border-black px-2 py-1">
                            <div class="flex justify-between">
                                <span>Rp</span> 
                                <span>{{ $trx->type == 'expense' && $trx->amount > 0 ? number_format($trx->amount, 0, ',', '.') : '-' }}</span>
                            </div>
                        </td>
                        <td class="border border-black px-2 py-1">
                            <div class="flex justify-between">
                                <span>Rp</span> 
                                <span>{{ number_format($currentBalance, 0, ',', '.') }}</span>
                            </div>
                        </td>
                    </tr>
                @endforeach

                {{-- Empty rows --}}
                @for($i = $no; $i <= max(8, $no + 2); $i++)
                    <tr>
                        <td class="border border-black px-2 py-1 text-right">{{ $i }}</td>
                        <td class="border border-black px-2 py-1"></td>
                        <td class="border border-black px-2 py-1"></td>
                        <td class="border border-black px-2 py-1"></td>
                        <td class="border border-black px-2 py-1"></td>
                        <td class="border border-black px-2 py-1"></td>
                    </tr>
                @endfor

                <tr class="font-bold">
                    <td colspan="3" class="border border-black px-2 py-1 text-center">JUMLAH</td>
                    <td class="border border-black px-2 py-1">
                        <div class="flex justify-between"><span>Rp</span> <span>{{ number_format($totalIncome, 0, ',', '.') }}</span></div>
                    </td>
                    <td class="border border-black px-2 py-1">
                        <div class="flex justify-between"><span>Rp</span> <span>{{ number_format($totalExpense, 0, ',', '.') }}</span></div>
                    </td>
                    <td class="border border-black px-2 py-1">
                        <div class="flex justify-between"><span>Rp</span> <span>{{ number_format($currentBalance, 0, ',', '.') }}</span></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-between mt-8 px-12 text-sm" style="font-family: Arial, sans-serif;">
            <div class="text-left">
                <p>MENGETAHUI</p>
                <p class="mb-16">KETUA KKKS</p>
                <p>{{ $ketuaName }}</p>
            </div>
            <div class="text-left">
                <p>{{ $kota }}, {{ Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="mb-16">BENDAHARA</p>
                <p>{{ $bendaharaName }}</p>
            </div>
        </div>
    </div>
</div>
