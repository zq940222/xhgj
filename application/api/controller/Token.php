<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 11:07
 */

namespace app\api\controller;


use app\api\model\Project_admin;
use app\api\service\UserToken;
use think\Controller;

class Token extends Controller
{
    public function TokenUser($name = '', $password = '')
    {
        $ut = new UserToken();
        $data['token'] = $ut->get($name,$password);
        return $this->success('请求成功','',$data);
    }

//    public function getTokenByWx($unionid = '')
//    {
//        $wxAuth = new WxAuth();
//        $token = $wxAuth->grantToken($unionid);
//        return [
//            'token' => $token
//        ];
//    }
//
//    public function bindWx($unionid = '')
//    {
//        (new TokenGet())->goCheck();
//        $wxAuth = new WxAuth();
//        $wxAuth->bind($unionid);
//        return (new SuccessMessage());
//    }
}