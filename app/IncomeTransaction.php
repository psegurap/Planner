<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeTransaction extends Model
{
    
    protected $table = 'incomes_transaction';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

}
