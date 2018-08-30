<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 10:21
 */

namespace app\api\controller;


use app\admin\model\DeviceLog;
use app\api\model\Device;
use app\api\model\Device_data;
use app\api\model\Device_log;
use app\api\model\Passageway;
use app\api\model\Passageway_category;
use app\api\model\Project_admin;
use app\api\model\Read_device;
use think\Controller;
use think\Db;
use think\Request;

class Project  extends Base
{
//     public function promessage(){
//         $uid=\app\api\service\Token::getCurrentUid();
//     }
     //项目概况-站点列表
     public function project(){
         $uid=\app\api\service\Token::getCurrentUid();
//                 $uid=Request::instance()->get('aid',0);
         $res=Project_admin::where('id',$uid)->find();
         if($res['type']==1){
             $data=Device::where('project_id',$res['p_id'])->order('install_last_time desc')
                 ->field('device_name,device_id,install_last_time,maintain_last_worker,longitude,latitude,status')
                 ->select();
         }else{
             $data=Device::join('project_admin_device p','p.device_id=device.device_id')
               ->where('p.project_admin_id',$uid)
               ->order('device.install_last_time desc')
               ->field('device.device_name,device.device_id,device.install_last_time,device.maintain_last_worker,device.longitude,device.latitude,device.status')
               ->select();
         }
         return $this->success('请求成功','',$data);
     }
     //项目概况-报警列表
    public function police(){
        $uid=\app\api\service\Token::getCurrentUid();
//        $uid=Request::instance()->get('aid',0);
        $res=Project_admin::where('id',$uid)->find();
        if($res['type']==1){
            $data=Device::where('project_id',$res['p_id'])
                ->where('status','neq',0)
                ->order('install_last_time desc')
                ->field('device_name,device_id,longitude,latitude,status')->select();
        }else{
            $data=Device::join('project_admin_device p','p.device_id=device.device_id')
                ->where('p.project_admin_id',$uid)
                ->where('device.status','neq',0)
                ->order('device.install_last_time desc')
                ->field('device.device_name,device.device_id,device.longitude,device.latitude,device.status')
                ->select();
        }
        return $this->success('请求成功','',$data);
    }
    //项目概况-通道
    /*
    DROP TABLE IF EXISTS `passageway_category`;
CREATE TABLE `passageway_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT '通道类别名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '数据类型:0=模拟量,1=开关量',
  `data_address` varchar(255) NOT NULL DEFAULT '' COMMENT '数据地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    */
    public function details(){
//         $uid=\app\api\service\Token::getCurrentUid();
         $did=Request::instance()->get('device_id',0);//站点id
         $pass=Passageway::with(['passagewayCategory'])->where('device_id',$did)->select();
         $data=[];
         foreach ($pass as $k=>$v){
             $data[$k]['id']=$v['id'];
             $data[$k]['device_id']=$v['device_id'];
             $data[$k]['name']=$v['name'];
             $data[$k]['change_range_max']=$v['change_range_max'];
             $data[$k]['change_range_min']=$v['change_range_min'];
             $data[$k]['max_range']=$v['max_range'];
             $data[$k]['min_range']=$v['min_range'];
             $data[$k]['count_time']=$v['count_time'];
             $data[$k]['value']=$v['value'];
             $data[$k]['change_value']=$v['change_value'];
             $data[$k]['status']=$v['status'];
             $data[$k]['type']=$v['passageway_category']['type'];
         }
         return $this->success('请求成功','',$data);
    }
    //日志
    public function log(){
        $uid=\app\api\service\Token::getCurrentUid();
        $did=Request::instance()->get('device_id',0);//站点id
        $data=DeviceLog::join('project_admin','project_admin.id=device_log.project_id')
            ->where('device_log.project_id',$uid)->where('device_log.device_id',$did)
            ->order('time desc')->field('name,device_log.*')->select();
        return $this->success('请求成功','',$data);
    }
    //站点基本信息
    public function unit(){
        $uid=\app\api\service\Token::getCurrentUid();
        $did=Request::instance()->get('device_id',0);//站点id
        //单元标识符
//        $uit =Read_device::where('device_id',$did)
//            ->field('mark')->find();
        $data=Device::where('device_id',$did)
            ->field('electric_type,protocol,environment,status,device_name,voltage,mark,accendant_name,accendant_department,accendant_email,accendant_mobile')->find();
//        $data['unit']=$uit['mark'];
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

    public function channel(){
        $pid=$id = input('param.id/d',0);//通道id
        $info=Passageway::where('id',$pid)->find();
        return  $this->success('请求成功','',$info);
    }
    //历史曲线
    public function history(){
        $id = input('param.id/d',0);
        $time = input('param.time/s','');
        $info=Passageway::where('id',$id)->find();
        if ($time)
        {
            list($stime,$etime)=explode(' - ', $time);
            $stime = strtotime($stime);
            $etime = strtotime($etime);
        }else{
            $stime = time()-12*60*60;
            $etime = time();
        }
        if($info['type']==1){
          return $this->error('无权限');
        }else{
            $array = Device_data::where('dra_id','=',$id)
                ->order('time asc')
                ->where('time','>=',$stime)
                ->where('time','<=',$etime)
                ->select();
            $data = [];
            foreach ($array as $value)
            {
                $data['time'][] = date('Y-m-d H:i',$value['time']);
                $data['data'][] = $value['data'];
            }
            return $this->success('请求成功','',$data);
        }

    }
    //上传日志
    public function addlog(){
        $uid=\app\api\service\Token::getCurrentUid();
        $deviceID = input('post.device_id/s','');
        $img = input('post.img/a',[]);
        $content = input('post.content/s','');
        $res = DeviceLog::create([
            'device_id' => $deviceID,
            'project_id' => $uid,
            'log_info' => $content,
            'img' => $img,
            'time' => time()
        ]);
        if ($res)
        {
            return $this->success('上传成功');
        }
        else{
            return $this->error('上传失败');
        }
    }


}