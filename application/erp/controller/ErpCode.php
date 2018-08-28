<?php
namespace app\erp\controller;
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/14
 * Time: 11:17
 */
class ErpCode
{
    /**
     * 全局登陆值
     * @var array
     */
    static $globalLoginCode=[
        '0'=>'页面登录',
        '1'=>'微信内页登录',
        '2'=>'微信扫码登陆',
    ];

    static public function is_admin($id = null)
    {
        $id = is_null($id) ? session('id') : $id;
        $admin_uids = explode(',', config('erp_admin'));//调整验证机制，支持多管理员，用,分隔
        return (in_array(intval($id), $admin_uids));//调整验证机制，支持多管理员，用,分隔

    }

    static public function get_admin()
    {
        $admin_uids = explode(',', config('user_admin')); //调整验证机制，支持多管理员，用,分隔
        return $admin_uids;
    }

}