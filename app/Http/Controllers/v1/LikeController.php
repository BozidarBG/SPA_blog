<?php

namespace App\Http\Controllers\v1;

use App\Models\Like;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;


class LikeController extends Controller
{
    public function toggle(Request $request, $slug){
        $article=Article::where('slug', $slug)->first();

        if(!$article){
            return response()->json(['error'=>true, 'data'=>'Requested article is not found!']);
        }

        $like=Like::withTrashed()->where(['user_id'=> auth()->id(), 'article_id'=> $article->id])->first();


        if($like){
            //user has liked before so now it is un-like
            if($like->trashed()){
                $like->restore();
                return response()->json(['success'=>true, 'article_is_liked_by_user'=>true]);

            }else{
                $like->delete();
                return response()->json(['success'=>true, 'article_is_liked_by_user'=>false]);
            }
            
        }else{
            //user has not liked this article before so it is now like
            $like=new Like();
            $like->user_id=auth()->id();
            $like->article_id=$article->id;
            $like->save();
            return response()->json(['success'=>true, 'article_is_liked_by_user'=>true]);
        }
        

    }
}
