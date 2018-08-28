<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


use app\base\model\budget\BudgetCopySpace;
use app\base\model\budget\PrintRequest;
use app\base\model\budget\SpaceTemplateUser;
use mikkle\tp_wechat\support\Db;
use think\Exception;
use think\Loader;
use think\Log;

class BudgetDefaultProject extends BaseDesign
{
    protected $table = "mk_budget_default_project";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = false;

    protected function setGuidAttr($value, $data)
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('guid',$mk_guid)->count()==1);
        return $mk_guid ;
    }
    public function getUserName($guid){
        $list=$this->where(['project_id'=>$guid,'status'=>1,'pid'=>0])->field("uuid,user_name,user_tel")->find();
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
    public function reviseBudge($data){
        if (empty($data)) return self::showReturnCode(1003);
        if (!isset($data['guid']) || !isset($data['id'])  ) return self::showReturnCode(1003);

        if (empty($data['pid'])){
            $arr=[
                'name'=>$data['name'],
                'desc'=>$data['desc'],
            ];
        }else{
            $price=$data['zc_dj']/(1-$data['zc_mlr'])+$data['zc_dj']/(1-$data['zc_mlr'])+$data['zc_dj']/(1-$data['zc_mlr']);
            $arr=[
                'name'=>$data['name'],
                'desc'=>$data['desc'],
                'number'=>$data['number'],
                'zc_dj'=>$data['zc_dj'],
                'fc_dj'=>$data['fc_dj'],
                'rg_dj'=>$data['rg_dj'],
                'zc_mlr'=>$data['zc_mlr'],
                'fc_mlr'=>$data['fc_mlr'],
                'rg_mlr'=>$data['rg_mlr'],
                'jx_xs'=>$data['jx_xs'],
                'sh_xs'=>$data['sh_xs'],
                'price'=>$price
            ];
        }
        try{
            $this->startTrans();
            $this->data($arr)->isUpdate(true,['guid'=>$data['guid']])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }

    }

    public function saveChangeDataInfo($data){
        if (empty($data)) return self::showReturnCode(1003);
        $req=$this->where(['guid'=>$data['guid'],'status'=>1])->find();
        if (empty($req)) return self::showReturnCodeWithOutData(1003);

        if (!$this->checkProjectDesign($req['project_id'])){
            return self::showReturnCodeWithOutData(1020,'该项目已申请预算打印，无法修改');
        }


        $arr=[
            'name'=>$data['name'],
            'desc'=>$data['desc'],
        ];
        try{
            $this->startTrans();
            $this->data($arr)->isUpdate(true,['guid'=>$data['guid']])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }




    public function getDataList($guid){
        $pro=$this->getList(['project_id'=>$guid,'pid'=>0]);
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
                $list=$this->getList(['project_id'=>$guid,'pid'=>$value['guid']]);
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
        $rate_list=Loader::model("base/BudgetDefaultRate")->getList(["project_id"=>$guid]);
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

    public function getDataList_copy($guid){
        $pro=$this->getList(['project_id'=>$guid,'pid'=>0]);
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
                $list=$this->getList(['project_id'=>$guid,'pid'=>$value['guid']]);
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
        $rate_list=Loader::model("base/BudgetDefaultRate")->getList(["project_id"=>$guid]);
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

        $re=$this->checkProjectDesign($pro_id);
        if(!$re){
            return self::showReturnCodeWithOutData(1003,'该项目已申请打印预算，无法修改');
        }


        try{
            $this->startTrans();
            $this->data($data)->isUpdate(false)->save();
            $guid=$this->guid;
            $list=Loader::model('base/MaterialTemplateBudgetAccess')->getList(['budget_id'=>$tem_id]);
            if (!empty($list)){
                $brr=[];
                foreach ($list as $value){
//                    $dataItem=$this->setCacheMaterialData()['listEdit'];
                   //  $dataItem=MaterialListEdit::quickGet($value['material_id']);
                   $dataItem=Loader::model('base/MaterialListEdit')->where(['guid'=>$value['material_id']])->find();
                    if(!empty($dataItem)){
                        $obj=$dataItem;
                        $arr['uuid']=$data['uuid'];
                        $arr['company_id']=$data['company_id'];
                        $arr['project_id']=$pro_id;
                        $arr['category_id']=$obj['category_id'];
                        $arr['pid']=$guid;
                        $arr['old_guid']=$obj['guid'];
                        $arr['name']=$obj['name'];
                        $arr['unit']=$obj['unit'];
                        $price=$this->countPrice($obj);
                        if ($price['code']=="1001"){
                            $arr['price']=$price['data']; //总单价
                        }
                        $arr['xuhao']=$obj['xuhao'];
                        $arr['control_price']=$obj['unit_price']+$obj['auxiliary_unit']+$obj['artificial_price'];//内控单价
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
                        $brr[]=$arr;
                    }
                }

                $this->saveAll($brr);
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            Log::error($e);
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function savaNumber($data,$guid){
        $req=$this->where(['guid'=>$guid,'status'=>1])->find();
        if (empty($req)) return self::showReturnCodeWithOutData(1003);

        if (!$this->checkProjectDesign($req['project_id'])){
            return self::showReturnCodeWithOutData(1020,'该项目已申请预算打印，无法修改');
        }

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
        $req=$this->where(['guid'=>$guid,'status'=>1])->find();
        if (empty($req)) return self::showReturnCodeWithOutData(1003);

        if (!$this->checkProjectDesign($req['project_id'])){
            return self::showReturnCodeWithOutData(1020,'该项目已申请预算打印，无法修改');
        }


        try{
            $this->startTrans();
            $this->data(['desc'=>$data['desc'],'name'=>$data['name']])->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function delSpaceData($data){
        if(empty($data)) return self::showReturnCodeWithOutData(1003,'没有接收到需要删除的数据');

        $req=$this->where(['guid'=>$data[0],'status'=>1])->find();
        if (empty($req)) return self::showReturnCodeWithOutData(1003);

        if (!$this->checkProjectDesign($req['project_id'])){
            return self::showReturnCodeWithOutData(1020,'该项目已申请预算打印，无法修改');
        }

        $guid_array=$this->where(["pid"=>["in",array_values($data)]])->column("guid");
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>["in",array_merge($guid_array,array_values($data))]])->save();


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

        $re=$this->checkProjectDesign($pro_id);
        if(!$re){
            return self::showReturnCodeWithOutData(1003,'该项目已申请打印预算，无法修改');
        }
        $model=new MaterialListEdit();
        try{
            $this->startTrans();
            if(is_array($id)){
                $brr=[];
                foreach ($id as $value){

                    $dataItem=$model->getInfoByGuId($value);
//                        $dataItem=Loader::model('base/MaterialListEdit')->where(['guid'=>$value['material_id']])->find();
                    if(!empty($dataItem)){
                        $obj=$dataItem;
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
                        $arr['category_id']=$obj['category_id'];
                        $arr['control_price']=$obj['unit_price']+$obj['auxiliary_unit']+$obj['artificial_price'];
                        $arr['desc']=$obj['desc'];
                        $arr['type']=$obj['type'];

                        $brr[]=$arr;
                    }

                }
                $this->saveAll($brr);
            }
            $this->commit();
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

        $re=$this->checkProjectDesign($data['pro_id']);
        if(!$re){
            return self::showReturnCodeWithOutData(1003,'该项目已申请打印预算，无法修改');
        }

        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['project_id'=>$data['pro_id']])->save();
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

        $re=$this->checkProjectDesign($data['pro_id']);
        if(!$re){
            return self::showReturnCodeWithOutData(1003,'该项目已申请打印预算，无法修改');
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
                $list=Loader::model("base/budget/SpaceTemplateUser")->getList(['pid'=>$data['guid']]);
                $brr=[];
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
                        $brr[]=$item;
                    }
                }
                $this->saveAll($brr);
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
                $brr=[];
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
                        $brr[]=$item;
                    }
                }
                $this->saveAll($brr);
            }
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function getSpaceData($data,$name,$mobile,$uuid){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['id'])) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['pro_id'])) return self::showReturnCodeWithOutData(1003);

        $re=$this->checkProjectDesign($data['pro_id']);
        if(!$re){
            return self::showReturnCodeWithOutData(1003,'该项目已申请打印预算，无法修改');
        }

        $model=new BudgetCopySpace();
        $obj=$model->where(['guid'=>$data['id'],'status'=>1])->find();
        if (empty($obj)) return self::showReturnCode(1003,'空间不存在');
        $list=$model->getList(['pid'=>$data['id']]);
        $obj=is_object($obj) ? $obj->toArray():$obj;
        $obj['project_id']=$data['pro_id'];
        $obj['uuid']=$uuid;
        $obj['user_name']=$name;
        $obj['user_tel']=$mobile;
        unset($obj['guid']);
        unset($obj['id']);
        unset($obj['space_type']);
        try{
            $this->startTrans();
            $this->data($obj)->isUpdate(false)->save();
            $guid=$this->guid;
            $brr=[];
            if (!empty($list)){
                foreach ($list as $value){
                    $value=is_object($value) ? $value->toArray():$value;
                    $value['project_id']=$data['pro_id'];
                    $value['uuid']=$uuid;
                    $value['user_name']=$name;
                    $value['user_tel']=$mobile;
                    $value['pid']=$guid;
                    unset($value['guid']);
                    unset($value['id']);
                    unset($value['space_type']);
                    $brr[]=$value;

                }
            }
            $this->saveAll($brr);
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }


    public function copyS($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['spa_id'])) return self::showReturnCodeWithOutData(1003);
        if (!is_array($data['spa_id'])) return self::showReturnCodeWithOutData(1003);

        $req=$this->where(['guid'=>$data['spa_id'][0],'status'=>1])->find();
        if (empty($req)) return self::showReturnCodeWithOutData(1003);

        if (!$this->checkProjectDesign($req['project_id'])){
            return self::showReturnCodeWithOutData(1020,'该项目已申请预算打印，无法修改');
        }

        try{
            $this->startTrans();
            foreach ($data['spa_id'] as $item){
                $obj=$this->getInfoByGuId($item);
                $obj=is_object($obj)?$obj->toArray():$obj;
                unset($obj['guid']);
                unset($obj['id']);
                $this->data($obj)->isUpdate(false)->save();
                $guid=$this->guid;
                $list=$this->getList(['pid'=>$item]);
                $brr=[];
                if (!empty($list)){
                    foreach ($list as $value){
                        $value=is_object($value) ? $value->toArray():$value;
                        $value['pid']=$guid;
                        unset($value['guid']);
                        unset($value['id']);
                        $brr[]=$value;
                    }
                }
                $this->saveAll($brr);
            }
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