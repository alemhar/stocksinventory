<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DailyAccount;
use Carbon\Carbon;

class DailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Request::get('report_type')) {
            $report_type = \Request::get('report_type');
        }
        if(\Request::get('from_transaction_date')) {
            $from_transaction_date = \Request::get('from_transaction_date');
        } else {
            $from_transaction_date = '';
        }
        if(\Request::get('to_transaction_date')) {
            $to_transaction_date = \Request::get('to_transaction_date');
        } else {
            $to_transaction_date = '';
        }

        if(\Request::get('transaction_date')) {
            $transaction_date = \Request::get('transaction_date');
        } else {
            $transaction_date = '';
        }

        if(\Request::get('sub_account_type')) {
            $sub_account_type = \Request::get('sub_account_type');
            $sub_account_type = str_replace('_', ' ', $sub_account_type);
        } else {
            $sub_account_type = '';
        }

        $transaction = DailyAccount::where(function($query) use ($from_transaction_date,$to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
            //$query->where('transaction_date','=', $transaction_date);
        })
        ->where('sub_account_type',$sub_account_type)
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();


        

        //->pluck('debit','credit','account_name');
        

        /* Working for PrintTest.vue
        $transaction = DailyAccount::
        groupBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        */

        /*
        $transaction = DailyAccount::where(function($query) use ($transaction_date){
            $query->where('transaction_date','=', $transaction_date);
        })
        ->groupBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();

        */

        //->pluck('debit','credit','account_name');
        

        /*
        $transaction = DailyAccount::where(function($query) use ($transaction_date){
            $query->where('transaction_date','=',$transaction_date);
        })->paginate(10);
        */    
        return $transaction;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function runninng()
    {

        if(\Request::get('start')) {
            $start = \Request::get('start');
        }
        if(\Request::get('end')) {
            $end = \Request::get('end');
        }

        if(\Request::get('transaction_date')) {
            $transaction_date = \Request::get('transaction_date');
        } else {
            $transaction_date = '';
        }

        $transaction = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
            //$query->where('transaction_date','=', $transaction_date);
        })
        ->where('transaction_date','<=', $transaction_date)
        //->groupBy('account_code')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();

        return $transaction;
    }

    public function monthlyTaxDeclaration(Request $request)
    {
        $totalSalesAndRevenues = 0;
        $totalOutputTax = 0;
        $totalInputTax = 0;
        $salesAndRevenuesPrivate = 0;
        $salesAndRevenuesGov = 0;
        $salesAndRevenuesExempt = 0;

        $inputTaxServices = 0;
        $inputTaxGoods = 0;
        $outputTaxPrivate = 0;
        $outputTaxGov = 0;

        $transaction_date = $request['transaction_date'];
        $company_id = $request['company_id'];

        /*
        if(\Request::get('transaction_date')) {
            $transaction_date = \Request::get('transaction_date');
        } else {
            $transaction_date = '';
        }

        if(\Request::get('company_id')) {
            $company_id = \Request::get('company_id');
        } else {
            $company_id = '';
        }
        */
        

        $to_transaction_date = Carbon::create($transaction_date);
        
        $from_transaction_date = Carbon::create($to_transaction_date->year, $to_transaction_date->month, 1);

        // Sales and Revenues
        $start = 41010000;
        $end = 41010099;
        
        $totalSalesAndRevenues = $this->totalCredit($start, $end, $from_transaction_date, $to_transaction_date);
        

        // Output Tax Private
        $start = 21051100;
        $end = 21051199;
        
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('entity_type','=','PRIVATE')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $outputTaxPrivate =  $transaction->credit - $transaction->debit;
        }

        // Output Tax Gov
        $start = 21051100;
        $end = 21051199;
        
       
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('entity_type','<>','PRIVATE')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $outputTaxGov =  $transaction->credit - $transaction->debit;
        }

        if($outputTaxPrivate > 0){
            $salesAndRevenuesPrivate = round(($outputTaxPrivate / 0.12 ) + 0.01, 2) ;
            //$salesAndRevenuesPrivate = $outputTaxPrivate / 0.12;
        }

        if($outputTaxGov > 0){
            $salesAndRevenuesGov = round($outputTaxGov / 0.12, 2);
        }
        
        // Input Tax Capital Goods and Goods
        $start = 11051100;
        $end = 11051199;
        
        
        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('type','<>','SERVICE')
        ->where('type','<>','NA')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $inputTaxGoods =  $transaction->credit - $transaction->debit;
        }

        

        // Input Tax Services
        $start = 11051100;
        $end = 11051199;
        

        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->where('type','=','SERVICE')
        ->where('type','<>','NA')
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $inputTaxServices =  $transaction->credit - $transaction->debit;
        }


        $totalAdvancesDue = 0;
        $totalPettyBankInventory = 0;
        $totalNonCurrentAssets = 0;
        $totalOtherCurrentAssets = 0;


        // Total Petty Cash Fund, Cash In Bank - Bank, Inventory
        $start = 11011200;
        $end = 11011499;
        
        
        $totalPettyBankInventory = $this->totalCredit($start, $end, $from_transaction_date, $to_transaction_date);
        
        // Total Advances and Due
        $start = 11031300;
        $end = 11031499;
        
        $totalAdvancesDue = $this->totalCredit($start, $end, $from_transaction_date, $to_transaction_date);
        
        // Total Other Current Assets
        $start = 11050000;
        $end = 11050099;
        
        $totalOtherCurrentAssets = $this->totalCredit($start, $end, $from_transaction_date, $to_transaction_date);
        
        // Total Non Current Assets
        $start = 15011100;
        $end = 15011599;
        
        $totalNonCurrentAssets = $this->totalCredit($start, $end, $from_transaction_date, $to_transaction_date);
        
        $salesAndRevenuesExempt = round($totalSalesAndRevenues - $salesAndRevenuesPrivate - $salesAndRevenuesGov,2);
        $totalOutputTax = $outputTaxPrivate + $outputTaxGov;

        $A12 = number_format((float)$salesAndRevenuesPrivate, 2, '.', '');
        $A12 = number_format((float)$outputTaxPrivate, 2, '.', '');
        $A13 = number_format((float)$salesAndRevenuesGov, 2, '.', '');
        $B13 = number_format((float)$outputTaxGov, 2, '.', '');
        $A14 = number_format((float)0.00, 2, '.', '');
        $A15 = number_format((float)$salesAndRevenuesExempt, 2, '.', '');
        $A16 = number_format((float)$totalSalesAndRevenues, 2, '.', '');
        $B16 = number_format((float)$outputTaxPrivate + $outputTaxGov, 2, '.', '');

        $P18 = $totalNonCurrentAssets + $totalOtherCurrentAssets + $totalAdvancesDue + $totalPettyBankInventory;
        $E18 = 0;
        if($inputTaxGoods > 0){
            $E18 = $inputTaxGoods / 0.12;
        }

        $I18 = 0;
        if($inputTaxServices > 0){
            $I18 = $inputTaxServices / 0.12;
        }

        $M18 = number_format((float)($P18 - $E18 - $I18), 2, '.', '');
        $F18 = number_format((float)$inputTaxGoods, 2, '.', '');
        $J18 = number_format((float)$inputTaxServices, 2, '.', '');
        $A19 = number_format((float)($inputTaxGoods + $inputTaxServices), 2, '.', '');
        $P18 = number_format((float)$P18, 2, '.', '');
        $E18 = number_format((float)$E18, 2, '.', '');
        $I18 = number_format((float)$I18, 2, '.', '');

        $A20 = 0;
        $B20 = 0;
        $D20 = 0;
        $E20 = 0;
        $C20 = 0; //($A15/$A16) * ($inputTaxGoods + $inputTaxServices);
        $F20 = $A20 + $B20 + $C20 + $D20 + $E20;
        $A21 = $A19 - $F20;
        $A22 = $B16 - $A21;
        $F23 = 0;
        $A24 = $A22 - $F23;


        $A20 = number_format((float)$A20, 2, '.', '');
        $B20 = number_format((float)$B20, 2, '.', '');
        $D20 = number_format((float)$D20, 2, '.', '');
        $E20 = number_format((float)$E20, 2, '.', '');
        $C20 = number_format((float)$C20, 2, '.', '');
        $F20 = number_format((float)$F20, 2, '.', '');
        $A21 = number_format((float)$A21, 2, '.', '');
        $A22 = number_format((float)$A22, 2, '.', '');
        $A24 = number_format((float)$A22, 2, '.', '');
        $A26 = number_format((float)0.00, 2, '.', '');

