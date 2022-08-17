<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserToken;
use Auth;

class BasicTokenMiddleware
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
        if ($request->header('Authorization') == null) {
            return response([
                "status"    =>  "error",
                "message"   =>  "You dont have a token on header"
            ], 401);
        }

        $userToken = UserToken::where('token', $request->header('Authorization'))->first();

        if (!$userToken) {
            return response([
                "status"    =>  "error",
                "message"   =>  "Your token  did not match any token in the database"
            ], 401);
        }

        Auth::loginUsingId($userToken->users_id);

        return $next($request);
    }
}
