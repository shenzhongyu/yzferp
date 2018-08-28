<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\project;


use app\base\model\Base;
use app\base\model\BaseHash;
use think\Db;
use think\Exception;
use think\Loader;

class ProjectContract extends BaseHash
{

    protected $table = "mk_project_contract";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;
    protected $hashKey='guid';
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
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        return  $this->where($map)->field($field)->select();
    }
    public function saveData($data){
        if (empty($data)){
            return self::showReturnCodeWithOutData(1003);
        }
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delProPhoto($guid){
        if (empty($guid)){
            return self::showReturnCodeWithOutData(1003);
        }
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }


    public function showProjectList($rows,$page,$project_name,$number){
        $list=Db::view("mk_project_contract a",'book_number,project_id,guid,project_name,contract_amount,price')
            ->view("mk_project_contract_audit b",'project_id','a.project_id=b.project_id&&a.book_number=b.book_number')
            ->where('a.company_id',$this->member_info->company_id)
            ->where('b.examine_status',"1")
            ->where('a.project_name','like','%'.$project_name.'%')
            ->where('a.book_number','like','%'.$number.'%')
            ->limit($rows)
            ->page($page)
            ->select();
        $total=Db::view("mk_project_contract a",'book_number,project_id,guid,project_name,contract_amount,price')
            ->view("mk_project_contract_audit b",'project_id','a.project_id=b.project_id&&a.book_number=b.book_number')
            ->where('a.company_id',$this->member_info->company_id)
            ->where('b.examine_status',"1")
            ->where('a.project_name','like','%'.$project_name.'%')
            ->where('a.book_number','like','%'.$number.'%')
            ->count();
        return ["rows"=>$list,'total'=>$total];
    }

}