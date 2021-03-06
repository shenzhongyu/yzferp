<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/4
 * Time: 11:25
 */

namespace app\wechat\controller;
use app\base\model\PersonnelUser;
use app\base\model\WeFans;
use com\wechat\TpWechat;
use think\Cache;
use think\Model;
use think\Session;
use think\Url;

class WeAuth extends Base
{
    protected $isWechatBrowser = false;
    protected $scope = 'snsapi_base';
    protected $uuid;
    protected $open_id;
    protected $member_info;
    protected $options;
    protected $isReg = false;
    protected $no_login = false;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $user_agent = $this->request->server('HTTP_USER_AGENT');
        if (! strpos($user_agent, 'MicroMessenger') === false ) $this->isWechatBrowser = true;

        //判断提交方式和是否微信浏览器
        if ($this->request->method() == 'GET' && $this->isWechatBrowser === true){
            //未登录 重新登录
            if (!$this->checkAuth()&& !$this->no_login )  $this->wxoauth();
            $this->isLogin=true;
            //设置全局登录
            if (!in_array($this->request->action(),['binding','register','Showerrs']) ){
                $this->loginGlobal();
            }
            if($this->isReg){
                //   if(!$this->checkUuidMobile()) $this->redirect('/index/WC_html_1/mainContainer.html#user/user_blind.html');
            }
        }elseif($this->request->isPost()){
            if (!in_array($this->request->action(),['setpassword']) ){
                $this->redirect('WeAction/showErrs');
            }
        }else{
            if (!in_array($this->request->action(),['showerrs']) ){
                $this->redirect('showErrs');
            }
        }

    }

    /**
     * 设置全局登录
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @return bool
     */
    public function loginGlobal(){
        if (!$this->open_id) return false;

        $fans=new WeFans();
        $fans_info=$fans->infoByOpenId($this->open_id);
        $this->member_info['appid']=$this->getOptions();
        if (!$fans_info){
            $fans->allowField(true)->save($this->member_info);
        }else{
            $fans->allowField(true)->save($this->member_info,['id' => $fans_info['id']]);
        }

        $member=new PersonnelUser();
        $member_info=$member->infoByOpenId($this->open_id);
        if (!$member_info){
            //设置全局登录 微信浏览器登录
            $this->redirect(Url::build('WeAction/register'));
            $this->uuid = $member_info['uuid'];
        }else{
            $this->setLoginGlobal($member_info,0);
            if (!$this->checkLoginGlobal()){
                $this->setLoginGlobal($member_info,0) ;
            }
        }
    }

    /**
     * 微信内页登录
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @return bool
     */
    public function wxoauth()
    {
        if ($this->checkAuth()) return false;
        $code = $this->request->param('code');
        if (Session::has('code','html5') && $code == Session::get('code','html5') && $code) return false;
        if (!$this->options) $this->getOptions();
        $options = [
            'token'=>$this->options["token"], //填写你设定的key
            'appid'=>$this->options["appid"], //填写高级调用功能的app id
            'appsecret'=>$this->options["appsecret"] //填写高级调用功能的密钥
        ];
        Session::set('appid',$this->options["appid"],'html5');
        $we_obj = new TpWechat($options);
        if ($code) {
            $json = $we_obj->getOauthAccessToken();
            if (!$json) {
                $this->redirect($this->request->baseUrl());
                // die('获取用户授权失败，请重新确认<a href="'.$this->request->baseUrl().'" >点击重试</a>');
            }
            $this->open_id = $json["openid"];
            $access_token = $json['access_token'];
            Session::set('open_id',$this->open_id,'html5');
            Session::set('code',$code,'html5');
            $user_info = $we_obj->getUserInfo($this->open_id);
            $user_info['appid']=Session::get('appid','html5');
            if ($user_info && !empty($user_info['nickname'])) {
                $this->member_info = $user_info;
            } elseif (strstr($json['scope'],'snsapi_userinfo')!==false) {
                $user_info = $we_obj->getOauthUserinfo($access_token,$this->open_id);
                $user_info['appid']=Session::get('appid','html5');
                if ($user_info && !empty($user_info['nickname'])) {
                    $this->member_info = $user_info;
                } else {
                    return $this->open_id;
                }
            }
            if ($this->member_info) {
                Session::set('member_info',$this->member_info,'html5');
                Session::set('open_id',$this->open_id,'html5');
                Session::delete('wx_redirect','html5');
                return $this->open_id;
            }
            $this->scope = 'snsapi_userinfo';
        }

        if ($this->scope=='snsapi_base') {
            $url = $this->request->url(true);
            Session::set('wx_redirect',$url,'html5');
        } else {
            $url =Session::get('wx_redirect','html5');
        }
        if (!$url) {
            Session::delete('wx_redirect','html5');
            die('获取用户授权失败');
        }


        $oauth_url = $we_obj->getOauthRedirect($url,"wxbase",$this->scope);
        header('Location:'.$oauth_url);
        die;


    }

    /**
     * 获取微信Options 使用缓存
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param int $appid
     * @return mixed
     */
    public function getOptions( $appid = 1){
        if(Cache::has('weOptions_'.$appid)){
            $weOptions=Cache::get('weOptions_'.$appid);
        }else{
            $weOptions=model('base/We')->info($appid)->toarray();
            Cache::set('weOptions_'.$appid,$weOptions,3600);
        }
        $this->options=$weOptions;
        $this->appid=$weOptions["appid"];
        return $weOptions["appid"];
    }

    /**
     * 判断登录是否成功
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @return bool
     */
    public function checkAuth()
    {
        if (!Session::has('member_info','html5')&&!Session::has('open_id','html5')){
            return false;
        }else{
            $this->member_info = Session::get('member_info','html5');
            $this->open_id = Session::get('open_id','html5');
            $this->appid = Session::get('appid','html5');
            if (!$this->options) $this->getOptions();
            return true;
        }
    }


}