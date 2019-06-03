<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign("servlet",DS.config("servlet").DS);
        $list = [
            "cetusCycle","fissures",
            "invasions","sortie",
            "syndicateMissions","vallisCycle",
            "voidTrader"
        ];
        $source = array();
        foreach($list as  $tmp){
            $source[$tmp] = cron($tmp);
        }
        $this->assign("source",json_encode($source));
        return $this->fetch();
    }

    public function updateInterval($key){
        return cron($key,true);
    }
    public function hello(){
       $this->assign("result","stop interval success<br>");
        return $this->fetch();
    }
}
