<?php


namespace App\Http\Middleware;


use App\User;
use Closure;

class TeamCreator
{
    public function handle($request, Closure $next)
    {
        if (!$request->get('_token')) {
            return response()->json(0);
        }
        $request['creator'] = User::where('_token','=',$request->get('_token'))->first()->id;
        return $next($request);
    }
}