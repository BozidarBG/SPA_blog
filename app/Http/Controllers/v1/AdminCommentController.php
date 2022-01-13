<?php

namespace App\Http\Controllers\v1;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AdminCommentController extends Controller
{
    public function destroy($id)
    {
        $comment=Comment::find($id);
        if(!$comment){
            return response()->json(['error'=>'This comment does not exist']);
        }

        $comment->delete();

        return response()->json(['success'=>true, 'data'=>'Comment deleted.']);

    }

}