/*
        1) TIN and Branch Code..
        2) RDO
        3) Tax Payers Name
        4) Line of Business
        5) Address
        6) Telephone Number

*/      

        
        /*
        return json_encode(['A12' => $A12,
                            'B12' => $B12,
                            'A13' => $A13,
                            'B13' => $B13,
                            'A14' => $A14,
                            'A15' => $A15,
                            'A16' => $A16,
                            'B16' => $B16,
                            'P18' => $P18,
                            'E18' => $E18,
                            'I18' => $I18,
                            'M18' => $M18,
                            'F18' => $F18,
                            'J18' => $J18,
                            'A19' => $A19
                            ]);

        */

        $RtnMonth = 0;
        $RtnYear = 0;
        $TIN1 = '006';
        $TIN2 = '047';
        $TIN3 = '082';
        $BranchCode = '000';
        $RDOCode = '113';
        $LineBus = 'CAR DEALER';
        $TaxPayerName = 'DAVAO TOKYO MOTOR SALES';
        $TelephoneNum = '0';
        $Address = 'DAVAO CITY';
        $ZipCode = '8000';         
        
        $TIN = $TIN1.$TIN2.$TIN3.$BranchCode; 
        $FORM = '2550M'; 
        $PERIOD = $to_transaction_date->month . $to_transaction_date->year;    
        $filename = $TIN.'-'.$FORM.'-'.$PERIOD.'.xml';

