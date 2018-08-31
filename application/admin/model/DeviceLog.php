<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 17:29
 */

namespace app\admin\model;


class DeviceLog extends BaseModel
{
    public function projectAdmin()
    {
        return $this->belongsTo('ProjectAdmin','project_id','id')->field(['id','name']);
    }

    //base64上传图片
    public function setImgAttr($value)
    {
        return json($value);
    }

    public function getImgAttr($value)
    {
        $array = json_decode($value,true);
        foreach ($array as &$v)
        {
            $v = $this->prefixImgUrl($v);
        }
        return $array;
    }
}