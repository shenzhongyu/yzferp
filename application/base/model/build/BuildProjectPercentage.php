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

class BuildProjectPercentage extends BaseHash
{

    protected $table = "mk_build_project_percentage";
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
    public function saveValue($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        $model=new DefaultProjectCopy();
        $map=[
            'project_id'=>$data['project_id'],
            'book_number'=>$data['book_number'],
            'category_id'=>$data['category_id'],
            'pid'=>['<>','0'],
        ];
        $list=$model->getList($map);
        $price=0;
        if (!empty($list)){
            foreach ($list as $value){
                 $price+=($value['zc_dj']+$value['fc_dj']+$value['rg_dj'])*$value['number'];
            }
        }
        $data['category_price']=$price;
        $m=[
            'project_id'=>$data['project_id'],
            'book_number'=>$data['book_number'],
            'category_id'=>$data['category_id'],
            'type'=>1,
        ];

        $obj=Loader::model('base/build/BuildSupervisionTem')->where($m)->find();
        $data['cetegory_uuid']=empty($obj)?'':$obj['user_id'];
        try{
            $this->startTrans();
            $this->data(['status'=>2])->isUpdate(true,['project_id'=>$data['project_id'],'book_number'=>$data['book_number'],'category_id'=>$data['category_id']])->save();
            $this->data($data)->isUpdate(false)->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }
}