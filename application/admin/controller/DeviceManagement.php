<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/27
 * Time: 16:06
 */

namespace app\admin\controller;


use app\admin\model\Device;
use app\admin\model\Passageway;
use app\admin\model\Projects;

class DeviceManagement extends BaseController
{
    /**
     * @desc 项目列表
     * @param int $page
     * @param int $size
     * @throws \think\exception\DbException
     */
    public function projectList($page = 1, $size = 10)
    {
        $keywords = input('param.keywords/s','');
        $where = [];
        if ($keywords)
        {
            $where['project_name|province'] = ['like',"%$keywords%"];
        }
        $data = Projects::order('build_start_time desc')
            ->where($where)
            ->paginate($size,false,['page'=>$page]);
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 添加项目
     */
    public function addProject()
    {
        $projectName = input('post.project_name/s','');
        $projectLogo = input('post.project_logo/s','');
        $province = input('post.province/s','');
        $longitude = input('post.longitude/s','');
        $latitude = input('post.latitude/s','');
        $projectExplain = input('post.project_explain/s','');

        $res = Projects::create([
            'project_name' => $projectName,
            'build_start_time' => time(),
            'project_explain' => $projectExplain,
            'logo' => $projectLogo,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'province' => $province
        ]);
        if ($res)
        {
            return $this->success('添加成功');
        }
        else{
            return $this->error('添加失败');
        }
    }

    /**
     * @desc 编辑项目
     * @throws \think\exception\DbException
     */
    public function editProject()
    {
        $id = input('post.id/d',0);
        $projectName = input('post.project_name/s','');
        $projectLogo = input('post.project_logo/s','');
        $province = input('post.province/s','');
        $longitude = input('post.longitude/s','');
        $latitude = input('post.latitude/s','');
        $projectExplain = input('post.project_explain/s','');

        $model = Projects::get($id);

        $model->project_name = $projectName;
        $model->project_explain = $projectExplain;
        if ($projectLogo)
        {
            $model->logo = $projectLogo;
        }
        $model->longitude = $longitude;
        $model->latitude = $latitude;
        $model->province = $province;
        $res = $model->save();

        if ($res)
        {
            return $this->success('编辑成功');
        }
        else{
            return $this->error('编辑失败');
        }
    }

    /**
     * @desc 删除项目
     */
    public function deleteProject()
    {
        $project_id = input('post.project_id/d',0);
        $device_ids = Device::where('project_id',$project_id)->column('device_id');
        Passageway::where('device_id','in',$device_ids)->delete();
        Device::destroy($device_ids);
        Projects::destroy($project_id);
        return $this->success('删除成功');
    }

    /**
     * @desc 站点列表
     * @param int $page
     * @param int $size
     * @throws \think\exception\DbException
     */
    public function deviceList($page = 1, $size = 10)
    {
        $keywords = input('param.keywords/s','');
        $where = [];
        if ($keywords)
        {
            $where['device_id|device_name'] = ['like',"%$keywords%"];
        }
        $array = Device::with(['project'])
            ->order('install_last_time desc')
            ->where($where)
            ->paginate($size,false,['page'=> $page])
            ->toArray();

        $data = [];
        $data['total'] = $array['total'];
        $data['per_page'] = $array['per_page'];
        $data['current_page'] = $array['current_page'];
        $data['last_page'] = $array['last_page'];
        foreach ($array['data'] as $value)
        {
            $data['data'][] = [
                'device_id' => $value['device_id'],
                'device_name' => $value['device_name'],
                'project_name' => $value['project']['project_name'],
                'province' => $value['project']['province'],
                'electric_type' => $value['electric_type'],
                'protocol' => $value['protocol'],
                'environment' => $value['environment'],
                'accendant_name' => $value['accendant_name'],
                'accendant_department' => $value['accendant_department'],
                'accendant_email' => $value['accendant_email'],
                'accendant_mobile' => $value['accendant_mobile']
            ];
        }
        return $this->success('请求成功','',$data);
    }

    public function addDevice()
    {
        $deviceName = input('post.device_name/s','');
        $deviceID = input('post.device_id/s','');
        $province = input('post.province/s','');
        $projectID = input('post.project_id/d',0);
        $electricType = input('post.electric_type/s','');
        $voltage = input('post.voltage/d','');

    }
}