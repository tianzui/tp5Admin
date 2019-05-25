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
        $token = checkHeader();
        $result = Db::table('user')->alias('u')->join('group g', 'u.id = g.uid')->field('u.*,g.group_id')->where('u.token', $token)->find();
        $roleId = $result['group_id'];
        $menuList = getMenuList($roleId);
        if (!empty($menuList)) {
            return json(['code' => 10000, 'msg' => '获取成功', 'data' => $menuList]);
        } else {
            return json(['code' => 10001, 'msg' => '数据异常']);
        }
    }
}
