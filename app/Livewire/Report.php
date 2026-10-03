<?php

namespace App\Livewire;

use App\Models\ReportCategory;
use App\Models\Setting;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class Report extends Component
{
    #[Url]
    public $reportType = 'wifi';

    #[Url]
    public int $month;

    #[Url]
    public int $year;

    public function mount()
    {
        if (! isset($this->month)) {
            $this->month = Carbon::now()->month;
        }
        if (! isset($this->year)) {
            $this->year = Carbon::now()->year;
        }
    }

    public function render()
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        $transactions = Transaction::where('report_type', $this->reportType)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $previousBalance = Transaction::where('report_type', $this->reportType)
            ->where('date', '<', $startDate->format('Y-m-d'))
            ->sum(\DB::raw("CASE WHEN type IN ('income', 'initial_balance') THEN amount ELSE -amount END"));

        return view('livewire.report', [
            'transactions' => $transactions,
            'previousBalance' => $previousBalance,
            'ketuaName' => Setting::getValue('nama_ketua', 'NAMA KETUA, S.Pd'),
            'bendaharaName' => Setting::getValue('nama_bendahara', 'NAMA BENDAHARA, S.Pd. I'),
            'kota' => Setting::getValue('kota', 'NAMA KOTA'),
            'category' => ReportCategory::where('code', $this->reportType)->first(),
        ])->layout('layouts.app', ['title' => 'Cetak Laporan']);
    }
}
