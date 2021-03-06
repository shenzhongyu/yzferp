<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/18
 * Time: 9:49
 */

namespace app\erp\controller;

use app\base\controller\Rsa;
use app\base\model\MaterialListEdit;
use app\erp\config\Config;
use app\erp\config\FieldValue;
use app\mk\controller\MkPages;
use mikkle\tp_wechat\support\Db;


class Test extends Base
{
    protected $b=array();
    function _initialize()
    {
        config('default_return_type','html');
        parent::_initialize(); // TODO: Change the autogenerated stub
    }


    public function rsa(){
        header('Content-Type:textml;Charset=utf-8;');

        $string = isset($_GET['a']) ? $_GET['a'] : '测试123';

        $pubfile = ROOT_PATH.'ssl/test.crt';
        $prifile = ROOT_PATH.'ssl/test.pem';

        $rsa =new Rsa($pubfile,$prifile);

        echo "<pre>";
        //生成签名
        echo "\n签名的字符串:\n$string\n\n";
        $sign = $rsa->sign($string);
        echo "\n生成签名的值:\n$sign";

        //验证签名
        $p=$rsa->verify($string, $sign);
        echo "\n验证签名的值:\n$p";


        //加密
        echo "\n\r加密的字符串:\n$string\n\n";
        $x = $rsa->encrypt($string);
        echo "\n生成加密的值:\n$x";

        //解密
        $y = $rsa->decrypt($x);
        echo "\n解密的值:\n$y";
        echo "</pre>";

        //创建新的密匙
        echo "\n创建新的密匙:\n";
        $rsa->buildNewKey();

    }



    function index(){
        $mk = new MkPages();
        $mk->setTitle('标题')
        ->keyText('id',"ID")
            ->keySelectValue('type','类型')

        ;



        dump(gd_info());
        return dump($mk);

    }


        function index2($a=0){


        $a = 'users_picture/save';
        var_dump(ltrim($a,'usersa_picture/'));







    }

    function index3(){
        $mk = new MkPages();
       return $mk->fetch('base');

    }

