<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::group(['prefix' => 'incomes'], function(){
        Route::get('/', 'IncomeController@incomes')->name('incomes');
        Route::post('/add_income', 'IncomeController@add_income');
        Route::post('/update_income', 'IncomeController@update_income');
        Route::post('/delete_income/{id}', 'IncomeController@delete_income');
        
        Route::group(['prefix' => 'transaction'], function(){
            Route::post('/add', 'IncomeController@add_transaction');
        });
    
    });

    Route::group(['prefix' => 'expenses'], function(){
        Route::get('/', 'ExpenseController@expenses')->name('expenses');
        Route::post('/add_expense', 'ExpenseController@add_expense');
        Route::post('/update_expense', 'ExpenseController@update_expense');
        Route::post('/delete_expense/{id}', 'ExpenseController@delete_expense');
        
        Route::group(['prefix' => 'transaction'], function(){
            Route::post('/add', 'ExpenseController@add_transaction');
        });
    
    });

    Route::group(['prefix' => 'transactions'], function(){
        Route::get('/', 'TransactionController@transactions')->name('transactions');
    });

    Route::get('/subscriptions', function(){
        return view('subscriptions');
    })->name('subscriptions');
});

Auth::routes();
Auth::routes(['verify' => true]);

// Route::get('/home', 'HomeController@index')->name('home');
