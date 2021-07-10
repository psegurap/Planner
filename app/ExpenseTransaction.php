<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'expenses_transaction';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function expense(){
        return $this->hasOne('App\Expenses', 'id', 'expense_id');   
    }
}
