<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model\finance;
use app\base\model\Base;

use think\Session;

class BankLog extends Base
{
    protected $table = "mk_finance_bank_log";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }


//    protected function getProjectContactsAttr($value, $data)
//    {
//        return ($this->belongsTo('ProjectContacts')->where(['project_guid'=>$data['guid']])->find());
//
//
//    }




    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = [];
        }
        if(!isset($map['status'])){
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }

    public function addProjectLog($data){
        $log=[];
        $log["log_desc"]=$data["desc"]; //账号说明
        $log['name']=$data['name'];   //账号名字
        $log['bank_uuid']=$data['bank_uuid'];  //资金管理者
        $log['bank_payStyle']=$data['style']; //支付方式
        $log['price']=$data['balance_price']; //账号余额
        if (!isset($data['uuid'])){
            $log["uuid"]=Session::get('uuid', 'Global');
        }else{
            $log["uuid"]=$data["uuid"];
        }
        $log["bank_guid"]=$data["guid"];
        $this->save($log);
    }
}