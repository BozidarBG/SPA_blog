<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Like;

class AdminArticleController extends Controller
{

    public function destroy($slug)
    {
        $article=Article::where('slug', $slug)->first();

        if(!$article){
            return response()->json(['error'=>true, 'data'=>'Requested article is not found!']);
        }

        $article->genres()->detach();

        Comment::where('article_id', $article->id)->delete();

        Like::withTrashed()->where('article_id', $article->id)->forceDelete();

        $article->delete();

        return response()->json(['success'=>true, 'data'=>"Article deleted."]);
    }
}
