<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/25
 * Time: 10:21
 */

namespace app\base\model;


use app\base\controller\WxApi;
use app\base\controller\Tree;
class WeMenu extends Base
{
    protected $table = "mk_we_menu";
    protected $insert = ['status'=>1,'guid'];
    protected $autoWriteTimestamp = true;
    protected $buttonType=[
        'rselfmenu_0_0'=>'scancode_waitmsg',
        'rselfmenu_0_1'=>'scancode_push',
        'rselfmenu_1_0'=>'pic_sysphoto',
        'rselfmenu_1_1'=>'pic_photo_or_album',
        'rselfmenu_1_2'=>'pic_weixin',
        'rselfmenu_2_0'=>'location_select',
    ];

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
        }
        if (empty($field)){
            $field = '';
        }
        return $this->where($map)->field($field)->select();

    }

    public function sendMenu($guid){
        //$appid=$this->getPerByGuId($guid)->appid;
        $buttons=db('WeMenuButton')->where('menu',$guid)->where('status','>',0)->order('sort', 'desc')->select();



        $tree = new Tree();
        $buttons = $tree->toTree($buttons, $pk = 'guid', $pid = 'pid', $child = 'sub_button');


        $my_menu['button']=$buttons;


        $model=new WxApi();
       return $model->createMenu($my_menu);
    }

    protected function formatMenu($button){

        if(strstr($button['key'],'http://')){
            return [
                'type' => 'view'
            ];

        } else {


            if (array_key_exists($button['key'],$this->buttonType)){
                $type= $this->buttonType[$button['key']];
            }else{
                $type= 'click';
            }
            return array(
                'type' => $type,
                'key' => $button['key']
            );
        }
    }




}