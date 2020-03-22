<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
    protected $fillable = [
        'name', 'address', 'tin', 'city', 'phone'
    ];

    //$companyId = (isset(Auth::user()->name) ? Auth::user()->company_id : '');
	
	//protected $connection = 'mysql_'.$companyId;
}
