<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
	protected $companyId = '1';
    //$companyId = (isset(Auth::user()->name) ? Auth::user()->company_id : '');
	
	protected $table = 'payees_1'.$companyId;

    protected $fillable = [
        'name', 'address', 'tin', 'city', 'phone'
    ];

    
}
