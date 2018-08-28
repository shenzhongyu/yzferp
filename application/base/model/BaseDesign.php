<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/7/31
 * Time: 10:58
 */

namespace app\base\model;


class BaseDesign extends Base
{
    protected  $field_list=[
        "unit_price"=>"unit_price",   //主材单价
        "unit_profit"=>"unit_profit" ,   //主材毛利润
        "auxiliary_unit"=>"auxiliary_unit",  //辅材单价
        "auxiliary_profit"=>"auxiliary_profit", //辅材毛利润
        "artificial_price"=>"artificial_price", //人工单价
        "artificial_profit"=>"artificial_profit",//人工毛利润
        "loss_coefficient"=>"loss_coefficient", //主材损耗系数
        "mechanical_coefficient"=>"mechanical_coefficient",//机械费系数
    ];


    public function countQuote($map,$table=""){
        if(!$map){
            return self::showReturnCodeWithOutData(1003,"查询参数缺失");
        }
        $map["status"]=1;
        $field = $this->field_list;
        $table=isset($table)?$table:$this->table;
        $list = $this->field($field)->table($table)->where($map)->select();
        if($list){

            $total_count = 0 ;

            foreach($list as $value){
                if(is_object($value)) {
                    $value = $value->toArray();
                }
                $count_array=$this->countPrice($value) ;
                if(isset($count_array["data"])){
                    $total_count+=$count_array["data"];
                }else{
                    self::showReturnCodeWithOutData(1003,"查询参数出错");
                }
            }

            return self::showReturnCode(1001,$total_count);

        }else{
            return self::showReturnCodeWithOutData(1003,"查询数据不存在");
        }

    }

    public function countPrice($data){
        if(!$this->checkCountDate($data)) return self::showReturnCodeWithOutData(1003,$this->error);
        $count_data=[
            "unit_price"=>$data["unit_price"],   //主材单价
            "unit_profit"=>$data["unit_profit"] ,   //主材毛利润
            "auxiliary_unit"=>$data["auxiliary_unit"],  //辅材单价
            "auxiliary_profit"=>$data["auxiliary_profit"], //辅材毛利润
            "artificial_price"=>$data["artificial_price"], //人工单价
            "artificial_profit"=>$data["artificial_profit"],//人工毛利润
            "loss_coefficient"=>$data["loss_coefficient"], //主材损耗系数
            "mechanical_coefficient"=>$data["mechanical_coefficient"],//机械费系数
        ];

        $result =  $this->countPriceDo($count_data);
        if($result||$result==0){
            return self::showReturnCode(1001,$result);
        }else{
            return self::showReturnCodeWithOutData(1003,'计算错误');
        }
    }
    public function countPriceForArr($data){ //获取单一的价格
        if(!$this->checkCountDate($data)) return self::showReturnCodeWithOutData(1003,$this->error);
        $count_data=[
            "unit_price"=>$data["unit_price"],   //主材单价
            "unit_profit"=>$data["unit_profit"] ,   //主材毛利润
            "auxiliary_unit"=>$data["auxiliary_unit"],  //辅材单价
            "auxiliary_profit"=>$data["auxiliary_profit"], //辅材毛利润
            "artificial_price"=>$data["artificial_price"], //人工单价
            "artificial_profit"=>$data["artificial_profit"],//人工毛利润
            "loss_coefficient"=>$data["loss_coefficient"], //主材损耗系数
            "mechanical_coefficient"=>$data["mechanical_coefficient"],//机械费系数
        ];

        $result =  $this->countPriceDoArr($count_data);
        if($result||$result==0){
            return self::showReturnCode(1001,$result);
        }else{
            return self::showReturnCodeWithOutData(1003,'计算错误');
        }
    }

    /**
     * 检验数据
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $data
     * @return bool
     */
    private function checkCountDate($data){
        foreach( $this->field_list as $value){
            if(!isset($data[$value])){
                $this->error ="字段[{$data[$value]}]不存在";
                return false;
            }
            if( !is_numeric($data[$value])){
                $this->error ="字段[{$data[$value]}]数据类型非数值型";
                return false;
            }

        }
        //百分数据不能小于0
        if ($data["unit_profit"]<0 || $data["auxiliary_profit"]<0 || $data["artificial_profit"]<0||$data["loss_coefficient"]<0||$data["mechanical_coefficient"]<0 ){
            $this->error ="字段数据不应小于0";
            return false;
        }


        return true;

    }

    private function countPriceDo($count_data){
        //计算公式
//        $price_formula_string="{$count_data['unit_price']}/(1-{$count_data['unit_profit']}+{$count_data['loss_coefficient']}+{$count_data['mechanical_coefficient']})";
//        $price_formula_string.="+{$count_data['auxiliary_unit']}/(1-{$count_data['auxiliary_profit']})";
//        $price_formula_string.="+{$count_data['artificial_price']}/(1-{$count_data['artificial_profit']})";
        $price_formula_string="{$count_data['unit_price']}/(1-{$count_data['unit_profit']})";
        $price_formula_string.="+{$count_data['auxiliary_unit']}/(1-{$count_data['auxiliary_profit']})";
        $price_formula_string.="+{$count_data['artificial_price']}/(1-{$count_data['artificial_profit']})";
        $price_formula_string.="+{$count_data['unit_price']}*{$count_data['loss_coefficient']}";
        $price_formula_string.="+{$count_data['unit_price']}*{$count_data['mechanical_coefficient']}";

        $result = eval("return $price_formula_string;");
        
        return $result ;
    }

    private function countPriceDoArr($count_data){
        //计算公式
        $price_unit="{$count_data['unit_price']}/(1-{$count_data['unit_profit']})";
        $price_auxiliary="{$count_data['auxiliary_unit']}/(1-{$count_data['auxiliary_profit']})";
        $price_artificial="{$count_data['artificial_price']}/(1-{$count_data['artificial_profit']})";
        $price_loss="{$count_data['unit_price']}*{$count_data['loss_coefficient']}";
        $price_mechanical="{$count_data['unit_price']}*{$count_data['mechanical_coefficient']}";
        $arr=[
            'price_unit'=>eval("return $price_unit;"), //主材单价
            'price_auxiliary'=>eval("return $price_auxiliary;"), //辅材单价
            'price_artificial'=>eval("return $price_artificial;"), //人工单价
            'price_loss'=>eval("return $price_loss;"), //损耗单价
            'price_mechanical'=>eval("return $price_mechanical;"), //机械单价
        ];
        $arr['price_sum']=$arr['price_unit']+$arr['price_auxiliary']+$arr['price_artificial']+$arr['price_loss']+$arr['price_mechanical'];
        return $arr ;
    }

}