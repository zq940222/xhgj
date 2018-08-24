<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 10:15
 */

namespace app\api\controller;


use app\api\model\Category;
use app\api\model\Device;
use app\api\model\Project_admin;
use app\api\model\Project_data;
use think\Db;

class Datum extends Base
{
    //资料查询-分项监测

    public function itemized(){
        $uid=\app\api\service\Token::getCurrentUid();
        $info=Project_admin::where('id',$uid)->find();
        if($info['type']==1){
            $device=Device::where('project_id',$info['p_id'])->field('device_id')->select();

        }else{
            $device=Device::where('project_admin_id',$uid)->select();
        }
        $data=[];
        foreach ($device as $k=>$v){
            $data=Project_data::join('category','category.id=project_data.category_id')->where('device_id',$v['device_id'])->select();
        }
//        $device=Device::where('project_id',$pid['p_id'])->field('device_id')->select();
//        $data=[];
//        foreach($device as $k=>$v){
//            $data=Project_data::join('category','category.id=project_data.category_id')->where('device_id',$v['device_id'])->select();
//
//        }
//        $data=Db::table('project_data')->select();
//        $data=Project_data::where('id','>',0)->update(['device_id'=>201806260001]);
//        $data=Project_data::insert(['category_id'=>1,'title'=>'测试标题1','cover'=>'','content'=>'测试内容']);
        return $this->success('请求成功','',$data);

    }
    /*DROP TABLE IF EXISTS `device`;
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
     *
     *
     *
     * DROP TABLE IF EXISTS `project_admin`;
CREATE TABLE `project_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `p_id` int(10) DEFAULT NULL COMMENT '项目id',
  `account_number` char(32) NOT NULL COMMENT '账号',
  `password` char(64) NOT NULL COMMENT '密码',
  `name` char(64) NOT NULL COMMENT '姓名',
  `department` char(64) NOT NULL COMMENT '部门',
  `email` char(32) DEFAULT '' COMMENT '邮箱',
  `phone_number` char(11) NOT NULL COMMENT '手机号',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1管理员 2巡逻员 0网管',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='项目管理员表';*/
    //资料查询-分项监测-所属区域
    /*
     * DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '区域名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/
    public function  device(){
      $data=Category::select();
      //$data=Category::insert(['name'=>'测试区域2']);
      return $this->success('请求成功','',$data);
    }


}