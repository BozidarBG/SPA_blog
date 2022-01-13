<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;


class AdminUserController extends Controller
{
    public function index(Request $request){

        $page=LengthAwarePaginator::resolveCurrentPage();
        $perPage=10;

        if($request->has('per_page')){

            $validation=Validator::make($request->all(), [
                'per_page'=>'integer|min:2|max:50'
            ]);

            if($validation->fails()){
                return response()->json(['error'=>true, 'data'=>'Error: you need to include per_page=number in you request']);

            }
            $perPage=$request->per_page;
        }

        $users=User::withCount(['banned', 'articles', 'comments'])->get();

        $results=$users->slice(($page-1)*$perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $users->count(), $perPage, $page,[
            'path'=>LengthAwarePaginator::resolveCurrentPath()
        ]);
        $paginated->appends(request()->all());

        return response()->json(['success'=>true, 'data'=>$paginated]);
    }
}
