<?php

namespace App\Http\Controllers;

use App\Libraries\Lib_const_status;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validatorFrom($rule, $mes = []) {
        $defaultMessages = [
            'required'   => ':attribute 参数不存在',
            'email'      => '邮箱不正确',
            'unique'     => ':attribute 已被占用',
            'alpha_dash' => ':attribute 有不允许字符',
            'max'        => ':attribute 超过最大限制',
            'confirmed'  => '两次密码不一致',
        ];
        $messages = array_merge($defaultMessages, $mes);
        $validator = Validator::make(Input::all(), $rule, $messages);
        if ($validator->fails()) {
            $messages = $validator->errors();
            foreach ($messages->all() as $msg) {
                return $this->initResponse(Lib_const_status::CORRECT,(int)$msg);
            }
        }
        return false;
    }

    /**
     * @param int $code
     * @param int $status
     * @return \stdClass
     */
    public  function   initResponse($code = Lib_const_status::CORRECT,$status = Lib_const_status::ERROR_REQUEST_PARAMETER)
    {
        $response_object = new \stdClass();
        $response_object->code = $code;
        $response_object->status = $status;
        $response_object->data = new \StdClass();
        return $response_object;
    }



    public function response($response_object){

        return response()->json($response_object);
    }


}
