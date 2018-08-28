<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


use app\base\controller\Curl;
use app\erp\config\FieldValue;
use think\Config;
use think\Db;
use think\Exception;
use think\Log;
use think\Request;

class PersonnelUser extends BaseHash
{

    protected $table = "mk_personnel_user";
    protected $insert = ['uuid','password'];
    protected $readonly = ['uuid'];
    protected $hashKey = "uuid";
    protected $autoWriteTimestamp = true;

    public function setStatusAttr($value){
        if(empty($value) && $value!=0 ) return 1;
        if(!in_array($value,[-1,0,1])) return 0;
        return $value;
    }
    protected function setUuidAttr()
    {
        do {
            $mk_guid= $this->create_uuid();
        } while ($this->where('uuid',$mk_guid)->count()==1);
        return $mk_guid ;
    }

    protected function setPasswordAttr($value){
        if (empty($value)) $value = '123123';
        if (strlen($value)==32 ||strlen($value)>30 ){
            return $value ;
        }
        return ModelCode::getMd5Password($value) ;
    }

    protected function setBindingCodeAttr(){
        try{
            $url =Config::get("wechat.wechat_get_binding_url");
            $data=[
                "domain"=>Request::instance()->host(),
            ];
            $binding_code = json_decode(Curl::curlPost($url,$data));
            if (!is_object($binding_code)){
                $binding_code =  json_decode(Curl::curlPost($url,$data));
            }
            if(is_object($binding_code)){
                if(isset($binding_code->data->binding_code)){
                    return $binding_code->data->binding_code;
                }
            }
            $this->throwException("获取失败");
        }catch (Exception $e){
            Log::error($e->getMessage());
            return "";
        }
    }

    public function infoByOpenId($open_id,$field = true){
        if (is_numeric($open_id)){
            $map['id']=$open_id;
        }else{
            $map['we_openid']=$open_id;
        }
        $info = $this->field($field)->where($map)->find();
        return  $info;
    }
     public function checkMobileExist($mobile=''){
         $count =$this->where(['mobile'=>$mobile])->count();

         if (!is_numeric($count))return false;
         return $count > 0 ? true : false ;
    }

    public function checkLogin($data){
        $username = $data['username'];
        $password = $data['password'];
        $map=['username'=>$username,'password'=>ModelCode::getMd5Password($password)];
        $map['status']=1;
        
        $data = $this->where($map)->find();

        if ($data){
            return self::showReturnCode(1001,$data);
        }else{
            return self::showReturnCodeWithOutData(1010);
        }
    }



    public function setPersonnelPassword($uuid,$password){
        return $this->save(['password'=>$password],['uuid'=>$uuid]);
    }


    /**
     * 根据手机号码绑定Openid
     * Power by Mikkle
     * QQ:776329498
     * @param $open_id
     * @param $mobile
     * @return bool|\Exception
     */
    public function bindingOpenIdByMobile($open_id,$mobile,$username){

        try{
            $this->startTrans();
           if( !$this->allowField(['we_openid'])->save(['we_openid'=>$open_id],['mobile'=>$mobile,'username'=>$username])==1){
               throw new Exception('更新的数量不是唯一,更新被取消');
           }
            $this->commit();
            return true;
        }catch (Exception $e){
            $this->rollback();
            return $e;
        }
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
//        if (!empty($rows)){
//            foreach ($rows as $value){
//                $jobs=PersonnelJobs::quickGet($value['jobs_id']);
//                $value['jobs_name']=empty($jobs)?'':$jobs['jobs_name'];
//            }
//        }
        return ['total'=>$total,'rows'=>$rows];
    }


