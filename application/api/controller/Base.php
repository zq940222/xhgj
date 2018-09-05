<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 17:09
 */

namespace app\api\controller;


use think\Controller;
use think\Db;

class Base extends Controller
{
    public function _initialize()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: *');
        parent::_initialize(); // TODO: Change the autogenerated stub
        $uid =\app\api\service\Token::getCurrentUid();//取id
        if($uid){
            return true;
        }else{
            return $this->error('登录失败');
        }
    }


}