<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/27
 * Time: 10:12
 */

namespace app\admin\model;


use think\Cache;
use think\Db;
use think\Exception;
use think\Request;

class UserToken
{
    public function get($username,$password)
    {
        //检查该用户是否存在
        $user = Db::table('project_admin')
            ->where('account_number','=',$username)
            ->where('password','=',$password)
            ->where('type','=',0)
            ->find();
        if (!$user)
        {
            throw new Exception('账户或密码错误');
        }
        return $this->grantToken($user);
    }

    private function grantToken($user)
    {
        $cachedValue = $this->prepareCachedValue($user);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);

        $request = cache($key, $value);
        if (!$request) {
            throw new Exception([
                'msg' => '服务器缓存异常',
                'errorCode' => '10005'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($user){
        $cachedValue['uid'] = $user['id'];
        return $cachedValue;
    }

    public static function generateToken(){
        //32个字符
        $randChars = getRandChar(32);
        //用三组加密
        $timestamp = $_SERVER['REQUEST_TIME'];
        //盐
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);

    }

    public static function getCurrentTokenVar($key){
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new Exception();
        }
        else{
            if (!is_array($vars)) {
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key,$vars)){
                return $vars[$key];
            }
            else{
                throw new Exception('尝试获取得Token变量并不存在');
            }
        }
    }

    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }
}