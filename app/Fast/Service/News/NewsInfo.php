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
     * type=1，重新上传薪资和社保 $relate_id=>重新上传id
     * type=2, 同意、拒绝消息 $relate_id=>上条消息id
     *
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

    /**
     * 消息同意。
     *
     * @param $newId
     *
     * @return mixed
     */
    public function agree($newId)
    {
        $news = News::find($newId);
        $news->status = 1;
        $news->update();

        return $news;
    }

    /**
     * 消息拒绝。
     *
     * @param $newId
     *
     * @return mixed
     */
    public function refuse($newId)
    {
        $news = News::find($newId);
        $news->status = 2;
        $news->update();

        return $news;
    }

    /**
     * 消息过期。
     *
     * @param $newId
     *
     * @return mixed
     */
    public function expirate($newId)
    {
        $news = News::find($newId);
        $news->status = 4;
        $news->update();

        return $news;
    }

    /**
     * 添加已读状态。
     *
     * @param $newId
     *
     * @return mixed
     */
    public function isRead($newId)
    {
        $news = News::find($newId);
        $news->is_read = 1;
        $news->update();

        return $news;
    }

    /**
     * 批量添加已读。
     *
     * @param $reader
     *
     * @return mixed
     */
    public function isReadMore($reader)
    {
        $news = News::where('receiver', $reader)->where('is_read', 0)->update(['is_read'=>1]);

        return $news;
    }
}