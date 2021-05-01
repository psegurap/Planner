<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Incomes;


class IncomeController extends Controller
{
    public function incomes(){
        $incomes = Incomes::where('user_id', Auth::user()->id)->get();
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
        $incomes = Incomes::where('user_id', Auth::user()->id)->get();
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
        $incomes = Incomes::where('user_id', Auth::user()->id)->get();
        return $incomes;
    }

    public function delete_income($id){
        Incomes::where('id', $id)->delete();
        $incomes = Incomes::where('user_id', Auth::user()->id)->get();
        return $incomes;
    }
}
