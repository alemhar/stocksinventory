<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Taxrate extends Model
{
    protected $fillable = [
        'tax_type',
        'tax_rate',
        'atc',
        'wtax_code',
        'description',
        'condition'
        ];

    public function getTable()
	{

		if(isset(Auth::user()->company_id)){
			$company_id = Auth::user()->company_id;
		} else {
			$company_id = '99';
		}
		
	    return 'taxrates_'.$company_id;
	}
}