    function json(){
        $data='{ "total": 15261 ,"rows":[{"Id":"15261","username":"mikkle","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"121.35.45.219","AddTime":"2017\/4\/18 8:56:05"},{"Id":"15260","username":"mikkle","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.97.52.255","AddTime":"2017\/4\/11 17:04:01"},{"Id":"15259","username":"mikkle","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.97.52.255","AddTime":"2017\/4\/11 16:59:18"},{"Id":"15258","username":"tenghaifeng","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.108.121.75","AddTime":"2017\/3\/1 14:18:39"},{"Id":"15257","username":"tenghaifeng","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.108.121.75","AddTime":"2017\/2\/22 17:12:37"},{"Id":"15256","username":"tenghaifeng","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.108.121.75","AddTime":"2016\/12\/16 12:06:36"},{"Id":"15255","username":"tenghaifeng","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.108.121.75","AddTime":"2016\/12\/1 17:57:30"},{"Id":"15254","username":"tenghaifeng","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.108.121.75","AddTime":"2016\/12\/1 17:55:28"},{"Id":"15253","username":"tenghaifeng","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/192.168.1.210\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/192.168.1.210\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"192.168.1.101","AddTime":"2016\/12\/1 17:54:45"},{"Id":"15252","username":"wuzhifang","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.108.121.75","AddTime":"2016\/11\/17 17:32:45"},{"Id":"15251","username":"tenghaifeng","Log_type":"DellContent","Log_content":"\u5220\u9664\u5546\u6237\u4FE1\u606F\uFF01","Log_data":"\u5220\u9664\u5546\u6237Id\u4E3A\u30108616\u3011\u7684\u5546\u6237\u4FE1\u606F\u3002","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/ShangHu_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:41:40"},{"Id":"15250","username":"tenghaifeng","Log_type":"DellContent","Log_content":"\u5220\u9664\u5546\u6237\u4FE1\u606F\uFF01","Log_data":"\u5220\u9664\u5546\u6237Id\u4E3A\u301010953\u3011\u7684\u5546\u6237\u4FE1\u606F\u3002","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/ShangHu_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:40:50"},{"Id":"15249","username":"tenghaifeng","Log_type":"DellContent","Log_content":"\u5220\u9664\u5546\u6237\u4FE1\u606F\uFF01","Log_data":"\u5220\u9664\u5546\u6237Id\u4E3A\u301010978\u3011\u7684\u5546\u6237\u4FE1\u606F\u3002","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/ShangHu_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:40:00"},{"Id":"15248","username":"tenghaifeng","Log_type":"DellContent","Log_content":"\u5220\u9664\u5546\u6237\u4FE1\u606F\uFF01","Log_data":"\u5220\u9664\u5546\u6237Id\u4E3A\u301011130\u3011\u7684\u5546\u6237\u4FE1\u606F\u3002","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/ShangHu_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:39:21"},{"Id":"15247","username":"tenghaifeng","Log_type":"DellContent","Log_content":"\u5220\u9664\u5546\u6237\u4FE1\u606F\uFF01","Log_data":"\u5220\u9664\u5546\u6237Id\u4E3A\u301011132\u3011\u7684\u5546\u6237\u4FE1\u606F\u3002","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/ShangHu_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:39:09"},{"Id":"15246","username":"tenghaifeng","Log_type":"DellContent","Log_content":"\u5220\u9664\u5546\u6237\u4FE1\u606F\uFF01","Log_data":"\u5220\u9664\u5546\u6237Id\u4E3A\u301011199\u3011\u7684\u5546\u6237\u4FE1\u606F\u3002","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/ShangHu_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:30:28"},{"Id":"15245","username":"tenghaifeng","Log_type":"Login","Log_content":"\u7528\u6237\u767B\u5F55\uFF01","Log_data":"\u6765\u6E90\u5730\u5740\uFF1Ahttp:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/login.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:30:03"},{"Id":"15244","username":"tenghaifeng","Log_type":"EditAddman","Log_content":"\u4FEE\u6539\u64CD\u4F5C\u5458\u4FE1\u606F\uFF01","Log_data":"\u64CD\u4F5C\u5458Id\u301026\u3011\uFF0C\u539F\u4FE1\u606F\uFF1A9,liuyuanmei,liuyuanmei123,\u5218\u8FDC\u5A9A,1,0,\u65B0\u4FE1\u606F\uFF1A9,liuyuanmei,liuyuanmei123,\u5218\u8FDC\u5A9A,0,0","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/addman_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:22:43"},{"Id":"15243","username":"tenghaifeng","Log_type":"EditAddman","Log_content":"\u4FEE\u6539\u64CD\u4F5C\u5458\u4FE1\u606F\uFF01","Log_data":"\u64CD\u4F5C\u5458Id\u301026\u3011\uFF0C\u539F\u4FE1\u606F\uFF1A9,liuyuanmei,liuyuanmei123,\u5218\u8FDC\u5A9A,0,0,\u65B0\u4FE1\u606F\uFF1A9,liuyuanmei,liuyuanmei123,\u5218\u8FDC\u5A9A,1,0","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/addman_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:22:29"},{"Id":"15242","username":"mikkle","Log_type":"DellAddMan","Log_content":"\u5220\u9664\u64CD\u4F5C\u5458\u4FE1\u606F\uFF01","Log_data":"\u5220\u9664\u64CD\u4F5C\u5458Id\u4E3A\u301011\u3011\u7684\u7528\u6237\u4FE1\u606F\u3002","Log_Url":"http:\/\/cf.haodecaifu.com\/MkCms_Html\/HaoDeShangHu_Admin\/show\/addman_List_show.html","Ip":"113.108.121.75","AddTime":"2016\/11\/16 17:11:42"}]}';
        return json(json_decode($data));
    }



    function show($url){
        return $this->fetch($url);
    }

