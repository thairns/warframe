<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//参数1：访问的URL，参数2：post数据(不填则为GET)
use think\Db;

function curl($url, $post=''){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
//    curl_setopt($curl, CURLOPT_REFERER, "https://www.thairns.com");
    if($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    return $data;
}
//定时任务
/**
 * @param $type         根据key来获取数据
 * @param bool $flag    是否直接到源获取数据
 * @return mixed|string
 */
function cron($type,$flag = false){

    try {
        $data = Db::table("source")->where("id", $type)->find();
        if (!$data || $flag) {
            $result = curl(config("url.api_host").config("interface.".$type));

            try{
                $result = json_decode($result);
                getLang($result);
                $result = json_encode($result,JSON_UNESCAPED_UNICODE);
            }catch (Exception $e){
                print($e);
            }

            try{
                if(strlen($result) > 0) {
                    if ($data) {
                        Db::table("source")->where('id', $type)->update(["context" => $result]);
                    } else {
                        Db::table("source")->insert([
                            "id" => $type,
                            "context" => $result
                        ]);
                    }
                }
            }catch (Exception $e){
                print("错误开始：".$e."\t End<br/>");
            }

            return $result;
        }
        return $data["context"];
    }catch (Exception $e){
        return $e->getMessage();
    }
}

/**
 * 将传入对象递归取得value
 * 然后通过lang获取对应数据
 */
function getLang(&$obj){
    foreach($obj as $key=>&$tmp){
        if(gettype($tmp) == "array" || gettype($tmp) == "object"){
            getLang($tmp);
        }else{
            if(gettype($tmp) === 'string'){
                $tmp = trim($tmp);
                if(strlen($tmp) == 0){
                    continue;
                }
                $tmp = trim($tmp);
                $tmp = preg($tmp);
                $tmp = parentheses($tmp);
                $tmp = relic($tmp);
                $tmp = lang($tmp);
            }
        }
    }
}

/**
 * 处理数据为
 * 10X Endo
 * 200 Oxium
 * 2,500 Credit Cache
 */
function preg($value){
    $pattern = "/^\d+X|^\d+\,?\d+\s/";
    $res = preg_filter($pattern,"",$value);
    if(!$res || trim($res) == ""){
        return $value;
    }
    $right = lang(trim($res));
    $left = "";
    preg_match($pattern,$value,$left);

    return $left[0]." ".$right;
}

/**
 * 处理数据为
 * Bode(Ceres)
 */
function parentheses($value){
    $pattern = "/[\(|\)]/";
    $arr = array_filter(preg_split($pattern,$value));
    $res = $value;
    if(count($arr) == 2){
        $res = $arr[0]."(".lang($arr[1]).")";
    }else{
        return $value;
    }
    return $res;
}

/**
 * 处理数据为
 * Neo C1 Relic
 */
function relic($value){
    if(strstr($value,"Relic")){
        $arr = explode(" ",$value);
        if(count($arr) == 3){
            return lang(trim($arr[0]))." ".$arr[1]." ".lang(trim($arr[2]));
        }
    }
    return $value;
}