$template = 
"<?xml version='1.0'?>	
<div>frm2550m:RtnYear=09frm2550m:RtnYear=</div>	
<div>frm2550m:txtYear=2020frm2550m:txtYear=</div>	
<div>frm2550m:OptAmendedYN1=falsefrm2550m:OptAmendedYN1=</div>	
<div>frm2550m:OptAmendedYN2=truefrm2550m:OptAmendedYN2=</div>	
<div>frm2550m:txtSheets=0frm2550m:txtSheets=</div>	
<div>frm2550m:txtTIN1=".$TIN1."frm2550m:txtTIN1=</div>	
<div>frm2550m:txtTIN2=".$TIN2."frm2550m:txtTIN2=</div>	
<div>frm2550m:txtTIN3=".$TIN3."frm2550m:txtTIN3=</div>	
<div>frm2550m:txtBranchCode=".$BranchCode."frm2550m:txtBranchCode=</div>	
<div>frm2550m:txtRDOCode=".$RDOCode."frm2550m:txtRDOCode=</div>	
<div>frm2550m:txtLineBus=".$LineBus."frm2550m:txtLineBus=</div>	
<div>frm2550m:txtTaxPayerName=".$TaxPayerName."frm2550m:txtTaxPayerName=</div>	
<div>frm2550m:txtTelephoneNum=".$TelephoneNum."frm2550m:txtTelephoneNum=</div>	
<div>frm2550m:txtAddress=".$Address."frm2550m:txtAddress=</div>	
<div>frm2550m:txtZipCode=".$ZipCode."frm2550m:txtZipCode=</div>	
<div>frm2550m:OptSpecialTax1=falsefrm2550m:OptSpecialTax1=</div>	
<div>frm2550m:OptSpecialTax2=truefrm2550m:OptSpecialTax2=</div>	
<div>frm2550m:lstSpecialTax=0frm2550m:lstSpecialTax=</div>	
<div>frm2550m:txtTax12A=".$A12."frm2550m:txtTax12A=</div>	// Output Tax Private / 12%
<div>frm2550m:txtTax12B=".$A12."frm2550m:txtTax12B=</div>	// Output Tax Private
<div>frm2550m:txtTax13A=".$A13."frm2550m:txtTax13A=</div>	// Output Tax Government / 12%
<div>frm2550m:txtTax13B=".$B13."frm2550m:txtTax13B=</div>	// Output Tax Government
<div>frm2550m:txtTax14=".$A14."frm2550m:txtTax14=</div>	
<div>frm2550m:txtTax15=".$A15."frm2550m:txtTax15=</div>	
<div>frm2550m:txtTax16A=".$A16."frm2550m:txtTax16A=</div>	
<div>frm2550m:txtTax16B=".$B16."frm2550m:txtTax16B=</div>	
<div>frm2550m:txtTax17A=0.00frm2550m:txtTax17A=</div>	
<div>frm2550m:txtTax17B=0.00frm2550m:txtTax17B=</div>	
<div>frm2550m:txtTax17C=0.00frm2550m:txtTax17C=</div>	
<div>frm2550m:txtTax17D=0.00frm2550m:txtTax17D=</div>	
<div>frm2550m:txtTax17E=0.00frm2550m:txtTax17E=</div>	
<div>frm2550m:txtTax17F=0.00frm2550m:txtTax17F=</div>	
<div>frm2550m:txtTax18A=0.00frm2550m:txtTax18A=</div>	
<div>frm2550m:txtTax18B=0.00frm2550m:txtTax18B=</div>	
<div>frm2550m:txtTax18C=0.00frm2550m:txtTax18C=</div>	
<div>frm2550m:txtTax18D=0.00frm2550m:txtTax18D=</div>	
<div>frm2550m:txtTax18E=".$E18."frm2550m:txtTax18E=</div>	
<div>frm2550m:txtTax18F=".$F18."frm2550m:txtTax18F=</div>	
<div>frm2550m:txtTax18G=0.00frm2550m:txtTax18G=</div>	
<div>frm2550m:txtTax18H=0.00frm2550m:txtTax18H=</div>	
<div>frm2550m:txtTax18I=".$I18."frm2550m:txtTax18I=</div>	
<div>frm2550m:txtTax18J=".$J18."frm2550m:txtTax18J=</div>	
<div>frm2550m:txtTax18K=0.00frm2550m:txtTax18K=</div>	
<div>frm2550m:txtTax18L=0.00frm2550m:txtTax18L=</div>	
<div>frm2550m:txtTax18M=".$M18."frm2550m:txtTax18M=</div>	
<div>frm2550m:txtTax18N=0.00frm2550m:txtTax18N=</div>	
<div>frm2550m:txtTax18O=0.00frm2550m:txtTax18O=</div>	
<div>frm2550m:txtTax18P=".$P18."frm2550m:txtTax18P=</div>	
<div>frm2550m:txtTax19=".$A19."frm2550m:txtTax19=</div>	
<div>frm2550m:txtTax20A=0.00frm2550m:txtTax20A=</div>	
<div>frm2550m:txtTax20B=0.00frm2550m:txtTax20B=</div>	
<div>frm2550m:txtTax20C=".$C20."frm2550m:txtTax20C=</div>	
<div>frm2550m:txtTax20D=0.00frm2550m:txtTax20D=</div>	
<div>frm2550m:txtTax20E=0.00frm2550m:txtTax20E=</div>	
<div>frm2550m:txtTax20F=".$F20."frm2550m:txtTax20F=</div>	
<div>frm2550m:txtTax21=".$A21."frm2550m:txtTax21=</div>	
<div>frm2550m:txtTax22=-".$A22."frm2550m:txtTax22=</div>	
<div>frm2550m:txtTax23A=0.00frm2550m:txtTax23A=</div>	
<div>frm2550m:txtTax23B=0.00frm2550m:txtTax23B=</div>	
<div>frm2550m:txtTax23C=0.00frm2550m:txtTax23C=</div>	
<div>frm2550m:txtTax23D=0.00frm2550m:txtTax23D=</div>	
<div>frm2550m:txtTax23E=0.00frm2550m:txtTax23E=</div>	
<div>frm2550m:txtTax23F=0.00frm2550m:txtTax23F=</div>	
<div>frm2550m:txtTax23G=0.00frm2550m:txtTax23G=</div>	
<div>frm2550m:txtTax24=-".$A24."frm2550m:txtTax24=</div>	
<div>frm2550m:txtTax25A=0.00frm2550m:txtTax25A=</div>	
<div>frm2550m:txtTax25B=0.00frm2550m:txtTax25B=</div>	
<div>frm2550m:txtTax25C=0.00frm2550m:txtTax25C=</div>	
<div>frm2550m:txtTax25D=0.00frm2550m:txtTax25D=</div>	
<div>frm2550m:txtTax26=-".$A26."frm2550m:txtTax26=</div>	
<div>frm2550m:txtAtcCde1=VB010frm2550m:txtAtcCde1=</div>	
<div>frm2550m:txtAmountSales1=16,696.43frm2550m:txtAmountSales1=</div>	
<div>frm2550m:txtOutputTax1=2,003.57frm2550m:txtOutputTax1=</div>	
<div>frm2550M:txtmodaltxtTotal12A=16,696.43frm2550M:txtmodaltxtTotal12A=</div>	
<div>frm2550M:txtmodaltxtTotal12B=2,003.57frm2550M:txtmodaltxtTotal12B=</div>	
<div>AtcCode1=trueAtcCode1=</div>	
<div>AtcCode2=falseAtcCode2=</div>	
<div>AtcCode3=falseAtcCode3=</div>	
<div>AtcCode4=falseAtcCode4=</div>	
<div>AtcCode5=falseAtcCode5=</div>	
<div>AtcCode6=falseAtcCode6=</div>	
<div>AtcCode7=falseAtcCode7=</div>	
<div>AtcCode8=falseAtcCode8=</div>	
<div>AtcCode9=falseAtcCode9=</div>	
<div>AtcCode10=falseAtcCode10=</div>	
<div>AtcCode11=falseAtcCode11=</div>	
<div>AtcCode12=falseAtcCode12=</div>	
<div>AtcCode13=falseAtcCode13=</div>	
<div>AtcCode14=falseAtcCode14=</div>	
<div>AtcCode15=falseAtcCode15=</div>	
<div>AtcCode16=falseAtcCode16=</div>	
<div>AtcCode17=falseAtcCode17=</div>	
<div>AtcCode18=falseAtcCode18=</div>	
<div>AtcCode19=falseAtcCode19=</div>	
<div>AtcCode20=falseAtcCode20=</div>	
<div>AtcCode21=falseAtcCode21=</div>	
<div>AtcCode22=falseAtcCode22=</div>	
<div>AtcCode23=falseAtcCode23=</div>	
<div>AtcCode24=falseAtcCode24=</div>	
<div>AtcCode25=falseAtcCode25=</div>	
<div>AtcCode26=falseAtcCode26=</div>	
<div>AtcCode27=falseAtcCode27=</div>	
<div>AtcCode28=falseAtcCode28=</div>	
<div>AtcCode29=falseAtcCode29=</div>	
<div>AtcCode30=falseAtcCode30=</div>	
<div>AtcCode31=falseAtcCode31=</div>	
<div>AtcCode32=falseAtcCode32=</div>	
<div>AtcCode33=falseAtcCode33=</div>	
<div>AtcCode34=falseAtcCode34=</div>	
<div>AtcCode35=falseAtcCode35=</div>	
<div>AtcCode36=falseAtcCode36=</div>	
<div>txtmodalTotalAmt=0.00txtmodalTotalAmt=</div>	
<div>txtmodalTotalInputTax=0.00txtmodalTotalInputTax=</div>	
<div>txtmodalTotalAmountSched3=0.00txtmodalTotalAmountSched3=</div>	
<div>txtmodalTotalInputTaxSched3=0.00txtmodalTotalInputTaxSched3=</div>	
<div>txtmodalTotalBalanceSched3A=0.00txtmodalTotalBalanceSched3A=</div>	
<div>txtmodalTotalBalanceSched3B=0.00txtmodalTotalBalanceSched3B=</div>	
<div>txtmodalTotalInputTax20ASched3=0.00txtmodalTotalInputTax20ASched3=</div>	
<div>txtinputtaxSched4=0.00txtinputtaxSched4=</div>	
<div>txtTaxableSaleSched4=0.00txtTaxableSaleSched4=</div>	
<div>txtInputTaxnotDirectSched4=0.00txtInputTaxnotDirectSched4=</div>	
<div>txtTotalInputTaxnotDirectSched4=0.00txtTotalInputTaxnotDirectSched4=</div>	
<div>txtTotalSaleSched4=0.00txtTotalSaleSched4=</div>	
<div>txtTotalInputSaletoGovernmentSched4=0.00txtTotalInputSaletoGovernmentSched4=</div>	
<div>txtlessStdTaxSched4=350.00txtlessStdTaxSched4=</div>	
<div>txtTotal20BSched4=0.00txtTotal20BSched4=</div>	
<div>txtinputtaxSched5=0.00txtinputtaxSched5=</div>	
<div>txtTotSaleSched5=3,360.00txtTotSaleSched5=</div>	
<div>txtAmountInputnotDirectSched5=3,540.00txtAmountInputnotDirectSched5=</div>	
<div>txtProductnotDirectSched5=474.70txtProductnotDirectSched5=</div>	
<div>txtSumTotalSaleSched5=25,056.43txtSumTotalSaleSched5=</div>	
<div>txtTotal20CSched5=474.70txtTotal20CSched5=</div>	
<div>txtmodalTotal23A=0.00txtmodalTotal23A=</div>	
<div>txtmodalTotalSched6AppliedCurrent=0.00txtmodalTotalSched6AppliedCurrent=</div>	
<div>txtmodalTotal23B=0.00txtmodalTotal23B=</div>	
<div>txtmodalTotalSched7AppliedCurrent=0.00txtmodalTotalSched7AppliedCurrent=</div>	
<div>txtmodalTotal23C=0.00txtmodalTotal23C=</div>	
<div>txtmodalTotalSched8AppliedCurrent=0.00txtmodalTotalSched8AppliedCurrent=</div>	
<div>txtFinalFlag=0txtFinalFlag=</div>	
<div>txtEnroll=NtxtEnroll=</div>	
<div>ebirOnlineConfirmUsername=ebirOnlineConfirmUsername=</div>	
<div>ebirOnlineUsername=ebirOnlineUsername=</div>	
<div>ebirOnlineSecret=ebirOnlineSecret=</div>	
<div>txtEmail=civanjee@yahoo.comtxtEmail=</div>	
<div>driveSelectTPExport=0driveSelectTPExport=</div>	
    
