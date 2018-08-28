<?php
namespace app\api\controller;
use app\base\controller\Excel;
use app\base\controller\Image;
use app\base\controller\PDF;
use app\base\controller\Redis;
use app\base\controller\RedisHash;
use app\base\controller\ReturnCode;
use app\base\controller\Upload;
use app\base\controller\AES;
use app\base\controller\Word;
use app\base\model\BaseHashText;
use app\base\model\engin\EnginProjectMaterial;
use app\base\model\finance\BankTransactionRecord;
use app\base\model\MaterialListEdit;
use app\base\model\PersonnelNode;
use app\base\model\PersonnelRoleNodeAccess;
use app\base\model\PersonnelUser;
use app\erp\controller\template_message\SendTemplateMessage;
use app\worker\controller\WechatMessage;
use mikkle\tp_wechat\base\AccessToken;

use mikkle\tp_wechat\support\Curl;
use mikkle\tp_wechat\Wechat;
use think\Cache;
use think\Config;
use think\console\input\Option;
use think\Db;
use think\Exception;
use think\File;
use think\helper\Hash;
use think\Loader;
use think\Log;
use think\Url;


/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/14
 * Time: 13:41
 */


class Test extends Base
{


    function index()
    {
       $re =  Db::table("mk_personnel_node")->where(["status"=>-1])->column("guid");

       $re = Db::table("mk_personnel_node")->where(["pid"=>["in",$re],"status"=>1])->column("id");

       Log::notice($re);
      $num =  Db::table("mk_personnel_node")->where(["id"=>["in",$re],"status"=>1])->update(["status"=>-1]);
       dump($re);
        dump($num);
    }
    function union()
    {

       $list= Db::table("mk_budget_default_book")->field(["company_id","project_id","guid"])
           ->where(["company_id"=>"CP595B5F6F26346102505"])
               ->union(function($query){
                   $query->table("mk_budget_default_book_copy")->field(["company_id","project_id","guid"])->
                   where(["company_id"=>"CP595B5F6F26346102505"])
                   ;
               })->select(false);

dump($list);
    }

    function mk_budget_default_project_copy(){

        $table = "mk_budget_default_project_copy";
        $pid_array = Db::table($table)->where(["pid"=>0])->column("guid");
     //   dump($pid_array);
       $re =  Db::table($table)->where(["pid"=>["not in",$pid_array ],])->where(["pid"=>["<>",0],"status"=>1,])->column("guid");
        dump(Db::table($table)->where(["guid"=>["in",$re]])->count());
        dump($re)  ;
    //    Db::table($table)->where(["guid"=>["in",$re]])->update(["status"=>2]);
  //      dump($pid_array = Db::table($table)->where(["pid"=>0,"project_id"=>"PA5A050CAC3DB96995110"])->count("guid"));
   //     dump(Db::table($table)->where(["project_id"=>"PA5A050CAC3DB96995110"])->count());
}
    public function indexView($page=1,$limit=20){
        $list =  Db::view("mk_project","guid,project_name")
            ->view("mk_project_related_person","uuid","mk_project_related_person.project_id=mk_project.guid")
            ->view("mk_personnel_user","name","mk_personnel_user.uuid=mk_project_related_person.uuid")
            ->where(["mk_project_related_person.department_type"=>2])
            ->where(["mk_project.company_id"=>"CP595B5F6F26346102505"])
            ->limit($limit)
            ->page($page)
            ->order("mk_project.id desc")
            ->select();





        dump($list);
    }
    public function menu(){
        $where['status']=1;
        $where['is_mobile']= empty($is_mobile)? 0 : 1 ;
        $list = Db::table('mk_personnel_node')->field('guid as id,pid,CONCAT(node_name,"(",IFNULL(node_desc,""),")")as text')->where($where)->select();
        dump( Loader::controller('base/Tree')->toTree($list,'id','pid','children')) ;

    }

    function clearHash(){

        dump(RedisHash::instance()->clearAll());

    }

    public function HashDemo(){
       // $online =RedisHash::instance()->setTable("online")->setKey("room1");
        $online =RedisHash::instance()->setHashKey("online:room1");
         $guid = "PU592FB8F95842F575356";
        dump($online->set($guid,serialize(PersonnelUser::quickGet("$guid"))));

        dump($online->get());

        $online->delete($guid);

        dump($online->get());
}


    function sendTemplateMessage($type = "moneyPending"){

        dump(SendTemplateMessage::SendTemplateMessage($type)) ;

    }

    function message(){

        $model = Wechat::message();

        dump($model->getAllPrivateTemplate());

    }





    function hash($guid = "PU592FB8F95842F575356"){
       // RedisHash::instance()->clear();
     //   $user = PersonnelUser::quickGet("$guid");
      //  dump($user);
        $guid = $user["department_id"] ;
        $table="mk_personnel_department";
        $department = RedisHash::quickGet("$table:$guid");
        dump($department["department_name"]);
        dump(RedisHash::quickGet("$table:$guid",["department_name"]));
    }

