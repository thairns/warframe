<?php
namespace app\index\controller;

use think\Controller;
use think\Cookie;
use think\Lang;

/**
 * 这是首页对外接口页面
 */
class Index extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
        $lang = Lang::range("zh-cn");//设定当前语言
        Lang::load(ROOT_PATH.'lang'.DS.$lang.DS.$lang.EXT,$lang);//加载当前语言包
        Cookie::set('think_var',$lang);
    }

    public function index()
    {
        $this->assign("servlet",DS.config("servlet").DS);
        $list = [
            "cetusCycle","fissures",
            "invasions","sortie",
            "syndicateMissions","vallisCycle",
            "voidTrader","arbitration",
            "kuva","nightwave"
        ];
        $source = array();
        foreach($list as  $tmp){
            $source[$tmp] = cron($tmp);
        }
        $this->assign("source",json_encode($source));
        return $this->fetch();
    }

    public function updateInterval(){
        $key = input("get.key");
        return json_encode(cron($key,true));
    }
    public function hello(){

        $this->assign("result","Speed Trigger");
        return $this->fetch();
    }
}
