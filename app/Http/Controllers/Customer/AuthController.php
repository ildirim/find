<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Customer\Customer;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }

		$user = Customer::create([
			'username' => $request->username,
			'firstname' => $request->firstname,
			'lastname' => $request->lastname,
			'phone' => $request->phone,
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'status' => $request->status,
			'is_sign' => $request->is_sign
		]);

		if ($user->exists) {
      		return response()->json(['success'=> true, 'message'=> 'Added new customer']);
		} else {
	      	return response()->json(['error'=> false, 'message'=> 'DB error']);
		}
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        // try to auth and get the token using api authentication
        if (!$token = auth('api')->attempt($credentials)) {
            // if the credentials are wrong we send an unauthorized error in json format
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'type' => 'bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
            
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }

    public function getUser()
    {
	    try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

	    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

	    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

	    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

	    }

	    return response()->json(compact('user'));
    }
}
