<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/27
 * Time: 11:52
 */

namespace app\api\controller;

use app\base\model\PersonnelUser;
use app\base\model\WeFans;
use app\base\model\WeLocation;
use app\base\model\WeMessage;
use app\base\model\WeTemplateMessage;
use mikkle\tp_wechat\Wechat;
use think\Cache;
use think\Controller;
use com\wechat\TpWechat;
use think\Exception;
use think\Hook;
use think\Log;

abstract class BaseWeApi extends Controller
{
    protected $app_id;
    protected $app_config;
    protected $options;
    protected $weObj;
    protected $openid;
    protected $type;
    protected $data;
    protected $fans;
    protected $valid = true;

    public function _initialize()
    {
        $this->options = $this->app_config;
        $this->app_id = $this->app_config['appid'];
    }


    public function jsSign($url='',$appid=1){
        if (!$this->request->isAjax()) return json(['err'=>true]);
        if ($this->request->server('HTTP_REFERER')!=$url) return json(['err'=>true]);
        $options = $this->options;
        $my_sign = Wechat::script($options)->getJsSign($this->request->server('HTTP_REFERER'));
        return json($my_sign);
    }

    public function index(){

        try{
        $options = $this->options;
        $this->weObj = new TpWechat($options);
        if ($this->valid==true){
            $this->weObj->valid();
        }

        $this->weObj->options=$options;

        //分解数据获得常用字段
        $get_rev = $this->weObj->getRev();

            if (empty($get_rev)){
                return $this->we_dump('false');
            }
        $this->openid = $get_rev->getRevFrom();
        $this->type = $get_rev->getRevType();
        $this->data = $get_rev->getRevData();

        if (empty($this->data)) {
            return $this->we_dump('false');
        }

        //用户检测，如果有就存入data,没有则存入数据库
        $fans = $this->hasSaveFans();


        //补充常用相关数据到DATA
        $this->data['appid']=$this->app_id;
        $this->data['openid']=$fans['openid'];
        $this->data['nickname']=$fans['nickname'];
        $this->fans=$fans;


        if ($this->saveWeMessage()===false){
            $this->weObj->text('处理中，请稍后！')->reply();die;
        };
        $reply=[];

        //根据消息类型 获取不同回复内容
        $message=$this->messageType();
            if(!empty($message)){
                //处理兼容
                if (!is_array($message)){
                    $reply['message']=$message;
                }else{
                    $reply=$message;
                }
                if (!isset($reply['type'])) $reply['type']='text';
                $reply['message']=isset($reply['message'])?$reply['message']:'知道吗!我爱你!请原谅我不知道如何回答你的问题!';

                $this->weObj->$reply['type']($reply['message'])->reply();
            }

        die;

        }catch (Exception $e){
            Log::error($e);

            die();
         //   $this->weObj->text("本宝宝生病了,请原谅我不知道如何回答你的问题!")->reply();
        }
    }

    // 处理微信消息总入口
    public function messageType(){

        switch($this->type) {
            case TpWechat::MSGTYPE_TEXT:

                // $reply=model('rob/Rob')->reply($this->data['Content'],$this->data);
                	$reply='发送的是'.$this->data['Content'];

                break;
            case TpWechat::MSGTYPE_IMAGE:

                $newsarray=[
                    [
                        'Title'=>cache($this->openid.$this->data['MsgId']).'你的图片发送成功',
                        'Description'=>'这是你发的图片吧',
                        'PicUrl'=>$this->data['PicUrl'],
                        'Url'=>'http://yzf.mikkle.cn/'
                    ],
                ];


                    $reply=['type'=>'news','message'=>$newsarray];

                break;
            case TpWechat::MSGTYPE_VOICE:
                if (!empty($this->data['Content'])){
                    $keyword=$this->data['Content'];  //TODO::语音识别 语义分析
                }else{
                    $keyword=$this->data->type;
                }
                $reply='发送的是:'.$keyword;

                break;
            case TpWechat::MSGTYPE_MUSIC:
                $reply='发送的是:'.TpWechat::MSGTYPE_MUSIC;
                break;

            case TpWechat::MSGTYPE_VIDEO:
                $reply='发送的是:'.TpWechat::MSGTYPE_VIDEO;
                break;
            case TpWechat::MSGTYPE_LOCATION:
                $reply='发送的是:'.TpWechat::MSGTYPE_LOCATION;
                break;
            case TpWechat::MSGTYPE_LINK:
                $reply='发送的是:'.TpWechat::MSGTYPE_LINK;
                break;
            case TpWechat::MSGTYPE_EVENT:
                $reply=$this->messageEvent();
                break;
            default:
                $reply=['type'=>'text','message'=>'success'];
                break;

        }

        return $reply;

    }

