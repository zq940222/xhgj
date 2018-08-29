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
use app\admin\model\PassagewayCategory;
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

    /**
     * @desc 添加站点
     */
    public function addDevice()
    {
        $deviceName = input('post.device_name/s','');
        $deviceID = input('post.device_id/s','');
        $province = input('post.province/s','');
        $projectID = input('post.project_id/d',0);
        $electricType = input('post.electric_type/s','');
        $voltage = input('post.voltage/s','');
        $protocol = input('post.protocol/d',0);
        $mark = input('post.mark/s','');
        $environment = input('post.environment/s','');
        $accendant_name = input('post.accendant_name/s','');
        $accendant_department = input('post.accendant_department/s','');
        $accendant_email = input('post.accendant_email/s','');
        $accendant_mobile = input('post.accendant_mobile/s','');

        $model = new Device();
        $model->device_name = $deviceName;
        $model->device_id = $deviceID;
        $model->province = $province;
        $model->project_id = $projectID;
        $model->electric_type = $electricType;
        $model->voltage = $voltage;
        $model->protocol = $protocol;
        $model->mark = $mark;
        $model->environment = $environment;
        $model->accendant_name = $accendant_name;
        $model->accendant_department = $accendant_department;
        $model->accendant_email = $accendant_email;
        $model->accendant_mobile = $accendant_mobile;
        $res = $model->save();
        if ($res)
        {
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }

    }

    /**
     * @desc 报警设置
     * @throws \think\exception\DbException
     */
    public function alarmSetting()
    {
        $deviceID = input('post.device_id/s','');
        $alarm_communication_time = input('post.alarm_communication_time/d',1);
        $alarm_type = input('post.alarm_type/d',0);

        $model = Device::get($deviceID);
        $model->alarm_communication_time = $alarm_communication_time;
        $model->alarm_type = $alarm_type;
        $res = $model->save();
        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    /**
     * @desc 通讯设置
     * @throws \think\exception\DbException
     */
    public function communicationSetting()
    {
        $deviceID = input('post.device_id/s','');
        $communicationdistance = input('post.communicationdistance/d',1);
        $ip = input('post.ip/s','');
        $port_number = input('post.port_number/s','');

        $model = Device::get($deviceID);
        $model->communicationdistance = $communicationdistance;
        $model->ip = $ip;
        $model->port_number = $port_number;
        $res = $model->save();
        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    /**
     * @desc 通道列表
     * @throws \think\exception\DbException
     */
    public function passList()
    {
        $page = input('param.page/d',1);
        $size = input('param.size/d',10);
        $device_id = input('param.device_id/s','');

        $where = [];
        $where['device_id'] = ['=',$device_id];

        $data = Passageway::order('id desc')
            ->with(['category'])
            ->where($where)
            ->paginate($size,false,['page'=> $page]);
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 新增通道
     */
    public function addPass()
    {
        $passageways = input('post.passageways/a',[]);

        $model = new Passageway();
        $res = $model->save($passageways);
        if ($res)
        {
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }

    /**
     * @desc 修改通道
     * @throws \think\exception\DbException
     */
    public function editPass()
    {
        $name = input('post.name/s','');
        $category_id = input('post.category_id/d',0);
        $start_coding = input('post.start_coding/s','');
        $end_coding = input('post.end_coding/s','');
        $a = input('post.a/d',1);
        $b = input('post.b/d',0);
        $switch_alarm = input('post.switch_alarm/d',0);
        $id = input('post.id/d',0);

        $model = Passageway::get($id);
        $model->name = $name;
        $model->category_id = $category_id;
        $model->start_coding = $start_coding;
        $model->end_coding = $end_coding;
        $model->a = $a;
        $model->b = $b;
        $model->switch_alarm = $switch_alarm;
        $res = $model->save();
        if ($res)
        {
            return $this->success('修改成功');
        }else{
            return $this->error('修改失败');
        }
    }

    /**
     * @desc 通道类型列表
     * @throws \think\exception\DbException
     */
    public function categoryList()
    {
        $data = PassagewayCategory::all();
        return $this->success('请求成功','',$data);
    }

}