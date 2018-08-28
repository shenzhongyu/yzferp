<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/3
 * Time: 15:37
 */

namespace app\base\controller;


use think\Controller;
use com\ucpaas\UcpaasSDK ;
use think\Log;

class Ucpaas extends Controller
{
    private $options = [
        //别人的账号
     //   'accountsid'=>'b6f7bc40a8b4638972f2d8154303c6be',
      //  'token'=>'f357bb9bdd5d9ee45654a48a489581d1',
     //   'appId'=>'7b565fe27d234b5f952e9069a5b7fc26',

        'accountsid'=>'7d58adf71bd4a34e339d51228c23c3fc',
        'token'=>'4f978c71f8ade99c1e0c67abb770aa70',
        'yzm_appId'=>'0a85214ea37b431aadb6235f0820b5e9'
    ];
    public function sendSms($phone,$templateId,$param){

        //初始化 $options必填
        $ucpass = new UcpaasSDK($this->options);
        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $appId = $this->options['yzm_appId'];
        $re_code = json_decode($ucpass->templateSMS($appId,$phone,$templateId,$param));
        if (is_object($re_code)){
            if (isset($re_code->resp->respCode)){
                if(  $re_code->resp->respCode=='000000'){
                    Log::notice("给{$phone}放送了一条短信");
                    return isset($re_code->resp->failure) ? '提交发送成功,但发送出错了!' : true;
                }
                return '短信服务器配置参数错误!'  ;
            }else{
                return '短信服务器通讯异常!'  ;
            }
        }
        return '短信服务器通讯信息格式错误!'  ;



    }


    //初始化必填



//初始化 $options必填
//$ucpass = new Ucpaas(options);

//开发者账号信息查询默认为json或xml

//echo $ucpass->getDevinfo('json');

//申请client账号
//$appId = "xxxx";
//$clientType = "0";
//$charge = "0";
//$friendlyName = '';
//$mobile = "18612345678";

//echo $ucpass->applyClient($appId, $clientType, $charge, $friendlyName, $mobile);

//删除client账号
//$appId = "xxxx";
//$clientNumber='xxxxx';

//echo $ucpass->releaseClient($clientNumber,$appId);

//删除client账号
//$appId = "xxxx";
//$start = "0";
//$limit = "100";
//
//echo $ucpass->getClientList($appId,$start,$limit);

//以Client账号方式查询Client信息
//$appId = "xxxx";
//$clientNumber='xxxx';
//
//echo $ucpass->getClientInfo($appId,$clientNumber);

//以手机号码方式查询Client信息
//$appId = "xxxx";
//$mobile = "18612345678";

//echo $ucpass->getClientInfoByMobile($appId,$mobile);

//应用话单下载,通过HTTPS POST方式提交请求，云之讯融合通讯开放平台收到请求后，返回应用话单下载地址及文件下载检验码。
//day 代表前一天的数据（从00:00 – 23:59）；week代表前一周的数据(周一 到周日)；month表示上一个月的数据（上个月表示当前月减1，如果今天是4月10号，则查询结果是3月份的数据）
//$appId = "xxxx";
//$date = "day";

//echo $ucpass->getBillList($appId,$date);

//Client充值,通过HTTPS POST方式提交充值请求，云之讯融合通讯开放平台收到请求后，返回Client充值结果。
//$appId = "xxxx";
//$clientNumber='xxxx';
//$clientType = "0";
//$charge = "0";

//echo $ucpass->chargeClient($appId,$clientNumber,$clientType,$charge);

//双向回拨,云之讯融合通讯开放平台收到请求后，将向两个电话终端发起呼叫，双方接通电话后进行通话。
//$appId = "xxxx";
//$fromClient = "xxxx";
//$to = "18612345678";
//$fromSerNum = '';
//$toSerNum = '';

//echo $ucpass->callBack($appId,$fromClient,$to);

//语音验证码,云之讯融合通讯开放平台收到请求后，向对象电话终端发起呼叫，接通电话后将播放指定语音验证码序列
//$appId = "xxxx";
//$verifyCode = "1256";
//$to = "18612345678";

//echo $ucpass->voiceCode($appId,$verifyCode,$to);



}