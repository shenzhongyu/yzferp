<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/5
 * Time: 17:20
 */

namespace app\install\controller;



use app\base\controller\Curl;
use app\base\model\PersonnelCompany;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelJobs;
use app\base\model\PersonnelRole;
use app\base\model\PersonnelUser;
use app\erp\config\FieldValue;
use app\erp\controller\BaseEasyUI;
use think\Config;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;
use think\Session;
use app\base\model\ModelCode;
class Install extends BaseEasyUI
{



    protected $db_config;
    public function _initialize(){
        $this->db_config =Config::get('database');
        $field_path='install/existence/use.php';
        $file='install/existence/role.php';
        if(file_exists(APP_PATH .$field_path)==true && file_exists(APP_PATH .$file)==true){
            $this->redirect(url('erp/login/login'));
        }
    }
    public  function install(){
        $this->checkUserdata();
        $arr=[
           'title'=>'装企ERP管理员账号',
        ];
        $this->assign($arr);
        return $this->fetch('install');
    }
    //第一步 
    public function installSteps(){
        $this->checkUserdata();
        if ($this->request->isPost()){
            try{
                $data=$this->request->post();
                $validate_name='base/Install.add';
                $result = $this->validate($data, $validate_name);
                if (true !== $result) return ['code' => '1003', 'msg' => $result];
                Session::set("data",$data,"install");
                return self::showJsonReturnCode(1001);
            }catch (Exception $e){
                Log::error($e->getMessage());
                return self::showJsonReturnCode(1008,[],'配置失败，请联系管理员');
            }
        }
    }
    //第二步
    public function InstallTable(){
        $this->checkUserdata();
        if ($this->request->isPost()){
            if ($this->checkSession('data')){
                $table=$this->createTable();
                if ($table==true){
                    return self::showJsonReturnCode(1001);
                }else{
                    return self::showJsonReturnCode(1003,[],'数据库配置失败，请联系管理员');
                }
            }else{
                return self::showJsonReturnCode(1012,[],'未配置参数,请上一步进行配置');
            }
        }else{
            $arr=[
                'title'=>'装企ERP数据库配置',
            ];
            $this->assign($arr);
            return $this->fetch('install_table');
        }
    }
    //第三步
    public function InstallNodeData(){
        $this->checkUserdata();
        if ($this->request->isPost()){
            if ($this->checkSession('isCreateTable')){
                $node=$this->insetNodeData();
                if ($node==true){
                    return self::showJsonReturnCode(1001);
                }else{
                    return self::showJsonReturnCode(1003,[],'系统数据配置失败，请联系管理员');
                }
            }else{
                return self::showJsonReturnCode(1012,[],'未配置数据库，请联系管理员');
            }
        }else{
            $arr=[
                'title'=>'装企ERP系统数据配置',
            ];
            $this->assign($arr);
            return $this->fetch('install_node_data');
        }
    }
    //第四步
    public function createUserData(){
        $this->checkUserdata();
        if ($this->request->isPost()){
            if ($this->checkSession('isInsetNodeData')){
                $user=$this->insetUserData();
                if ($user==true){
                    $file='install/existence/use.php';
                    if (!is_dir(APP_PATH . dirname($file))) {
                        // 创建目录
                        mkdir(APP_PATH . dirname($file), 0777, true);
                    }
                    if (!is_file(APP_PATH . $file)) {
                        file_put_contents(APP_PATH .$file,'1');
                    }
                    if (is_file(ROOT_PATH . "public_html/index.html")) {
                        unlink ( ROOT_PATH . "public_html/index.html" );
                    }

                    $pass_file='install/extra/password.php';
                    $text='<?php return '.var_export(['password'=>ModelCode::getMd5Password('YZFErp123456')],true).';';
                    if (!is_dir(APP_PATH . dirname($pass_file))) {
                        // 创建目录
                        mkdir(APP_PATH . dirname($pass_file), 0777, true);
                    }
                    if (!is_file(APP_PATH . $pass_file)) {
                        file_put_contents(APP_PATH .$pass_file,$text);
                    }

                    return self::showJsonReturnCode(1001);
                }else{
                    return self::showJsonReturnCode(1003,[],'管理员数据配置失败，请联系管理员');
                }
            }else{
                return self::showJsonReturnCode(1012,[],'未配置系统数据，请联系管理员');
            }
        }else{
            $arr=[
                'title'=>'装企ERP管理员数据配置',
            ];
            $this->assign($arr);
            return $this->fetch('install_user_data');
        }
    }
    //第五步  创建角色
    public function createRoleMarket(){
        $file='install/existence/role.php';
        if(file_exists(APP_PATH .$file)==true){
            $this->redirect(url('erp/login/login'));
        }
        $use_file='install/existence/use.php';
        if(file_exists(APP_PATH .$use_file)==false){
            $this->redirect(url('install/install'));
        }
        if ($this->request->isPost()){
            $data=$this->request->post();
            $flag=$this->createDefaultRole($data);
            if ($flag==true){
                if (!is_dir(APP_PATH . dirname($file))) {
                    // 创建目录
                    mkdir(APP_PATH . dirname($file), 0777, true);
                }
                if (!is_file(APP_PATH . $file)) {
                    file_put_contents(APP_PATH .$file,'1');
                }
                if (is_file(ROOT_PATH . "public_html/index.html")) {
                    unlink ( ROOT_PATH . "public_html/index.html" );
                }

                return self::showReturnCodeWithOutData(1001);
            }else{
                return self::showJsonReturnCode(1012,[],'未配置系统数据，请联系管理员');
            }
        }else{
            $role=Config::get("default_role_data");
            $arr=Config::get("role_json");
            $arr['role_json']=$role;
            $this->assign($arr);
            return $this->fetch('install_skip/create_role');
        }
    }
    public function createSuccess(){
        if ($this->request->isPost()){

        }else{
            $arr=[
                'title'=>'装企ERP配置完成',
            ];
            $this->assign($arr);
            return $this->fetch('install_skip/success');
        }
    }