    //更新数据库方法
    public function updateDb(){
        $dbname=Config::get("database");
        $a=new DatabaseUpgrade();
        try{
            $a->updateDatabase($dbname["database"],'yzfErp');
        }catch (Exception $e){
            Log::error($e->getMessage());
        }
    }


//    public function saveData(){
//        $list=Db::table('mk_personnel_role_node_access')->where(['role_id'=>'RB5A051445D50E4531005'])->select();
//        $file=APP_PATH.'install/extra/sql_role_node_data.php';
//        //缓存
//        $text='<?php return '.var_export($list,true).';';
//        if(false!==fopen($file,'w+')){
//            file_put_contents($file,$text);
//            echo '创建成功';
//        }else{
//            echo '创建失败';
//        }
//    }

//    public function saveData(){
//        $list=Db::table('mk_personnel_node')->where(['status'=>'1','auth_grade'=>1])->select();
//        $file=APP_PATH.'install/extra/sql_node_data.php';
//        //缓存
//        $text='<?php return '.var_export($list,true).';';
//        if(false!==fopen($file,'w+')){
//            file_put_contents($file,$text);
//            echo '创建成功';
//        }else{
//            echo '创建失败';
//        }
//    }


//    public function saveData(){
//        $list=Db::table('mk_material_list_edit')->where(['status'=>1,'type'=>1,'company_id'=>'CP595B5F6F26346102505'])->select();
//        $model=new MaterialListEdit();
//        foreach ($list as $item=> $value){
//            unset($list[$item]['id']);
//            unset($list[$item]['guid']);
//            $list[$item]['type']=3;
//            $list[$item]['package_id']="QT5A0BE06208A26504856";
//            $list[$item]['package_type']="QT5A0BD57F7748B102555";
//        }
//        $model->saveAll($list);
//    }


    //
//    public function saveData(){
//        $list=Db::connect('mysql://yzfErp:yzfErp@127.0.0.1:3306/yzfErp#utf8')->table('mk_personnel_role')->where(['company_id'=>'CP5919455BF27FA981025','status'=>1])->select();
//        //        $install_db = Db::connect('mysql://zouozehua:zouozehua@119.23.27.179:3306/yzf#utf8');
//        foreach ($list as $item=>$value){
//            unset($list[$item]['id']);
//            unset($list[$item]['company_id']);
////            $install_db->table('mk_personnel_role_node_access')->insert($value);
//        }
//        $file=APP_PATH.'install/extra/default_role_data.php';
//        //缓存
//        $text='<?php return '.var_export($list,true).';';
//        if(false!==fopen($file,'w+')){
//            file_put_contents($file,$text);
//            echo '创建成功';
//        }else{
//            echo '创建失败';
//        }
//        //
//    }
//    public function saveRoleData(){
//        $role=Config::get('default_role_data');
//        $list=[];
//        foreach ($role as $value){
//            $list[]=Db::connect('mysql://yzfErp:yzfErp@127.0.0.1:3306/yzfErp#utf8')->table('mk_personnel_role_node_access')->where(['role_id'=>$value['guid']])->select();
//        }
//        //        $install_db = Db::connect('mysql://zouozehua:zouozehua@119.23.27.179:3306/yzf#utf8');
//        foreach ($list as $item=>$value){
//            unset($list[$item]['id']);
////            $install_db->table('mk_personnel_role_node_access')->insert($value);
//        }
//        $file=APP_PATH.'install/extra/default_role_node_data.php';
//        //缓存
//        $text='<?php return '.var_export($list,true).';';
//        if(false!==fopen($file,'w+')){
//            file_put_contents($file,$text);
//            echo '创建成功';
//        }else{
//            echo '创建失败';
//        }
//        //
//    }
//    public function saveRoleNodeData(){
//        $role=Config::get('default_role_node_data');
//        $list=[];
//        foreach ($role as $value){
//            foreach ($value as $item){
//                $list[]=$item;
//            }
//        }
//        //        $install_db = Db::connect('mysql://zouozehua:zouozehua@119.23.27.179:3306/yzf#utf8');
//        foreach ($list as $item=>$value){
//            unset($list[$item]['id']);
////            $install_db->table('mk_personnel_role_node_access')->insert($value);
//        }
//        $file=APP_PATH.'install/extra/default_role_node_access_data.php';
//        //缓存
//        $text='<?php return '.var_export($list,true).';';
//        if(false!==fopen($file,'w+')){
//            file_put_contents($file,$text);
//            echo '创建成功';
//        }else{
//            echo '创建失败';
//        }
//        //
//    }

      public function showData(){

      }
}