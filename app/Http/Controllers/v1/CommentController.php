<?php

namespace App\Http\Controllers\v1;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\CommentCollection;


class CommentController extends Controller
{

    public function store(Request $request)
    {
        $validation=Validator::make($request->all(), [
            'content'=>'required|string|min:2|max:1000',
            'article_id'=>'required|exists:articles,id'
        ]);

        if(!$validation->fails()){
            $comment=new Comment();
            $comment->user_id=auth()->id();
            $comment->article_id=$request->article_id;
            $comment->content=$request['content'];
            $comment->save();

            return response()->json(['success'=>true, 'data'=>$comment]);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
    }


    public function update(Request $request, $id)
    {
        $validation=Validator::make($request->all(), [
            'content'=>'required|string|min:2|max:1000',
            'article_id'=>'required|exists:articles,id'
        ]);

        if(!$validation->fails()){
            $comment=Comment::find($id);
            if(!$comment){
                return response()->json(['error'=>'This comment does not exist']);
            }

            if($comment->user_id !== auth()->id()){
                return response()->json(['error'=>true, 'data'=>'Not allowed!']);
            }

            $comment->content=$request['content'];
            $comment->save();

            return response()->json(['success'=>true, 'data'=>$comment]);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
    }

    public function destroy($id)
    {
        $comment=Comment::find($id);

        if(!$comment){
            return response()->json(['error'=>'This comment does not exist']);
        }

        if($comment->user_id !== auth()->id()){
            return response()->json(['error'=>true, 'data'=>'Not allowed!']);
        }

        $comment->delete();

        return response()->json(['success'=>true, 'data'=>'Comment deleted.']);

    }

    public function myComments(Request $request){

        $page=LengthAwarePaginator::resolveCurrentPage();
        $perPage=10;

        if($request->has('per_page')){

            $validation=Validator::make($request->all(), [
                'per_page'=>'integer|min:2|max:50'
            ]);

            if($validation->fails()){
                return response()->json(['error'=>'Request is wrong'], 403);

            }
            $perPage=$request->per_page;
        }

        $comments=Comment::with('user')->where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        $results=$comments->slice(($page-1)*$perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $comments->count(), $perPage, $page,[
            'path'=>LengthAwarePaginator::resolveCurrentPath()
        ]);
        $paginated->appends(request()->all());
        return new CommentCollection($paginated); //ok
    }


}
