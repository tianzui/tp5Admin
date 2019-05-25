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
        $this->checkHeader();
        $roleId = \session('roleId');
        $menuList = $this->getMenuList($roleId);
        if (!empty($menuList)) {
            return json(['code' => 10000, 'msg' => '获取成功', 'data' => $menuList]);
        } else {
            return json(['code' => 10001, 'msg' => '数据异常']);
        }
    }

    public  function getRole(){
        $this->checkHeader();
        //当前页码
        $currentPage = input('post.currentPage');
        $countList = Db::table('role')->field('count(*)')->find();
        $count =  $countList['count(*)'];
        $pageSize = input('post.PageSize');
        $startNum = ($currentPage - 1)*$pageSize;
        $limit = "$startNum,$pageSize";
        $roleList = Db::table('role')->limit($limit)->select();
        if(!empty($roleList)){
            return json(['code'=>10000,'msg'=>'查询成功','data'=>$roleList,'count'=>$count]);
        }else{
            return json(['code'=>10001,'msg'=>'查询失败']);
        }
    }

    public function addRole(){
        $this->checkHeader();
        $name = input('post.name');
        $desc = input('post.desc');
        $data = ['title'=>$name,'role_desc'=>$desc];
        $result = Db::table('role')->insertGetId($data);
        if(!empty($result)){
            return json(['code'=>10000,'msg'=>'添加成功']);
        }else{
            return json(['code'=>10001,'msg'=>'添加失败']);
        }
    }

    public function delRole(){
        $roleId = input('post.id');
        $result = Db::table('role')->delete($roleId);
        if(!empty($result)){
            return json(['code'=>10000,'msg'=>'删除成功']);
        }else{
            return json(['code'=>10001,'msg'=>'删除失败']);
        }
    }

    public function checkHeader(){
        //获取请求头
        $token = Request::instance()->header('Authorization');
        $result = Db::table('user')->where('token', $token)->find();
        if (empty($result)) {
            return json(['code' => 10002, 'msg' => '请重新登录']);
        }
    }

    public function getMenuList($roleId)
    {
        $menu_list = Db::table('menu')->select();
        if ($roleId != '1') {
            $auth = new \Auth();
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
                    if($value['id']==$v['pid']){
                        $powerList[$key]['childern'][] = array("name"=>$v['title'],"id"=>$v['id'],"url"=>$v['name']);
                    }
                }
            }
        }
        return $powerList;
    }
}
