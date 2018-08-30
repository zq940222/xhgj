<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 18:19
 */

namespace app\api\model;


use think\Model;

class Projects extends Model
{
    public function device()
    {
        return $this->hasMany('Device','project_id','id');
    }
}