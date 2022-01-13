<?php

namespace App\Http\Controllers\v1;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use File;
use Log;

class ProfileController extends Controller
{
    public function updateAvatar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|max:2000',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>true, 'data'=>$validator->errors()->all()]);

        }

        $image = $request->file('avatar');
        // Rename image
        $filename = time().auth()->user()->name. '.' .$image->guessExtension();

        $image->move(auth()->user()->avatars_folder, $filename);

        $image = isset(auth()->user()->avatar) ? auth()->user()->avatar : null;

        if ($image) {
            $path = parse_url(auth()->user()->avatars_folder.auth()->user()->avatar);
            File::delete(public_path($path['path']));
        }

        auth()->user()->avatar = $filename;
        auth()->user()->save();
        return response()->json(['success'=>true, 'data'=>'Avatar uploaded successfully!']);
    }

    public function deleteAvatar(){
        $image=auth()->user()->avatar;

        if ($image) {
            $path=parse_url(auth()->user()->avatars_folder.auth()->user()->avatar);
            File::delete(public_path($path['path']));
            auth()->user()->avatar=null;
            auth()->user()->save();
        }


        return response()->json(['success'=>true, 'data'=>'Avatar deleted successfully!']);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'email|required',
            'old_password' => 'required',
            'new_password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>true, 'data'=>$validator->errors()->all()]);

        }
        if($request->email=== auth()->user()->email && Hash::check($request->old_password, auth()->user()->password)){
            $user=auth()->user();
        }else{
            return response()->json(['errors'=> true, 'data'=>'Your credentials are wrong']);
        }
        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success'=>true, 'data'=>'Pasword updated successfully!']);


    }

    public function deleteProfile(Request $request){

        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>true, 'data'=>$validator->errors()->all()]);

        }
        if(Hash::check($request->password, auth()->user()->password)){
            $user=auth()->user();
        }else{
            return response()->json(['errors'=> true, 'data'=>'Password is incorrect']);
        }

        //delete articles genres
        $articles=$user->articles()->get();
        foreach($articles as $article){
            $article->genres()->detach();//
        }
        //delete articles
        Article::where('user_id', $user->id)->delete();
        //delete comments
        Comment::where('user_id', $user->id)->delete();
        //delete likes
        Like::withTrashed()->where('user_id', $user->id)->forceDelete();

        //delete token
        $user->tokens()->delete();
        //delete avatar picture if exists
        if($user->avatar){
            $path=parse_url(auth()->user()->avatars_folder.auth()->user()->avatar);
            File::delete(public_path($path['path']));
        }
        //and user
        $user->delete();

        return response()->json(['success'=>true, 'data'=>'Profile deleted successfully!']);
    }



}
