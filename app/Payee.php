<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Payee extends Model
{
	
    //isset(Auth::user()->name) ? Auth::user()->company_id : '1';
	public function getTable()
	{
		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
	    return 'payees_'.$company_id;
	}
	//protected $table = 'payees_'.$companyId;


    protected $fillable = [
		'name', 'address', 'tin', 'city', 'phone', 
		'contactemail',
		'payable',
		'receivable',
		'entity_type'
    ];


}
