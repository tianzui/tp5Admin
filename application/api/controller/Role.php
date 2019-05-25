<?php
namespace app\api\controller;
use app\api\controller\base;
use think\Db;
use think\Request;
use think\auth\Auth;

class Role extends Base
{

    /**
     * 获取角色
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRole()
    {
        checkHeader();
        //当前页码
        $currentPage = input('post.currentPage');
        $countList = Db::table('role')->field('count(*)')->find();
        $count = $countList['count(*)'];
        $pageSize = input('post.PageSize');
        $startNum = ($currentPage - 1) * $pageSize;
        $limit = "$startNum,$pageSize";
        $roleList = Db::table('role')->limit($limit)->select();
        if (!empty($roleList)) {
            return json(['code' => 10000, 'msg' => '查询成功', 'data' => $roleList, 'count' => $count]);
        } else {
            return json(['code' => 10001, 'msg' => '查询失败']);
        }
    }


    /**
     * 添加角色
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addRole()
    {
        checkHeader();
        $name = input('post.name');
        $desc = input('post.desc');
        $data = ['title' => $name, 'role_desc' => $desc];
        $result = Db::table('role')->insertGetId($data);
        if (!empty($result)) {
            return json(['code' => 10000, 'msg' => '添加成功']);
        } else {
            return json(['code' => 10001, 'msg' => '添加失败']);
        }
    }

    /**
     * 删除角色
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function delRole()
    {
        checkHeader();
        $roleId = input('post.id');
        $result = Db::table('role')->delete($roleId);
        if (!empty($result)) {
            return json(['code' => 10000, 'msg' => '删除成功']);
        } else {
            return json(['code' => 10001, 'msg' => '删除失败']);
        }
    }


    /**
     * 修改角色
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function editRole()
    {
        checkHeader();
        $name = input('post.name');
        $desc = input('post.desc');
        $id = input('post.id');
        $result = Db::table('role')->where('id', $id)->update(['title' => $name, 'role_desc' => $desc]);
        if (!empty($result)) {
            return json(['code' => 10000, 'msg' => '修改成功']);
        } else {
            return json(['code' => 10001, 'msg' => '修改失败']);
        }
    }

}
