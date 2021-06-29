<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;
use App\Expense;
// use App\IncomeTransaction;

class ExpenseController extends Controller
{
        // ------------ Begin Income Table Functions

        public function expenses(){

            // $incomes = $this->expenses_calculated_info();
            // $transactions = IncomeTransaction::with('income')->orderBy('date', 'desc')->get()->groupBy(function($item){
            //     return $item->date;
            // });
            // return view('expense', compact('incomes', 'transactions'));
            return view('expenses');
        }
    
        public function add_income(Request $request){
    
            $coming_info = $request->expense_info;
            $income_data = [
                'name' => $coming_info['name'],
                'expected_amount' => floatval($coming_info['amount_expected']),
                'description' => $coming_info['description'],
                'user_id' => Auth::user()->id
            ];
    
            Expense::create($income_data);
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
            $incomes = Incomes::with(['transactions'])->where('user_id', Auth::user()->id)->get();
            
            //mapping to calculate the transactions made to the income
            $incomes = $incomes->map(function($income){
                $income['current_addition'] = 0;
                foreach ($income['transactions'] as $transaction) {
                    $income['current_addition'] += $transaction['amount'];
                }
                $income['current_addition'] = round($income['current_addition'], 2);
                return $income;
            });
            //mapping to calculate difference between transaction added to amount expected
            $incomes = $incomes->map(function($income){
                $income['difference'] = 0;
                $income['difference']  = ($income['current_addition'] - $income['expected_amount']);
                $income['difference'] = round($income['difference'], 2);
                return $income;
            });
    
            return $incomes;
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