    // 处理微信事件消息
    public function messageEvent(){
        $reply=null;
        switch($this->data['Event']){
            case TpWechat::EVENT_SUBSCRIBE:
                //记录关注
                //$res=db('WeFans')->where('openid',$this->openid)->update(['subscribe'=>1,'subscribe_time'=>time()]);

                if (isset($this->data['EventKey'])){
                    $EventKey=explode('_',$this->data['EventKey'])[1];
                    //判断登录缓存是否存在
                    if(Cache::has('log_'.$EventKey)){

                        $reply = $this->messageLogEvent($EventKey);
                    }else{
                        $reply=['type'=>'text','message'=>'感谢你的关注'];
                    }
                }else{
                    $reply=['type'=>'text','message'=>'感谢你的关注'];
                }

                break;
            case TpWechat::EVENT_UNSUBSCRIBE:
                $res=db('WeFans')->where('openid',$this->openid)->update(['subscribe'=>0,'unsubscribe_time'=>time()]);
                $reply=['type'=>'text','message'=>'success'];
                break;
            case TpWechat::EVENT_SCAN:
                //记录扫码获取机器人回答
                $EventKey=$this->data['EventKey'];
                //判断登录缓存是否存在
                if(Cache::has('log_'.$EventKey)) {
                    $reply = $this->messageLogEvent($EventKey);;
                }else{
                    $reply=['type'=>'text','message'=>TpWechat::EVENT_SCAN];
                }
//
//                $reply=model('rob/Rob')->reply($answer_id,$this->data,2);

                break;
            case TpWechat::EVENT_LOCATION:
//        /     /   $reply=['type'=>'text','message'=>'ok'];
                break;
            case TpWechat::EVENT_MENU_CLICK:
               // $reply['message']='success';
            //    $reply=model('rob/Rob')->reply($this->data['EventKey'],$this->data,2);
                break;
            case TpWechat::EVENT_MENU_SCAN_PUSH:
                $reply=TpWechat::EVENT_MENU_SCAN_PUSH;
                break;
            case TpWechat::EVENT_MENU_SCAN_WAITMSG:

                $reply=TpWechat::EVENT_MENU_SCAN_WAITMSG;
                break;
            case TpWechat::EVENT_MENU_PIC_SYS:
                //   cache($this->openid.$this->data['MsgId'],0,5);
                $reply=['type'=>'text','message'=>'ok1'];
                break;
            case TpWechat::EVENT_MENU_PIC_PHOTO:

                break;
            case TpWechat::EVENT_MENU_PIC_WEIXIN:

                break;
            case TpWechat::EVENT_MENU_LOCATION:
                break;
            case TpWechat::EVENT_SEND_MASS:
                $reply['message']='success';
                break;
            case TpWechat::EVENT_SEND_TEMPLATE:
                //模板消息发送成功
                $reply['message']='success';
                break;
            case TpWechat::EVENT_KF_SEESION_CREATE:

                break;
            case TpWechat::EVENT_KF_SEESION_CLOSE:

                break;
            case TpWechat::EVENT_KF_SEESION_SWITCH:

                break;
            case TpWechat::EVENT_CARD_PASS:

                break;
            case TpWechat::EVENT_CARD_NOTPASS:

                break;
            case TpWechat::EVENT_CARD_USER_GET:

                break;
            case TpWechat::EVENT_CARD_USER_DEL:

                break;
            case TpWechat::WifiConnected :

                break;
            case TpWechat::ShakearoundUserShake :

                break;
            default:
                break;
        }
        return $reply;
    }

