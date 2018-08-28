<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * Email:776329498@qq.com
 * Date: 2017/2/9
 * Time: 11:26
 */

namespace app\base\controller;

class WxMessages
{

    public static $template_message=[
        'reg'=>[
            'title'=>'成为会员通知',
            'template_id'=>'RuYmNOY3oicGT6mkWcB5IFn64mni4NCGlfdjD-vsXOA',
            'url'=>'http://{网站域名}/index/WC_html/user/perfectUserInfo.html',
            'data'=>[
                'first'=>['value'=>'你好，你已通过微信注册成[{网站名称}]的会员,下面的是你的注册信息',],
                'keyword1'=>['value'=>'{微信昵称}',],
                'keyword2'=>['value'=>'{会员积分}',],
                'keyword3'=>['value'=>'{当前时间}',],
                'remark'=>['value'=>'感谢你的加入,点击进入浏览',],
            ],
        ],

    ];



    private static function getMessages($message_type,$message_title){
        switch ($message_type) {
            case 'template_message':
                if (isset(self::$template_message[$message_title])) {
                    return self::$template_message[$message_title];
                }else {
                    return false;
                }
                break;
            case 'customMessage':

                break;
            case 'sendGroupMassMessage':

                break;

            default:
                # code...
                break;
        }

    }

    private static function getTemplateMessage($message_title,$fans){
        $message_data = self::getMessages('template_message',$message_title);
        $replace_data = $message_data['data'];
        foreach ($message_data as $key => $message_arr){
            if ($key=='data'){
                foreach ($message_arr as $title =>$str){
                    $replace_data[$title]['value']=self::messageReplaceParameter($replace_data[$title]['value'],$fans);
                }
            }else{
                $message_data[$key]=self::messageReplaceParameter($message_arr,$fans);
            }

        }
         $message_data['data']=$replace_data;
        return $message_data;
    }


    private static function messageReplaceParameter($string='',$data=''){
        //处理转化只传fans信息
        if(!isset($data['fans']) && isset($data['openid'])){
            $data['fans']=$data;
        }
        $replaces=[
            // 系统相关
            '{当前日期}'=>date('Y-m-d',time()),
            '{当前时间}'=>date('Y-m-d H:i:s',time()),
            '{网站域名}'=> 'test.mikkle.cn',
            '{网站名称}'=>'为了家健康',
            // 特殊字符
            '</br>'=>"\n",
            '<br/>'=>"\n",
            '</p>'=>"\n",
            // 粉丝相关
            '{粉丝ID}'=>is_int($data['fans']['id'])? 520000 + $data['fans']['id']:'9100'.$data['fans']['id'],
            '{粉丝OPENID}'=>$data['fans']['openid'],
            '{微信昵称}'=>$data['fans']['nickname'],
            '{会员积分}'=>isset($data['fans']['integral']) ? $data['fans']['integral'].'积分' : '暂无积分' ,
            // 微信设置相关
        ];
        // 循环替换
        foreach ($replaces as $key => $replace) {
            $string=str_replace($key ,$replace, $string);
        }
        return $string;
    }


    static public function getMessageData($message_title,$fans,$message_type='template_message'){
        switch ($message_type) {
            case 'template_message':
                return self::getTemplateMessage($message_title,$fans);
                break;
            case 'customMessage':

                break;
            case 'sendGroupMassMessage':

                break;

            default:
                # code...
                break;
        }

    }


}