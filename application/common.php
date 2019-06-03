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
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
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
function cron($type,$flag = false){

    try {
        $data = Db::table("source")->where("id", $type)->find();
        if (!$data || $flag) {
            $data = curl(config("url.api_host").config("interface.".$type));

            if($data){
                Db::table("source")->where('id',$type)->update(["context"=>$data]);
            }else {
                Db::table("source")->insert([
                    "id" => $type,
                    "context" => $data
                ]);
            }
            return $data;
//            return addslashes($data);
        }
        return $data["context"];
//        return addslashes($data["context"]);
    }catch (Exception $e){
        return $e->getMessage();
    }
}
