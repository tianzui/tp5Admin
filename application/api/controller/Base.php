<?php

namespace app\api\controller;

use think\Controller;
use think\auth\Auth;
use think\Db;

class Base extends Controller
{
    public function _initialize()
    {
        $roleId = checkHeader();
        if($roleId=='noLogin'){
            return json(['code'=>10001,'msg'=>'您未登录']);
        }elseif ($roleId=='noPower'){
            return json(['code'=>10002,'msg'=>'您没有权限']);
        }
    }
}
