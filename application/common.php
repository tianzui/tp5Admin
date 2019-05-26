<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\auth\Auth;
use think\Db;
use think\Request;

/**
 * 获取角色的权限
 * @param $roleId
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getCheckMenu($roleId)
{
    $menu_list = \think\Db::table('auth_rule')->select(); //获取所有菜单
    $powerList = getTree($menu_list,0,0);
    //需求
    //需要一个默认展开的数组，设为一级ID的数组 firstArr
    //需要默认勾选的数组
    $firstArr = array();
    $firstList = Db::table('auth_rule')->where(['isMenu' => 1])->select();
    foreach ($firstList as $key => $value) {
        array_push($firstArr, $value['id']);
    }
    $checkArr = array();
    $where['id'] = $roleId;
    $roleList = \think\Db::table('auth_group')->where($where)->find();
    $rules = $roleList['rules'];
    $checkArr = explode(",", $rules);
    $list = array("power" => $powerList, "open" => $firstArr, "check" => $checkArr);
    return $list;
}


/**
 * 获取登录菜单
 * @param $roleId
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getMenuList($roleId)
{
    $menu_list = Db::table('auth_rule')->where('isMenu', 1)->select();
    if ($roleId != '1') {
        $auth = new \think\auth\Auth();
        $result = Db::table('auth_group')->where('id', $roleId)->find();
        $rules = $result['rules'];
        $where = array();
        $where['id'] = array('in', $rules);
        $where['isMenu'] = 1;
        $menu_list = Db::table('auth_rule')->where($where)->select();
    }
    $powerList = array();
    foreach ($menu_list as $key => $value) {
        foreach ($menu_list as $k => $v) {
            if ($value['pid'] == 0) {
                $powerList[$key]['id'] = $value['id'];
                $powerList[$key]['name'] = $value['title'];
                $powerList[$key]['pid'] = $value['pid'];
                $powerList[$key]['icon'] = $value['icon'];
                if ($value['id'] == $v['pid']) {
                    $powerList[$key]['child'][] = array("name" => $v['title'], "id" => $v['id'], "url" => $v['name']);
                }
            }
        }
    }
    return $powerList;
}

/**
 * 组装tag数组
 * @param $roleId
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function getTreeList($roleId){
    $menu_list = Db::table('auth_rule')->select();
    if ($roleId != '1') {
        $auth = new \think\auth\Auth();
        $result = Db::table('auth_group')->where('id', $roleId)->find();
        $rules = $result['rules'];
        $where = array();
        $where['id'] = array('in', $rules);
        $where['isMenu'] = 1;
        $menu_list = Db::table('auth_rule')->where($where)->select();
    }
    $powerList = getTree($menu_list,0,0);
}

/**
 * 递归数组
 * @param $arr
 * @param $pid
 * @param $level
 * @return array
 */
function getTree($arr,$pid,$level)
{
    $list =array();
    foreach ($arr as $k=>$v){
        if ($v['pid'] == $pid){
            $v['level']=$level;
            $v['child'] = getTree($arr,$v['id'],$level+1);
            $list[] = $v;
        }
    }
    return $list;
}

/**
 * 校验每次前端请求的请求头
 * @return string|\think\response\Json
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function checkHeader()
{
    //获取请求头
    $token = Request::instance()->header('Authorization');
    $result = Db::table('admin_user')->where('token', $token)->find();
    if (empty($result)) {
        return 'noLogin';
    }
    $result2 = Db::table('admin_user')->alias('u')->join('auth_group_access g', 'u.id = g.uid')->field('u.*,g.group_id')->where('u.token', $token)->find();
    $roleId = $result2['group_id'];
    //实例化auth
    $auth = Auth::instance();
    $controller = request()->controller();
    $action = request()->action();
    $name = $controller.'-'.$action;
    //无需验证的操作
    $uneed_check = array('Index-getmenu');
    if($roleId!=1){
        if(!in_array($name,$uneed_check)){
            if(!$auth->check($name,$roleId)){
                return 'noPower';
            }
        }
    }
    return $roleId;
}