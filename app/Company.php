<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{   
    protected $fillable = [
        'company', 
        'address', 
        'address2', 
        'tin', 
        'city', 
        'phone', 
        'contactname', 
        "contactno", 
        'contactemail',
        'tin1',
        'tin2',
        'tin3',
        'branch_code',
        'rdo_code',
        'line_of_business',
        'zip_code'
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
