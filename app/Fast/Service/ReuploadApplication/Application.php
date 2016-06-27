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
}