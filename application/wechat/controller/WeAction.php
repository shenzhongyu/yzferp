<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/4
 * Time: 10:52
 */

namespace app\wechat\controller;


use app\base\controller\Rsa;
use app\base\model\PersonnelUser;
use think\Exception;
use think\Log;
use think\Url;

class WeAction extends Base
{

    public function loginWechat($open_id=""){
        try{
            if($open_id){
                $open_id = str_replace(" ","+",$open_id);
                $open_id = Rsa::instance()->decrypt($open_id);

                if(!$open_id){
                    throw new Exception("用户不存在");
                }
                $member=new PersonnelUser();
                $member_info=$member->infoByOpenId($open_id);
                if (!$member_info){
                    //设置全局登录 微信浏览器登录
                    $this->redirect(Url::build('WeAction/register'));
                }else{
                    $this->setLoginGlobal($member_info,0);
                    $this->redirect(Url::build('index/index'));
                }
            }else{
                $this->redirect('http://wechat.yzferp.com/center/we_action/loginWechat');
            }
        }catch(Exception $e){
            Log::error($e->getMessage());
            return $this->fetch('weui/errs');
        }
    }

    public function binding(){
        $this->redirect('http://wechat.yzferp.com/center/we_action/binding');
    }

    public function register(){
        return $this->fetch('weui/register');
    }


    public function bindingSuccess(){
        return $this->fetch('weui/success');
    }

    public function showErrs(){
        return $this->fetch('weui/errs');
    }

    public function resetPassword()
    {

        if(!isset($this->open_id)){
            return $this->fetch('weui/binding');
        }
        if(!isset($this->uuid)){
            return "1001";
        }
        $assign_data = [
            'title' =>'密码找回',
            'uuid'=>$this->uuid
        ];
        $this->assign($assign_data);
        return $this->fetch('weui/password_set');


    }

    public function setPassWord(){
        if($this->request->isPost()){
            $data=$this->request->post();
            if($this->uuid != $data["uuid"]){
                return $this->fetch('weui/binding');
            }
            $validate_name='base/PersonnelUser.login';
            $result = $this->validate($data, $validate_name);
            if (true !== $result) return ['code' => '1003', 'msg' => $result,];
            $re=model('base/PersonnelUser')->setPersonnelPassword($data["uuid"],$data["password"]);
            if($re==1){
                return self::showReturnCodeWithOutData(1001);
            }else{
                return self::showReturnCodeWithOutData(1003);
            }
        }
    }
    //
    public function setPassWordSuccess(){
        return $this->fetch('weui/password_success');
    }
}