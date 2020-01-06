<?php

namespace App\Http\Controllers\File;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use File;

class FileController extends Controller
{
    /**
    * 视频上传类
    * @param string video 上传的视频
    *
    **/
    public function uploads(Request $request)
    {

        $file = $request->file('file'); 

        if ($file->isValid()) {

       		$dir = 'uploads/video/';/*上传目录 public目录下 uploads/thumb 文件夹*/ 
            /*文件名。格式：时间戳 + 6位随机数 + 后缀名*/
            $filename = time() . mt_rand(100000, 999999) . '.' . $file ->getClientOriginalExtension();
            $file->move($dir, $filename);
            $path = $dir . $filename;
            return [
            	'code' => 0,
            	'data' => $path
            ];
        }
    	
    }

    /**
    * 图片上传
    * @param string video 上传的视频
    *
    **/
    public function img(Request $request)
    {

        $file = $request->file('file'); 

        if ($file->isValid()) {

            $dir = 'uploads/img/';/*上传目录 public目录下 uploads/thumb 文件夹*/ 
            /*文件名。格式：时间戳 + 6位随机数 + 后缀名*/
            $filename = time() . mt_rand(100000, 999999) . '.' . $file ->getClientOriginalExtension();
            $file->move($dir, $filename);
            $path = $dir . $filename;
            return [
                'code' => 0,
                'data' => $path
            ];
        }
        
    }

    /**
     * 图片上传  layui 上传
     * @param Request $request
     * @return string
     */
    function LayerUpload(Request $request){

        $file = $request->file('file');
        $file_path = 'images/';
        // 判断文件是否上传
        if ($file) {
            // 获取后缀名
            $ext=$file->getClientOriginalExtension();
            // 新的文件名
            $newFile=date('YmdHis',time()).'_'.rand().".".$ext;
            // 上传文件操作
            $url = nowUrl().'/'.$file_path.date("Ymd",time())."/".$newFile;
            $path = public_path().'/'.$file_path.date("Ymd",time());
            if (!is_dir($path)) {
                mkdir($path,0777,true);
            }
            if($file->move($path,$newFile)){
                return json_encode(['code'=>0,'msg'=>'图片信息','data'=>['src'=>$url]]);
            }
        }
    }

    /**
    * 视频上传类
    * @param string video 上传的视频
    *
    **/
    public function video(Request $request)
    {

        $file = $request->file('file'); 

        if ($file->isValid()) {

       		$dir = 'uploads/video/';/*上传目录 public目录下 uploads/thumb 文件夹*/ 
            /*文件名。格式：时间戳 + 6位随机数 + 后缀名*/
            $filename = time() . mt_rand(100000, 999999) . '.' . $file ->getClientOriginalExtension();
            $file->move($dir, $filename);
            $path = $dir . $filename;
            return [
            	'code' => 0,
            	'data' => $path
            ];
        }
    	
    }

    /**
    * 删除成功
    * @param string video 删除成功
    *
    **/
    public function del_file(Request $request)
    {

        $file = $request->input('file'); 

        $file = mb_substr($file,1,100);
       
        if (!file_exists($file)) {
            return [
                'code' => 0,
                'msg' => '文件不存在'
            ];
        }

        $result = unlink($file);
        if ($result) {
            return [
                'code' => 1,
                'msg' => '删除成功',
                'file' => $file
            ];
        }

        
    }


}
