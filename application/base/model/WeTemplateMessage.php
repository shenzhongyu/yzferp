<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/27
 * Time: 14:48
 */

namespace app\base\model;


class WeTemplateMessage extends Base
{
    protected $table = 'mk_we_template_message';
    protected $autoWriteTimestamp = true;
    public function infoByMsgId($key, $field = true){
        if (!$key) return false;
        $map['MsgID'] = $key;
        return $this->field($field)->where($map)->find();
    }
    public function updateTemplateMessage($MsgID){
        $save_data['status'] = 1;
        return $this->update($save_data,['MsgID'=>$MsgID]);

    }

}