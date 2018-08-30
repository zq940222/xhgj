<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 14:34
 */

namespace app\api\model;


use think\Model;

class Project_inspect_log extends Model
{
   //base64上传图片
    public function setImgAttr($value)
    {
        return json($value);
    }

    public function getImgAttr($value)
    {
        return json_decode($value,true);
    }
}