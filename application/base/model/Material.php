<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model;


use app\erp\config\Config;
use app\erp\config\FieldValue;
use think\Db;

class Material extends Base
{

    protected $table = "mk_material";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }
    public function getUnitAttr($value, $data){

        //$array= (new FieldValue())->unit_name;
        $array=FieldValue::getFieldValue("unit_name");
        return isset($array[$value]) ? $array[$value] : $value;
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

    public function getMaterialList($data){
        if(!isset($data['id'])){
            return ["code"=>1003,"msg"=>"查询错误"];
        }

        if(is_array($data['id'])){
            $total=0;
            $rows=[];
            foreach($data['id'] as $value){
                $list= $this->getListCont(["category_id"=>$value,'company_id'=>$data['company_id']]);
                $total+=$list["total"];
                if(!empty($list["rows"])){
                    foreach($list["rows"] as $v){
                        $rows[]=$v;
                    }
                }
            }
            return  ["total"=>$total,'rows'=>$rows];
        }else{
            $list= $this->getListCont(["category_id"=>$data['id'],'company_id'=>$data['company_id']]);
            return  $list;
        }
    }

    public function getListCont($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status'] = 1 ;
        }
        if (empty($field)){
            $field = '';
        }

        $total = $this->where($map)->count();
        $rows=$this->where($map)->field($field)->select();
        return  ["total"=>$total,'rows'=>$rows];
    }

    public function showMaterialList($data,$map=[],$field=false){
        if (empty($map)){
            $map=['status'=>1];
        }else if (!isset($map['status'])){
            $map['status']=1;
        }
        $page=1;
        $limit=10;
        if (!empty($data)) {
            if (isset($data['page'])) {
                $page = is_numeric($data['page']) ? $data['page'] : '1';
            }
            if (isset($data['limit'])) {
                $limit = is_numeric($data['limit']) ? $data['limit'] : '10';
            }
        }
        $model=new MaterialCategory();
        $list=$this->getEasyUiJsonNo($field,$map,$page,$limit);
        if (!empty($list['rows'])){
            foreach ($list['rows'] as $key=>$value){
                $type=$model->getInfoByGuId($value['category_id']);
                $list['rows'][$key]['category_id']=empty($type)?'材料':$type['category_name'];
            }
        }
        return $list;
    }

}