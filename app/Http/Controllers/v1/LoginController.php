<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if(auth()->check()){
            return response()->json(['error'=>true, 'data'=>'You are aleready logged in!']);
        }
        $result=Validator::make($request->all(),[
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if(!$result->fails()){
            $credentials = request(['email', 'password']);
            if (!auth()->attempt($credentials)) {
                return response()->json(['errors'=>true, 'data'=>"Your credentials are wrong!"]);
            }else{
                $user = User::where('email', $request->email)->first();
                $authToken = $user->createToken('auth-token')->plainTextToken;

                return response()->json(['success'=>true, 'access_token' => $authToken, 'token_type'=>'Bearer']);
            }

        }else{
            return response()->json(['errors'=>$result->errors()->all()]);
        }
    }

    public function logout(Request $request){

        auth()->user()->tokens()->delete();

        return response()->json(['success'=>true, 'data'=>"You are logged out!"]);
    }
}
