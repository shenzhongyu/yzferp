<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/3
 * Time: 14:16
 */

namespace app\wechat\controller;



use think\Controller;
use think\Exception;
use think\exception\TemplateNotFoundException;
use think\Request;
use think\Url;

class Error extends Controller
{
    private $controllers=['Infoin','Show'];
    private $ext=['jpg','gif','png'];
    private $errorUrl="index/myError";
    private $error;
    private $create = false;//是否生成
    private $localimage;//原图路径
    private $remoteimage;//缩略图保存路径
    private $localinfo;//原图属性

    public function _initialize(){
        if(!in_array($this->request->controller(),$this->controllers)){


            dump(in_array($this->request->controller(),$this->controllers));
          $this->redirect(Url::build($this->errorUrl));
        }





    }
    // 空操作
    public function _empty() {
        try{
            $fetch=$this->fetch($this->request->action());
            if($fetch){
                return $fetch;
            }else{
                throw new Exception("方法不存在!");
            }
        }catch (TemplateNotFoundException $e){
            $this->error( $e->getMessage());
        } catch (Exception $e){
            $this->error($e->getMessage());
        }






    }

}