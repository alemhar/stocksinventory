<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
	
    
	protected $table = 'payees_'.isset(Auth::user()->name) ? Auth::user()->company_id : '1';


    protected $fillable = [
        'name', 'address', 'tin', 'city', 'phone'
    ];

    
}
