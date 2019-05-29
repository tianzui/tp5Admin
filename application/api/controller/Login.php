<?php

namespace app\api\controller;

use Firebase\JWT\JWT;
use think\Db;
use think\Controller;

class Login extends Controller
{
    public function login()
    {
        $username = input('post.username');
        $password = md5(input('post.password'));
        $result = Db::table('admin_user')->where("username = '$username' AND password = '$password'")->find();
        if (!empty($result)) {
            $newObj = array(
                "id" => $result['id'],
                "username" => $result['username'],
                "time" => time() + 3600
            );
            $key = "vuethinkkphp";
            $token = JWT::encode($newObj, $key);
            //账号密码正确时，返回token给前端
            return json(['code' => 10000, 'msg' => '登录成功', 'data' => $token]);
        } else {
            return json(['code' => 10001, 'msg' => '登录失败']);
        }
    }

    /**
     * 解密测试，上线前需删除
     */
    public function getKey()
    {
        $key = "vuethinkkphp";
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJhZG1pbiIsInRpbWUiOjE1NTkxMjEyNDF9.DeSm7MYnYzu3zsmPk0SSMM3pB4KLRBLKjwjMU_xnGFE';
        $list = JWT::decode($token, $key, array('HS256'));
        dump($list);
    }
}
