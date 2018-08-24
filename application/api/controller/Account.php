<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 14:54
 */

namespace app\api\controller;


use app\api\model\Device;
use app\api\model\Project_admin;
use app\api\model\Project_admin_device;
use think\Request;

class Account extends Base
{
   //账号管理
    public function management(){
//        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $uid=Request::instance()->get('aid',0);//站点id
        $info=Project_admin::where('id',$uid)->find();
        if($info['type']==1){
            $data = Project_admin::with(['device'])
                ->where('p_id',$info['p_id'])->where('type',2)->select();
            $device = [];
            foreach($data as $key => $value) {
                $device[$key]['account_number'] = $value['account_number'];
                $device[$key]['name'] = $value['name'];
                $device[$key]['device_name']=[];
                foreach ($value->device as $v)
                {
                    $device[$key]['device_name'][] = $v->device_name;
                }
                $device[$key]['time'] = $value['create_time'];
            }
        }else{
            $device=Device::join('project_admin_device pro','pro.device_id=device.device_id')
                ->where('project_admin_id',$uid)->column('device_name');
            $info['device_name']=$device;
//            $data=$info;
        }
//        $data=Project_admin_device::select();
        return $this->success('请求成功','',$device);
    }
}