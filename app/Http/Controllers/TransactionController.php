<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;
use App\ExpenseTransaction;
use App\IncomeTransaction;


class TransactionController extends Controller
{
    public function transactions(){

        $Incomes_transactions = IncomeTransaction::with('income')->where('user_id', Auth::user()->id)->get();
        $Expenses_transactions = ExpenseTransaction::with('expense')->where('user_id', Auth::user()->id)->get();

        $transactions = $Incomes_transactions->merge($Expenses_transactions);
        $transactions = $transactions->sortByDesc('date');

        $transactions = $transactions->groupBy(function($item){
                return $item->date;
        });

        return view('transactions', compact('transactions'));
    }
}
