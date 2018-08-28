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
use think\Config;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;
use think\Session;

class Update extends Base
{


    public function checkUpdate(){
        if ($this->request->isPost()){
            $version=$this->readVersion();
            $url='http://wechat.yzferp.com/center/update/versionCheck';
            $re=Curl::curlPost($url,["version"=>$version,'key'=>'123']);
            $arr=json_decode($re,true);
            if (empty($arr)) return self::showJsonReturnCode(1003);
            if($arr["code"]=="1001"){
                $desc=$arr["data"]["desc"];
                $desc=explode("；",$desc);
                $list=[
                    "version"=>$arr["data"]["version"],
                ];
                $file=APP_PATH.'install/extra/version.php';
                //缓存
                $text='<?php return '.var_export($list,true).';';
                if(false!==fopen($file,'w+')){
                    file_put_contents($file,$text);
                }else{
                    return self::showJsonReturnCode(1003);
                }
                return self::showJsonReturnCode(1001,["desc"=>$desc,"version"=>$arr["data"]["version"]],"操作成功");

            }else{
                return json($arr);
            }
        }else{
            $arr=[];
            $this->assign($arr);
            return $this->fetch('check_update');
        }
    }



    public  function update(){
        return self::showJsonReturnCode(1001);
        $deposit_path=RUNTIME_PATH.'version/text.zip';  //下载更新包存放位置
        $this->checkDirBuild(RUNTIME_PATH.'version');
        $upload_url='http://wechat.yzferp.com/update/text.zip'; //下载路径
        $compress_path='version/update'; //解压路径

        $res=Curl::curlDownload($upload_url,$deposit_path);
        if($res!=false){

            $zip = new \ZipArchive();
            $zip->open($deposit_path);
            $re=$zip->extractTo(RUNTIME_PATH.$compress_path);
            if($re==true){
                $arr=[];
                halt(RUNTIME_PATH.$compress_path."/test");
                $arr=$this->tree($arr,RUNTIME_PATH.$compress_path."/test");
                halt($arr);
            }
        }





//        $deposit_path=RUNTIME_PATH.'version/text.zip';  //下载更新包存放位置
//        $this->checkDirBuild(RUNTIME_PATH.'version');
//        $upload_url='http://wechat.yzferp.com/update/text.zip'; //下载路径
//        $compress_path=RUNTIME_PATH.'version/update'; //解压路径
//
//        $res=Curl::curlDownload($upload_url,$deposit_path);
//        if($res!=false){
//            $zip = new \ZipArchive();
//            $zip->open($deposit_path);
//            $re=$zip->extractTo($compress_path);
//            if($re==true){
//                $arr=[
//                    [   'path'=>'test/applyRepare.html',
//                        'write'=>'wechat/view/project/applyRepare.html',
//                        'type'=>'txt',
//                    ],
//                    [   'path'=>'test/Project.php',
//                        'write'=>'wechat/controller/Project.php',
//                        'type'=>'txt',
//                    ],
//                    [   'path'=>'test/repareDetails.html',
//                        'write'=>'wechat/view/project/repareDetails.html',
//                        'type'=>'txt',
//                    ],
//                    [   'path'=>'test/repareDetailsCustomer.html',
//                        'write'=>'wechat/view/project/repareDetailsCustomer.html',
//                        'type'=>'txt',
//                    ],
//                    [   'path'=>'test/repareList.html',
//                        'write'=>'wechat/view/project/repareList.html',
//                        'type'=>'txt',
//                    ],
//                    [   'path'=>'test/repareListCustomer.html',
//                        'write'=>'wechat/view/project/repareListCustomer.html',
//                        'type'=>'txt',
//                    ],
//                ];
//                foreach ($arr as $value){
//                    $file_path=$compress_path.$value['path'];
//                    $content=$this->readUpdateField($file_path);
//                    switch ($value['type']){
//                        case 'sql':
//
//                            break;
//                        case 'txt':
//                            $flag=$this->writeUpdateField(APP_PATH.$value['write'],$content);
//                            if ($flag!=true){
//                                return self::showJsonReturnCode(1003,[],'更新失败');
//                            }
//                            break;
//
//                        default:
//                            break;
//                    }
//                }
//                return self::showJsonReturnCode(1003,[],'更新成功');
//
//            }
//        }

    }
    protected function tree($arr_file, $directory, $dir_name='')
    {

        $mydir = dir($directory);
        while($file = $mydir->read())
        {
            if((is_dir("$directory/$file")) AND ($file != ".") AND ($file != ".."))
            {
                $this->tree($arr_file, "$directory/$file", "$dir_name/$file");
            }
            else if(($file != ".") AND ($file != ".."))
            {
                $arr_file[] = "$dir_name/$file";
            }
        }
        $mydir->close();
        return $arr_file;
    }

}