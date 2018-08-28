<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22
 * Time: 14:27
 */

namespace app\base\model;


use app\erp\config\FieldValue;

class ProjectContacts extends Base
{
    protected $table = "mk_project_contacts";
    protected $insert = ['guid','status'=>1];
    protected $autoWriteTimestamp = true;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }
    public function getProjectCont($guid){
        $contUser=$this->getList(["project_guid"=>$guid],'contact_name,contact_number,householder_relation,sex');
        $pro_cont_name=""; //业主的名字
        $pro_cont_phone=""; //业主电话
        if (!empty($contUser)){
            foreach ($contUser as $value){
                if ($value['householder_relation']==1){
                    $pro_cont_name=$value['contact_name'];
                    $pro_cont_phone=$value['contact_number'];
                    break;
                }
            }
            if (empty($pro_cont_name)){
                $pro_cont_name=$contUser[0]['contact_name'];
                $pro_cont_phone=$contUser[0]['contact_number'];
            }
        }
        return $arr=[
            'cont_name'=>$pro_cont_name,
            'cont_p'=>$pro_cont_phone,
        ];
    }



    public function getSexAttr($value, $data){
        $array=FieldValue::getFieldValue("sex_value");
        return isset($array[$value]) ? $array[$value] : $value;


    }
    public function getHouseholderRelationAttr($value, $data){
        $array=FieldValue::getFieldValue("householder_relation");
        return isset($array[$value]) ? $array[$value] : $value;


    }





    public function getList($map = [],$field=true){

        if (empty($map)){
            $map = ["status"=>1];
        }else{
            $map['status']=1;
        }

        return $this->where($map)->field($field)->select();

//        $list_obj = $this->where($map)->field($field)->select();
//
//        $data = [];
//        foreach ($list_obj as $value){
//          //  dump($value);
//            $data = $value->append(['project_contacts'])->toArray();
//        }
//        return $data;

    }





}