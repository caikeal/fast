<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/29
 * Time: 19:39
 */

namespace App\SmsLib;

class SendTemplateSMS
{
    public function __construct(REST $rest)
    {
        $this->rest=$rest;
    }

    //主帐号,对应开官网发者主账号下的 ACCOUNT SID
    protected $accountSid = 'aaf98f894e8a784b014e90f847bd09b4';

    //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    protected $accountToken = '0f6e256d295246baae7f97a6f74f9e03';

    //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    protected $appId = 'aaf98f894f402f15014f437ff67e01ef';

    //请求地址
    //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    //生产环境（用户应用上线使用）：app.cloopen.com
    protected $serverIP = 'app.cloopen.com';

    //请求端口，生产环境和沙盒环境一致
    protected $serverPort = '8883';

    //REST版本号，在官网文档REST介绍中获得。
    protected $softVersion = '2013-12-26';

    /**
     * 发送模板短信
     * @string to 手机号码集合,用英文逗号分开
     * @array datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @string $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
     */
    public function sendTemplateSMS($to, $datas, $tempId)
    {
        // 初始化REST SDK
        $this->rest->setSever($this->serverIP,$this->serverPort,$this->softVersion);
        $this->rest->setAccount($this->accountSid, $this->accountToken);
        $this->rest->setAppId($this->appId);

        // 发送模板短信
//        echo "Sending TemplateSMS to $to <br/>";
        $result = $this->rest->sendTemplateSMS($to, $datas, $tempId);
        if ($result == NULL) {
//            return response()->json(['message'=>'网络错误'],403);
            return 0;
        }
        if ($result->statusCode != 0) {
//            return response()->json(['message'=>$result->statusMsg],403);
            return 0;
        } else {
            // 获取返回信息
            return 1;
            //$smsmessage = $result->TemplateSMS;
        }
    }
}