All Rights Reserved BIR 2012.";
    
    
    
    $filenameXML = sys_get_temp_dir().'/'.$filename;
    
    $filenamepathhandle = fopen($filenameXML,'w');

    fwrite($filenamepathhandle,$template);
    fclose($filenamepathhandle);

    //header('Content-Type: text/csv; charset=utf-8');
    //header("Content-Transfer-Encoding: Binary"); 
    //header("Content-disposition: attachment; filename=\"" . basename($filenameXML) . "\""); 
    //readfile($filenameXML);
    
    $headers = [
        'Content-Type' => 'application/xml',
     ];

    return response()->download($filenameXML, $filename, $headers);

    //unlink($filenameXML);
    //exit();

    }
    
    public function totalCredit($start, $end, $from_transaction_date, $to_transaction_date){
        
        $totalCredit = 0;

        $transactions = DailyAccount::where(function($query) use ($start,$end){
            $query->whereBetween('account_code', [$start, $end]);
        })
        ->where(function($query) use ($from_transaction_date, $to_transaction_date){
            $query->whereBetween('transaction_date', [$from_transaction_date, $to_transaction_date]);
        })
        ->groupBy('account_code')
        ->orderBy('account_code')
        ->selectRaw('sum(debit_amount) as debit,sum(credit_amount) as credit, account_name, id')
        ->get();
        foreach($transactions as $transaction){
            $totalCredit =  $transaction->credit - $transaction->debit;
        }

        return $totalCredit;
    }
    

}
