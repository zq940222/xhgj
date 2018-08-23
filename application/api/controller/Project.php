<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 10:21
 */

namespace app\api\controller;


use app\api\model\Device;
use app\api\model\Device_log;
use app\api\model\Passageway;
use app\api\model\Project_admin;
use app\api\model\Read_device;
use think\Controller;
use think\Db;
use think\Request;

class Project  extends Base
{
     //项目概况-站点列表
     public function project(){
         $uid=\app\api\token\Token::getCurrentUid();
         $pid=Project_admin::where('id',$uid)->field('p_id')->find();
         $data=Device::where('project_id',$pid['p_id'])->order('install_last_time desc')
             ->field('device_name,device_id,install_last_time,maintain_last_worker,status')->select();
         return $this->success('请求成功','',$data);
     }
     //项目概况-报警列表
    public function police(){
        $uid=\app\api\token\Token::getCurrentUid();
        $pid=Project_admin::where('id',$uid)->field('p_id')->find();
        $data=Device::where('project_id',$pid['p_id'])->where('status','neq',0)
            ->order('install_last_time desc')
            ->field('device_name,device_id,status')->select();
        return $this->success('请求成功','',$data);
    }
    //项目概况-站点详情
    public function details(){
        $uid=\app\api\token\Token::getCurrentUid();
         $did=Request::instance()->get('device_id',0);//站点id
         $data=Passageway::join('device','device.device_id=passageway.device_id')
             ->where('passageway.device_id',$did)
             ->field('name,change_range_max,change_range_min,max_range,max_range,min_range,count_time,value,change_value')
             ->select();
         return $this->success('请求成功','',$data);
    }
    //日志
    public function log(){
        $uid=\app\api\token\Token::getCurrentUid();
        $did=Request::instance()->get('device_id',0);//站点id
        $data=Device_log::join('project_admin','project_admin.id=device_log.project_id')
            ->where('project_id',$uid)->where('device_id',$did)
            ->order('time desc')->field('name,log_info,time')->select();
        return $this->success('请求成功','',$data);
    }
    //站点基本信息
    public function unit(){
        $uid=\app\api\token\Token::getCurrentUid();
        $did=Request::instance()->get('device_id',0);//站点id
        $pid=Project_admin::where('id',$uid)->field('p_id')->find();
        $data=Device::where('project_id',$pid['p_id'])->where('device_id',$did)
            ->field('electric_type,protocol,environment,status,device_name')->find();
        //单元标识符
        $uit =Read_device::where('device_id',$did)
            ->field('mark')->find();
        $data['unit']=$uit['mark'];
        return $this->success('请求成功','',$data);
    }
    //管理员
    public function admins(){
        $uid=\app\api\token\Token::getCurrentUid();
        $data=Project_admin::where('id',$uid)
            ->field('name,department,email,phone_number')->find();
        return $this->success('请求成功','',$data);
    }
}