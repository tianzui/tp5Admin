<?php

namespace app\Home\controller;

use think\Controller;

class index extends Controller
{
    public function index(){
        // return view();
        return $this->fetch();
        //第一个是助手函数，一般推荐使用四个，其他的更不常用，就不介绍了
    }

    public function demo(){
        return $this->fetch();
    }
}
