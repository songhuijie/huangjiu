<?php

namespace App\Http\Middleware;


use App\Libraries\Lib_const_status;
use App\Model\User;
use App\Services\AccessEntity;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
class CheckMiddle extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        return True;

        // return $next("");
    }
}