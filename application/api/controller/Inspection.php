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
    public function polling(){
        $uid=\app\api\service\Token::getCurrentUid();
        $data=Project_inspect_log::where('project_admin_id',$uid)->select();
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