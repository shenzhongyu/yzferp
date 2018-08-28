<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/5
 * Time: 17:20
 */

namespace app\install\controller;




use app\base\controller\Curl;
use app\base\model\ModelCode;
use think\Config;
use think\Db;
use think\Exception;
use think\helper\hash\Md5;
use think\Loader;
use think\Log;
use think\Session;

class Reset extends Base
{



    public function siteReset(){
        if($this->request->isPost()){
            $password=$this->request->post('password');
            if(!empty($password)){
                if(!$this->checkUserData()){
                    return json(["code"=>'1010','msg'=>'该网站尚未使用，无需重置']);
                }
                if($this->checkPassWord(trim($password))){
                    try{
                        if (is_file(APP_PATH . "install/existence/use.php")) {
                            unlink ( APP_PATH . "install/existence/use.php" );
                        }
                        if (is_file(APP_PATH . "install/existence/role.php")) {
                            unlink ( APP_PATH . "install/existence/role.php" );
                        }
                        if(is_file(APP_PATH . "install/index.html")){
                            $con=file_get_contents(APP_PATH . "install/index.html");
                            if($this->writeField("public_html/index.html",$con)){
                                return json(["code"=>'1001','msg'=>'网站重置成功']);
                            }else{
                                return json(["code"=>'1002','msg'=>'网站重置失败']);
                            }
                        }else{
                            return json(["code"=>'1005','msg'=>'配置参数丢失，无法重置']);
                        }

                    }catch (Exception $e){
                        Log::error($e->getMessage());
                        return json(["code"=>'1002','msg'=>'网站重置失败']);
                    }

                }else{
                    return json(["code"=>'1004','msg'=>'密码错误']);
                }
            }else{
                return json(["code"=>'1003','msg'=>'密码参数不存在']);
            }
        }else{
            $arr=[
                'url'=>url("siteReset")
            ];
            $this->assign($arr);
            return $this->fetch('password');
        }

    }

    protected function checkPassWord($value){
        if (!empty($value)){
            $a=ModelCode::getMd5Password($value);
            $b=Config::get('password');
            if($a!=$b["password"]){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    protected  function  checkUserData(){
        $field_path='install/existence/use.php';
        if(file_exists(APP_PATH .$field_path)==true){
            return true;
        }else{
            return false;
        }
    }

}