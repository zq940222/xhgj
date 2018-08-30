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
    public function management($page=1,$size=10){
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
                    ->paginate($size,false,['page'=>$page])->toArray();
            }else{
                $res=Project_admin::with(['device'])
                    ->where('p_id',$info['p_id'])
                    ->where('type',2)
                    ->paginate($size,false,['page'=>$page])->toArray();
            }
            $data['total']=$res['total'];
            $data['per_page']=$res['per_page'];
            $data['current_page']=$res['current_page'];
            $data['last_page']=$res['last_page'];
            foreach($res['data'] as $key => $value) {
                $data['data'][$key]['id'] = $value['id'];
                $data['data'][$key]['account_number'] = $value['account_number'];
                $data['data'][$key]['name'] = $value['name'];
                $data['data'][$key]['device_name']=[];
                foreach ($value['device'] as $v)
                {
                    $data['data'][$key]['device_name'][] = $v['device_name'];
                }
                $data['data'][$key]['time'] = $value['create_time'];
            }
        }else{
            $list=Project_admin::where('id',$uid)->paginate($size,false,['page'=>$page])->toArray();
            $res=Device::join('project_admin_device pro','pro.device_id=device.device_id')
                ->where('project_admin_id',$uid)
                ->column('device_name');
            $data['total']=$list['total'];
            $data['per_page']=$list['per_page'];
            $data['current_page']=$list['current_page'];
            $data['last_page']=$list['last_page'];
            $data['data'][0]['id']=$list['data'][0]['id'];
            $data['data'][0]['account_number']=$list['data'][0]['account_number'];
            $data['data'][0]['name']=$list['data'][0]['name'];
            $data['data'][0]['device_name']=$res;
            $data['data'][0]['time']=$list['data'][0]['create_time'];
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
                $list=Project_admin_device::where('device_id',$v['device_id'])->where('project_admin_id',$id)->delete();
            }
            if($list){
                return $this->success('删除成功','');
            }else{
                return $this->error('删除失败');
            }

        }else{
            return $this->error('无权限');
        }
    }

    //新增巡查员
    public function addment(){
        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $res=Project_admin::where('id',$uid)->find();
        $account_number= input('post.account_number/s','');
        $name = input('post.name/s','');
        $password = input('post.password/s','');
        $device_id=input('post.device_id/s','');
        $arr=Project_admin::where('account_number',$account_number)->find();
        $arr2=Project_admin::where('name',$name)->find();
        if($res['type']==1){
         if($arr || $arr2){
             if($arr){
                 return $this->error('账号已存在');
             }elseif($arr2){
                 return $this->error('用户名已存在');
             }
         }else{
             $aid=Project_admin::insertGetId(['p_id'=>$res['p_id'],'account_number'=>$account_number,'password'=>$password,'name'=>$name,'create_time'=>time(),'type'=>2]);
            $dev=explode(',',$device_id);
            for($i=0;$i<count($dev);$i++){
              $pdev=Project_admin_device::insert(['device_id'=>$dev[$i],'project_admin_id'=>$aid]);
            }
            if($aid&&$pdev){
                return $this->success('添加成功','');
            }else{
                return $this->error('添加失败');
            }

         }

        }else{
            return $this->error('无权限');
        }
    }
    //账号管理-项目站点列表
    public function devices(){
        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $res=Project_admin::where('id',$uid)->find();
        $data=Device::where('project_id',$res['p_id'])
            ->field('device_name,device_id')
            ->select();
        return $this->success('请求成功','',$data);
    }

    //重新分配巡查员信息
    public function ments(){
        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $res=Project_admin::where('id',$uid)->find();
        $xid=Request::instance()->get('id',0);//巡查员id
        if($res['type']==1){
          $data=Project_admin::where('id',$xid)
              ->field('id,account_number,name')
              ->find();
          return $this->success('请求成功','',$data);
        }else{
            return $this->error('无权限');
        }
    }
    //确认分配
    public function editment(){
        $xid= input('post.id/d',0);
        $device_id=input('post.device_id/s','');
        $uid=\app\api\service\Token::getCurrentUid();//登录id
        $info=Project_admin::where('id',$uid)->find();
        $list=Project_admin_device::alias('p')
            ->join('device d','d.device_id=p.device_id','left')
            ->where('d.project_id',$info['p_id'])
            ->where('p.project_admin_id',$xid)
            ->field('p.*')
            ->select();
        for($i=0;$i<count($list);$i++){
            $pro=Project_admin_device::where('id',$list[$i]['id'])
                ->delete();
        }
        if($pro){
            $dev=explode(',',$device_id);
            for($i=0;$i<count($dev);$i++){
                $data=Project_admin_device::insert(['device_id'=>$dev[$i],'project_admin_id'=>$xid]);
            }
            if($data){
                return $this->success('分配成功','');
            }else{
                return $this->error('分配失败');
            }
        }else{
            return $this->error('稍后再试');
        }
    }

}