<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28
 * Time: 16:21
 */

namespace app\api\model;


use think\Model;

class DeviceLog extends Model
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