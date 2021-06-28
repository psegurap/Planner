<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'incomes_transaction';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function income(){
        return $this->hasOne('App\Incomes', 'id', 'income_id');   
    }
}
