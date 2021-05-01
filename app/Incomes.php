<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incomes extends Model
{
    use SoftDeletes;

    protected $table = 'incomes';
    protected $guarded = [];
    protected $dates = ['deleted_at'];
}
