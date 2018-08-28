<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model\budget;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\budget\SpaceTemplateUser;
use think\Exception;
use think\Loader;

class DefaultProjectCopy extends BaseHash
{
    protected $table = "mk_budget_default_project_copy";
    protected $insert = ['status'=>1];
    protected $autoWriteTimestamp = false;
    protected $hashKey="guid";
    public function getUserName($guid,$book_number){
        $list=$this->where(['project_id'=>$guid,'status'=>1,'pid'=>0,'book_number'=>$book_number])->field("uuid,user_name,user_tel")->find();
        $arr=[];
        if (!empty($list)){
            if (empty($list['user_name'])){
                $user=$this->table('mk_personnel_user')->where(['uuid'=>$list['uuid']])->field('name,mobile')->find();
                $arr['name']=$user['name'];
                $arr['tel']=$user['mobile'];
            }else{
                $arr['name']=$list['user_name'];
                $arr['tel']=$list['user_tel'];
            }

        }
        return $arr;
    }

    public function getDataList($guid,$book_number){
        $pro=$this->getList(['project_id'=>$guid,'pid'=>0,'book_number'=>$book_number]);
        $arr=[];
        $i=1;
        $zj_price=0;//直接费用
        if (!empty($pro)){
            foreach ($pro as $value){
                $brr=[];
                $brr['name']=$i.'、'.$value['name'];
                $i++;
                $brr['child']=[];
                $xj_price=0; //小计费用
                $list=$this->getList(['project_id'=>$guid,'pid'=>$value['guid'],'book_number'=>$book_number]);
                if (!empty($list)){
                    foreach ($list as $item){
                        $crr=[];
                        $crr['name']=$item['name'];
                        $crr['unit']=$item['unit'];
                        $crr['number']=empty($item['number'])? "0":$item['number'];
                        $crr['xuhao']=empty($item['xuhao']) ? $item['id']:$item['xuhao'];
                        $crr['zc_price']=$item['zc_dj']/(1-$item['zc_mlr']);
                        $crr['fc_price']=$item['fc_dj']/(1-$item['fc_mlr']);
                        $crr['rg_price']=$item['rg_dj']/(1-$item['rg_mlr']);
                        $crr['sh_price']=$item['zc_dj']*($item['sh_xs']);
                        $crr['jx_price']=$item['zc_dj']*($item['jx_xs']);
                        $crr['desc']=$item['desc'];
                        $crr['price']=$item['price'];
//                        $crr['price']=$crr['zc_price']+$crr['fc_price']+$crr['rg_price']+$crr['sh_price']+$crr['jx_price'];
                        $crr['price_sum']=$item['number']*$crr['price'];
                        $xj_price+=$crr['price_sum'];

                        $brr['child'][]=$crr;
                    }
                }else{
                    $brr['child']=[];
                }
                $brr['xj_price']=$xj_price;
                $brr['desc']=$value['desc'];
                $zj_price+=$xj_price;
                $arr[]=$brr;
            }
        }
        $rate=[];
        $rate_list=Loader::model("base/budget/DefaultRateCopy")->getList(["project_id"=>$guid,'book_number'=>$book_number]);
        $sum_all=0; //合计费用
        $rate[]=[
            'name'=> '直接费用',
            'value'=>$zj_price,
            'unit'=>'元',
            'rate_value'=>'',
            'desc'=>'工程施工实际总费用',
        ];
        $sum_all+=$zj_price;
        if (!empty($rate_list)){
            foreach ($rate_list as $value){
                $a=$value['rate_default_type'];
                $p=0;
                switch ($a){
                    case '*':
                        $sum_all+=$zj_price*$value['rate_default_value'];
                        $p=$zj_price*$value['rate_default_value'];
                        break;
                    case '-':
                        $sum_all+=$zj_price-$value['rate_default_value'];
                        $p=$zj_price-$value['rate_default_value'];
                        break;
                    case '/':
                        $sum_all+=$zj_price/$value['rate_default_value'];
                        $p=$zj_price/$value['rate_default_value'];
                        break;
                    case '+':
                        $sum_all+=$zj_price+$value['rate_default_value'];
                        $p=$zj_price+$value['rate_default_value'];
                        break;
                    default:
                        break;
                }
                $rate[]=[
                    'name'=> $value['rate_default_name'],
                    'value'=>$p,
                    'unit'=>'元',
                    'rate_value'=>$value['rate_default_value'],
                    'desc'=>$value['desc'],
                ];
            }
        }
        $rate[]=[
            'name'=>'总造价',
            'value'=>$sum_all,
            'unit'=>'元',
            'rate_value'=>'',
            'desc'=>'工程预算总造价',
        ];
        return [
            'body'=>$arr,
            'foot'=>$rate,
        ];
    }

