<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign("context","This is the value");
        return $this->fetch();
    }
    public function hello(){
        return "hello";
    }
}
