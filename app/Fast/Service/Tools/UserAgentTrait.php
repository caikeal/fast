<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/25
 * Time: 20:32
 */

namespace App\Fast\Service\Tools;


trait UserAgentTrait
{
    public function beautyFileName($fileName, $ua){
        //解决不同浏览器下载excel时标题解析乱码问题
        if (preg_match("/msie|edge|safari|firefox/", $ua)) {
            $base_title=urlencode($fileName);
        }else{
            $base_title=$fileName;
        }

        return $base_title;
    }
}