<?php

namespace App\Livewire;

use App\Models\Receipt;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class ReceiptComponent extends Component
{
    public $receipts;

    public $receipt_number;

    public $received_from;

    public $amount_words;

    #[Url]
    public $for_payment;

    #[Url]
    public $amount;

    public $place;

    #[Url]
    public $date;

    #[Url]
    public $receiver_name;

    public $selectedReceiptIds = [];

    public $showPrintView = false;

    protected $rules = [
        'receipt_number' => 'required|string',
        'received_from' => 'required|string',
        'amount_words' => 'required|string',
        'for_payment' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'place' => 'required|string',
        'date' => 'required|date',
        'receiver_name' => 'required|string',
    ];

    public function mount()
    {
        $this->place = Setting::getValue('kota', 'Nama Kota');
        $this->received_from = Setting::getValue('terima_dari', 'Bendahara');

        $lastReceipt = Receipt::orderBy('id', 'desc')->first();
        if ($lastReceipt && preg_match('/(\d+)$/', $lastReceipt->receipt_number, $matches)) {
            $number = intval($matches[1]) + 1;
            $this->receipt_number = str_pad($number, strlen($matches[1]), '0', STR_PAD_LEFT);
        } else {
            $this->receipt_number = '001';
        }

        $this->date = $this->date ?: Carbon::now()->format('Y-m-d');
        if ($this->amount && empty($this->amount_words)) {
            $this->updatedAmount($this->amount);
        }
        $this->loadReceipts();
    }

    public function loadReceipts()
    {
        $this->receipts = Receipt::orderBy('created_at', 'desc')->get();
    }

    public function updatedAmount($value)
    {
        if ($value && is_numeric($value)) {
            $words = trim($this->terbilang($value));
            $this->amount_words = ucwords($words).' Rupiah';
        } else {
            $this->amount_words = '';
        }
    }

    private function terbilang($angka)
    {
        $angka = abs((int) $angka);
        $baca = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        $terbilang = '';

        if ($angka < 12) {
            $terbilang = ' '.$baca[$angka];
        } elseif ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10).' belas';
        } elseif ($angka < 100) {
            $terbilang = $this->terbilang($angka / 10).' puluh'.$this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $terbilang = ' seratus'.$this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = $this->terbilang($angka / 100).' ratus'.$this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = ' seribu'.$this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = $this->terbilang($angka / 1000).' ribu'.$this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang = $this->terbilang($angka / 1000000).' juta'.$this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $terbilang = $this->terbilang($angka / 1000000000).' milyar'.$this->terbilang(fmod($angka, 1000000000));
        }

        return $terbilang;
    }

    public function saveReceipt()
    {
        $this->validate();

        Receipt::create([
            'receipt_number' => $this->receipt_number,
            'received_from' => $this->received_from,
            'amount_words' => $this->amount_words,
            'for_payment' => $this->for_payment,
            'amount' => $this->amount,
            'place' => $this->place,
            'date' => $this->date,
            'receiver_name' => $this->receiver_name,
        ]);

        $this->reset(['receipt_number', 'received_from', 'amount_words', 'for_payment', 'amount', 'receiver_name']);
        $this->loadReceipts();

        session()->flash('message', 'Kwitansi berhasil disimpan.');
    }

    public function showPrint()
    {
        if (count($this->selectedReceiptIds) > 0) {
            $this->showPrintView = true;
        } else {
            session()->flash('message', 'Pilih minimal satu kwitansi untuk dicetak.');
        }
    }

    public function hidePrint()
    {
        $this->showPrintView = false;
    }

    public function deleteReceipt($id)
    {
        Receipt::find($id)->delete();
        $this->loadReceipts();
        session()->flash('message', 'Kwitansi berhasil dihapus.');
    }

    public function render()
    {
        $selectedReceiptsToPrint = collect();
        if ($this->showPrintView && count($this->selectedReceiptIds) > 0) {
            $selectedReceiptsToPrint = Receipt::whereIn('id', $this->selectedReceiptIds)->get();
        }

        return view('livewire.receipt-component', [
            'selectedReceiptsToPrint' => $selectedReceiptsToPrint,
        ])->layout('layouts.app', ['title' => 'Cetak Kwitansi']);
    }
}