    protected static function checkDirBuild($dirname)
    {
        if (!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }
    }

    protected  function createTable(){
        try{
            $sql_path='install/extra/sql.txt';
            $install_db = Db::connect($this->db_config);
            $sql= file_get_contents(APP_PATH.$sql_path);
            $sql_array=explode(";",$sql);

            foreach($sql_array as $value){
                if(!empty(trim($value))){
                    if(trim($value)!=""){
                        $install_db->execute($value.";");
                    }

                }
            }

            $this->setSession('isCreateTable');
            return true;
        }catch (Exception $e){
            Log::error($e->getMessage());
            return false;
        }
    }

    protected  function insetNodeData(){
        try{
            $install_db = Db::connect($this->db_config);
            $sql_json=Config::get("sql_node_data");
            $role_data_json=Config::get("sql_role_node_data");

            $install_db->table('mk_personnel_node')->insertAll($sql_json);
            $install_db->table('mk_personnel_role_node_access')->insertAll($role_data_json);

            $this->setSession('isInsetNodeData');
            return true;
        }catch (Exception $e){
            Log::error($e);
            return false;
        }
    }

    protected function insetUserData(){
        try{
//            $install_db = Db::connect($this->db_config);
            $user_json=$this->checkSession('data');

            $model_company= new PersonnelCompany();
            $model_department=new PersonnelDepartment();
            $model_jobs=new PersonnelJobs();
            $model_user=new PersonnelUser();

            $company_data=[
                'company_name'=>$user_json['company_name'],
                'company_desc'=>isset($user_json['company_desc']) ? $user_json['company_desc'] :''
            ];
            $model_company->save($company_data);
            $com_id=$model_company->guid;
            Session::set("company",$com_id,"install");


            $department_type=array_search("总经办",FieldValue::getFieldValue("department_type"))?:1;
            $department_data=[
                'company_id'=>$com_id,
                'department_type'=>$department_type,
                'department_name'=>'总经办',
            ];
            $model_department->save($department_data);
            $dep_id=$model_department->guid;

            $jobs_data=[
                'company_id'=>$com_id,
                'department_type'=>$department_type,
                'department_id'=>$dep_id,
                'jobs_name'=>'管理员',
            ];
            $model_jobs->save($jobs_data);
            $job_id=$model_jobs->guid;

            $role_data=[
                'company_id'=>$com_id,
                'role_name'=>'管理员',
                'guid'=>'RB5A051445D50E4531005',
                'create_time'=>'1506068377',
                'update_time'=>'1506068377',
                'status'=>1,
            ];
            Db::table('mk_personnel_role')->insert($role_data);

            $user_data=[
                'company_id'=>$com_id,
                'department_type'=>$department_type,
                'department_id'=>$dep_id,
                'jobs_id'=>$job_id,
                'name'=>$user_json['name'],
                'username'=>$user_json['username'],
                'password'=>$user_json['password'] ,
                'role_id'=>'RB5A051445D50E4531005',
                'status'=>1

            ];
            $arr=[
                'name'=>'admin',
            ];
            $model_user->save($arr);
            $uuid=$model_user->uuid;
            $model_user->save($user_data,['uuid'=>$uuid]);
            $this->setSession('insetUserData');
            return true;
        }catch (Exception $e){
            Log::error($e);
            return false;
        }
    }
    protected function createDefaultRole($data=[]){
        if (!empty($data)){
            if (!isset($data['role'])){
                return false;
            }
            if (!is_array($data['role'])){
                return false;
            }
            if (empty($data['role'])){
                return true;  //数组为空，代表一个都没选
            }
            try{
                $role=Config::get('default_role_data');
                $role_node=Config::get('default_role_node_access_data');
                $arr=[];
                foreach ($data['role'] as $value){
                    $role[$value]['company_id']=$this->checkSession('company');
                    $arr[]=$role[$value];
                }
                Db::table('mk_personnel_role')->insertAll($arr);

                $brr=[];
                foreach ($arr as $value){
                    foreach ($role_node as $item){
                        if ($item['role_id']==$value['guid']){
                            $brr[]=$item;
                        }
                    }
                }
                Db::table('mk_personnel_role_node_access')->insertAll($brr);


                return true;
            }catch (Exception $e){
                Log::error($e);
                return false;
            }

        }else{
            return false;
        }
    }
    protected function setSession($name){
        Session::set("$name",true,"install");
    }
    protected function checkSession($name){
       return Session::get("$name","install");
    }

    protected  function  checkUserData(){
        $field_path='install/existence/use.php';
        if(file_exists(APP_PATH .$field_path)==true){
            $this->redirect(url('install/install/createRoleMarket'));
        }
    }

}