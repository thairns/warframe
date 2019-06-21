<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/6/11
 * Time: 16:44
 */

namespace app\index\controller;


use think\Controller;
use think\Cookie;
use think\Exception;
use think\Lang;

class Source extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
        $lang = Lang::range("zh-cn");//设定当前语言
        Lang::load(ROOT_PATH . 'lang' . DS . $lang . DS . $lang . EXT, $lang);//加载当前语言包
        Cookie::set('think_var', $lang);
    }

    public function test(){
        $a = "Bode(Ceres)";
        $left = strpos($a,"(");
        print(substr($a,0,$left)."<br/>");
        print(substr($a,$left + 1,strlen($a) - $left -2)."<br/>");
        print(strstr($a,"("));
    }

    public function pec(){
        $data = json_decode(cron("vallisCycle"),JSON_UNESCAPED_UNICODE);
        foreach($data as $o){
//            print date($o["expiry"]);
        }
    }

    public function parentheses(){
        $a = "Bode(Ceres)";
        $p = "/[\(|\)]/";
        var_dump(array_filter(preg_split($p,$a)));
    }

    public function preg(){
        $a = "Neo C1 ";
        print(strstr($a,"Relic"));
       if(strstr($a,"Relic")){
           $arr = explode(" ",$a);
           var_dump($arr);
       }
    }
}