<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Lib_config;
use App\Libraries\Lib_const_status;
use App\Model\About;
use App\Model\Recommend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;

class RecommendController extends Controller
{
    private $user;
    private $recommend;
    private $about;
    public function __construct(User $user,Recommend $recommend,About $about)
    {
        $this->user = $user;
        $this->recommend = $recommend;
        $this->about = $about;
    }

    /**
     * 推荐列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function RecommendList(Request $request){

        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'page'=>'int',
        ],[
            'int'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }
        $page = $all['page']?$all['page']:Lib_config::PAGE;
        $limit = Lib_config::LIMIT;
        $response_json = $this->initResponse();
        $res = $this->recommend->getAll($page,$limit);
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data = $res;
        return $this->response($response_json);
    }

    /**
     * 推荐详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function RecommendDetail(Request $request){
        $all = $request->all();
        $fromErr = $this->validatorFrom([
            'recommend_id'=>'required',
        ],[
            'required'=>Lib_const_status::ERROR_REQUEST_PARAMETER,
        ]);
        if($fromErr){//输出表单验证错误信息
            return $this->response($fromErr);
        }

        $response_json = $this->initResponse();

        $detail = $this->recommend->find($all['recommend_id']);
        if($detail){
            $response_json->status = Lib_const_status::SUCCESS;
            $response_json->data = $detail;
        }else{
            $response_json->status = Lib_const_status::RECOMMENDED_ARTICLES_NOT_EXISTENT;
        }
        return $this->response($response_json);
    }

    /**
     * 返回关于信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function AboutInfo(){
        $response_json = $this->initResponse();
        $about = $this->about->first();
        $response_json->status = Lib_const_status::SUCCESS;
        $response_json->data->content = $about->content;
        return $this->response($response_json);
    }
}