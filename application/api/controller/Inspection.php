<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 14:31
 */

namespace app\api\controller;


use app\api\model\Project_inspect_log;
use think\Request;

class Inspection extends Base
{
   //巡检记录
    /*DROP TABLE IF EXISTS `project_inspect_log`;
CREATE TABLE `project_inspect_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_admin_id` int(10) NOT NULL COMMENT '巡检员id',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `content` text NOT NULL COMMENT '巡检记录内容',
  `img` varchar(255) NOT NULL COMMENT '巡检记录图片内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;*/
    public function polling(){
        $uid=\app\api\service\Token::getCurrentUid();
        $data=Project_inspect_log::insert(['project_admin_id'=>2,'create_time'=>'1535040000','content'=>'测试巡检内容2','img'=>'112']);

//        $data=Project_inspect_log::where('project_admin_id',$uid)->select();
        return $this->success('请求成功','',$data);
    }
    //搜索巡检记录
    public function checks(){
        $uid=\app\api\service\Token::getCurrentUid();
        $create_time=Request::instance()->post('create_time',0);//站点id
        $time = strtotime($create_time);
        $start = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time));
        $end = mktime(23, 59, 59, date("m", $time), date("d", $time), date("Y", $time));
        $where = [
            'create_time' => ['between', [$start, $end]],
            'project_admin_id' => $uid,
        ];
        $data=Project_inspect_log::where($where)->select();
        return $this->success('请求成功','',$data);

    }

}