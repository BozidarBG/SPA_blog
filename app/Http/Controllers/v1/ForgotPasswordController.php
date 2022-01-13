<?php

namespace App\Http\Controllers\v1;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Support\Facades\Validator;
use Log;
use Auth;
use App\Models\User;

class ForgotPasswordController extends Controller
{

    public function forgotPassword(Request $request){

        $validation=Validator::make($request->all(), [
            'email'=>'email|required'
        ]);

        if(!$validation->fails()){
            $user=User::where('email', $request->email)->first();
            if(!$user){
                return response()->json(['error'=>true, 'data'=>'No such user']);
            }

            Password::sendResetLink($request->all());

            return response()->json(['success'=>true, 'data'=>'Reset email sent successfully']);
        }else{
            return response()->json(['error'=>true, 'data'=>$validation->errors()->all()]);
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response(['success'=>true,
                'data'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }

}
