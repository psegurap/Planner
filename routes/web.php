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
        
       
    });

    // Route::get('/incomes', function(){
    //     return view('incomes');
    // })->name('incomes');

    Route::get('/bills', function(){
        return view('bills');
    })->name('bills');

    Route::get('/subscriptions', function(){
        return view('subscriptions');
    })->name('subscriptions');
});

Auth::routes();
Auth::routes(['verify' => true]);

// Route::get('/home', 'HomeController@index')->name('home');
