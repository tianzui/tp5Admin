<?php

namespace app\api\controller;

use app\api\controller\base;
use think\Db;
use think\Request;
use think\auth\Auth;

class Index extends Base
{
    public function index()
    {
        return "这是tp后端的接口";
    }

    public function getMenu()
    {
        $roleId = checkHeader();
        $menuList = getMenuList($roleId);
        if (!empty($menuList)) {
            return json(['code' => 10000, 'msg' => '获取成功', 'data' => $menuList]);
        } else {
            return json(['code' => 10001, 'msg' => '数据异常']);
        }
    }

    public function logOut(){
        return json(['code'=>10000,'msg'=>'退出成功']);
    }
}
