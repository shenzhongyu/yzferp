<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\build;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\budget\DefaultProjectCopy;
use app\base\model\budget\DefaultRateCopy;
use app\base\model\MaterialDataStyle;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class BuildContractRint extends BaseHash
{

    protected $table = "mk_build_contract_rint";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;
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
    public function saveBuild($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data(['status'=>2])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number']])->save();
            $this->data($data)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
    public function editCheck($data,$guid){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['examine_status'])) return self::showReturnCodeWithOutData(1003);
        if (!in_array($data['examine_status'],[-1,1])) return self::showReturnCodeWithOutData(1003);
        $obj=$this->where(['guid'=>$guid])->find();
        $model=new Project();
        try{
            $this->startTrans();
            $re=$this->data($data)->isUpdate(true,['guid'=>$guid,'examine_status'=>0])->save();
            if ($re!=1){
                $this->throwException("该审核信息已发生变动，请刷新数据");
            }else{
                if ($data['examine_status']=="1"){
                    $model->data(['feedback_stage'=>3])->isUpdate(true,['guid'=>$obj['project_id']])->save();
                }
            }

            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function returnBuContract($project_id,$book_number){
        $list=$this->where(['project_id'=>$project_id,'book_number'=>$book_number,'status'=>1])->find();
        $num_price=0; //直接费
        $sum_price=0; //总费用
        $arr=[];
        $arr['rows']=[];
        $model_design=new DefaultProjectCopy();
        $model_rate=new DefaultRateCopy();
        if (!empty($list)){
            $list=is_object($list)?$list->toArray():$list;
            if(!empty($list['rate_id'])){
                $rate_arr=explode(',',$list['rate_id']);
            }else{
                $rate_arr=[];
            }

            $category_arr=explode(',',$list['category_id']);
            $model=new MaterialDataStyle();
            if (!empty($category_arr)){
                foreach ($category_arr as $value){
                    $project_list=$model_design->getList(['project_id'=>$project_id,'book_number'=>$book_number,'category_id'=>$value,'pid'=>['<>','0']]);
                    $price_control=0;
                    $category_name=$model->getInfoByGuId($value);
                    //$category_name=MaterialDataStyle::quickGet($value);
                    if (!empty($project_list)){
                        foreach ($project_list as $key=>$item){
                            $price_control+=($item['zc_dj']+$item['fc_dj']+$item['rg_dj'])*$item['number'];
                            $item['price_sum']=number_format(($item['zc_dj']+$item['fc_dj']+$item['rg_dj'])*$item['number'],2);
                            $item['_parentId']=$value;
                            $arr['rows'][]=$item;
                        }
                    }
                    $arr['rows'][]=[
                        'guid'=>$value,
                        'name'=>$category_name['categories_name'],
                        'desc'=>$category_name['categories_desc'],
                        'price_sum'=>number_format($price_control,2),
                    ];
                    $num_price+=$price_control;
                }
            }
            $sum_price=$num_price;
            if (!empty($rate_arr)){
                foreach ($rate_arr as $value){
                    $map=[
                        'guid'=>$value,
                        'project_id'=>$project_id,
                        'book_number'=>$book_number,
                        'status'=>1,
                    ];
                    $rate_obj=$model_rate->where($map)->find();
                    $arr['footer'][]=[
                        'guid'=>$value,
                        'name'=>$rate_obj['rate_default_name'],
                        'desc'=>$rate_obj['desc'],
                        'control_price'=>$rate_obj['rate_default_value'],
                        'price_sum'=>number_format($num_price*$rate_obj['rate_default_value'],2),
                    ];
                    $sum_price+=$num_price*$rate_obj['rate_default_value'];
                }
            }
        }
        $arr['footer'][]=[
            'guid'=>'0',
            'name'=>'合计',
            'desc'=>'',
            'price_sum'=>number_format($sum_price,1),
        ];
        $arr['total']=count($arr['rows']);
        return $arr;
    }
    public function returnBuContractBook($project_id,$book_number){
        $list=$this->where(['project_id'=>$project_id,'book_number'=>$book_number,'status'=>1])->find();
        $num_price=0; //直接费
        $sum_price=0; //总费用
        $arr=[];
        $arr['rows']=[];
        $model_design=new DefaultProjectCopy();
        $model_rate=new DefaultRateCopy();

        if (!empty($list)){
            $list=is_object($list)?$list->toArray():$list;
            $rate_arr=explode(',',$list['rate_id']);
            $category_arr=explode(',',$list['category_id']);
            $model=new MaterialDataStyle();
            if (!empty($category_arr)){
                foreach ($category_arr as $k=>$value){
                    $project_list=$model_design->getList(['project_id'=>$project_id,'book_number'=>$book_number,'category_id'=>$value,'pid'=>['<>','0']]);
                    $price_control=0;
                    $category_name=$model->getInfoByGuId($value);
                    //$category_name=MaterialDataStyle::quickGet($value);
                    if (!empty($project_list)){
                        foreach ($project_list as $key=>$item){
                            $price_control+=($item['zc_dj']+$item['fc_dj']+$item['rg_dj'])*$item['number'];
                            $item['control_price']=$item['zc_dj']+$item['fc_dj']+$item['rg_dj'];
                            $item['price_sum']=number_format(($item['zc_dj']+$item['fc_dj']+$item['rg_dj'])*$item['number'],2);
                            $arr['rows'][$k]["child"][]=$item;
                        }
                    }else{
                        $arr['rows'][$k]['child']=[];
                    }
                    $arr['rows'][$k]['con'][]=[
                        'guid'=>$value,
                        'name'=>$category_name['categories_name'],
                        'desc'=>$category_name['categories_desc'],
                        'price_sum'=>$price_control,
                    ];
                    $num_price+=$price_control;
                }
            }
            $sum_price=$num_price;
            if (!empty($rate_arr)){
                foreach ($rate_arr as $value){
                    $map=[
                        'guid'=>$value,
                        'project_id'=>$project_id,
                        'book_number'=>$book_number,
                        'status'=>1,
                    ];
                    $rate_obj=$model_rate->where($map)->find();
                    $arr['footer'][]=[
                        'guid'=>$value,
                        'name'=>$rate_obj['rate_default_name'],
                        'desc'=>$rate_obj['desc'],
                        'control_price'=>$rate_obj['rate_default_value'],
                        'price_sum'=>$num_price*$rate_obj['rate_default_value'],
                    ];
                    $sum_price+=$num_price*$rate_obj['rate_default_value'];
                }
            }
        }
        $arr['footer'][]=[
            'guid'=>'0',
            'name'=>'合计',
            'desc'=>'',
            'control_price'=>'',
            'price_sum'=>$sum_price,
        ];
        $arr['print_number']=$list['code'];
        $arr['total']=count($arr['rows']);
        return $arr;
    }

}