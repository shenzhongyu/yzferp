<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


class OfficeNotice extends BaseHash
{
    protected $table = "mk_office_notice";
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
        return $this->where($map)->field($field)->order(["create_time"=>"desc"])->select();

    }

    public function getListByGuId($guid,$map=[],$field=true){
        $map['guid']=$guid;
        $map['status']=1;
        return $this->where($map)->field($field)->order(["create_time"=>"desc"])->find();
    }

    public function saveNotice($data){
        try{
            $this->startTrans();

            $info = isset($data["info"])?$data["info"] : null;
            unset($data["info"]);
            $this->data($data)->isUpdate(false)->save();
            $new_order = $this->guid;
            if (empty($new_order)) throw new \Exception("通知公告发布失败1");
            if( $data["type"]=="2" && !empty($info)){
                foreach($info as $array){
                    $save_data = [
                        "notice_id"=>$new_order,
                        "department_id"=>$array,
                    ];
                    $this->table('mk_office_notice_access')->insert($save_data);
                }
            }
            $this->commit();
            return ['code' => '1001', 'msg' => '通知公告发布成功'];
        }catch (\Exception $e){

            $this->rollback();
            return ['code'=>'1008','msg'=>$e->getMessage()];
        }

    }


}