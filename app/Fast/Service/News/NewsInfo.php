<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/26
 * Time: 23:05
 */

namespace App\Fast\Service\News;


use App\News;

class NewsInfo
{
    /**
     * 保存消息。
     *
     * type=1，重新上传薪资和社保
     * @param $sender
     * @param $receiver
     * @param $type
     * @param $relate_id
     * @param $content
     *
     * @return News
     */
    public function storeNews($sender,$receiver,$type,$relate_id,$content)
    {

        $news = new News();
        $news->sender = $sender;
        $news->receiver = $receiver;
        $news->type = $type;
        $news->is_read = 0;
        $news->status = 3;
        $news->relate_id = $relate_id;
        $news->content = $content;
        $news->save();

        return $news;
    }
}