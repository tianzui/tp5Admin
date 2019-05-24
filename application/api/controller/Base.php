<?php
namespace app\api\controller;
use think\Controller;
use think\auth\Auth;

class Base extends Controller
{
	public function _initialize()
	{
		$controller = request()->controller();
		$action = request()->action();
		$module = request()->module();
		//无需验证的操作
		$uneed_check = array('login', 'logout');
		$uneed_check_cont = array('Index');
		$roleId = \session('roleId');
		if (in_array($controller, $uneed_check_cont) || $roleId == '1') {
				//后台首页控制器无需验证,超级管理员无需验证
				return true;
		} elseif (strpos($action, 'ajax') || in_array($action, $uneed_check)) {
				//所有ajax请求不需要验证权限
				return true;
		} else {
				//实例化auth
				$auth = new \Auth();
				if (!$auth->check($module . '/' . $controller . '/' . $action, $roleId)) {
						$this->error('您没有权限');
				}
		}
	}
}
