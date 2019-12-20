<?php

namespace App\Http\Middleware;

use App\Libraries\Lib_const_status;
use App\Model\User;
use App\Services\AccessEntity;
use Closure;

class CheckAccessToken
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
        $access_token = $request->header('access_token');
        $user = new User();
        $token_array = $user->getByAccessToken($access_token);

        if($token_array && $token_array->expires_in > time()){

            $access_entity = AccessEntity::getInstance();
            $access_entity->user_id = $token_array->id;
            $access_entity->user_img = $token_array->user_img;
            $access_entity->user_nickname = $token_array->user_nickname;
            return $next($request);
        }else{
            $response_object = new \stdClass();
            $response_object->code = Lib_const_status::CORRECT;
            $response_object->status = Lib_const_status::USER_TOKEN_FAILURE;
            $response_object->data = new \StdClass();
            return response()->json($response_object);
        }
//        return $next($request);
    }
}

