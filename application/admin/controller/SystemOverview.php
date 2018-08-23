<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 9:54
 */

namespace app\admin\controller;



use app\admin\model\Projects;
use app\library\AdminException;
use think\Exception;
use think\exception\HttpException;

class SystemOverview extends BaseController
{
    /**
     * @desc 项目列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function projectList()
    {
        $obj = Projects::order('build_start_time desc')
            ->relation(['device'])->select();
        $data = [];
        foreach ($obj as $value)
        {
            $status = 0;
            $count = 0;
            foreach ($value['device'] as $v)
            {
                if ($v['status'] != 0)
                {
                    $status = 1;
                }
                $count += 1;
            }
            $data[] = [
                'project_name' => $value['project_name'],
                'device_count' => $count,
                'build_start_time' => $value['build_start_time'],
                'longitude' => $value['longitude'],
                'latitude' => $value['latitude'],
                'status' => $status
            ];
        }
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 报警列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function alarmList()
    {
        $obj = Projects::order('build_start_time desc')
            ->relation(['device'])->select();
        $data = [];
        foreach ($obj as $value)
        {
            $status = 0;
            $count = 0;
            foreach ($value['device'] as $v)
            {
                if ($v['status'] != 0)
                {
                    $status = 1;
                    $count += 1;
                }

            }
            if ($status == 1)
            {
                $data[] = [
                    'project_name' => $value['project_name'],
                    'device_exception_count' => $count,
                    'longitude' => $value['longitude'],
                    'latitude' => $value['latitude'],
                ];
            }
        }
        return $this->success('请求成功','',$data);
    }

    public function projectConstruction($page = 1,$size = 10)
    {
        $data = Projects::order('build_start_time desc')
            ->paginate($size,false,['page'=> $page]);

        return $this->success('请求成功','',$data);
    }
}