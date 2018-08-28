<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\budget;


use app\base\model\Base;
use app\base\model\BudgetDefaultProject;
use think\Db;
use think\Exception;
use think\Loader;

class SpaceTemplateUser extends Base
{

    protected $table = "mk_budget_space_template";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = false;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function saveSpace($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['guid'])|| !isset($data['type'])) return self::showReturnCodeWithOutData(1003);
        if (empty($data['guid'])|| empty($data['type'])) return self::showReturnCodeWithOutData(1003);
        $obj=Loader::model('base/BudgetDefaultProject')->getInfoByGuId($data['guid'],['status'=>1]);
        if (empty($obj)) return self::showReturnCodeWithOutData(1003,'该空间不存在,请联系管理员');
        unset($obj['id']);
        unset($obj['guid']);
        unset($obj['project_id']);
        unset($obj['user_name']);
        unset($obj['user_tel']);
        $obj['space_type']=$data['type'];
        $list=Loader::model('base/BudgetDefaultProject')->getList(['pid'=>$data['guid']]);
        try{
            $this->startTrans();
            $this->data($obj->toArray())->isUpdate(false)->save();
            $guid=$this->guid;
            if (!empty($list)){
                foreach ($list as $value){
                    unset($value['id']);
                    unset($value['guid']);
                    unset($value['project_id']);
                    unset($value['user_name']);
                    unset($value['user_tel']);
                    $value['pid']=$guid;
                    $this->data($value->toArray())->isUpdate(false)->save();
                }
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function saveTem($guid,$pro_id){
        if (empty($guid)) return self::showReturnCodeWithOutData(1003);
        $list=Loader::model('base/BudgetDefaultProject')->getList(['project_id'=>$pro_id,'pid'=>0]);
        try{
            $this->startTrans();
            if (!empty($list)){
                foreach ($list as $value){
                    $list_n=Loader::model('base/BudgetDefaultProject')->getList(['project_id'=>$pro_id,'pid'=>$value['guid']]);
                    unset($value['id']);
                    unset($value['guid']);
                    unset($value['uuid']);
                    unset($value['project_id']);
                    unset($value['user_name']);
                    unset($value['user_tel']);
                    $value['space_id']=$guid;
                    $value=$value->toArray();
                    $value['pid']=0;
                    // $this->table("mk_budget_space_template")->save($value);
                    $this->data($value)->isUpdate(false)->save();
                    $pid=$this->guid;
                    if (!empty($list_n)){
                        foreach ($list_n as $item){
                            unset($item['id']);
                            unset($item['guid']);
                            unset($item['uuid']);
                            unset($item['project_id']);
                            unset($item['user_name']);
                            unset($item['user_tel']);
                            $item['space_id']=$guid;
                            $item=$item->toArray();
                            $item['pid']=$pid;
                            $this->data($item)->isUpdate(false)->save();
                        }

                    }
                }
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }

    }
}