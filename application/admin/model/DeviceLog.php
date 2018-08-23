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
}