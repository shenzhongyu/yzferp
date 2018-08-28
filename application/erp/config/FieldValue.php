<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/5/16
 * Time: 16:00
 */

namespace app\erp\config;



class FieldValue
{
    public static $instance;
    public $design_type=['1'=>'量房阶段','2'=>'设计阶段'];

    /*默认的预算协议*/
    public $desc='';
    /**/
    public  $status = ['' => '全部', '-1' => '已删除', 0 => '禁用', 1 => '正常'];
    public  $status_value = ['-1' => '<font color=red>已删除</font>', 0 => '禁用', 1 => '正常'];
    public  $sex = ['' => '全部', 1=> '男', 2 => '女'];
    public  $sex_value = [ 1=> '男', 2 => '女'];
    public  $is_menu = [''=>'全部',0=>'隐藏',1=> '显示'];
    public  $statistics = [''=>'全部',0=>'否',1=> '是'];
    public  $auth_grade = [''=>'全部',0=>'不登录',1=> '需登录'];
    public  $budget_type = [1=>'家装',2=> '工装',3=>'套餐'];
    public  $waste_single_type=[1=>'对手抢单',2=> '客户原因',3=> '其他原因'];
    //是否在优秀团队显示
    public  $hide=[1=>'显示',-1=> '不显示'];
    //部门类型
    public  $department_type = [''=>'--',1=>'总经办',2=> '人事部',3=> '设计部',4=> '工程部',5=> '财务部',6=> '市场部',7=> '材料部',8=> '客服部'];
    //项目阶段
    public  $project_phase = [''=>'--',1=>'潜在项目',2=> '设计项目',3=> '预算项目',4=> '待建项目',5=> '在建项目',6=> '完工项目'];
    //反馈阶段
    public  $feedback_stage = [''=>'--',1=>'业务阶段',2=> '工程阶段',3=> '设计阶段',4=> '预算阶段'];
    //项目来源
    public  $customer_source = [''=>'--',1=>'电话来访',2=>'上门咨询',3=>'网站咨询',4=> '朋友介绍',5=> '广告招揽',6=> '邮件咨询',7=> '客户拜访',8=> '电话名单',9=>'其它来源',10=> '自然客户',11=> '业主手册',12=> '设计师介绍',13=> '项目经理介绍',
        14=> '博客推广', 15=> '论坛推广',16=> '微博推广',17=> '施工工地参观',18=> '网络客户',19=> '客户微推荐',20=> '微信客户来源',21=> 'PC客户来源',22=> 'APP客户来源',23=> '商家介绍',24=> '主动上门',25=>'信用家',26=>'保驾护航'];
    //装修类型
    public  $decoration_type = [''=>'--',1=> '半包',2=> '全包',3=> '装壁纸',4=> '墙壁粉刷',5=> '维修',6=> '水电'];
    //楼房均价
    public  $building_price = [''=>'--',1=> '16500-32000',2=> '12000-165000',3=> '8500-12000'];
    //屋室结构
    public  $living_room_structure = [''=>'--',1=>'两室两厅一卫',2=>'标间',3=>'两室两厅两卫',4=> '三室两厅两卫',5=> '三房两厅一卫',6=> '其他',7=> '办公楼',8=> '宿舍楼',9=>'工厂装修',10=> '办公室',11=> '茶水间',12=> '洗手间',13=> '会议室',14=> '图书室'];
    //房屋用途
    public  $housing_use = [''=>'--',1=> '住宅',2=> '商铺',3=> '别墅',4=> '写字楼',5=> '工厂装修'];
    //装修风格
    public  $decoration_style = [''=>'--',1=> '现代简约风格',2=> '恬淡田园风格',3=> '新中式风格',4=> '欧式古典风格',5=> '地中海风格',6=> '日式风格',7=> '美式风格',8=> '欧式风格'];
    //房屋朝向
    public  $house_orientation = [''=>'--',1=> '东',2=> '南',3=> '西',4=> '北'];
    //采光
    public  $lighting = [''=>'--',1=> '暗光',2=> '柔光',3=> '亮光'];
    //房屋类型
    public  $house_type = [''=>'--',1=> '现房',2=> '准现房',3=> '期房'];
    //装修档次
    public  $decoration_grade = [''=>'--',1=> '经济型',2=> '中档型',3=> '豪华型',4=>'简装',5=>'精装',6=>'豪装',7=>'钻装'];
    //色彩取向
    public  $color_orientation = [''=>'--',1=> '红色',2=> '黄色',3=> '蓝色',4=> '白色',5=> '黑色',6=> '灰色'];
    //与户主关系
    public  $householder_relation = [1=> '本人',2=> '亲戚',3=> '朋友',4=> '家人'];
    //字体颜色
    public $font_color= [''  =>'--',1=> 'Blue',2=> 'Red',3=> 'Magenta',4=> 'DarkMagenta',5=> 'Olive'];
    //装饰项目单位名称
    public $unit_name=[''=>'--',1=>'㎡',2=>'套',3=>'件',4=>'项',5=>'台',6=>'个',9=>'m',10=>'片',11=>'樘',12=>'扇'];
//    //办公用品单位
//    public $supplies_unit=[1=>'㎡',2=>'套',3=>'件',4=>'项',5=>'台',6=>'个',9=>'m',10=>'片',11=>'樘',12=>'扇'];
    //日志类型
    public $log_type=[1=>'人工编辑',2=>'系统记录'];
    //请假类型
    public $leave_type=[1=>'病假',2=>'事假',3=>'婚假',4=>'产假'];
    //审核状态
    public $audit_status=[0=>'未审核',1=>'审核通过',-1=>'审核不通过'];
    //审阅状态
    public $manager_status=[-1=>"未审阅",1=>"已审阅"];
    //工作计划等级
    public $plan_grade=[4=>'很重要',3=>'重要',2=>'普通',1=>'一般'];
    //工作状态
    public $work_schedule=[0=>'执行中',1=>"已完成"];
    //处理状态
    public $processing_status=["-1"=>'未处理',"1"=>'正在处理','2'=>'已完成'];
    //菜单按钮类型
    public $button_type=[''=>'选择快速选择','view'=>'链接','click'=>'点击','view_limited'=> '图文','media_id'=> '图片','scancode_waitmsg'=> '扫码带提示','scancode_push'=> '扫码推事件','pic_sysphoto'=> '系统拍照发图','pic_photo_or_album'=> '拍照或者相册发图','pic_weixin'=> '微信相册发图','location_select'=>'发送位置'];
    //tag_id
    public $tag_id=[1=>"未知"];
    //client_platform_type
    public $client_platform_type=[1=>"未知"];
    //损耗类型
    public $loss_type=['0'=>'不计损耗','1'=>'主材损耗',2=>'辅材损耗',3=>'主辅材损耗'];

    static public function getFieldValue($name,$type=false){
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }
        switch ($type){
            case "array":
                $array=[];
                foreach ( self::$instance->$name as $item=>$value){
                    $array[]=["value"=>$item,"text"=>$value];
                }
                return $array;
                break;
            case "item":
                $array=[];
                foreach ( self::$instance->$name as $item=>$value){
                    $array[$item]=$value;
                }
                return $array;
                break;

            default:
                return self::$instance->$name;
        }

    }





}