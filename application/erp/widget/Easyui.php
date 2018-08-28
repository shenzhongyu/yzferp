<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/12
 * Time: 14:12
 */

namespace app\erp\widget;


use app\erp\config\FieldValue;
use app\erp\config\ListUrl;
use think\Controller;
use think\Exception;

use think\Loader;
use think\Session;

class Easyui extends Controller
{
    public function showAll($name,$array){


        if (!is_array($array)){
            $field_value = new FieldValue();
            if(isset($field_value->$array)){
                $array = $field_value->$array;
                if ($name=="customer_source"){
                   $m=Session::get('member_info','Global');
                   if (isset($m['company_id'])){
                       switch ($m['company_id']){
                           case 'CP595B5F6F26346102505':
                           case 'CP595DC08091DCD485749':
                           case 'CP595DC0AA7E982975510':
                           case 'CP595DC0C086B1A485654':
                           case 'CP5993ADA2D3F6E501005':
                               break;
                           default:
                               unset($array['25']);
                               unset($array['26']);
                               break;
                       }
                   }

                }
            }
        }
        $array_str="[";
        $value_str="{";
        foreach ($array as $item=>$value){
            $array_str.="{\"value\":\"{$item}\",\"text\":\"{$value}\"},";
            $value_str.="'{$item}':'{$value}',";
        }
        $array_str.="]";
        $value_str.="}";
        return "
         var array_{$name}={$array_str};
         var value_{$name}={$value_str};
        ";
    }

    public function showArray($name,$array){
        $field_value = new FieldValue();
        if (!is_array($array)){

            if(isset($field_value->$array)){
                $array = $field_value->$array;
            }
        }


        $array_str="[";
        foreach ($array as $item=>$value){
            $array_str.="{\"value\":\"{$item}\",\"text\":\"{$value}\"},";
        }
        $array_str.="]";

        return "
         var array_{$name}={$array_str};
        ";
    }

    public function showValue($name,$array){
        if (!empty( $array)) {

        }


        if (!is_array($array)){
            $field_value = new FieldValue();
            if(isset($field_value->$array)){
                $array = $field_value->$array;
            }
        }
        $value_str="{";
        try {
            foreach ($array as $item=>$value){

                if(is_array($value )){

                }
                $value_str.="'{$item}':'{$value}',";
            }
            $value_str.="}";
        }catch (Exception $e){


        }

        return "
         var value_{$name}={$value_str};
        ";
    }
    public function showValueByUrl($name,$label,$map=[]){
        $list_url = new ListUrl();
        if (isset($list_url->$label)){
            $label= $list_url->$label;
           $array =  Loader::model($label['model_name'])->getEasyUilist($label['field_id'],$label['field_text'],$map);

            return $this->showValue($name,$array);

        }

    }

    public function showArrayByUrl($name,$label,$map=[]){
        $list_url = new ListUrl();
        if (isset($list_url->$label)){
            $label= $list_url->$label;
            $array =  Loader::model($label['model_name'])->getEasyUilist($label['field_id'],$label['field_text'],$map);
            return $this->showArray($name,$array);
        }

    }

    public function showAllByUrl($name,$label,$map=[]){
        $list_url = new ListUrl();
        if (isset($list_url->$label)){
            $label= $list_url->$label;
            $array =  Loader::model($label['model_name'])->getEasyUilist($label['field_id'],$label['field_text'],$map);
            return $this->showAll($name,$array);
        }

    }









    public function showFilterByUrl($name,$url){

        $url = url($url);
            return"
        { field: '{$name}', type: 'combobox', options:{
            panelHeight: 'auto',
            url: '{$url}',
            onChange: function (value) {
                  if (value == ''|| value == 'all' ) {
                    $(mikkleClass.datagrid).datagrid('removeFilterRule', '{$name}');
                  } else {
                    $(mikkleClass.datagrid).datagrid('addFilterRule', {
                                field: '{$name}',
                                op: 'equal',
                                value: value,
                                type: 'text'
                            });
                  }
                 $(mikkleClass.datagrid).datagrid('doFilter');
               }
            }
         },";
        }



    public function formatter(){
        return "formatter:function(value,row,index){return mikkleClass.formatter(value,row,index,this.field)}";
    }

    public function showFilter($name){
        return"
        { field: '{$name}', type: 'combobox', options:{
            panelHeight: 'auto',
            data: this.array_{$name},
            onChange: function (value) {
                  if (value == ''|| value == 'all' ) {
                    $(mikkleClass.datagrid).datagrid('removeFilterRule', '{$name}');
                  } else {
                    $(mikkleClass.datagrid).datagrid('addFilterRule', {
                                field: '{$name}',
                                op: 'equal',
                                value: value,
                                type: 'text'
                            });
                  }
                 $(mikkleClass.datagrid).datagrid('doFilter');
               }
            }
         },";
    }

    public function showInput($field,$name,$options){
        return "<section>
        <label for=\"{$field}\" class=\"label_1\">{$name}:</label>
        <label for=\"{$field}\" class=\"label_2\">
            <input class=\"easyui-validatebox\" type=\"text\" name=\"{$field}\"
                   data-options=\"{$options}\" placeholder=\"请填写{$name}\"
                   style=\"width: 80%\"></input>
        </label>
    </section>";
    }

    // 过滤 不安全的html标签，输出安全的html
    function op_h($text, $type='html')
    {
        $text_tags = ''; // 无标签格式
        $link_tags = '<a>'; // 只保留链接
        $image_tags = '<img>'; // 只保留图片
        $font_tags = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>'; // 只存在字体样式
        $base_tags = $font_tags . '<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>'; // 标题摘要基本格式
        $form_tags = $base_tags . '<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>'; // 兼容Form格式
        $html_tags = $base_tags . '<ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>'; // 内容等允许HTML的格式
        $all_tags = $form_tags . $html_tags . '<!DOCTYPE><meta><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>'; // 专题等全HTML格式
        $text = $this->real_strip_tags($text, ${$type . '_tags'}); // 过滤标签

        if ( $type != 'all' ) { // 过滤攻击代码
            while ( preg_match('/(<[^><]+)(ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background[^-]|codebase|dynsrc|lowsrc)([^><]*)/i', $text, $mat) ) { // 过滤危险的属性，如：过滤on事件lang js
                $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
            }
            while ( preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat) ) {
                $text = str_ireplace($mat[0], $mat[1] . $mat[3], $text);
            }
        }
        return $text;
    }
// 过滤
    function html($text)
    {
        return $this->op_h($text);
    }
    // --- builder
    function real_strip_tags($str, $allowable_tags="")
    {
        // $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
        return strip_tags($str, $allowable_tags);
    }


}