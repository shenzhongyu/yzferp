<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/28
 * Time: 17:08
 */

namespace app\api\controller;


use think\Loader;

class SendMessageApi extends Base
{

    public function sendMessage(){

        $open_id = 'oztsNwiddObBmmjT9C-5EJViOy48';  //老大的
        //$open_id = 'oztsNwsDDRiT_FfQ3BVWb1tiuiik';
        $add_message = [
            'title' => '访客留言通知通知',

            'template_id' => 'gx3i-3YSpWiFWwv2VqtYMFJRQqkQDweWmDNcFR7VXsw',
            'url' =>'http://www.yydzs.cn/admin/main.html',
            'data' => [
                'first' => ['value' => '管理员您好,有人给你留言了',],
                'user' => ['value' => 'IP地址',],
                'ask' => ['value' =>implode(',',$this->request->post()),],
                'remark' => ['value' => '请见快处理。',],
            ],
        ];
        $reg = Loader::controller('base/WxApi')->sendTemplateMessage($add_message,$open_id);
        return $reg ? "true" : "false" ;

    }


}