    //生成绑定码
    public function getBanDingCode($data){
        if (empty($data)) return self::showReturnCode(1003);
        if (!isset($data['id'])) return self::showReturnCode(1003);
        $obj = $this->where(['uuid' => $data['id']])->find();
        if (empty($obj)) return self::showReturnCode(1003, [], '该用户不存在');

        if (!empty($obj['banding_code'])) {
            return self::showReturnCode(1003, [], '该用户已有绑定码');
        }
        try {
            $binding_code = "";
            $url = Config::get("wechat.wechat_get_binding_url");
            $push_data = [
                "domain" => Request::instance()->host(),
            ];
            $binding_code = json_decode(Curl::curlPost($url, $push_data));
            if (!is_object($binding_code)) {
                $binding_code = json_decode(Curl::curlPost($url, $push_data));
            }
            if (is_object($binding_code)) {
                if (isset($binding_code->data->binding_code)) {
                    $banding_code = $binding_code->data->binding_code;
                }
            }
            if (empty($banding_code)) {
                throw new Exception("获取绑定码失败");
            }
            $this->data(['binding_code' => $banding_code])->isUpdate(true, ['uuid' => $data['id']])->save();
            return self::showReturnCodeWithOutData(1001);
        } catch (Exception $e) {
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }
    //重置绑定码
    public function resetBanDingCode($data){
        if (empty($data)) return self::showReturnCode(1003);
        if (!isset($data['id'])) return self::showReturnCode(1003);
        $obj = $this->where(['uuid' => $data['id']])->find();
        if (empty($obj)) return self::showReturnCode(1003, [], '该用户不存在');
        try {
            $we_openid=$obj["we_openid"];
            $this->data(['binding_code' =>'','we_openid'=>''])->isUpdate(true, ['uuid' => $data['id']])->save();

            $url = Config::get("wechat.wechat_remove_fans_domain_url");
            $push_data = [
                "open_id" =>  "$we_openid",
            ];
            $reset_code = json_decode(Curl::curlPost($url, $push_data));
            if (!is_object($reset_code)) {
                $reset_code = json_decode(Curl::curlPost($url, $push_data));
            }
            return self::showReturnCodeWithOutData(1001);
        } catch (Exception $e) {
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }

    /**
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $open_id
     * @param $binding_code
     * @return bool|\Exception|Exception
     */
    public function bindingOpenIdByBindingCode($open_id = "", $binding_code = "")
    {
        try {
            if (empty($open_id) || empty($binding_code)) {
                throw new Exception('参数出错');
            }
            if ($this->where(["we_openid"=>$open_id])->count()>0){
                throw new Exception("用户已经绑定过微信,请先联系管理员解绑!");
            }
            if ($this->data(['we_openid' => $open_id,'binding_code'=>''])->isUpdate(true, ['binding_code' => $binding_code])->save() != 1) {
                throw new Exception('绑定失败,请核对要是否正确');
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //修改用户资料
    public function editUserInfo($data,$id){
        if (empty($id)) return self::showReturnCode(1003);
        if (empty($data)) return self::showReturnCode(1003);
        $re=$this->data($data)->isUpdate(true,['uuid'=>$id])->save();
        if ($re==1){
            return self::showReturnCodeWithOutData(1001);
        }else{
            return self::showReturnCodeWithOutData(1003,'修改失败');
        }
    }
    //在线用户
    public function getUserOnline($company_id){
        try{
            $list=Db::view('mk_personnel_log_record','uuid')
                ->view('mk_personnel_user','name,department_id,jobs_id,mobile','mk_personnel_user.uuid=mk_personnel_log_record.uuid')
                ->view('mk_personnel_department','department_name','mk_personnel_department.guid=mk_personnel_user.department_id')
                ->view('mk_personnel_jobs','jobs_name','mk_personnel_jobs.guid=mk_personnel_user.jobs_id')
                ->where('mk_personnel_user.company_id','=',$company_id)
                ->whereTime('mk_personnel_log_record.create_time','-2 hours')
                ->group('uuid')
                ->select();
            return self::showReturnCode(1001,$list);
        } catch (Exception $e) {
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }

    public function salesChampion($company_id){  //显示本月的销售冠军
        try{
        $list=Db::view('mk_project_contract_audit','project_id')
            ->view('mk_project_contract','uuid','mk_project_contract.project_id=mk_project_contract_audit.project_id')
            ->view('mk_personnel_user','name','mk_personnel_user.uuid=mk_project_contract.uuid')
            ->where('mk_project_contract_audit.status','=','1')
            ->where('mk_project_contract_audit.examine_status','=','1')
            ->where('mk_personnel_user.company_id','=',$company_id)
            ->group('mk_project_contract.project_id')
//            ->whereTime('mk_project_contract_audit.create_time', 'month')
            ->select();
        $arr=[];
        if (!empty($list)){
            foreach ($list as $value){
                $arr[]=$value['name'];
            }
        }
        return self::showReturnCode(1001,['name'=>$this->getMostElements($arr)]);
        } catch (Exception $e) {
            return self::showReturnCodeWithOutData(1008, $e->getMessage());
        }
    }

    public function getMostElements($arr) {
        $arr = array_count_values($arr);
        asort($arr);
        $findNum =  end($arr);
        foreach ($arr as $k => $v) {
            if ($v != $findNum) {
                unset($arr[$k]);
            }
        }
        return array_keys($arr)[0];
    }



    //市场营销的跟踪量统计
    public function marketData($time='m'){
        $list=Db::view('mk_personnel_user','name,uuid')
            ->view('mk_project','feedback_stage,engin_status,status','mk_project.uuid=mk_personnel_user.uuid')
            ->where('mk_personnel_user.status','=','1')
            ->where('mk_personnel_user.department_id','=',$this->member_info->department_id)
            ->where('mk_personnel_user.company_id','=',$this->member_info->company_id)
            ->whereTime('mk_project.create_time',$time)
            ->select();
        return $list;
    }
    public function marketDataAll($time='m'){
        $type=array_search("市场部",FieldValue::getFieldValue("department_type"))?:6;
        $dep_list=Db::table('mk_personnel_department')
            ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
            ->select();
        $arr=[];
        if (!empty($dep_list)){
            foreach ($dep_list as $value){
                $arr[]=$value['guid'];
            }
        }else{
            $arr[]=$this->member_info->department_id;
        }
        $list=Db::view('mk_personnel_user','name,uuid')
            ->view('mk_project','feedback_stage,engin_status,status','mk_project.uuid=mk_personnel_user.uuid')
            ->where('mk_personnel_user.status','=','1')
            ->where('mk_personnel_user.department_id','in',$arr)
            ->where('mk_personnel_user.company_id','=',$this->member_info->company_id)
            ->whereTime('mk_project.create_time',$time)
            ->select();
        return $list;
    }
    public function serviceDataAll($time='m'){
        $type=array_search("客服部",FieldValue::getFieldValue("department_type"))?:8;
        $dep_list=Db::table('mk_personnel_department')
            ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
            ->select();
        $arr=[];
        if (!empty($dep_list)){
            foreach ($dep_list as $value){
                $arr[]=$value['guid'];
            }
        }else{
            $arr[]=$this->member_info->department_id;
        }
        $list=Db::view('mk_personnel_user','name,uuid')
            ->view('mk_project','feedback_stage,engin_status,status','mk_project.uuid=mk_personnel_user.uuid')
            ->where('mk_personnel_user.status','=','1')
            ->where('mk_personnel_user.department_id','in',$arr)
            ->where('mk_personnel_user.company_id','=',$this->member_info->company_id)
            ->whereTime('mk_project.create_time',$time)
            ->select();
        return $list;
    }
    //统计 用户和项目之间的关联数据
    public function statisticsUserWithProject($list,$user){
        if (!empty($list['rows'])){
            foreach ($list['rows'] as $key=>$value ){
                $sum=0;
                $cg=0;
                $fa=0;
                if (!empty($list)){
                    foreach ($user as $item){
                        if ($value['uuid']==$item['uuid']){
                            if ($item['feedback_stage']>=2){
                                $cg++;
                            }
                            if ($item['status']==-2){
                                $fa++;
                            }
                            $sum++;
                        }
                    }
                }
                $list['rows'][$key]['sum']=$sum;
                $list['rows'][$key]['success']=$cg;
                $list['rows'][$key]['false']=$fa;
                $list['rows'][$key]['now']=$sum-$cg-$fa;
                $list['rows'][$key]['rate']=$sum==0?'0%':($cg/$sum*100).'%';
            }
        }
        return $list;
    }
//统计 用户和项目之间的关联数据
    public function statisticsUserWithDesign($list,$user){
        if (!empty($list['rows'])){
            foreach ($list['rows'] as $key=>$value ){
                $sum=0;
                $cg=0;
                $fa=0;
                if (!empty($list)){
                    foreach ($user as $item){
                        if ($value['uuid']==$item['uuid']){
                            if ($item['feedback_stage']>=2){
                                $re=Db::table('mk_project_contract_audit')
                                    ->where(['project_id'=>$item['project_id'],'examine_status'=>1])
                                    ->select();
                                if(!empty($re)){
                                    $cg++;
                                }
                            }
                            if ($item['status']==-2){
                                $fa++;
                            }
                            $sum++;
                        }
                    }
                }
                $list['rows'][$key]['sum']=$sum;
                $list['rows'][$key]['success']=$cg;
                $list['rows'][$key]['false']=$fa;
                $list['rows'][$key]['now']=$sum-$cg-$fa;
                $list['rows'][$key]['rate']=$sum==0?'0%':($cg/$sum*100).'%';
            }
        }
        return $list;
    }
    //
    public function designData($time='m'){
        $list=Db::view('mk_personnel_user','name,uuid')
            ->view('mk_project_related_person','project_id','mk_project_related_person.uuid=mk_personnel_user.uuid')
            ->view('mk_project','feedback_stage,engin_status,status','mk_project.guid=mk_project_related_person.project_id')
            ->where('mk_personnel_user.status','=','1')
            ->where('mk_personnel_user.department_id','=',$this->member_info->department_id)
            ->where('mk_personnel_user.company_id','=',$this->member_info->company_id)
            ->whereTime('mk_project_related_person.create_time',$time)
            ->select();
        return $list;
    }
    public function designDataAll($time='m'){
        $type=array_search("设计部",FieldValue::getFieldValue("department_type"))?:3;
        $dep_list=Db::table('mk_personnel_department')
            ->where(['company_id'=>$this->member_info->company_id,'status'=>1,'department_type'=>$type])
            ->select();
        $arr=[];
        if (!empty($dep_list)){
            foreach ($dep_list as $value){
                $arr[]=$value['guid'];
            }
        }else{
            $arr[]=$this->member_info->department_id;
        }
        $list=Db::view('mk_personnel_user','name,uuid')
            ->view('mk_project_related_person','project_id','mk_project_related_person.uuid=mk_personnel_user.uuid')
            ->view('mk_project','feedback_stage,engin_status,status','mk_project.guid=mk_project_related_person.project_id')
            ->where('mk_personnel_user.status','=','1')
            ->where('mk_personnel_user.department_id','in',$arr)
            ->where('mk_personnel_user.company_id','=',$this->member_info->company_id)
            ->whereTime('mk_project_related_person.create_time',$time)
            ->select();
        return $list;
    }
}