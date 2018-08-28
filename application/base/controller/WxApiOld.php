<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * Email:776329498@qq.com
 * Date: 2017/2/7
 * Time: 8:52
 */

namespace app\base\controller;
use com\wechat\TpWechat;
use think\Db;
use think\Loader;

class WxApiOld extends Base
{
    private $appid;
    private $weObj;
    private $options;
    private $openid;
    private $token;
    private $type;
    private $data;
    private $fans;
    private $errors=[];
    public function _initialize($appid=1)
    {
        parent::_initialize();

//        $options = [
//            'token'=>'YZFKJ',
//            'appid'=>'wxa3f229dad32836ea',
//            'appsecret'=>'02e0b81c046ea6084759013df8daeda3',
//            'encodingaeskey'=>'zRkyumgpMBZlPI66xWcpDghZecPXhzOCS8VXYaVh62d',
//        ];
        $options = [
        'token'=>'yzferp',
        'appid'=>'wxa13efbf234271d72',
        'appsecret'=>'1fc235c095038500c9c7f1e0b9eca2f2',
        'encodingaeskey'=>'i1ttHxdPJ5ul0HSF5ZcHSFJnxRq5QMMtOgY5Z4nsdRf',
        ];

        $this->weObj = new TpWechat($options);
     //  $this->weObj->sendTemplateMessage();
        $this->options= $options;
        $this->appid = $appid;
    }

    public function getJsSign($url){
        return $this->weObj->getJsSign($url);
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
        $this->weObj->checkAuth();
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
    public function sendTemplateMessage($data,$openid='')
    {
        if(!empty($openid) && empty($this->fans)){
            $this->getFansInfo($openid);
        }
        $reply['touser'] = $openid ? : $this->fans['openid'];
        //模版id长度小于40 可能是原始消息库ID
        $reply['template_id']= strlen($data['template_id'])<40 ? $this->weObj->addTemplateMessage($data['template_id']) : $data['template_id'];
        $reply['url']=$data['url'];
        $reply['data']=$data['data'];

        if(!$this->token) $this->getToken();
        $res=$this->weObj->sendTemplateMessage($reply);
        if(!is_numeric($res)) {
            $this->getToken();
            $res=$this->weObj->sendTemplateMessage($reply);
        }
        if(!is_numeric($res)) {
            $this->getToken();
            $res=$this->weObj->sendTemplateMessage($reply);
        }

        if (!is_numeric($res)){
            $this->errors[]='发送模版消息失败了';
            return false;
        }else{
            $this->saveMessage('templateMessage',@$data['title']?:'发送了模版消息',$res);
            return true;
        }

    }

    /**
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param string $MsgType
     * @param string $Content
     * @param string $MsgId
     * @return bool
     */
    protected function saveMessage($MsgType='templateMessage',$Content='',$MsgId=''){
        //记录发送日志
        $fans=$this->fans;
        $message=[
            'appid'=>$fans['appid'],
            'nickname'=>$fans['nickname'],
            'ToUserName'=>$fans['openid'],
            'Content'=>$Content,
            'MsgID'=>$MsgId,
            'CreateTime'=>time(),
            'MsgType'=>$MsgType,
            'status'=>0
        ];
        $code = Loader::model('base/WeTemplateMessage')->editData($message);
         if($code['code'] == 1001 ){
            return true;
        }else{
             $this->errors[]='保存发送消息失败了'.$code['code'];
             return false;
         }
    }

    public function getMenu(){
        $res=$this->weObj->checkAuth();

        $menu=$this->weObj->getMenu();
        return $menu;
    }
    public function createMenu($data){
        $this->getToken();

        $menu=$this->weObj->createMenu($data);

        if (!$menu){
            return $this->getErrText($this->weObj->errCode) ;
        }
        return $menu ;

    }

    public function getTMlist(){

        $re=$this->weObj->checkAuth();
        $list  =  $this->weObj->getTemplateAllPrivate();

        Loader::model('base/WeTemplateMessagesList')->saveall($list['template_list']);

        foreach($list['template_list'] as $arr){
          //  dump( model('base/WeTemplateMessagesList')->save($arr));
            dump($arr);
        }
       return ;

    }

    /***
     * 根据openid或者uuid获取fans信息
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param string $open_id openid或者uuid
     * @return bool 返回到$this->fans
     */
    protected function getFansInfo($openid=''){
        if( !$openid && !$this->fans){
            $this->errors[]='方法getFansInfo的参数不全';
            return false;
        }
        if($this->fans){
            return true;
        }else{
            $fans=Loader::model('base/WeFans')->infoByOpenId($openid)->toArray();
            if ($fans){
                $this->openid=$fans['openid'];
                $this->fans=$fans;
                return true;
            }else{
                if($this->getFansInfoFromWx($openid)){
                    return true;
                }else{
                    $this->errors[]='查询的FansInfo不存在';
                    return false;
                }

            }
        }
    }

    /**从微信获取用户信息
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param $openid
     * @return array
     */
    protected function getFansInfoFromWx($openid){
        $this->weObj->checkAuth();
        $fans=$this->weObj->getUserInfo($openid );
        if ($fans){
            $this->saveFansInfo($fans);
            $this->fans=$fans;
            $this->openid=$fans['$fans'];
            return true;
        }else{
            $this->errors[]='通过接口获取FansInfo失败';
            return false;
        }
    }

    protected function getToken(){
        $token=$this->weObj->checkAuth();
        if ($this->weObj->errCode==40001){
            $this->weObj->resetAuth();
            $token =  $this->weObj->checkAuth();
        }
        $this->token=$token;
    }
    /*
     * 保存fans信息
     */
    protected function saveFansInfo ($fans){

        $fans['appid']=$this->appid;
        $re=Loader::model('base/WeFans')->save($fans);

    }

    private function getErrText($err){
        return WxErrCode::getErrText($err);
    }
    /*
     * 写入或者输出错误日志
     */
    private function writeLog($error){
      //  echo $error;
    }
    /**
     * 析构函数 处理错误信息
     */
    public function __destruct()
    {
        if ($this->weObj->errCode) {
            $error=$this->getErrText($this->weObj->errCode);
            $this->writeLog($error);
            if ($this->weObj->errCode==40001)$this->weObj->resetAuth();
        }
        if ($this->errors) {
            foreach($this->errors as $err)
            $this->writeLog($err);
        }
    }


}