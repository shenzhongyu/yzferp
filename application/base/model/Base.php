<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/14
 * Time: 16:23
 */

namespace app\base\model;


use think\Cache;
use think\Exception;
use think\Loader;
use think\Model;
use think\Request;
use think\Session;

abstract class Base extends Model
{
    protected $cache = false;
    protected $uuid;
    protected $member_info;

    protected $readonly = ['guid'];
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->checkLoginGlobal();
    }

    public function checkLoginGlobal()
    {
        if (Session::has('uuid', 'Global') && Session::has('member_info', 'Global')) {
            $this->uuid = Session::get('uuid', 'Global');
            $this->member_info =Session::get('member_info', 'Global');
            return true;
        } else {
            return false;
        }
    }
    public function noLoginThrowErr(){
        if(empty($this->uuid)){
            throw new Exception("用户未登录");
        }
    }

    protected static function init()
    {
        self::event('after_insert', function ($model) {
            try {
                $base_name = basename(str_replace('\\', '/', get_called_class()), '.php');
                $request = Request::instance();
                $guid = $request->param('id');
                $data = [];
                if (method_exists($model, 'data')) {
                    if (empty($guid) && isset($model->data['guid'])) {
                        $guid = $model->guid;
                    }
                    if (empty($guid) && isset($model->data['uuid'])) {
                        $guid = $model->uuid;
                    }
                }
                if (empty($data["guid"]) && !empty($request->param('guid'))) {
                    $data["guid"] = $request->param('guid');
                }
                $data["uuid"] = Session::get('uuid', 'Global');
                $data['type'] = "after_insert";
                $data['url'] = $request->url();
                $data['model_name'] = $base_name;
                $data["guid"] = $guid;
                $data["param"] = json_encode($request->param());
                $data["ip"] = $request->ip();
                $data["content"] = json_encode($model->getData());
                $data["update_time"] = time();
                $model->table('mk_system_log')->insert($data);
            }catch (Exception $e){


            }
        });

        self::event('after_update', function ($model) {
            $base_name = basename(str_replace('\\', '/', get_called_class()), '.php');
            $request = Request::instance();
            $guid = $request->param('id');
            $data=[];
            if (method_exists($model, 'data')){
                if (empty($guid)&& isset($model->data['guid'])){
                    $guid = $model->guid;
                }
                if (empty($guid) && isset($model->data['uuid'])){
                    $guid = $model->uuid;
                }
            }

            if(empty($data["guid"]) && isset($data["guid"])){
                if (is_array($_REQUEST['guid'])){
                    $guid = json_encode($request->param('guid/a'));
                }else{
                    if ( !empty($request->param('guid'))){
                        $guid = $request->param('guid');
                    }
                }
            }


            $data["uuid"] = Session::get('uuid', 'Global');
            $data['type']="after_update";
            $data['url']=$request->url();
            $data['model_name']=$base_name;
            $data["guid"]=$guid;
            $data["param"]=json_encode($request->param());
            $data["ip"]=$request->ip();
            $data["content"]=json_encode($model->getData());
            $data["update_time"]=time();
            $model->table('mk_system_log')->insert($data);
        });
    }



    static public function showReturnCode($code = '', $data = [], $msg = ''){
        return \app\base\controller\Base::showReturnCode($code, $data, $msg);
    }
    static public function showReturnCodeWithOutData($code = '', $msg = '')
    {
        return \app\base\controller\Base::showReturnCode($code, [], $msg);
    }


    /**
     * 向二维数组中追加 删除一列值
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $array
     * @param array $append
     * @param array $unset
     * @return mixed
     */
    static public function changeArrayColumnData($array,$append=[],$unset=[]){

        if(empty($array)||!is_array($append)||!is_array($unset)){
            return $array;
        }
        $re_array=$array;
        foreach($array as $item=>$value){
            //追加值
            if ($append){
                foreach($append as $item_append=>$value_append){
                 //   echo "append [$item_append]为$value_append";
                    $re_array[$item][$item_append]=$value_append;
                }
            }
            //剔除值
            if ($unset){
                foreach($unset as $v){
                    //echo "del {$item} $v";
                    unset($re_array[$item][$v]);
                }
            }
        }
        return $re_array;
    }


    /**
     * 根据有Id修改信息 无Id 新增信息
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     * @param $data
     * @return false|int|string
     * @throws
     */
    public function editData($data){
        if (isset($data['id'])){
            if (is_numeric($data['id']) && $data['id']>0){
                    $save = $this->allowField(true)->save($data,[ 'id' => $data['id']]);
            }else{
                $save  = $this->allowField(true)->save($data);
            }
        }else{
            $save  = $this->allowField(true)->save($data);
        }
        if ( $save == 0 || $save == false) {
            $res=[  'code'=> 1009,  'msg' => '数据更新失败', ];
        }else{
            $res=[  'code'=> 1001,  'msg' => '数据更新成功',  ];
        }
        return $res;
    }

    public function setStatusDel($id){
        return $this->save(['status'=>-1],['id'=>$id]);
    }
    public function setStatusDelGuid($id){
        return $this->save(['status'=>-1],['guid'=>$id]);
    }
    public function setStatusDelByGuid($id){
        $base_name = basename(str_replace('\\', '/', get_called_class()), '.php');
        $guid_name =$base_name=="PersonnelUser"?"uuid":"guid";
        return $this->save(['status'=>-1],[$guid_name=>$id]);
    }





    /**
     * 创建个性GUID
     * Power by Mikkle
     * QQ:776329498
     * @param string $base_code
     * @return string
     */
    public function create_uuid($base_code = '')
    {
        if (empty($base_code)) {
            $base_name = basename(str_replace('\\', '/', get_called_class()), '.php');
            $uuid_list = ModelCode::$uuid_list;
            $base_code = isset($uuid_list[$base_name]) ? $uuid_list[$base_name] : 'QT';
        }
        $uuid = $base_code . strtoupper(uniqid()) . $this->builderRand(6);
        return $uuid;
    }

    /**
     * 输入标点字符转换
     * Power by Mikkle
     * QQ:776329498
     * @param $data
     * @return string
     */
    protected function toChineseLabel($data){
        $change = ['<'=>'《'];
        $change += ['>'=>'》'];
        $change += ['\''=>'’'];
        $change += ['"'=>'”'];
        return strtr($data,$change);
    }

    /**
     * 创建随机数
     * Power by Mikkle
     * QQ:776329498
     * @param int $num  随机数位数
     * @return string
     */
    public function builderRand($num=8){
        return substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $num);
    }
    public function getEasyUiJsonNo($field=true,$where =['1'=>1], $page = 1,$limit = 20 , $order = ['id'=>'desc'] ){
        $total = $this->where($where)->count();
        $rows = $this->field($field)->page($page)->limit($limit)->order($order)->where($where)->select();
        if (!empty($rows)){
            foreach ($rows as $key=>$value){
                $value['serial_number']=$key+($page-1)*$limit+1;
            }
        }
        return ['total'=>$total,'rows'=>$rows];
    }
    public function getEasyUiJson($field=true,$where =['1'=>1], $page = 1,$limit = 20 , $order = ['id'=>'desc'] ){

        $total = $this->where($where)->count();
        $rows = $this->field($field)->page($page)->limit($limit)->order($order)->where($where)->select();

        //用来显示用户登录的username
        foreach($rows as $value){
            if(isset($value->data['uuid'])){
                $model=new PersonnelUser();
                $user=$model->getInfoByUuId($value->data["uuid"]);
                if(!empty($user)){
                    $value->data["userneme"]=$user["username"];
                    $department=Loader::model("base/PersonnelDepartment")->getPerByGuId($user["department_id"]);
                    $value->data["department_name"]=$department["department_name"];
                    $jobs=Loader::model("base/PersonnelJobs")->getPerByGuId($user["jobs_id"]);
                    $value->data["jobs_name"]=$jobs["jobs_name"];
                    $value->data["name"]=$user["name"];
                }
            }
            if(isset($value["end_time"])){
                $value["end_time"]=date("Y-m-d", $value["end_time"]);
            }
            if(isset($value["start_time"])){
                $value["start_time"]=date("Y-m-d", $value["start_time"]);
            }
        }
        if (!empty($rows)){
            foreach ($rows as $key=>$value){
                $value['serial_number']=$key+($page-1)*$limit+1;
            }
        }
        //
        return ['total'=>$total,'rows'=>$rows];
    }

    //参数查询
    public function getEasyUiParameterJson($field=true,$where =['1'=>1], $page = 1,$limit = 20 , $order = ['id'=>'desc'] ){
        $total = $this->where($where)->count();
        $rows = $this->field($field)->page($page)->limit($limit)->order($order)->where($where)->select();
        $model_user= new \app\base\model\PersonnelUser();
        $model_dep= new \app\base\model\PersonnelDepartment();
        $model_jobs=new \app\base\model\PersonnelJobs();
        foreach($rows as $value){
            if(isset($value["uuid"])){
                $project_user=$model_user->getInfoByUuId($value["uuid"]);
                $value["username"]=$project_user['username'];
                $value["name"]=$project_user['name'];
            }
            if(isset($value["department_id"])) {
                $project_dep = $model_dep->getPerByGuId($value["department_id"]);
                $value["department_name"] = $project_dep['department_name'];
            }
            if(isset($value["jobs_id"])){
                $project_jobs=$model_jobs->getPerByGuId($value["jobs_id"]);
                $value["jobs_name"]=$project_jobs['jobs_name'];
            }
            if(isset($value["project_guid"])){
                $model_pro=new \app\base\model\Project();
                $project_name=$model_pro->getPerByGuId($value["project_guid"]);
                $value["project_name"]=$project_name['project_name'];
            }

            if(isset($value["end_time"])){
                $value["end_time"]=date("Y-m-d", $value["end_time"]);
            }
            if(isset($value["start_time"])){
                $value["start_time"]=date("Y-m-d", $value["start_time"]);
            }
        }
        if (!empty($rows)){
            foreach ($rows as $key=>$value){
                $value['serial_number']=$key+($page-1)*$limit+1;
            }
        }
        return ['total'=>$total,'rows'=>$rows];

    }

    public function getEasyUilist($id,$text,$map=[]){
//        $map=["status"=>'1'];
        $map['status'] = 1 ;
        if (!empty($id)&&!empty($text)){
            $field = " {$id} as id,{$text} as text";
        }else{
            $field = true;
        }
        $objs= $this->where($map)->field($field)->select();
        $array = [];
        foreach($objs as $obj){
            $array[$obj['id']] = $obj['text'];
        }

        return $array;

    }

    public function getInfoById($id,$map=[],$field=true){
        $map['id']=$id;
        $map['status']=1;
        return $this->where($map)->field($field)->find();
    }

    public function getProjectByProjectGuId($guid,$map=[],$field=true){
        $map['project_guid']=$guid;
        $map['status']=1;
        return $this->where($map)->field($field)->find();
    }
    public function getInfoByUuId($guid,$map=[],$field=true){
        $map['uuid']=$guid;
        $map['status']=1;
        return $this->where($map)->field($field)->find();
    }
    //del
    public function getPerByGuId($guid,$map=[],$field=true){
        $map['guid']=$guid;
        $map['status']=1;
        return $this->where($map)->field($field)->find();
    }

    public function getInfoByGuId($guid,$map=[],$field=true,$append=[]){
        $base_name = basename(str_replace('\\', '/', get_called_class()), '.php');
        $guid_name =$base_name=="PersonnelUser"?"uuid":"guid";
        $map[$guid_name]=$guid;
        $map['status']=1;
        $object = $this->where($map)->field($field)->find();
        if(!empty($object)&&!empty($append)){
            return $object->append($append);
        }else{
            return $object;
        }
    }

    /**
     * 通过查询条件获取单条数据(对象)
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param array $map  查询条件
     * @param bool|true $field 字段
     * @param array $append 追加已经定义获取器字段
     * @param bool|true $status
     * @return $this|array|false|\PDOStatement|string|Model
     */
    public function getInfoByMap($map=[],$field=true,$append=[],$status=true){
        if($status&&!isset($map['status'])){
            $map['status']=1;
        }
        $object = $this->where($map)->field($field)->find();
        if(!empty($object)&&!empty($append)){
            return $object->append($append);
        }else{
            return $object;
        }
    }

    /**
     * 通过查询条件获取单条数据(数组)
     * User: Mikkle
     * Q Q:776329498
     * @param array $map
     * @param bool|true $field
     * @param array $append
     * @param bool|true $status
     * @return array
     */
    public function getArrayByMap($map=[],$field=true,$append=[],$status=true){
        if($status&&!isset($map['status'])){
            $map['status']=1;
        }
        $object = $this->where($map)->field($field)->find();
        if(!empty($object)&&!empty($append)){
            $return = $object->append($append);
        }else{
            $return = $object;
        }
        return empty($return) ? [] : $return->toArray();
    }


    /**
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param array $map
     * @param bool|true $field
     * @param array $append 这需要在模型里增加获取器
     * @param bool|true $status
     * @return array
     */
    public function getListByMap($map=[],$field=true,$append=[],$status=true){
        if($status&&!isset($map['status'])){
            $map['status']=1;
        }
        $object_list = $this->where($map)->field($field)->select();
        $list=[];
        if(!empty($object_list)){
            foreach($object_list as $item=>$value){
                if(!empty($append)){
                    $list[]= $value->append($append)->toArray();
                }else{
                    $list[]= $value->toArray();
                }
            }
        }
        return $list;
    }


    public function saveInfoByGuid($data){
        $base_name = basename(str_replace('\\', '/', get_called_class()), '.php');
        $guid_name = $base_name=="PersonnelUser" ? "uuid":"guid";
        if (isset($data['guid']) && !isset($data['uuid'])){
            $guid = $data['guid'];
        }elseif(!isset($data['guid']) && isset($data['uuid'])){
            $guid = $data['uuid'];
        }elseif(isset($data['guid']) && isset($data['uuid'])){
            $guid = $data['guid'];
        }else{
            return self::showReturnCode(1003);
        }
        unset($data[$guid_name]);
        $re =  $this->isUpdate(true)->allowField(true)->save($data,[$guid_name => $guid]);
        if ($re==1 ){
//            switch($base_name){
//                case "OfficePlan":
//
//
//                    break;
//                case "Project":
//
//
//                    break;
//                default:;
//            }

            return self::showReturnCode(1001);
        }

        return self::showReturnCode(1007,[],'数据无变化或者不存在');
    }

    //缓存基本内容
    public function setCacheSystem(){
        if ($this->cache==true && Cache::has("cacheSystem")){
            return Cache::get("cacheSystem");
        }else{
            $company=$this->getAllData("base/PersonnelCompany");
            $department=$this->getAllData("base/PersonnelDepartment");
            $jobs=$this->getAllData("base/PersonnelJobs");
            $user=$this->getAllData("base/PersonnelUser");
            $system_info=[
                "company"=>$company,
                "department"=>$department,
                'jobs'=>$jobs,
                'user'=>$user,
            ];
            Cache::set('cacheSystem',$system_info);
            return $system_info;
        }
    }
    //缓存材料商和材料类别信息
    public function setCacheMaterialOfStyle(){
        if ($this->cache==true && Cache::has("cacheMaterialOfStyle")){
            return Cache::get("cacheMaterial");
        }else{
            $material_category=$this->getAllData("base/MaterialCategory");
            $material_supplier=$this->getAllData("base/MaterialSupplier");
            $system_info=[
                "material_category"=>$material_category, //材料类别
                'material_supplier'=>$material_supplier,
            ];
            Cache::set('cacheMaterialOfStyle',$system_info);
            return $system_info;
        }
    }

    //缓存主材信息,基装项和设计预算信息
    public function setCacheMaterialData(){
        if ($this->cache==true && Cache::has("cacheMaterial")){
            return Cache::get("cacheMaterial");
        }else{
            $material=$this->getAllData("base/Material");
            $dataItem=$this->getAllData("base/MaterialDataItem");
            $dataItemStyle=$this->getAllData("base/MaterialDataStyle");
            $budget=$this->getAllData('base/DesignBudget');
            $budget_project=$this->getAllData('base/DesignBudget','project_guid');
            $budgetListAccess=$this->getAllData('base/DesignBudgetListAccess');
            $listEdit=$this->getAllData('base/MaterialListEdit');
            $system_info=[
                "material"=>$material,
                "dataItem"=>$dataItem,
                'budget'=>$budget,
                'budget_project'=>$budget_project,
                'dataItemStyle'=>$dataItemStyle,
                'budgetListAccess'=>$budgetListAccess,
                'listEdit'=>$listEdit,
            ];
            Cache::set('cacheMaterial',$system_info);
            return $system_info;
        }
    }
    //缓存项目信息
    public function setCacheProjectData(){
        if ($this->cache==true && Cache::has("cacheProject")){
            return Cache::get("cacheProject");
        }else{
            $project=$this->getAllData("base/Project");
            $project_design=$this->getAllData("base/DesignPhase"); //项目设计进度
            $rate=$this->getAllData('base/MaterialRateItem'); //固定费率
            $project_address=$this->getAllData('base/ProjectBuilding','project_guid');
            $project_budget=$this->getAllData('base/BudgetDefaultProject');//获取所有的预算空间
            $project_budget_pro=$this->getAllData('base/BudgetDefaultProject','project_id');//获取所有的预算空间
            $project_budget_book=$this->getAllData('base/BudgetDefaultBook','project_id');//获取所有的预算书
            $system_info=[
                "project"=>$project,
                'project_design'=>$project_design,
                'rate'=>$rate,
                'project_address'=>$project_address,
                'project_budget'=>$project_budget,
                'budget_pro'=>$project_budget_pro,
                'budget_book'=>$project_budget_book,

            ];
            Cache::set('cacheProject',$system_info);
            return $system_info;
        }
    }

    //获取数据缓存
    public function getAllData($data,$index=""){
        $list=Loader::model($data)->getSelect();
        $arr=[];
        if(!empty($list)){
            foreach($list as $value){
                if (empty($index)){
                    if(isset($value["guid"])){
                        $arr[$value["guid"]]=$value;
                    }else if( !isset($value["guid"]) && isset($value["uuid"])){
                        $arr[$value["uuid"]]=$value;
                    }
                }else{
                    if(isset($value[$index])){
                        $arr[$value[$index]]=$value;
                    }
                }

            }
        }
        return $arr;
    }
    public function getSelect($map = [],$field=false){
        if (empty($map)){

        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();
    }

    /**
     * 带有乐观锁的修改
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $save_data　　　修改的数据
     * @param string $edit_pk  修改的ＩＤ字段名称
     * @param string $version_field　　乐观锁版本号字段名称
     * @return array
     */
    public function editDateWithLock($save_data,$edit_pk="",$version_field=""){
        if (empty($version_field)){
            $version_field = isset($this->versionField) ? $this->versionField : "edit_version";
        }
        if (empty($edit_pk)){
            $edit_pk = isset($this->editPk) ? $this->editPk : $this->getPk();
        }
        //判断PK字段是否存在
        if (!isset($save_data[$edit_pk])||!isset($save_data[$version_field])){
            return self::showReturnCodeWithOutData(1003,"参数缺失");
        }else{
            //设置升级检索条件 PK和版本号
            $map[$edit_pk] = $save_data[$edit_pk];
            $map[$version_field] = $save_data[$version_field];
            //剔除PK
            unset($save_data[$edit_pk]);
        }
        try{
            //检测版本字段
            if($this->hasColumn($version_field)){
                throw new Exception("乐观锁版本字段[$version_field]不存在");
            }
            $original_data = $this->where($map)->find();
            if (empty($original_data)){
                throw new Exception("此条信息已经变动了,请重新操作!");
            }
            foreach ($save_data as $item=>$value){
                if (isset($original_data[$item])){
                    //修改的数值不变时候 剔除
                    if ($original_data[$item]==$value){
                        unset( $save_data[$item]);
                    }elseif($item!=$version_field){
                        unset( $original_data[$item]);
                    }
                }else{
                    //修改的字段不存在 剔除
                    unset( $save_data[$item]);
                }
            }
            if(empty($save_data)){
                throw new Exception("修改的数值无变化");
            }
            //版本号升级
            $save_data[$version_field]=(int)$original_data[$version_field]+1;
            if (1!=$this->allowField(true)->save($save_data,$map)){
                throw new Exception("修改信息出错:".$this->getError());
            }
            //记录修改日志
            //$this->saveEditLog($original_data,$save_data);
            return self::showReturnCodeWithOutData(1001);
        }catch (Exception $e){
            $msg=$e->getMessage();
            return self::showReturnCodeWithOutData(1003,$msg);
        }
    }

    /**
     * 判断字段是否存在
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $column
     * @param string $table
     * @return bool
     */
    protected function hasColumn($column,$table=""){
        $table = isset($table)?$table:$this->table;
        if (empty($table)||$column){
            $this->error="hasColumn方法参数缺失";
            return false;
        }
        $sql = "SELECT * FROM information_schema.columns WHERE table_schema=CurrentDatabase AND table_name = '{$table}' AND column_name = '{$column}'";
        return $this->query($sql) ? true : false;
    }

    static public function throwException($msg){
        throw new Exception($msg);
    }


    public function getTime($data,$field,$flag=true){   //转换数据库字段时间戳
        if (empty($data)) return  $data;
        if($flag==true){
            if (!isset($data['rows'])) return $data;
            if (is_array($data['rows'])){
                foreach ($data['rows'] as $key=> $value){
                    if (isset($value[$field])){
                        if (!empty($value[$field])){
                            if(is_int($value[$field])){
                                if($value[$field]>strtotime("1970-12-31 23:59:59")){
                                    $data['rows'][$key][$field]=date("Y-m-d ",$value[$field]);
                                }
                            }

                        }

                    }
                }
            }
        }else{
            if (is_array($data)){
                foreach ($data as  $key=>$value){
                    if (isset($value[$field])){
                        if (!empty($value[$field])){
                            if(is_int($value[$field])) {
                                if ($value[$field] > strtotime("1970-12-31 23:59:59")) {
                                    $data[$key][$field] = date("Y-m-d ", $value[$field]);
                                }
                            }
                        }
                    }
                }
            }else{
                if (!empty($data[$field])){
                    if(is_int($data[$field])) {
                        if ($data[$field] > strtotime("1970-12-31 23:59:59")) {
                            $data[$field] = date("Y-m-d ", $data[$field]);
                        }
                    }
                }
            }
        }

        return $data;
    }
    public function getTimeHours($data,$field,$flag=true){   //转换数据库字段时间戳
        if (empty($data)) return  $data;
        if($flag==true){
            if (!isset($data['rows'])) return $data;
            if (is_array($data['rows'])){
                foreach ($data['rows'] as $key=> $value){
                    if (isset($value[$field])){
                        if (!empty($value[$field])){
                            if(is_int($value[$field])) {
                                if ($value[$field]>strtotime("1970-12-31 23:59:59")) {
                                    $data['rows'][$key][$field] = date("Y-m-d H:i:s", $value[$field]);
                                }
                            }
                        }

                    }
                }
            }
        }else{
            if(is_array($data)){
                foreach ($data as $key=> $value){
                    if (isset($value[$field])){
                        if (!empty($value[$field])){
                            if(is_int($value[$field])) {
                                if ($value[$field] > strtotime("1970-12-31 23:59:59")) {
                                    $data[$key][$field] = date("Y-m-d H:i:s", $value[$field]);
                                }
                            }
                        }
                    }
                }
            }else{
                if (!empty($data[$field])){
                    if(is_int($data[$field])) {
                        if ($data[$field] > strtotime("1970-12-31 23:59:59")) {
                            $data[$field] = date("Y-m-d H:i:s", $data[$field]);
                        }
                    }
                }
            }
        }
        return $data;
    }

    function getCountObj($model="",$map=[],$type="int"){
        if (empty($map)){
            $map=['status'=>1];
        }elseif(!isset($map["status"])){
            $map["status"] = 1 ;
        }
        if (empty($model)){
            $model='ProjectAudit';
        }
        $count=Loader::model($model)->where($map)->count();

        switch ($type){
            case "array":
                $arr=[
                    'number'=>$count,
                ];
                return $arr;
                break;
            case "int":
                return $count;
            default :
                return $count;
        }
    }


    /**
     * 判断自动完成是否存在
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $field
     * @param $isUpdate
     * @return bool
     */
    protected function checkAutoCompleteExist($field,$isUpdate){
        $auto= $isUpdate ? $this->update : $this->insert ;
        return  array_key_exists($field,$this->transactAutoCompleteFieldArray($this->auto,$auto));
    }

    /**
     * 整合自动完成字段
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param array $array
     * @param array $merge
     * @return array
     */
    protected function transactAutoCompleteFieldArray($array=[],$merge=[]){
        $auto=array_merge($array,$merge);
        foreach ($auto as $field => $value) {
            if (is_integer($field)) {
                $auto[$value]=null;
                unset($auto[$field]);
            }
        }
        return $auto;
    }


    /**
     * 保存当前数据对象
     * @access public
     * @param array  $data     数据
     * @param array  $where    更新条件
     * @param string $sequence 自增序列名
     * @return integer|false
     */
    public function save($data = [], $where = [], $sequence = null)
    {
        if (is_string($data)) {
            $sequence = $data;
            $data     = [];
        }

        if (!empty($data)) {
            // 数据自动验证
            if (!$this->validateData($data)) {
                return false;
            }

            if (!empty($where)) {
                $this->isUpdate    = true;
                $this->updateWhere = $where;
            }
            // 数据对象赋值
            foreach ($data as $key => $value) {
                //增加了验证是否存在自动完成的
                if ($this->checkAutoCompleteExist($key,$this->isUpdate)) continue;
                $this->setAttr($key, $value, $data);
            }
        }

        // 自动关联写入
        if (!empty($this->relationWrite)) {
            $relation = [];
            foreach ($this->relationWrite as $key => $name) {
                if (is_array($name)) {
                    if (key($name) === 0) {
                        $relation[$key] = [];
                        foreach ($name as $val) {
                            if (isset($this->data[$val])) {
                                $relation[$key][$val] = $this->data[$val];
                                unset($this->data[$val]);
                            }
                        }
                    } else {
                        $relation[$key] = $name;
                    }
                } elseif (isset($this->relation[$name])) {
                    $relation[$name] = $this->relation[$name];
                } elseif (isset($this->data[$name])) {
                    $relation[$name] = $this->data[$name];
                    unset($this->data[$name]);
                }
            }
        }

        // 数据自动完成
        $this->autoCompleteData($this->auto);

        // 事件回调
        if (false === $this->trigger('before_write', $this)) {
            return false;
        }
        $pk = $this->getPk();
        if ($this->isUpdate) {
            // 自动更新
            $this->autoCompleteData($this->update);

            // 事件回调
            if (false === $this->trigger('before_update', $this)) {
                return false;
            }

            // 获取有更新的数据
            $data = $this->getChangedData();

            if (empty($data) || (count($data) == 1 && is_string($pk) && isset($data[$pk]))) {
                // 关联更新
                if (isset($relation)) {
                    $this->autoRelationUpdate($relation);
                }
                return 0;
            } elseif ($this->autoWriteTimestamp && $this->updateTime && !isset($data[$this->updateTime])) {
                // 自动写入更新时间
                $data[$this->updateTime]       = $this->autoWriteTimestamp($this->updateTime);
                $this->data[$this->updateTime] = $data[$this->updateTime];
            }

            if (empty($where) && !empty($this->updateWhere)) {
                $where = $this->updateWhere;
            }

            // 保留主键数据
            foreach ($this->data as $key => $val) {
                if ($this->isPk($key)) {
                    $data[$key] = $val;
                }
            }

            if (is_string($pk) && isset($data[$pk])) {
                if (!isset($where[$pk])) {
                    unset($where);
                    $where[$pk] = $data[$pk];
                }
                unset($data[$pk]);
            }

            // 检测字段
            $allowFields = $this->checkAllowField(array_merge($this->auto, $this->update));

            // 模型更新
            if (!empty($allowFields)) {
                $result = $this->getQuery()->where($where)->strict(false)->field($allowFields)->update($data);
            } else {
                $result = $this->getQuery()->where($where)->update($data);
            }

            // 关联更新
            if (isset($relation)) {
                $this->autoRelationUpdate($relation);
            }

            // 更新回调
            $this->trigger('after_update', $this);

        } else {
            // 自动写入
            $this->autoCompleteData($this->insert);

            // 自动写入创建时间和更新时间
            if ($this->autoWriteTimestamp) {
                if ($this->createTime && !isset($this->data[$this->createTime])) {
                    $this->data[$this->createTime] = $this->autoWriteTimestamp($this->createTime);
                }
                if ($this->updateTime && !isset($this->data[$this->updateTime])) {
                    $this->data[$this->updateTime] = $this->autoWriteTimestamp($this->updateTime);
                }
            }

            if (false === $this->trigger('before_insert', $this)) {
                return false;
            }

            // 检测字段
            $allowFields = $this->checkAllowField(array_merge($this->auto, $this->insert));
            if (!empty($allowFields)) {
                $result = $this->getQuery()->strict(false)->field($allowFields)->insert($this->data);
            } else {
                $result = $this->getQuery()->insert($this->data);
            }

            // 获取自动增长主键
            if ($result && is_string($pk) && (!isset($this->data[$pk]) || '' == $this->data[$pk])) {
                $insertId = $this->getQuery()->getLastInsID($sequence);
                if ($insertId) {
                    $this->data[$pk] = $insertId;
                }
            }

            // 关联写入
            if (isset($relation)) {
                foreach ($relation as $name => $val) {
                    $method = Loader::parseName($name, 1, false);
                    $this->$method()->save($val);
                }
            }

            // 标记为更新
            $this->isUpdate = true;

            // 新增回调
            $this->trigger('after_insert', $this);
        }
        // 写入回调
        $this->trigger('after_write', $this);

        // 重新记录原始数据
        $this->origin = $this->data;

        return $result;
    }



}