<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/4
 * Time: 10:52
 */

namespace app\erp\controller;


use app\base\model\PersonnelUser;

class WeAction extends WeAuth
{
    public function binding(){
        $user_model =new PersonnelUser();
        if ($user_model->where(['we_openid'=>$this->open_id])->count()==1){
            return $this->fetch('weui/success');
        }
       return $this->fetch('weui/binding');
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
    public function setPassWordSuccess(){
        return $this->fetch('weui/password_success');
    }
}