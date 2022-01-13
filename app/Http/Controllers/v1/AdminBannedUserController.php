<?php

namespace App\Http\Controllers\v1;

use App\Models\BannedUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminBannedUserController extends Controller
{

    public function index()
    {
        $banned_users=BannedUser::with('banned_user', 'banned_by')->withTrashed()->orderBy('created_at', 'desc')->get();
        return response()->json(['success'=>true, 'data'=>$banned_users]);
    }

    public function banUser(Request $request)
    {
        $validation=Validator::make($request->all(), [
            'user_id'=>'required',
            'reason'=>'required|string|max:255',
            'plus_days'=>"required|integer"
        ]);

        if(!$validation->fails()){
            $user=User::find($request->user_id);

            if(!$user){
                return response()->json(['error'=>true, 'data'=>'This user is not found!']);
            }

            $ban=new BannedUser();
            $ban->user_id=$request->user_id;
            $ban->banned_by=auth()->id();
            $ban->reason=$request->reason;
            $ban->until=Carbon::now()->addDays($request->plus_days);
            $ban->save();
            return response()->json(['success'=>true, 'data'=>"User $user->name is banned successfully."]);

        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
    }


    public function removeBan($id)
    {

        $user=User::where('id', $id)->first();

        if(!$user){
            return response()->json(['error'=>true, 'data'=>'This user is not found!']);
        }

        $banned=BannedUser::where('user_id', $id)->first();
        if($banned){
            $banned->delete();
            return response()->json(['success'=>true, 'data'=>"User $user->name is un-banned successfully."]);
        }else{
            return response()->json(['errors'=>"Ban for this user does not exists!"]);

        }
    }


}


