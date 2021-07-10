<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;
use App\Expenses;
// use App\IncomeTransaction;

class ExpenseController extends Controller
{
        // ------------ Begin Expenses Table Functions

        public function expenses(){

            $expenses = $this->expenses_calculated_info();
            // $transactions = IncomeTransaction::with('income')->orderBy('date', 'desc')->get()->groupBy(function($item){
            //     return $item->date;
            // });
            // return view('expenses', compact('expenses', 'transactions'));
            return view('expenses', compact('expenses'));
        }
    
        public function add_expense(Request $request){
    
            $coming_info = $request->expense_info;
            $income_data = [
                'name' => $coming_info['name'],
                'expected_amount' => floatval($coming_info['amount_expected']),
                'description' => $coming_info['description'],
                'user_id' => Auth::user()->id
            ];
    
            Expenses::create($income_data);
            $expenses = $this->expenses_calculated_info();
            return $expenses;
        }
    
        public function update_income(Request $request){
            $coming_info = $request->income_info;
    
            $income_data = [
                'name' => $coming_info['name'],
                'expected_amount' => floatval($coming_info['amount_expected']),
                'description' => $coming_info['description'],
            ];
            Incomes::where('id', $request->income_id)->update($income_data);
            
            $incomes = $this->expenses_calculated_info();
            return $incomes;
        }
    
        public function delete_income($id){
            Incomes::where('id', $id)->delete();
            IncomeTransaction::where('income_id', $id)->where('user_id', Auth::user()->id)->delete();
            $incomes = $this->expenses_calculated_info();
            
            return $incomes;
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
                $expense['difference']  = ($expense['current_addition'] - $expense['expected_amount']);
                $expense['difference'] = round($expense['difference'], 2);
                return $expense;
            });
    
            return $expenses;
        }
    
        // -------------- End Income Table Functions
    
    
        // -------------- Begin Income Transaction Table Functions
        
        public function add_transaction(Request $request){
            $incoming_info = $request->transaction;
    
            $transaction_info = [
                "amount" => $incoming_info['amount'],
                "description" => $incoming_info['description'],
                "date" => $incoming_info['date'],
                "income_id" => $request['income_id'],
                "user_id" =>  Auth::user()->id
            ];
    
            IncomeTransaction::create($transaction_info);
    
            $transactions = IncomeTransaction::with('income')->orderBy('date', 'desc')->get()->groupBy(function($item){
                return $item->date;
            });
            $incomes = $this->expenses_calculated_info();
            
            return ['incomes' => $incomes, 'transactions' => $transactions];
        }
        
        // -------------- End Income Transaction Table Functions
}
