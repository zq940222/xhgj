<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 11:07
 */

namespace app\api\controller;


use app\api\token\UserToken;

class Token
{
    public function TokenUser($name = '', $password = '')
    {
        $ut = new UserToken();
        $token = $ut->get($name,$password);
        return [
            'token' => $token
        ];
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