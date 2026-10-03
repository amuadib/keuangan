<?php

namespace App\Livewire;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Get all data for the current year
        $currentYear = Carbon::now()->year;

        $transactions = Transaction::whereYear('date', $currentYear)->get();

        $monthlyIncome = array_fill(1, 12, 0);
        $monthlyExpense = array_fill(1, 12, 0);

        foreach ($transactions as $trx) {
            $month = Carbon::parse($trx->date)->month;
            if ($trx->type == 'income' || $trx->type == 'initial_balance') {
                $monthlyIncome[$month] += $trx->amount;
            } elseif ($trx->type == 'expense') {
                $monthlyExpense[$month] += $trx->amount;
            }
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        return view('livewire.dashboard', [
            'labels' => json_encode($labels),
            'incomes' => json_encode(array_values($monthlyIncome)),
            'expenses' => json_encode(array_values($monthlyExpense)),
            'year' => $currentYear,
        ])->layout('layouts.app', ['title' => 'Dashboard']);
    }
}
