<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;

class AuthController extends Controller
{

    public function generateToken(Request $request, $user)
    {
        if(Auth::check()) {
            $request->request->add([
                'grant_type'    => 'password',
                'client_id'     => 2,
                'client_secret' => 'DRD5NNVfb5wcMDYB8cq5IaIf4bsYF3c4oxi9xpVX',
                'username'      => $user->email,
                'password'      => $request->password,
                'scope'         => '*'
            ]);
            $token          = Request::create('oauth/token', 'POST');
            $response       = \Route::dispatch($token);
            $json           = (array) json_decode($response->getContent());
            $json['status'] = 'success';
            $json['user']   = $user;
            $response->setContent(json_encode($json));
            return $response;
        } else {
            return response()->json(['error' => 'Not logged in yet', 'status' => 'fail', 'user' => null], 401);
        }
    }

    public function userRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'mobile' => ['required', 'numeric', 'min:10'],
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
	        return response()->json([
                'status' => 'fail',
                "error" => 'invalid_credentials',
			    "message" => $validator->messages()
            ], 401);
	    }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        if ($user) {
        	
	        // Check user using email or username and overwrite AuthenticatesUsers trait
	        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

	        $credentials = [
		        $field => $request->username,
		        'password' => $request->password
		    ];
	        
	        Auth::attempt($credentials);
	    	$user = Auth::user();

	    	return $this->generateToken($request, $user);
        } else {
        	return response()->json([
	            "error" => "invalid_credentials",
	            'status' => 'fail',
			    "message" => "The user credentials were incorrect."
	        ]);
        }
    }

    public function userLogin(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
	        return response()->json([
                'status' => 'fail',
                "error" => 'invalid_credentials',
			    "message" => $validator->messages()
            ]);
	    }

        // Check user using email or username and overwrite AuthenticatesUsers trait
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $credentials = [
	        $field => $request->username,
	        'password' => $request->password
	    ];
        
        if (!Auth::attempt($credentials)) {
        	return response()->json([
                "error" => "invalid_credentials",
                'status' => 'fail',
			    "message" => "The user credentials were incorrect."
            ]);
        }

    	$user   = Auth::user();

    	return $this->generateToken($request, $user);
    }

    public function userLogout(Request $request)
    {
        $request->user()->token()->revoke(); //Logout
        // $request->user()->token()->delete(); // Delete row
        return response()->json([
            'status' => 'success',
            "message" => "User logged out successfully."
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'newpassword' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                "error" => 'invalid_data',
                "message" => $validator->messages()
            ]);
        }

        $userId = $request->user()->id;
        $getRecUserPass = User::where('id', $userId)->pluck('password')->first();

        if ( Hash::check($request->password, $getRecUserPass) ) {
            $sql = User::where('id', $userId)->update([
                'password' => Hash::make($request->newpassword),
            ]);
            return response()->json([
                'status' => 'success',
                "message" => ""
            ]);
        }
        return response()->json([
            'status' => 'fail',
            "error" => 'invalid_password',
            "message" => ""
        ]);
    }
}
