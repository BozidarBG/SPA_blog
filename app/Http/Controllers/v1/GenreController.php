<?php

namespace App\Http\Controllers\v1;

use App\Models\Genre;
use App\Http\Resources\GenreCollection;
use App\Http\Resources\GenreResource;
use App\Http\Controllers\Controller;


class GenreController extends Controller
{

    public function index()
    {
        $genres=Genre::withCount(['articles'])->get();
        return response()->json(['success'=>true, 'data'=>new GenreCollection($genres)]);
    }

    public function show($id)
    {
        $genre=Genre::where('id', $id)->withCount(['articles'])->first();
        if(!$genre){
            return response()->json(['error'=>'This genre does not exist'], 404);

        }
        return response()->json(['success'=>true, 'data'=>new GenreResource($genre)]);

    }




}
