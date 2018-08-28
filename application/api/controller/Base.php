<?php
namespace app\api\controller;

/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/12
 * Time: 14:52
 */
abstract class Base extends \app\base\controller\Base
{


    /**
     * 检查登陆信息
     * Power by Mikkle
     * QQ:776329498
     */
    public function _initialize()
    {
        if ($this->checkLoginGlobal()) {
            $this->isLogin = true;
        }
    }


    protected function addLog(){
        $this->log[] =[
            'uuid'=> $this->uuid ,
            'url'=>$this->request->url(true),
            'method'=>$this->request->method(),
            'data' => $this->getData(),
            'ip'=>$this->request->ip(),
        ];
    }

    /**
     * * 验证规则 当验证不通过 返回错误值
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:2016.12.15
     * @param $data 要验证的数据数组
     * @param $validate_name 验证类名字
     * @param bool|false $is_return_code
     * @return 出错返回1003错误码和错误详情
     */
    protected function checkValidate($data, $validate_name, $is_return_code = true)
    {
        $result = $this->validate($data, $validate_name);
        if (true !== $result) {
            if ($is_return_code == true) {
                return ['code' => 1003, 'msg' => $result];
            } else {
                echo '{"code":1003,"msg":"' . $result . '"}';
                die;
            }
        }
    }



}