<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 9:58
 */

namespace app\admin\model;


class Device extends BaseModel
{
    public function project()
    {
        return $this->belongsTo('Projects','project_id','id');
    }
}