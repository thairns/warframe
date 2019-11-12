<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2019/7/4
 * Time: 16:11
 */

namespace app\index\controller;

/**
 * 文件上传类
 */
class Upload{
    /**
     * 图片上传
     */
    public function uploadImage(){
        header("Content-Type:text/html; charset=utf-8");
        header("Access-Control-Allow-Origin: *");

        error_reporting(E_ALL & ~E_NOTICE);

        //保存地址
        $savePath = UPLOADS_PATH.date('Ymd').DS;
        $saveURL = UPLOADS_RELATIVE_PATH.date('Ymd').DS;

        $formats  = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp')
        );

        $name = 'editormd-image-file';

        if (isset($_FILES[$name]))
        {
            $imageUploader = new EditorMdUploader($savePath, $saveURL, $formats['image'], true);  // Ymdhis表示按日期生成文件名，利用date()函数

            $imageUploader->config(array(
                'maxSize' => 1024,        // 允许上传的最大文件大小，以KB为单位，默认值为1024
                'cover'   => true         // 是否覆盖同名文件，默认为true
            ));

            if ($imageUploader->upload($name))
            {
                $imageUploader->message('上传成功！', 1);
            }
            else
            {
                $imageUploader->message('上传失败！', 0);
            }
        }
    }
}