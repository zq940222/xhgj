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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='通道表';

    DROP TABLE IF EXISTS `device`;
CREATE TABLE `device` (
  `device_id` char(32) NOT NULL COMMENT '设备id',
  `project_id` int(10) NOT NULL COMMENT '项目id',
  `ip` char(64) NOT NULL,
  `last_time` int(10) NOT NULL COMMENT '最后通讯时间',
  `longitude` decimal(10,0) NOT NULL COMMENT '经度',
  `latitude` decimal(10,0) NOT NULL COMMENT '纬度',
  `maintain_last_worker` varchar(255) NOT NULL COMMENT '最后维护人',
  `install_last_time` int(10) NOT NULL COMMENT '最后安装时间',
  `device_name` varchar(255) NOT NULL COMMENT '设备名称',
  `status` tinyint(1) NOT NULL COMMENT '设备状态  0正常  1 数据异常 2 通讯失败',
  `electric_type` varchar(255) NOT NULL COMMENT '供电方式',
  `protocol` tinyint(1) NOT NULL COMMENT '通讯协议',
  `environment` varchar(255) NOT NULL COMMENT '设备检测环境',
  PRIMARY KEY (`device_id`),
  KEY `ip` (`ip`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设备表(站点表)';


    DROP TABLE IF EXISTS `project_admin`;
CREATE TABLE `project_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `p_id` int(10) NOT NULL COMMENT '项目id',
  `account_number` char(32) NOT NULL COMMENT '账号',
  `password` char(64) NOT NULL COMMENT '密码',
  `name` char(64) NOT NULL COMMENT '姓名',
  `department` char(64) NOT NULL COMMENT '部门',
  `email` char(32) DEFAULT '' COMMENT '邮箱',
  `phone_number` char(11) NOT NULL COMMENT '手机号',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1管理员 2巡逻员',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='项目管理员表';



    DROP TABLE IF EXISTS `device_log`;
CREATE TABLE `device_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备(站点)id',
  `project_id` int(10) NOT NULL COMMENT '设备管理员id',
  `time` int(10) NOT NULL COMMENT '添加时间',
  `log_info` text NOT NULL COMMENT '日志信息',
  `img` text NOT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='设备(站点)日志表';
    */
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
        $data['unit'] =Read_device::where('device_id',$did)
            ->field('mark')->find();
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