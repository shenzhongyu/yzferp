<?php
// +----------------------------------------------------------------------
// | my
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\base\model;

use think\Model;

class We extends Base
{

    protected $table = "mk_we";

    /**
     * 获取微信配置信息，需要缓存
     */
    public function info($appid){
       if (is_numeric($appid)){
        $map['id']=$appid;
      }else{
        $map['appid']=$appid;
      }
      $options = $this->where($map)->find();
      return $options;
    }

    


    public function getList($map = [],$field=false){
        if (empty($map)){
            $map = ['status'=>1];
        }
        if (empty($field)){
            $field = 'id as id,title as text';
        }
        return $this->where($map)->field($field)->select();

    }

    
}