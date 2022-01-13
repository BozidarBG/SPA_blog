<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BannedUser as Banned;
use Carbon\Carbon;

class BannedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $banned_user=Banned::where('user_id', auth()->id())->first();
        if($banned_user){
            $now=Carbon::now();
            if($banned_user->until > $now ){
                return response()->json(['errors'=>true, 'data'=>'You are banned until '.$banned_user->until]);
            }else{
                //ban has expired
                $banned_user->delete();
                return $next($request);
            }
         
        }
        return $next($request);
    }
}
