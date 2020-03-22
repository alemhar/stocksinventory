<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
	
    //isset(Auth::user()->name) ? Auth::user()->company_id : '1';
	public function getTable()
	{
		if(isset(Auth::user()->company_id)){
			$company_id = '1';
		} else {
			$company_id = '1';
		}
	    return 'payees_'.$company_id;
	}
	//protected $table = 'payees_'.$companyId;


    protected $fillable = [
        'name', 'address', 'tin', 'city', 'phone'
    ];

    
}
