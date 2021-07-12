<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;
use App\Expenses;
use App\ExpenseTransaction;

class ExpenseController extends Controller
{
        // ------------ Begin Expenses Table Functions

        public function expenses(){

            $expenses = $this->expenses_calculated_info();
            $transactions = ExpenseTransaction::with('expense')->where('user_id', Auth::user()->id)->orderBy('date', 'desc')->get()->groupBy(function($item){
                return $item->date;
            });
            return view('expenses', compact('expenses', 'transactions'));
            // return view('expenses', compact('expenses'));
        }
    
        public function add_expense(Request $request){
    
            $coming_info = $request->expense_info;
            $expense_data = [
                'name' => $coming_info['name'],
                'expected_amount' => floatval($coming_info['amount_expected']),
                'description' => $coming_info['description'],
                'user_id' => Auth::user()->id
            ];
    
            Expenses::create($expense_data);
            $expenses = $this->expenses_calculated_info();
            return $expenses;
        }
    
        public function update_expense(Request $request){
            $coming_info = $request->expense_info;
    
            $expense_data = [
                'name' => $coming_info['name'],
                'expected_amount' => floatval($coming_info['amount_expected']),
                'description' => $coming_info['description'],
            ];
            Expenses::where('id', $request->expense_id)->update($expense_data);
            
            $expenses = $this->expenses_calculated_info();
            return $expenses;
        }
    
        public function delete_expense($id){
            Expenses::where('id', $id)->delete();
            ExpenseTransaction::where('expense_id', $id)->where('user_id', Auth::user()->id)->delete();
            $expenses = $this->expenses_calculated_info();
            
            return $expenses;
        }
    
        public function expenses_calculated_info (){
            $expenses = Expenses::with(['transactions'])->where('user_id', Auth::user()->id)->get();
            
            //mapping to calculate the transactions made to the expense
            $expenses = $expenses->map(function($expense){
                $expense['current_addition'] = 0;
                foreach ($expense['transactions'] as $transaction) {
                    $expense['current_addition'] += $transaction['amount'];
                }
                $expense['current_addition'] = round($expense['current_addition'], 2);
                return $expense;
            });
            //mapping to calculate difference between transaction added to amount expected
            $expenses = $expenses->map(function($expense){
                $expense['difference'] = 0;
                $expense['difference']  = ($expense['expected_amount'] - $expense['current_addition']);
                $expense['difference'] = round($expense['difference'], 2);
                return $expense;
            });
    
            return $expenses;
        }
    
        // -------------- End Expense Table Functions
    
    
        // -------------- Begin Expense Transaction Table Functions
        
        public function add_transaction(Request $request){
            $incoming_info = $request->transaction;
    
            $transaction_info = [
                "amount" => $incoming_info['amount'],
                "description" => $incoming_info['description'],
                "date" => $incoming_info['date'],
                "expense_id" => $request['expense_id'],
                "user_id" =>  Auth::user()->id
            ];
    
            ExpenseTransaction::create($transaction_info);
    
            $transactions = ExpenseTransaction::with('expense')->where('user_id', Auth::user()->id)->orderBy('date', 'desc')->get()->groupBy(function($item){
                return $item->date;
            });
            $expenses = $this->expenses_calculated_info();
            
            return ['expenses' => $expenses, 'transactions' => $transactions];
        }
        
        // -------------- End Expense Transaction Table Functions
}
