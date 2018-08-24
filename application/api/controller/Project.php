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
         $uid=\app\api\service\Token::getCurrentUid();
         $pid=Project_admin::where('id',$uid)->field('p_id')->find();
         $data=Device::where('project_id',$pid['p_id'])->order('install_last_time desc')
             ->field('device_name,device_id,install_last_time,maintain_last_worker,status')->select();
         return $this->success('请求成功','',$data);
     }
     //项目概况-报警列表
    public function police(){
        $uid=\app\api\service\Token::getCurrentUid();
        $pid=Project_admin::where('id',$uid)->field('p_id')->find();
        $data=Device::where('project_id',$pid['p_id'])->where('status','neq',0)
            ->order('install_last_time desc')
            ->field('device_name,device_id,status')->select();
        return $this->success('请求成功','',$data);
    }
    //项目概况-站点详情
    public function details(){
        $uid=\app\api\service\Token::getCurrentUid();
         $did=Request::instance()->get('device_id',0);//站点id
         $data=Passageway::where('passageway.device_id',$did)
             ->field('passageway.*')
             ->select();
         return $this->success('请求成功','',$data);
    }
    //日志
    public function log(){
        $uid=\app\api\service\Token::getCurrentUid();
        $did=Request::instance()->get('device_id',0);//站点id
        $data=Device_log::join('project_admin','project_admin.id=device_log.project_id')
            ->where('device_log.project_id',$uid)->where('device_log.device_id',$did)
            ->order('time desc')->field('name,device_log.*')->select();
        return $this->success('请求成功','',$data);
    }
    //站点基本信息
    public function unit(){
        $uid=\app\api\service\Token::getCurrentUid();
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
        $uid=\app\api\service\Token::getCurrentUid();
        $data=Project_admin::where('id',$uid)
            ->field('name,department,email,phone_number')->find();
        return $this->success('请求成功','',$data);
    }
    //历史曲线-通道信息

    public function Channel(){
        $pid=Request::instance()->get('id',0);//通道id
        $info=Passageway::where('id',$pid)->find();
        return  $this->success('请求成功','',$info);
    }

}