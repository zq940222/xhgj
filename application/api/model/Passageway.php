<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 15:22
 */

namespace app\api\model;


use think\Model;

class Passageway extends Model
{
    public function device()
    {
        return $this->belongsTo('Device','device_id','device_id');
    }
}