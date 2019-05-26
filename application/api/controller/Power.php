<?php
namespace app\api\controller;
use app\api\controller\base;
use think\Db;
use think\Request;
use think\auth\Auth;

class Power extends Base
{

    /**
     * 修改权限
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function editPower()
    {
        checkHeader();
        $id = input('post.id');
        $rules = input('post.rules');
        $data['rules'] = $rules;
        $result = Db::table('auth_group')->where('id', $id)->update($data);
        if (!empty($result)) {
            return json(['code' => 10000, 'msg' => '设置成功']);
        } else {
            return json(['code' => 10001, 'msg' => '设置失败']);
        }
    }


    /**
     * 获取权限
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPower()
    {
        $roleId = checkHeader();
        if($roleId=='noLogin'){
            return json(['code'=>10001,'msg'=>'您未登录']);
        }elseif ($roleId=='noPower'){
            return json(['code'=>10002,'msg'=>'您没有权限']);
        }
        $roleId = input('post.roleId');
        $list = getCheckMenu($roleId);
        if (!empty($list)) {
            return json(['code' => 10000, 'msg' => '查询成功', 'data' => $list]);
        } else {
            return json(['code' => 10001, 'msg' => '查询失败']);
        }
    }

    /**
     * 展示权限
     */
    public function getTagPower(){
        checkHeader();
        $result = Db::table('auth_rule')->select();
        if(!empty($result)){
            return json(['code'=>10000,'msg'=>'查询成功','data'=>$result]);
        }else{
            return json(['code'=>10001,'msg'=>'查询失败']);
        }
    }

    /**
     * 获取权限级别
     */
    public function getSelect(){
        $list = Db::table('auth_rule')->select();
        $arr = array();
        foreach ($list as $Key => $value){
            if($value['isMenu']==1&&$value['pid']!=0){
                array_push($arr,array("label"=>$value['title'],"value"=>$value['id']));
            }
        }
        if(!empty($arr)){
            return json(['code'=>10000,'msg'=>'查询成功','data'=>$arr]);
        }else{
            return json(['code'=>10001,'msg'=>'查询失败']);
        }
    }

    /**
     * 添加权限规则
     */
    public function addPower(){
        $name = input('post.name');
        $title = input('post.title');
        $value = input('post.value');
        $data = ['title' => $title, 'name' => $name,'pid'=>$value];
        $result = Db::table('auth_rule')->insert($data);
        if(!empty($result)){
            return json(['code'=>10000,'msg'=>'添加成功']);
        }else{
            return json(['code'=>10001,'msg'=>'添加失败']);
        }
    }
}
