<?php

namespace App\Http\Controllers\v1;

use App\Models\Article;
use App\Models\Genre;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleSingleResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Log;


class ArticleController extends Controller
{

    public function index(Request $request)
    {
        $page=LengthAwarePaginator::resolveCurrentPage();
        $perPage=10;
        //if request contains ?per_page=integer, we will display that many results per page
        //if request is wrong, we will return error
        if($request->has('per_page')){

            $validation=Validator::make($request->all(), [
                'per_page'=>'integer|min:2|max:50'
            ]);

            if($validation->fails()){
                return response()->json(['error'=>true, 'data'=>'Error: you need to include per_page=number in you request']);
            }
            $perPage=$request->per_page;
        }

        //do we have genre in request genre=genre_name
        //if such genre_name does not exist, we will return error
        if($request->has('genre')){
            $genre=Genre::where('name', $request->genre)->first();
            if(!$genre){
                return response()->json(['error'=>true, 'data'=>'This genre does not exist']);

            }
            $articles=$genre->articles()->with('user', 'likes', 'comments', 'genres')->withCount(['comments','likes'])->orderBy('created_at', 'desc')->get();
        }else{
            $articles=Article::with('user', 'likes', 'comments', 'genres')->withCount(['comments','likes'])->orderBy('created_at', 'desc')->get();
        }

        $results=$articles->slice(($page-1)*$perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $articles->count(), $perPage, $page,[
            'path'=>LengthAwarePaginator::resolveCurrentPath()
        ]);
        $paginated->appends(request()->all());

        return response()->json(['success'=>true, 'data'=>new ArticleCollection($paginated)]);
    }


    public function store(Request $request)
    {
        //Log::info($request->all());
        $validation=Validator::make($request->all(), [
            'title'=>'required|string|min:2|max:255',
            'body'=>'required|string|min:2',
            'genres.*'=>'exists:genres,id'
        ]);

        if(!$validation->fails()){
            $article=new Article();
            $article->user_id=auth()->id();
            $article->title=$request->title;
            $article->slug=Str::slug($request->title);
            $article->body=$request->body;
            $article->save();

            if($request->has('genres')){
                $article->genres()->attach($request->genres);
            }

            return response()->json(['success'=>true, 'data'=>$article]);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
    }


    public function show($slug)
    {
        $article=Article::with('user', 'likes', 'comments', 'genres')->withCount(['comments','likes'])->where('slug', $slug)->first();

        if($article){
            return  new ArticleSingleResource($article);
        }else{
            return response()->json(['error'=>true, 'data'=>"Article not found"]);
        }
    }


    public function update(Request $request, $slug)
    {
        $validation=Validator::make($request->all(), [
            'title'=>'required|string|min:2|max:255',
            'body'=>'required|string|min:2',
            'genres.*'=>'exists:genres,id'
        ]);

        if(!$validation->fails()){
            $article=Article::where('slug', $slug)->first();

            if(!$article){
                return response()->json(['error'=>true, 'data'=>'Requested article is not found!']);
            }

            if($article->user_id !== auth()->id()){
                return response()->json(['error'=>true, 'data'=>'Not allowed!']);
            }

            $article->title=$request->title;
            $article->slug=Str::slug($request->title);
            $article->body=$request->body;
            $article->save();

            if($request->has('genres')){
                $article->genres()->sync($request->genres);
            }else{
                $article->genres()->detach();
            }

            return response()->json(['success'=>true, 'data'=>$article]);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
    }

    public function destroy($slug)
    {
        $article=Article::where('slug', $slug)->first();

        if(!$article){
            return response()->json(['error'=>true, 'data'=>'Requested article is not found!']);
        }

        if($article->user_id !== auth()->id()){
            return response()->json(['error'=>true, 'data'=>'Not allowed!']);
        }
        $article->genres()->detach();
        //delete comments
        Comment::where('article_id', $article->id)->delete();
        //delete likes
        Like::withTrashed()->where('article_id', $article->id)->forceDelete();
        $article->delete();
        return response()->json(['success'=>true, 'data'=>"Article deleted."]);
    }


    public function myArticles(Request $request)
    {

        $page=LengthAwarePaginator::resolveCurrentPage();
        $perPage=10;
        //if request contains ?per_page=integer, we will display that many results per page
        //if request is wrong, we will return error
        if($request->has('per_page')){

            $validation=Validator::make($request->all(), [
                'per_page'=>'integer|min:2|max:50'
            ]);

            if($validation->fails()){
                return response()->json(['error'=>true, 'data'=>'Error: you need to include per_page=number in you request']);

            }
            $perPage=$request->per_page;
        }

        $articles=Article::with('user', 'likes', 'comments', 'genres')->withCount(['comments','likes'])->where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        $results=$articles->slice(($page-1)*$perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $articles->count(), $perPage, $page,[
            'path'=>LengthAwarePaginator::resolveCurrentPath()
        ]);
        $paginated->appends(request()->all());
        return response()->json(['success'=>true, 'data'=>new ArticleCollection($paginated)]);

    }
}
