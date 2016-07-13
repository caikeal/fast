<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/6/26
 * Time: 17:31
 */

namespace App\Fast\Service\ReuploadApplication;

use App\ReuploadApplication;

class Application
{
    /**
     * 保存申请记录。
     * 
     * @param $managerId
     * @param $leaderId
     * @param $uploadId
     *
     * @return ReuploadApplication
     */
    public function saveApplication($managerId, $leaderId, $uploadId)
    {
        $reupload = new ReuploadApplication();
        $reupload->applier = $managerId;
        $reupload->receiver = $leaderId;
        $reupload->upload_id = $uploadId;
        $reupload->status = 3;
        $reupload->expiration = 7;
        $reupload->save();

        return $reupload;
    }

    /**
     * 消息同意。
     *
     * @param $reuploadId
     *
     * @return mixed
     */
    public function agree($reuploadId)
    {
        $reupload = ReuploadApplication::find($reuploadId);
        $reupload->status = 1;
        $reupload->update();

        return $reupload;
    }

    /**
     * 消息拒绝。
     *
     * @param $reuploadId
     *
     * @return mixed
     */
    public function refuse($reuploadId)
    {
        $reupload = ReuploadApplication::find($reuploadId);
        $reupload->status = 2;
        $reupload->update();

        return $reupload;
    }

    /**
     * 消息过期。
     *
     * @param $reuploadId
     *
     * @return mixed
     */
    public function expirate($reuploadId)
    {
        $reupload = ReuploadApplication::find($reuploadId);
        $reupload->status = 4;
        $reupload->update();

        return $reupload;
    }
}