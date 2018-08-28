<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/17
 * Time: 15:51
 */

namespace app\base\model\personal;


use app\base\model\Base;
use app\base\model\BaseHash;
use app\base\model\budget\DefaultProjectCopy;
use app\base\model\budget\DefaultRateCopy;
use app\base\model\MaterialDataStyle;
use app\base\model\PersonnelUser;
use app\base\model\Project;
use think\Db;
use think\Exception;
use think\Loader;

class UserOpus extends BaseHash
{

    protected $table = "mk_excellent_opus";
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

    public function getTeamData($map = [],$field=false,$page='1',$limit="8",$order=['id'=>'desc']){
        if (empty($map)){
            $map = ['status'=>1];
        }else{
            $map['status']=1;
        }
        if (empty($field)){
            $field = '';
        }
        $total = $this->where($map)->count();
        $rows = $this->field($field)->page($page)->limit($limit)->order($order)->where($map)->select();
        $model_user=new PersonnelUser();
        if (!empty($rows)){
            foreach ($rows as $value){
                if (isset($value['uuid'])){
                    $user=$model_user->getInfoByGuId($value['uuid']);
                    //$user=PersonnelUser::quickGet($value['uuid']);
                    $value['uuid']=empty($user)?'':$user['name'];
                }
            }
        }
        return ['total'=>$total,'rows'=>$rows];
    }
    public function saveBuild($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
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
    public function editCheck($data,$guid){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (empty($guid)) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data($data)->isUpdate(true,['guid'=>$guid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

    public function delUserOpus($data){
        if (empty($data)) return self::showReturnCodeWithOutData(1003);
        if (!isset($data['arr'])) return self::showReturnCodeWithOutData(1003);
        try{
            $this->startTrans();
            $this->data(['status'=>-1])->isUpdate(true,['guid'=>['in',$data['arr']],'uuid'=>$this->uuid])->save();
            $this->commit();
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $this->rollback();
            return self::showReturnCodeWithOutData(1008,$e->getMessage());
        }
    }

}