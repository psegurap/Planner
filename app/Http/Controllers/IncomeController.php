<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Incomes;
use App\IncomeTransaction;



class IncomeController extends Controller
{

    // ------------ Begin Income Table Functions

    public function incomes(){

        $incomes = $this->incomes_calculated_info();
        return view('incomes', compact('incomes'));
    }

    public function add_income(Request $request){

        $coming_info = $request->income_info;
        $income_data = [
            'name' => $coming_info['name'],
            'expected_amount' => floatval($coming_info['amount_expected']),
            'description' => $coming_info['description'],
            'user_id' => Auth::user()->id
        ];

        Incomes::create($income_data);
        $incomes = $this->incomes_calculated_info();
        return $incomes;
    }

    public function update_income(Request $request){
        $coming_info = $request->income_info;

        $income_data = [
            'name' => $coming_info['name'],
            'expected_amount' => floatval($coming_info['amount_expected']),
            'description' => $coming_info['description'],
        ];
        Incomes::where('id', $request->income_id)->update($income_data);
        
        $incomes = $this->incomes_calculated_info();
        return $incomes;
    }

    public function delete_income($id){
        Incomes::where('id', $id)->delete();
        $incomes = $this->incomes_calculated_info();
        
        return $incomes;
    }

    public function incomes_calculated_info (){
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

        $incomes = $this->incomes_calculated_info();
        
        return $incomes;
    }
    
    // -------------- End Income Transaction Table Functions

}
