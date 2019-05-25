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
        $result = Db::table('role')->where('id', $id)->update($data);
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
        checkHeader();
        $roleId = input('post.roleId');
        $list = getCheckMenu($roleId);
        if (!empty($list)) {
            return json(['code' => 10000, 'msg' => '查询成功', 'data' => $list]);
        } else {
            return json(['code' => 10001, 'msg' => '查询失败']);
        }
    }

}
