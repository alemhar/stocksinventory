<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
	protected $companyId = 1;
    //isset(Auth::user()->name) ? Auth::user()->company_id : '1';
	
	protected $table = 'payees_'.$companyId;


    protected $fillable = [
        'name', 'address', 'tin', 'city', 'phone'
    ];

    
}