    /**
     * 微信API接口 扫码登录
     * Power by Mikkle
     * QQ:776329498
     * @param $EventKey
     * @param bool|false $subscribe
     * @return array
     */
    protected function messageLogEvent($EventKey,$subscribe=false){
        $user_model=new PersonnelUser();
        if ($user_model ->where(['we_openid'=>$this->openid])->count() ==1 ){
            //写入登录缓存
            $log_data=[
                'code'=>1001,
                'content'=>'登录成功',
                'data'=>$this->openid,
            ];
            Cache::set('log_'.$EventKey,$log_data,120);
            if ($subscribe){
                $reply=['type'=>'text','message'=>'感谢你的关注,你已经微信登录成功.登录ID['.$EventKey.']'];
            }else{
                $reply=['type'=>'text','message'=>'你已经微信登录成功.登录ID['.$EventKey.']'];
            }
        }else{
            $news_array=[
                [
                    'Title'=>'你的微信暂时未绑定ERP系统',
                    'Description'=>'现在去绑定',
                    'PicUrl'=>'',
                    'Url'=>$this->request->domain().'/erp/we_action/binding?sid='.$EventKey,
                ],
            ];
            $reply=['type'=>'news','message'=>$news_array];
        }
        return $reply;
    }

    protected function saveWeMessage(){
        //定义message的Model

        $model_message=$this->getWeMessageModel();
        //查询是否已经接受该消息

        if (isset($this->data['MsgId'])){
            if ($model_message->infoByMsgId($this->data['MsgId'])) {
                return false;
            }
        }

        //记录消息
        if (isset($this->data['Recognition'])){
            if (is_string($this->data['Recognition'])){
                $this->data['Content'] = $this->data['Recognition'];
            }else{
                $this->data['Content'] = 'Translation failure!';
                unset( $this->data['Recognition'] ) ;
            }
        }
        if(isset($this->data['Latitude'])){
            $this->getWeLocationModel()->editData($this->data);
        }else if(isset($this->data['MsgID'])){
            $this->getWeTemplateMessageModel()->updateTemplateMessage($this->data['MsgID']);
        }else{
            $model_message->editData($this->data);
        }

    }

    protected function hasSaveFans($openid=''){
        try{
            $openid = $openid ? : $this->openid;
            if (empty($openid)){return false; }
            $model_fans=$this->getWeFansModel();

            $fans=$model_fans->infoToArray($openid);
            if (!$fans){
                $fans= $this->weObj->getUserInfo($openid);
                if ($fans){
                    if(is_object($fans)){
                        $fans=$fans->toArray();
                    }
                    $fans['appid']=$this->weObj->options['appid'];
                    $model_fans->editData($fans);
                    $fans=$model_fans->infoToArray($openid);
                    if(is_object($fans)){
                        $fans=$fans->toArray();
                    }
                }else{
                    //todo 报警
                }
            }else{
                //记录心跳
                $model_fans->where('openid',$this->openid)->setField('update_time',time());
            }
            return $fans ;
        }catch (Exception $e){
            Log::error($e);
            return false;
        }

    }

    protected function we_dump($value){
        ob_start();
        var_dump($value);
        $back = ob_get_clean();
        $this->weObj->text($back)->reply();
        ob_end_flush();
    }
    protected function getWeFansModel()
    {
        return new WeFans();
    }

    protected function getWeMessageModel()
    {
        return new WeMessage();
    }
    protected function getWeTemplateMessageModel()
    {
        return new WeTemplateMessage();
    }

    protected function getWeLocationModel()
    {
        return new WeLocation();
    }


    /**
     * 图文链接转换
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $info
     * @return mixed|string
     */
    protected function getUrl($info){
        if($info['url']){
            $url = str_replace(['{token}','{wecha_id}'], [config('token'), $this->weObj->getRev()->getRevFrom()], $info['url']);
        }else{
            //如果没有写外链，跳转到微官网详情页
            $url = config('site_url').URL('/index/img/index?id='.$info['id']);
        }

        return $url;
    }

    /**
     * 获取缩略图的地址
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $v
     * @return string
     */
    protected function getImgUrl($v){
        //如果是外部的网址
        if(strpos($v, 'http') === false){
            return config('img_server').$v;
        }else{
            return $v;
        }
    }

}