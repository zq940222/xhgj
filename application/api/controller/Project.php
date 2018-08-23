<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 10:21
 */

namespace app\api\controller;


use app\api\model\Device;
use app\api\model\Passageway;
use app\api\model\Project_admin;
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
    /*DROP TABLE IF EXISTS `passageway`;
CREATE TABLE `passageway` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备id',
  `name` varchar(255) NOT NULL COMMENT '通道名称',
  `change_range_max` char(32) NOT NULL COMMENT '变化范围 最大值',
  `change_range_min` char(32) NOT NULL COMMENT '变化范围 最小值',
  `max_range` char(32) NOT NULL COMMENT '最大限值范围',
  `min_range` char(32) NOT NULL COMMENT '最小限值范围',
  `count_time` int(10) NOT NULL COMMENT '统计时间',
  `value` char(32) NOT NULL COMMENT '监测值',
  `change_value` char(32) NOT NULL COMMENT '数据变频值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='通道表';*/
    public function details(){
         $did=Request::instance()->get('device_id',0);//站点id
         $device=Device::where('device_id',$did)->field('device_name,status')->select();
         $info=Passageway::where('device_id',$did)->select();
         $data['device_name']=$device['device_name'];
//         $data['status']=$device['status'];
         return $this->success('请求成功','',$info);
    }
}