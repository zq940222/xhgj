<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/27
 * Time: 16:06
 */

namespace app\admin\controller;


class DeviceManagement extends BaseController
{
    public function projectList()
    {
        $keywords = input('param.keywords/s','');
    }
}