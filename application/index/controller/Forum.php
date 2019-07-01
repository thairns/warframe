<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/6/26
 * Time: 14:11
 */

namespace app\index\controller;


use think\Controller;

class Forum extends Controller{
    public function index(){
        $this->assign("message","message");
        return $this->fetch();
    }

}