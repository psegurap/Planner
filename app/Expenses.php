<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expenses extends Model
{
    use SoftDeletes;

    protected $table = 'expenses';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function transactions(){
        return $this->hasMany('App\ExpenseTransaction', 'expense_id', 'id');   
    }
}
