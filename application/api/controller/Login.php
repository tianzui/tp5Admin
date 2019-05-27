<?php

namespace app\api\controller;
use think\Db;
use think\Controller;

class Login extends Controller
{
    public function login()
    {
        $username = input('post.username');
        $password = md5(input('post.password'));
        $result = Db::table('admin_user')->where("username = '$username' AND password = '$password'")->find();
        $role = Db::table('auth_group_access')->where('uid',$result['id'])->find();
        if (!empty($result)) {
            //账号密码正确时，返回token给前端
            session('roleId',$role['group_id']);
            return json(['code' => 10000, 'msg' => '登录成功', 'data' => $result]);
        } else {
            return json(['code' => 10001, 'msg' => '登录失败']);
        }
    }

    public function makeToken($username)
    {
        $str = md5(uniqid(md5($username), true)); //生成一个不会重复的字符串
        $str = sha1($str); //加密
        return $str;
    }

    public function getMenu(){
        $ruleList = Db::table('auth_rule')->select();
        $rules = get($ruleList,0,0);
        dump($rules);
    }
}
