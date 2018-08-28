<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/24
 * Time: 16:45
 */

namespace app\base\model;


class BaseHashText extends BaseHash
{
    protected $table="mk_we_template_message";

    protected $autoWriteTimestamp = true;
    protected $hashKey="guid";

}