    public function getDataList_copy($guid,$book_number){
        $pro=$this->getList(['project_id'=>$guid,'pid'=>0,'book_number'=>$book_number]);
        $arr=[];
        $i=1;
        $zj_price=0;//直接费用
        if (!empty($pro)){
            foreach ($pro as $value){
                $brr=[];
                $brr['name']=$i.'、'.$value['name'];
                $i++;
                $brr['child']=[];
                $xj_price=0; //小计费用
                $list=$this->getList(['project_id'=>$guid,'pid'=>$value['guid'],'book_number'=>$book_number]);
                if (!empty($list)){
                    foreach ($list as $item){
                        $crr=[];
                        $crr['name']=$item['name'];
                        $crr['unit']=$item['unit'];
                        $crr['number']=empty($item['number'])? "0":$item['number'];
                        $crr['xuhao']=empty($item['xuhao']) ? $item['id']:$item['xuhao'];
                        $crr['zc_price']=$item['zc_dj']*(1+$item['zc_mlr']);
                        $crr['fc_price']=$item['fc_dj']*(1+$item['fc_mlr']);
                        $crr['rg_price']=$item['rg_dj']*(1+$item['rg_mlr']);
                        $crr['sh_price']=$item['zc_dj']*($item['sh_xs']);
                        $crr['jx_price']=$item['zc_dj']*($item['jx_xs']);
                        $crr['desc']=$item['desc'];
                        $crr['price']=$item['price'];
//                        $crr['price']=$crr['zc_price']+$crr['fc_price']+$crr['rg_price']+$crr['sh_price']+$crr['jx_price'];
                        $crr['price_sum']=$item['number']*$crr['price'];
                        $xj_price+=$crr['price_sum'];

                        $brr['child'][]=$crr;
                    }
                }else{
                    $brr['child']=[];
                }
                $brr['xj_price']=$xj_price;
                $brr['desc']=$value['desc'];
                $zj_price+=$xj_price;
                $arr[]=$brr;
            }
        }
        $rate=[];
        $rate_list=Loader::model("base/budget/DefaultRateCopy")->getList(["project_id"=>$guid,'book_number'=>$book_number]);
        $sum_all=0; //合计费用
        $rate[]=[
            'name'=> '直接费用',
            'value'=>$zj_price,
            'unit'=>'元',
            'rate_value'=>'',
            'desc'=>'工程施工实际总费用',
        ];
        $sum_all+=$zj_price;
        if (!empty($rate_list)){
            foreach ($rate_list as $value){
                $a=$value['rate_default_type'];
                $p=0;
                switch ($a){
                    case '*':
                        $sum_all+=$zj_price*$value['rate_default_value'];
                        $p=$zj_price*$value['rate_default_value'];
                        break;
                    case '-':
                        $sum_all+=$zj_price-$value['rate_default_value'];
                        $p=$zj_price-$value['rate_default_value'];
                        break;
                    case '/':
                        $sum_all+=$zj_price/$value['rate_default_value'];
                        $p=$zj_price/$value['rate_default_value'];
                        break;
                    case '+':
                        $sum_all+=$zj_price+$value['rate_default_value'];
                        $p=$zj_price+$value['rate_default_value'];
                        break;
                    default:
                        break;
                }
                $rate[]=[
                    'name'=> $value['rate_default_name'],
                    'value'=>$p,
                    'unit'=>'元',
                    'rate_value'=>$value['rate_default_value'],
                    'desc'=>$value['desc'],
                ];
            }
        }
        $rate[]=[
            'name'=>'总造价',
            'value'=>$sum_all,
            'unit'=>'元',
            'rate_value'=>'',
            'desc'=>'工程预算总造价',
        ];
        return [
            'body'=>$arr,
            'foot'=>$rate,
        ];
    }







    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status']='1';
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }
    public function getCont($map=[]){   //计数
        return $this->where($map)->count();
    }
    public function saveBudgetData($data){
        $pro_id=$data['project_id']; //项目id
        $tem_id=$data['old_guid']; //为了查找该空间下的子类
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $guid=$this->guid;
            $list=Loader::model('base/MaterialTemplateBudgetAccess')->getList(['budget_id'=>$tem_id]);
            if (!empty($list)){
                foreach ($list as $value){
                    $dataItem=$this->setCacheMaterialData()['listEdit'];
                    if(isset($dataItem[$value['material_id']])){
                        $obj=$dataItem[$value['material_id']];
                        $arr['uuid']=$data['uuid'];
                        $arr['company_id']=$data['company_id'];
                        $arr['category_id']=$obj['category_id'];
                        $arr['project_id']=$pro_id;
                        $arr['pid']=$guid;
                        $arr['old_guid']=$obj['guid'];
                        $arr['name']=$obj['name'];
                        $arr['unit']=$obj['unit'];
                        $price=$this->countPrice($obj);
                        if ($price['code']=="1001"){
                            $arr['price']=$price['data']; //总单价
                        }
                        $arr['xuhao']=$obj['xuhao'];
                        $arr['zc_dj']=$obj['unit_price'];  //主材单价
                        $arr['zc_mlr']=$obj['unit_profit']; //主材毛利润
                        $arr['fc_dj']=$obj['auxiliary_unit'];  //辅材单价
                        $arr['fc_mlr']=$obj['auxiliary_profit']; //辅材毛利润
                        $arr['rg_dj']=$obj['artificial_price'];  //人工单价
                        $arr['rg_mlr']=$obj['artificial_profit']; //人工毛利润
                        $arr['sh_xs']=$obj['loss_coefficient'];
                        $arr['jx_xs']=$obj['mechanical_coefficient'];
                        $arr['desc']=$obj['desc'];
                        $arr['type']=1;
                        $this->data($arr)->isUpdate(false)->save();
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
    public function savaNumber($data,$guid){
        try{
            $this->startTrans();
            $this->data(['number'=>$data['number']])->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function savaDesc($data,$guid){
        try{
            $this->startTrans();
            $this->data(['desc'=>$data['desc']])->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delSpaceData($data){
        if(empty($data)) return self::showReturnCodeWithOutData(1003,'没有接收到需要删除的数据');
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>["in",array_values($data)]])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();

            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function saveSpaceMaterial($data){
        if (empty($data) || empty($data['pid']) ||empty($data['type']) || empty($data['chi_id']) ||empty($data['project_id'])  )  return self::showReturnCodeWithOutData(1003,'没有接收到需要添加的数据');
        $id=$data['chi_id'];
        $pro_id=$data['project_id'];
        $guid=$data['pid'];
        try{
            if(is_array($id)){
                $this->startTrans();
                foreach ($id as $value){
                    if ($data['type']=="1"){
                        $dataItem=$this->setCacheMaterialData()['listEdit'];
                        $cont=$this->getCont(['pid'=>$guid,'project_id'=>$pro_id,'old_guid'=>$value,'status'=>1]);
                        if ($cont>=1){
                            $this->where(['old_guid'=>$value])->delete();
                        }

                        if(isset($dataItem[$value])){
                            $obj=$dataItem[$value];
                            $arr['uuid']=$data['uuid'];
                            $arr['company_id']=$data['company_id'];
                            $arr['project_id']=$pro_id;
                            $arr['pid']=$guid;
                            $arr['old_guid']=$obj['guid'];
                            $arr['name']=$obj['name'];
                            $arr['unit']=$obj['unit'];
                            $price=$this->countPrice($obj);
                            if ($price['code']=="1001"){
                                $arr['price']=$price['data']; //总单价
                            }
                            $arr['xuhao']=$obj['xuhao'];
                            $arr['zc_dj']=$obj['unit_price'];  //主材单价
                            $arr['zc_mlr']=$obj['unit_profit']; //主材毛利润
                            $arr['fc_dj']=$obj['auxiliary_unit'];  //辅材单价
                            $arr['fc_mlr']=$obj['auxiliary_profit']; //辅材毛利润
                            $arr['rg_dj']=$obj['artificial_price'];  //人工单价
                            $arr['rg_mlr']=$obj['artificial_profit']; //人工毛利润
                            $arr['sh_xs']=$obj['loss_coefficient'];
                            $arr['jx_xs']=$obj['mechanical_coefficient'];
                            $arr['desc']=$obj['desc'];
                            $arr['type']=1;
                            $this->data($arr)->isUpdate(false)->save();
                        }
                    }else if($data['type']=="2"){
                        $material=$this->setCacheMaterialData()['material'];
                        $cont=$this->getCont(['pid'=>$guid,'project_id'=>$pro_id,'old_guid'=>$value,'status'=>1]);
                        if ($cont>=1){
                            $this->where(['old_guid'=>$value])->delete();
                        }
                        if (isset($material[$value])){
                            $obj=$material[$value];
                            $arr['uuid']=$data['uuid'];
                            $arr['company_id']=$data['company_id'];
                            $arr['project_id']=$pro_id;
                            $arr['pid']=$guid;
                            $arr['old_guid']=$obj['guid'];
                            $arr['name']=$obj['material_name'].' '.$obj['specifications'].$obj['material_version'].' ';
                            $arr['unit']=$obj['unit_name'];
                            $arr['price']=$obj['material_price']; //总单价
                            $arr['xuhao']=isset($obj['xuhao']) ? $obj['xuhao']: $obj['id'];
                            $arr['zc_dj']=$obj['floor_price'];  //主材单价
                            $arr['zc_mlr']=''; //主材毛利润
                            $arr['fc_dj']='';  //辅材单价
                            $arr['fc_mlr']=''; //辅材毛利润
                            $arr['rg_dj']='';  //人工单价
                            $arr['rg_mlr']=''; //人工毛利润
                            $arr['sh_xs']='';
                            $arr['jx_xs']='';
                            $arr['desc']=$obj['material_desc'];
                            $arr['type']=2;
                            $this->data($arr)->isUpdate(false)->save();
                            $obj['de_pro_id']=$this->guid;
                            $obj['project_id']=$pro_id;
                            $obj['old_id']=$obj['guid'];
                            unset($obj['id']);
                            unset($obj['guid']);
                            unset($obj['update_time']);
                            unset($obj['create_time']);

                            BudgetDefaultMaterial::create($obj->toArray());
                        }
                    }else{
                        return self::showReturnCodeWithOutData(1003,'数据类型无法判断');
                    }
                }
                $this->commit();
            }
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function copyTem($data,$user_name,$user_tel,$uuid){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['id']) || !isset($data['pro_id'])) return self::showReturnCodeWithOutData(1003);
        $model=new SpaceTemplateUser();
        $list=$model->getList(['space_id'=>$data['id'],'pid'=>0]);
        try{
            $this->startTrans();
            $this->where(['project_id'=>$data['pro_id']])->delete();
            if (!empty($list)){
                foreach ($list as $value){
                    $list_n=$model->getList(['space_id'=>$data['id'],'pid'=>$value['guid']]);
                    $value['project_id']=$data['pro_id'];
                    $value['user_name']=$user_name;
                    $value['user_tel']=$user_tel;
                    $value['uuid']=$uuid;
                    unset($value['id']);
                    unset($value['guid']);
                    unset($value['space_id']);
                    $value=$value->toArray();
                    $this->data($value)->isUpdate(false)->save();
                    $guid=$this->guid;
                    if (!empty($list_n)){
                        foreach ($list_n as $item){
                            $item['project_id']=$data['pro_id'];
                            $item['user_name']=$user_name;
                            $item['user_tel']=$user_tel;
                            $item['uuid']=$uuid;
                            $item['pid']=$guid;
                            unset($item['id']);
                            unset($item['guid']);
                            unset($item['space_id']);
                            $item=$item->toArray();
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
    public function copyData($data){
        $value=Loader::model("base/budget/SpaceTemplateUser")->getInfoByGuId($data['guid']);
        try{
            $this->startTrans();
            if (!empty($value)){
                $value['project_id']=$data['pro_id'];
                $value['company_id']=$data['company_id'];
                $value['uuid']=$data['uuid'];
                $value['user_name']=$data['user_name'];
                $value['user_tel']=$data['user_tel'];
                unset($value['id']);
                unset($value['guid']);
                unset($value['space_id']);
                $value=$value->toArray();
                $this->data($value)->isUpdate(false)->save();
                $guid=$this->guid;
                $list=Loader::model("base/budget/SpaceTemplateUser")->getList(['pid'=>$data['guid']]);
                if (!empty($list)){
                    foreach ($list as $item){
                        $item['project_id']=$data['pro_id'];
                        $item['company_id']=$data['company_id'];
                        $item['uuid']=$data['uuid'];
                        $item['user_name']=$data['user_name'];
                        $item['user_tel']=$data['user_tel'];
                        $item['pid']=$guid;
                        unset($item['id']);
                        unset($item['guid']);
                        unset($item['space_id']);
                        $item=$item->toArray();
                        $this->data($item)->isUpdate(false)->save();
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
    public function copyDataMore($data){
        $t=Loader::model("base/budget/SpaceTemplateUser")->getInfoByGuId($data['arr'][0]);
        if (!empty($t)){
            $value=Loader::model("base/budget/SpaceTemplateUser")->getInfoByGuId($t['pid']);
        }
        try{
            $this->startTrans();
            if (!empty($value)){
                $value['project_id']=$data['pro_id'];
                $value['company_id']=$data['company_id'];
                $value['uuid']=$data['uuid'];
                $value['user_name']=$data['user_name'];
                $value['user_tel']=$data['user_tel'];
                unset($value['id']);
                unset($value['guid']);
                unset($value['space_id']);
                $value=$value->toArray();
                $this->data($value)->isUpdate(false)->save();
                $guid=$this->guid;
                foreach ($data['arr'] as $item){
                    $item=Loader::model("base/budget/SpaceTemplateUser")->getInfoByGuId($item);
                    if (!empty($item)){
                        $item['project_id']=$data['pro_id'];
                        $item['company_id']=$data['company_id'];
                        $item['uuid']=$data['uuid'];
                        $item['user_name']=$data['user_name'];
                        $item['user_tel']=$data['user_tel'];
                        $item['pid']=$guid;
                        unset($item['id']);
                        unset($item['guid']);
                        unset($item['space_id']);
                        $item=$item->toArray();
                        $this->data($item)->isUpdate(false)->save();
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