<?php

namespace App\Livewire;

use App\Models\Recipient;
use App\Models\ReportCategory;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionManager extends Component
{
    use WithPagination;

    #[Url]
    public $reportType = 'wifi';

    #[Url]
    public int $month;

    #[Url]
    public int $year;

    // Transaction form
    public $date;

    public $description;

    public $type = 'income';

    public $amount;

    public $recipient_id = null;

    protected $rules = [
        'date' => 'required|date',
        'description' => 'required|string',
        'type' => 'required|in:income,expense,initial_balance',
        'amount' => 'required|numeric|min:0',
        'recipient_id' => 'nullable|exists:recipients,id',
    ];

    public function mount()
    {
        if (! isset($this->month)) {
            $this->month = Carbon::now()->month;
        }
        if (! isset($this->year)) {
            $this->year = Carbon::now()->year;
        }
        $this->date = $this->date ?? Carbon::now()->format('Y-m-d');
    }

    public function saveTransaction()
    {
        $this->validate();

        Transaction::create([
            'report_type' => $this->reportType,
            'date' => $this->date,
            'description' => $this->description,
            'type' => $this->type,
            'amount' => $this->amount,
            'recipient_id' => $this->recipient_id ?: null,
        ]);

        $this->reset(['description', 'amount', 'recipient_id']);
        $this->type = 'income';

        session()->flash('message', 'Transaksi berhasil ditambahkan.');
    }

    public function deleteTransaction($id)
    {
        Transaction::find($id)->delete();
        session()->flash('message', 'Transaksi berhasil dihapus.');
    }

    public function render()
    {
        $transactions = Transaction::where('report_type', $this->reportType)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.transaction-manager', [
            'transactions' => $transactions,
            'categories' => ReportCategory::orderBy('name', 'asc')->get(),
            'recipients' => Recipient::orderBy('name', 'asc')->get(),
        ])->layout('layouts.app');
    }
}
