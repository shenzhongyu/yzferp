<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/31
 * Time: 15:12
 */

namespace app\base\controller;


use mikkle\tp_wechat\Wechat;
use think\Loader;
use think\Log;

class WxApi
{
    protected $options;
    static public $instance;
    protected $appid;

    public function __construct($options=[])
    {
        $this->options = $options;
    }


    /**
     * 发送消息操作入口
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param string $openid
     * @param $message_title   //消息标识  WxMessages::文件中设置
     * @param string $message_type    //消息类型 template_message 模版消息 custom_message 客服消息
     * @return bool
     */
    public function sendMessage($openid='',$message_title,$message_type='template_message'){

        if(!$this->getFansInfo($openid)) return false;
        //根据发送类型发送信息固定格式信息!
        switch ($message_type) {
            case 'template_message':
                $message_data=WxMessages::getMessageData($message_title,$this->fans);
                return $this->sendTemplateMessage($message_data);
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
    /**
     * 发送模版消息
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param $openid
     * @param $data
     * [
     * 'title=>'模版标题'
    template_id'=>'LsIc21raK3kWuX8j8hgBwJ-1cWn35PIdxgz2KMxgMPQ',
    'url'=>'http://baidu.com',
    'data'=>[
    'first'=>['value'=>'你好'],
    'keyword1'=>['value'=>'你好'],
    'remark'=>['value'=>'你好']
    ]
     * @return bool
     */
    public function sendTemplateMessage($data)
    {
        $we_message = Wechat::message($this->options);
        $res = $we_message->sendTemplateMessage($data);

        if (!is_numeric($res)){
            Log::error($this->getErrText($we_message->errCode));
            return false;
        }else{
            $this->appid=$we_message->getAppId();
            $this->saveMessage('templateMessage',$data,$res);
            return true;
        }

    }

    /**
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param string $MsgType
     * @param string $data
     * @param string $MsgId
     * @return bool
     */
    protected function saveMessage($MsgType='templateMessage',$data="",$MsgId=''){
        //记录发送日志
        $data_array = json_decode($data);
        $message=[
            'appid'=>$this->appid,
            'nickname'=>isset($data_array->nickname)?$data_array->nickname:"" ,
            'ToUserName'=>isset($data_array->touser)?$data_array->touser:"" ,
            'Content'=>$data,
            'MsgID'=>$MsgId,
            'CreateTime'=>time(),
            'MsgType'=>$MsgType,
            'status'=>0
        ];
        $code = Loader::model('base/WeTemplateMessage')->editData($message);
        if($code['code'] == 1001 ){
            return true;
        }else{
            Log::error($this->getErrText("保存模版消息失败了"));
            return false;
        }
    }




    public function getMenu(){
        $we_menu =  Wechat::menu($this->options);
        $menu=$we_menu->getMenu();
        return $menu;
    }
    public function createMenu($data){
        $we_menu =  Wechat::menu($this->options);
        $menu=$we_menu->createMenu($data);

        if (!$menu){
            Log::error($this->getErrText($we_menu->errCode));
            return false ;
        }
        return $menu ;
    }





    private function getErrText($err){
        return Wechat::getErrText($err);
    }

    static public function instance($options=[]){
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }


}