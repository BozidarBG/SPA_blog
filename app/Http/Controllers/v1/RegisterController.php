<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;


class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $result=Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            'agree'=>['required']
        ]);

        if(!$result->fails()){
            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
            //Auth::login($user);

            return response()->json(['success'=>true, 'data'=>$user]);
        }else{
            return response()->json(['errors'=>$result->errors()->all()]);
        }


    }

    public function verifyEmail($id, $hash){
        $user=User::find($id);
        if(!$user){
            return response()->json(['errors'=>true, 'data'=>'User not found!']);
        }
        if(!hash_equals($hash, sha1($user->getEmailForVerification()))){
            return response()->json(['errors'=>true, 'data'=>'Something is wrong with the link you provided!']);
        }

        if(!$user->hasVerifiedEmail()){
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return response()->json(['success'=>true, 'data'=>'Account is verified']);
    }

    public function resendConfirmEmailAddress(Request $request){
        //Log::info($request->all());
        $user=User::where('email', $request->email)->first();
        if(!$user){
            return response()->json(['errors'=>true, 'data'=>'User not found!']);
        }
        $user->sendEmailVerificationNotification();
        return response()->json(['success'=>true, 'data'=>'Email resent. Please, check your inbox!']);
    }

}
