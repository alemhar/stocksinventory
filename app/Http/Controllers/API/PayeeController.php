<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payee;

class PayeeController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (\Gate::allows('isAdmin') || \Gate::allows('isUser')) {
            return Payee::latest()->paginate(5);
        }  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //return ['message' => 'I have received you request!'];
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'address' => 'required|string|max:191',
            'city' => 'required|string|max:191',
            'phone' => 'required|string|max:191'

        ]);

        return Payee::create([
            'name' => $request['name'],
            'address' => $request['address'],
            'city' => $request['city'],
            'tin' => $request['tin'],
            'phone' => $request['phone']
        ]);
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
        $payee = Payee::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'address' => 'required|string|max:191',
            'city' => 'required|string|max:191',
            'phone' => 'required|string|max:191'

        ]);


        $payee->update($request->all());

        return ['message' => 'Payee info updated!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$this->authorize('isAdmin');

        $payee = Payee::findOrFail($id);

        // Send Request
        $payee->delete();
        
        return ['message' => 'Payee Deleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $accounts = Payee::where(function($query) use ($search){
                $query->where('id','LIKE',"%$search%")
                        ->orWhere('name','LIKE',"%$search%");
            })->paginate(10);

        }else{
            $accounts = Payee::latest()->paginate(10);
        }
        return $accounts;
    }
}
