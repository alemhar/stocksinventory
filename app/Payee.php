<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
	
    
	protected $table = 'payees_'.isset(Auth::user()->name) ? '1' : '1';


    protected $fillable = [
        'name', 'address', 'tin', 'city', 'phone'
    ];

    
}
