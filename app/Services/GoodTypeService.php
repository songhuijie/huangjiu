<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/23
 * Time: 16:36
 */
namespace App\Services;

use App\Model\GoodsType;

class GoodTypeService{

    /**
     * goods_type
     * @return array
     */
    public static function GoodsType(){
        $goods_type = new GoodsType();

        $all = $goods_type->getAll();
        if($all){
            $array = [];
            foreach($all as $k=>$v){
                $array[$v->id] = $v->type_name;
            }
            return $array;
        }
    }
}