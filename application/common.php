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
function getCheckMenu($roleId){
    $menu_list = \think\Db::table('menu')->select(); //获取所有菜单
    $powerList = array();
    //组装Element tree的数组
    foreach ($menu_list as $key => $value) {
        foreach ($menu_list as $k => $v) {
            if ($value['pid'] == 0) {
                $powerList[$key]['id'] = $value['id'];
                $powerList[$key]['label'] = $value['title'];
                if ($value['id'] == $v['pid']) {
                    $powerList[$key]['children'][] = array("label" => $v['title'], "id" => $v['id'],"disabled"=>false);
                }
            }
        }
    }
    //需求
    //需要一个默认展开的数组，设为一级ID的数组 firstArr
    //需要默认勾选的数组
    $firstArr = array();
    $firstList = Db::table('menu')->where('pid',0)->select();
    foreach ($firstList as $key => $value){
        array_push($firstArr,$value['id']);
    }
    $checkArr = array();
    $where['id'] = $roleId;
    $roleList = \think\Db::table('role')->where($where)->find();
    $rules = $roleList['rules'];
    $checkArr = explode(",",$rules);
    $list = array("power"=>$powerList,"open"=>$firstArr,"check"=>$checkArr);
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
    $result = Db::table('user')->where('token', $token)->find();
    if (empty($result)) {
        return json(['code' => 10002, 'msg' => '请重新登录']);
    }
    return $token;
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
    $menu_list = Db::table('menu')->select();
    if ($roleId != '1') {
        $auth = new \think\auth\Auth();
        $authList = $auth->getGroups($roleId);
        $rules = $authList[0]['rules'];
        $where = array();
        $where['id'] = array('in', $rules);
        $menu_list = Db::table('menu')->where($where)->select();
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
                    $powerList[$key]['children'][] = array("name" => $v['title'], "id" => $v['id'], "url" => $v['name']);
                }
            }
        }
    }
    return $powerList;
}