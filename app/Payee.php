<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
    protected $fillable = [
        'name', 'address', 'tin', 'city'
    ];
}
