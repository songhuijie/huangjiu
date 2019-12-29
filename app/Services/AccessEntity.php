<?php
/**
 * Created by PhpStorm.
 * User: hkx
 * Date: 2019/8/5
 * Time: 5:38 PM
 */
namespace App\Services;

use Illuminate\Support\Facades\Request;

/**
 * 注册访问者信息全局变量
 * Class AccessEntity
 * @package App\Services
 */
class AccessEntity{
    private static $_instance = null;
    public $user_id = 0;
    public $access_token = '';
    public $user_nickname = '';//昵称
    public $user_img = '';//头像
    public $lang = null; //语言
    /**
     * 注册一个实例
     * @return UserEntity|null
     */
    public static function getInstance()
    {
        if(self::$_instance === null){
            self::$_instance = new AccessEntity();
        }
        return self::$_instance;
    }
    /**
     * 语言转化可选语言包
     * @param $lang
     * @return string
     */
    public function langToLangPack($lang)
    {
        $lang = strtolower($lang);
        switch ($lang){
            case 'zh':
            case 'zh-tw':
            case 'zh-cn':
                $lang_pack = 'zh-cn';
                break;
            case 'ja-jp':
                $lang_pack = 'ja-jp';
                break;
            default:
                $lang_pack = 'en-us';
                break;
        }
        return $lang_pack;
    }
    /**
     * 设置语言,可能的语言语言包
     * @param string $lang
     */
    public function setLang($lang =''){
        if($this->lang == null){
            if(empty($lang)){
                $lang = Request::get('lang');
            }
            $this->lang = $this->langToLangPack($lang);
        }
    }

    /**
     * 获取可选语言
     * @return string
     */
    public function getLang(){
        $this->setLang();
        return $this->lang;
    }

}
