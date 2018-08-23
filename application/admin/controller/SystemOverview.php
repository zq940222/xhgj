<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 9:54
 */

namespace app\admin\controller;



use app\admin\model\Projects;

class SystemOverview extends BaseController
{
    public function ProjectList()
    {
        $data = Projects::order('maintain_last_time desc')
            ->relation(['device'])->select();
        return $this->success('请求成功','',$data);
    }
}