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
use think\Request;

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
        foreach ($device as $v){
            $data[]=Project_data::where('device_id',$v['device_id'])
                ->paginate(5,false,['page'=>1]);
//            $data=$v['device_id'];
        }
        return $this->success('请求成功','',$data);

    }
    //资料查询-分项监测 按区域查询
    public function cate(){
        $cid=Request::instance()->get('category_id',0);//区域id
        $uid=\app\api\service\Token::getCurrentUid();
        $info=Project_admin::where('id',$uid)->find();
        if($info['type']==1){
            $device=Device::where('project_id',$info['p_id'])->field('device_id')->select();

        }else{
            $device=Device::where('project_admin_id',$uid)->select();
        }
        foreach ($device as $v){
                $data[]=Project_data::where('device_id',$v['device_id'])
                    ->where('project_data.category_id',$cid)
                    ->paginate(5,false,['page'=>1]);
        }
        return $this->success('请求成功','',$data);
    }
    //分项监测所属区域
    public function  device(){
      $data=Category::select();
//      $data=Category::insert(['name'=>'测试区域2']);
      return $this->success('请求成功','',$data);
    }
    public function delpro(){
        $pid=Request::instance()->get('id',0);//资料id
        $info=Project_data::where('id',$pid)->delete();
        if($info==1){
            $data['status']=1;
            $data['res']='删除成功';
            return $this->success('请求成功','',$data);
        }else{
            $data['status']=0;
            $data['res']='删除失败';
            return $this->success('请求成功','',$data);
        }
    }


}