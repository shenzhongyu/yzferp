<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/3
 * Time: 12:02
 */

namespace app\base\validate;


use think\Db;
use think\Loader;
use think\Validate;
use think\exception\ClassNotFoundException;
class Base extends Validate
{

    /**
     * 验证是否唯一
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则 格式：数据表,字段名,排除ID,主键名
     * @param array     $data  数据
     * @param string    $field  验证字段名
     * @return bool
     */
    protected function uniqueGuid($value, $rule, $data, $field)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        if (false !== strpos($rule[0], '\\')) {
            // 指定模型类
            $db = new $rule[0];
        } else {
            try {
                $db = Loader::model($rule[0]);
            } catch (ClassNotFoundException $e) {
                $db = Db::name($rule[0]);
            }
        }
        $key = isset($rule[1]) ? $rule[1] : $field;

        if (strpos($key, '^')) {
            // 支持多个字段验证
            $fields = explode('^', $key);
            foreach ($fields as $key) {
                if (!isset($data[$key])&&$key=="status"){
                    $map[$key] = 1;
                }elseif(!isset($data[$key])){

                }else{
                    $map[$key] = $data[$key];
                }
            }
        } elseif (strpos($key, '=')) {
            parse_str($key, $map);
        } else {
            $map[$key] = $data[$field];
        }

        $pk = "guid";

        if (isset($rule[2])) {
            if(!empty($rule[2])){
                $map[$pk] = ['neq', $rule[2]];
            }
        }

        if (isset($data[$pk])) {
            $map[$pk] = ['neq', $data[$pk]];
        }

        if ($db->where($map)->field($pk)->find()) {

            return false;
        }
        return true;
    }


    /**
     * 验证是否唯一
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则 格式：数据表,字段名,排除ID,主键名
     * @param array     $data  数据
     * @param string    $field  验证字段名
     * @return bool
     */
    protected function uniqueUuid($value, $rule, $data, $field)
    {
        if (is_string($rule)) {
            $rule = explode(',', $rule);
        }
        if (false !== strpos($rule[0], '\\')) {
            // 指定模型类
            $db = new $rule[0];
        } else {
            try {
                $db = Loader::model($rule[0]);
            } catch (ClassNotFoundException $e) {
                $db = Db::name($rule[0]);
            }
        }
        $key = isset($rule[1]) ? $rule[1] : $field;

        if (strpos($key, '^')) {
            // 支持多个字段验证
            $fields = explode('^', $key);
            foreach ($fields as $key) {
                if (!isset($data[$key])&&$key=="status"){
                    $map[$key] = 1;
                }elseif(!isset($data[$key])){

                }else{
                    $map[$key] = $data[$key];
                }
            }
        } elseif (strpos($key, '=')) {
            parse_str($key, $map);
        } else {
            $map[$key] = $data[$field];
        }

        $pk = "uuid";

        if (isset($rule[2])) {
            if(!empty($rule[2])){
                $map[$pk] = ['neq', $rule[2]];
            }
        }

        if (isset($data[$pk])) {
            $map[$pk] = ['neq', $data[$pk]];
        }

        if ($db->where($map)->field($pk)->find()) {

            return false;
        }
        return true;
    }


}