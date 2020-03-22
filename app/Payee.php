<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
	
    //isset(Auth::user()->name) ? Auth::user()->company_id : '1';
	public function __construct() {
	    parent::__construct();
	    $this->table = 'payees_'isset(Auth::user()->name) ? Auth::user()->company_id : '1';

	}

	//protected $table = 'payees_'.$companyId;


    protected $fillable = [
        'name', 'address', 'tin', 'city', 'phone'
    ];

    
}
