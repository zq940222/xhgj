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
        $uid=\app\api\service\Token::getCurrentUid();//登录id
//        $uid=Request::instance()->get('aid',0);
        $info=Project_admin::where('id',$uid)->find();
        $name=Request::instance()->get('name',0);//账号或姓名
        if($info['type']==1){
            if($name){
                $res=Project_admin::with(['device'])
                    ->where('p_id',$info['p_id'])
                    ->where('type',2)
                    ->where('name','like','%'.$name.'%')
                    ->whereOr('account_number','like','%'.$name.'%')
                    ->select();
            }else{
                $res = Project_admin::with(['device'])
                    ->where('p_id',$info['p_id'])->where('type',2)->select();
            }

            $data = [];
            foreach($res as $key => $value) {
                $data[$key]['id'] = $value['id'];
                $data[$key]['account_number'] = $value['account_number'];
                $data[$key]['name'] = $value['name'];
                $data[$key]['device_name']=[];
                foreach ($value->device as $v)
                {
                    $data[$key]['device_name'][] = $v->device_name;
                }
                $data[$key]['time'] = $value['create_time'];
            }
        }else{
            $res=Device::join('project_admin_device pro','pro.device_id=device.device_id')
                ->where('project_admin_id',$uid)->column('device_name');
            $data['id']=$info['id'];
            $data['name']=$info['name'];
            $data['account_number']=$info['account_number'];
            $data['device_name']=$res;
            $data['time']=$info['create_time'];
        }
//        $data=Project_admin_device::select();
        return $this->success('请求成功','',$data);
    }
    //删除巡查员
    public function delment(){
        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $res=Project_admin::where('id',$uid)->find();
        $id=Request::instance()->get('id',0);
        if($res['type']==1){
            $info=Device::where('project_id',$res['p_id'])->field('device_id')->select();
            foreach ($info as $v){
                Project_admin_device::where('device_id',$v['device_id'])->where('project_admin_id',$id)->delete();
            }
                return $this->success('删除成功','');
        }else{
            return $this->error('无权限');
        }
    }
    //新增巡查员
    public function addment(){
        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $res=Project_admin::where('id',$uid)->find();
        if($res['type']==1){

        }else{
            return $this->error('无权限');
        }
    }
    //重新分配巡查员
    public function editment(){
        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $res=Project_admin::where('id',$uid)->find();
        if($res['type']==1){

        }else{
            return $this->error('无权限');
        }
    }

}