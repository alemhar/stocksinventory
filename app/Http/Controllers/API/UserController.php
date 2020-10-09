<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Company;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            return User::latest()->paginate(5);
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

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'font_size' => $request['font_size'],
            'password' => Hash::make($request['password']),
        ]);
    }


    public function updateAccountInfo(Request $request){

        $user = auth('api')->user();
        $company_id = $user->company_id;
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);
        
        $currentPhoto = $user->photo;
        
        if($request->photo != $currentPhoto){
            
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            
            $request->merge(['photo' => $name]);
            
            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
            
            
        }
        
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        
        $user->update($request->all());
        
        if(isset($request['tin1'])){
            $tin1 = $request['tin1'];
        } else {
            $tin1 = '';
        }
        
        if(isset($request['tin2'])){
            $tin2 = $request['tin2'];
        } else {
            $tin2 = '';
        }

        if(isset($request['tin3'])){
            $tin3 = $request['tin3'];
        } else {
            $tin3 = '';
        }

        if(isset($request['branch_code'])){
            $branch_code = $request['branch_code'];
        } else {
            $branch_code = '';
        }

        if(isset($request['rdo_code'])){
            $rdo_code = $request['rdo_code'];
        } else {
            $rdo_code = '';
        }

        if(isset($request['line_of_business'])){
            $line_of_business = $request['line_of_business'];
        } else {
            $line_of_business = '';
        }

        if(isset($request['zip_code'])){
            $zip_code = $request['zip_code'];
        } else {
            $zip_code = '';
        }

        if(isset($request['company'])){
            $company = $request['company'];
        } else {
            $company = '';
        }

        if(isset($request['address'])){
            $address = $request['address'];
        } else {
            $address = '';
        }

        if(isset($request['address2'])){
            $address2 = $request['address2'];
        } else {
            $address2 = '';
        }

        $current_company = Company::findOrFail($company_id);

        $current_company->company = $company;
        $current_company->address = $address;
        $current_company->address2 = $address2;
        $current_company->tin1 = $tin1;
        $current_company->tin2 = $tin2;
        $current_company->tin3 = $tin3;
        $current_company->branch_code = $branch_code;
        $current_company->rdo_code = $rdo_code;
        $current_company->line_of_business = $line_of_business;
        $current_company->zip_code = $zip_code;

        $current_company->save();

        //return ['message' => "Success"];

        return $user;
    }

    public function account()
    {
        $current_user = auth('api')->user();
        $company_id = $current_user->company_id;
        $current_company = Company::findOrFail($company_id);

        $current_user->company = $current_company->company;
        $current_user->tin1 = $current_company->tin1;
        $current_user->tin2 = $current_company->tin2;
        $current_user->tin3 = $current_company->tin3;
        $current_user->branch_code = $current_company->branch_code;
        $current_user->rdo_code = $current_company->rdo_code;
        $current_user->line_of_business = $current_company->line_of_business;
        $current_user->zip_code = $current_company->zip_code;
        $current_user->address = $current_company->address;
        $current_user->address2 = $current_company->address2;
        
        //dd($current_user);
        return  $current_user;

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
        $user = User::findOrFail($id);
        $company_id = $user->company_id;
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);
        
        $user->update($request->all());
        
        

        if(isset($request['tin1'])){
            $tin1 = $request['tin1'];
        } else {
            $tin1 = '';
        }
        
        if(isset($request['tin2'])){
            $tin2 = $request['tin2'];
        } else {
            $tin2 = '';
        }

        if(isset($request['tin3'])){
            $tin3 = $request['tin3'];
        } else {
            $tin3 = '';
        }

        if(isset($request['branch_code'])){
            $branch_code = $request['branch_code'];
        } else {
            $branch_code = '';
        }

        if(isset($request['rdo_code'])){
            $rdo_code = $request['rdo_code'];
        } else {
            $rdo_code = '';
        }

        if(isset($request['line_of_business'])){
            $line_of_business = $request['line_of_business'];
        } else {
            $line_of_business = '';
        }

        if(isset($request['zip_code'])){
            $zip_code = $request['zip_code'];
        } else {
            $zip_code = '';
        }


        $current_company = Company::where('company_id', '=', $company_id)->firstOrFail();

        $current_company->tin1 = $tin1;
        $current_company->tin2 = $tin2;
        $current_company->tin3 = $tin3;
        $current_company->branch_code = $branch_code;
        $current_company->rdo_code = $rdo_code;
        $current_company->line_of_business = $line_of_business;
        $current_company->zip_code = $zip_code;

        $current_company->save();

        return ['message' => 'User info updated!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // isAdmin is from /app/Providers/AuthServiceProvider
        $this->authorize('isAdmin');

        $user = User::findOrFail($id);

        // Send Request
        $user->delete();
        
        return ['message' => 'User Deleted'];
    }

    public function search(){
        if ($search = \Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%")
                        ->orWhere('type','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }
        return $users;
    }
}
