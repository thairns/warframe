<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $list = [
            "cetusCycle","fissures",
            "invasions","sortie",
            "syndicateMissions","vallisCycle",
            "voidTrader"
        ];
        foreach($list as  $tmp){
            $this->assign($tmp,cron($tmp));
        }
        return $this->fetch();
    }
    public function hello(){
       $this->assign("result","stop interval success<br>");
        return $this->fetch();
    }
}
