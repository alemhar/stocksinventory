<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{   
    protected $fillable = [
        'name', 'address', 'address2', 'tin', 'city', 'phone', 'contactname', "contactno", 'contactemail'
    ];

    /*
    public function getTable()
	{
		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
	    return 'companies_'.$company_id;
    }
    */
}