    function BankTransactionRecord(){
        $save_data = [
            "project_id" => 11111,
            "finance_mode" => 1,
            "book_number" => 12512551,
            "bank_id" => "FA599FF3764DDAA545210",
            "desc" => "asdasdasd",
            "type" => 1,
            "price"=>2651255
        ];

      dump( (new BankTransactionRecord)->createBankTransactionRecord($save_data));

    }




    public function RedisHash(){
        $info = ReturnCode::$return_code;

        $redis = RedisHash::instance();

        $redis->setTable("test")->setKey("guid")->set($info);


      dump($redis->get(1002));
    }


    public function loadExcel(){
        if(empty($this->member_info)){
            return self::showJsonReturnCodeWithOutData(1004);
        }

        try{
            $getExcelObject=Excel::loadExcel("test.xls");
            if(empty($getExcelObject)){
                throw new Exception("导入的EXCEL不存在");
            }
            $sheetName=$getExcelObject->getSheetNames();
            if(empty($sheetName)){
                throw new Exception("导入的EXCEL内容不存在");
            }

        }catch (Exception $e){
            return self::showJsonReturnCodeWithOutData(1049,$e->getMessage());
        }


        dump($sheetName);

        $sheet = $getExcelObject->getSheetByName($sheetName[0])->toArray();

        $title =array_shift($sheet);
        $options = Config::get("import.item");
        dump($title == array_values($options["field_list"]));


        dump($sheet);
    }

    function downLoadPDF(){
        PDF::downLoadPDF("<a>11sdfsdfsdfsdfsdfsdfsdfsdf11</a>");
    }
    function redis(){
        $redis=Redis::instance(["index"=>0]);
        $redis_1=Redis::instance(["index"=>1]);
        $redis_2=Redis::instance(["index"=>2]);
        $redis->set("name","This is mikkle's redis");
        $redis_1->set("name","This is mikkle's redis_1");
        $redis_2->set("name","This is mikkle's redis_2");
        dump( $redis->get("name"));
        dump( $redis_1->get("name"));
        dump( $redis_2->get("name"));
        dump($redis);
        dump($redis_1);
        dump($redis_2);

        $redis=new Redis();
        $redis_1=new Redis(["index"=>1]);
        $redis_2=new Redis(["index"=>2]);
        $redis->set("name","This is mikkle's redis");
        $redis_1->set("name","This is mikkle's redis_1");
        $redis_2->set("name","This is mikkle's redis_2");
        dump( $redis->get("name"));
        dump( $redis_1->get("name"));
        dump( $redis_2->get("name"));
        dump($redis);
        dump($redis_1);
        dump($redis_2);

    }



    public function excel()
    {

        $excel=new Excel();
        $table_name="mk_material_list_edit";
        $field=[
            "xuhao"=>"序号",
            "name"=>"项目名称",
            "unit"=>"项目单位",
            "unit_price"=>"主材单价",
            "unit_profit"=>"主材毛利润",
            "auxiliary_unit"=>"辅材单价",
            "auxiliary_profit"=>"辅材毛利润",
            "artificial_price"=>"人工单价",
            "artificial_profit"=>"人工毛利润",
            "loss_coefficient"=>"主材损耗系数",
            "mechanical_coefficient"=>"机械费系数",
            "desc"=>"备注说明",
        ];
        $map=["status"=>1];
        $map2=["status"=>-1];
        $excel->setExcelName("下载装修项目");
        $excel->setColumnWidth([10,30,8,10,10,10,10,10,10,10,10,80]);


        $excel->createSheetByModel("装修项目","base/MaterialListEdit",$field,$map);
        $excel->createSheet("已删除装修项目",$table_name,$field,$map2);
        $excel->downloadExcel();

    }


    public function i($file_name = "file"){


        if($this->request->isPost()){
            if ($this->request->isPost()) {
                $pic = $this->request->file($file_name);
                if (isset($pic)) {
                    if (is_array($pic) && !is_object($pic)){
                        return self::showReturnCodeWithOutData(1054, "只容许单张图片上传");
                    }
                    $rule=[];
                    return Upload::uploadPicture($pic,"",true,$rule);
                } else {
                    return self::showReturnCodeWithOutData(1010, "上传的图片不存在");
                }
            } else {
                return self::showJsonReturnCodeWithOutData(1002);
            }

        }

        return $this->fetch("upload");




    }





    /**
     * 创建配置文件
     */
    public function buildConfig($path ='base/config/' )
    {
        $data=[1,2,[1,2,3]];

        $config_str = $this->ArrayToString($data);
        dump($config_str);
        PHP_EOL;
        $content = "<?php \n\n return {$config_str} ;\n";
        $file = APP_PATH .$path. "config.php";

        return file_put_contents($file, $content);
    }

    protected function ArrayToString($array){
        if (is_array($array)){
            $str='';
            foreach ($array as $item=>$value){
                if(is_array($value)){
                    if (!is_integer($item)){
                        $str .= "{$item}=>{$this->ArrayToString($value)},\n";
                    }else{
                        $str .="{$this->ArrayToString($value)},\n";
                    }
                }else{
                    if (!is_integer($item)){
                        $str .= "{$item}=>{$value},\n";
                    }else{
                        $str .= "{$value},\n";
                    }
                }
            }
            $str_return ="[\n$str]";
        }else{
            $str_return ="[]";
        }
        return $str_return;
    }
}