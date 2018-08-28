<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model;


use app\erp\config\FieldValue;
use think\Db;
use think\Exception;
use think\Loader;

class BudgetDefaultBook extends BaseHash
{

    protected $table = "mk_budget_default_book";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = false;
    protected $hashKey="guid";
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
    public function getBook($guid){
        $list=$this->getList(["project_id"=>$guid]);
        $arr=[];
        if (!empty($list)){
            $arr['name']=$list[0]['default_book_com_name'];
            $arr['address']=$list[0]['address'];
            $arr['logo']=$list[0]['logo'];
            $arr['desc']=$list[0]['desc'];
            $arr['budget_style']=$list[0]['budget_style'];
            $arr['area']=$list[0]['zx_area'];
            $arr['gc_adress']=$list[0]['gc_adress'];
            $arr['cont_name']=$list[0]['cont_name'];
            $arr['cont_pone']=$list[0]['cont_pone'];

        }else{
            $arr['name']='';
            $arr['address']='';
            $arr['logo']='';
            $arr['desc']='';
            $arr['budget_style']='';
            $arr['area']='';
            $arr['gc_adress']='';
            $arr['cont_name']='';
            $arr['cont_pone']='';
        }
        return $arr;
    }


    public function saveData($data,$pro_id,$com_id){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['budget_type'])) return self::showReturnCodeWithOutData(1003,'未选择预算类型');
        try {
            if (isset($data['book'])){
                $list=Loader::model('base/BudgetListEdit')->getList(['guid'=>$data['book']]);
            }else{
                return self::showReturnCodeWithOutData(1001);
            }
            $pro=Loader::model("base/Project")->getList(['guid'=>$pro_id],'decoration_style,project_address,decoration_area');
            $style='';
            if(!empty($pro)){
                $fie=new FieldValue();
                if (isset($pro[0]['decoration_style'])){
                    $style=$fie->decoration_style[$pro[0]['decoration_style']];
                }
            }
            $contUser=Loader::model("base/ProjectContacts")->getList(["project_guid"=>$pro_id],'contact_name,contact_number,householder_relation,sex');
            $pro_cont_name=""; //业主的名字
            $pro_cont_phone=""; //业主电话
            if (!empty($contUser)){
                foreach ($contUser as $value){
                    if ($value['householder_relation']=='本人'){
                        $pro_cont_name=$value['contact_name'];
                        $pro_cont_phone=$value['contact_number'];
                        break;
                    }
                }
                if (empty($pro_cont_name)){
                    $pro_cont_name='';
                    $pro_cont_phone='';
                }
            }
            $this->startTrans();
            if(!empty($list)) {
                $data_book = [
                    'project_id' => $pro_id,
                    'default_book_style' => $list[0]['style'],
                    'default_book_com_name' => $list[0]['com_name'],
                    'address' => $list[0]['address'],
                    'telephone' => $list[0]['telephone'],
                    'desc' => $list[0]['desc'],
                    'logo' => $list[0]['logo'],
                    'company_id' => $com_id,
                    'budget_style'=>$style,
                    'zx_area'=>empty($pro) ? '' : $pro[0]['decoration_area'],
                    'gc_adress'=>empty($pro) ? '' : $pro[0]['project_address'],
                    'cont_name'=>$pro_cont_name,
                    'cont_pone'=>$pro_cont_phone,
                    'budget_type'=>empty($data['budget_type'])?"1":$data['budget_type'],
                ];
                $this->data($data_book)->isUpdate(false)->save();
            }else{
                throw new Exception('预算书模板错误');
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function saveChangeInfo($data,$pro_id){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (empty($pro_id)) return self::showReturnCodeWithOutData(1003);

        if (!$this->checkProjectDesign($pro_id)){
            return self::showReturnCodeWithOutData(1020,'该项目已申请预算打印，无法修改');
        }

        if(isset($data['serial_number'])){
            unset($data['serial_number']);
        }
        try {
            $this->startTrans();
            $this->data($data)->isUpdate(true,['project_id'=>$pro_id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    //更换预算类型
    public function editBudgetType($data,$pro_id){
        if (empty($data)) return self::showReturnCodeWithOutData(1002);
        if (empty($pro_id)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['budget_type'])) return self::showReturnCodeWithOutData(1004);
        if (!isset(FieldValue::getFieldValue('budget_type')[$data['budget_type']]))  return self::showReturnCodeWithOutData(1008);
        try {
            $this->startTrans();
            $this->data($data)->isUpdate(true,['project_id'=>$pro_id])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    //检测项目预算是否提交审核
    public function checkProjectDesign($pro_id){
        $obj=Db::table('mk_budget_print_request')->where(['project_id'=>$pro_id,'status'=>1])->find();
        $revise=Db::table('mk_budget_revise')->where(['project_id'=>$pro_id,'status'=>1])->find();
        if (empty($obj)){
            return true;
        }else{
            //申请预算修改有没有通过
            if(empty($revise) || $obj['examine_status']!="-1"){
                return false;
            }else if($revise['examine_status']!="1"){
                return false;
            }else{
                return true;
            }

        }

    }

}