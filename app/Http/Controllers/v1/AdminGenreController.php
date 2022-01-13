<?php

namespace App\Http\Controllers\v1;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class AdminGenreController extends Controller
{
    public function store(Request $request)
    {
        $validation=Validator::make($request->all(), [
            'name'=>'required|string|min:2|max:255',
            'description'=>'required|string|min:2',
        ]);

        if(!$validation->fails()){
            $genre=new Genre();
            $genre->name=$request->name;
            $genre->description=$request->description;
            $genre->save();

            return response()->json(['success'=>true, 'data'=>$genre]);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
    }

    public function update(Request $request, $id)
    {
        $validation=Validator::make($request->all(), [
            'name'=>'required|string|min:2|max:255',
            'description'=>'required|string|min:2',
        ]);

        if(!$validation->fails()){
            $genre=Genre::find($id);

            if(!$genre){
                return response()->json(['error'=>true, 'data'=>'Requested genre is not found!']);
            }

            $genre->name=$request->name;
            $genre->description=$request->description;
            $genre->save();

            return response()->json(['success'=>true, 'data'=>$genre]);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
    }

    public function destroy($id)
    {
        $genre=Genre::find($id);

        if(!$genre){
            return response()->json(['error'=>true, 'data'=>'Requested genre is not found!']);
        }
        foreach($genre->articles as $article){
            $article->pivot->delete();
        }
        $genre->delete();

        return response()->json(['success'=>true, 'data'=>"Genre deleted."]);
    }

}

