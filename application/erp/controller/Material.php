<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/23
 * Time: 13:02
 */

namespace app\erp\controller;

use app\base\model\Base;
use app\base\model\BudgetListEdit;
use app\base\model\MaterialCategory;
use app\base\model\MaterialDataStyle;
use app\base\model\MaterialListEdit;
use app\base\model\MaterialTemplateBudgetAccess;
use app\base\model\PersonnelCompany;
use app\base\model\PersonnelDepartment;
use app\base\model\PersonnelUser;
use app\base\model\ProjectContacts;
use app\base\model\ProjectRelatedPerson;
use app\base\model\ProjectRemindTime;
use app\base\model\ProjectStructure;
use app\base\model\ProjectBuilding;
use app\base\model\ProjectTraceLog;
use app\erp\config\FieldValue;
use phpDocumentor\Reflection\Types\Object_;
use think\Loader;
use think\Session;
use app\erp\config\Config;


class Material extends Auth
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->config_list = Config::$Material;
    }
    public function showMaterialAdd(){
        config(['default_ajax_return' => 'html',]);
        return $this->fetch('material_add');
    }
    public function showMaterialSupplier(){
        config(['default_ajax_return' => 'html',]);
        return $this->fetch('material_supplier_add');
    }
    /*供应商添加*/
    public function addMaterialSupplier(){
        if($this->request->isPost()){
            $data=$this->request->post();
            $model_name="base/MaterialSupplier";
            $validate_name="base/MaterialSupplier.add";
            $data["company_id"]=$this->member_info->company_id;
            $re=$this->editData(false,$validate_name,$model_name,$data);
            return $re;
        }else{
            return self::showReturnCodeWithOutData(1003);
        }
    }
    /*材料类别列表*/
    public function showMaterialCategory(){
        if($this->request->isPost()){
            $model="base/MaterialCategory";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title' =>"类别列表",
                'data_url'=>url('showMaterialCategory'),
                'dialog_url'=>url('showCategoryEdit'),
            ];
            $this->assign($assign_data);
            return $this->fetch('material_category_list');
        }
    }
    /*材料类别添加*/
    public function showCategoryEdit($action="",$id=""){
        if(empty($action)){
            return self::showJsonReturnCode("1003");
        }
        $model_name="base/MaterialCategory";
        $validate_name="base/MaterialSupplier.category";
        $data=$this->request->post();
        $data["company_id"]=$this->member_info->company_id;
        switch($action){
            case 'add':case 'edit':
                config(['default_ajax_return' => 'html',]);
                return $this->fetch('category_edit');
                break;
            case "saveAdd":
                $re=$this->editData(false,$validate_name,$model_name,$data);
                return $re;
                break;
            case "saveEdit":
                if (isset($id)) {
                    $save_data = $this->request->post();
                    $save_data['guid'] = $id;
                    return json($this->doModelAction($save_data, 'base/MaterialSupplier.category', 'base/MaterialCategory', 'saveInfoByGuid'));
                } else {
                    return self::showReturnCodeWithOutData(1003);
                }
                break;
            case "loadData":
                if (isset($id)) {
                    return json(model($model_name)->getInfoByGuid($id));
                } else {
                    return json([]);
                }
                break;
            case "del":
                if (isset($id)) {
                    return self::showReturnCodeWithOutData(model($model_name)->setStatusDelByGuid($id) ? 1001 : 1010);
                } else {
                    return self::showReturnCodeWithOutData(1010);
                }
                break;
            default:
                break;
        }
    }

    public function showSupplierJson(){
        $list=Loader::model("base/MaterialSupplier")->getList(["company_id"=>$this->member_info->company_id]);
        return $list;
    }
    public function showBaseStyleJson(){
        $list=Loader::model("base/MaterialBaseStyle")->getList(["company_id"=>$this->member_info->company_id]);
        array_unshift($list,['guid'=>'','base_name'=>'--']) ;
        return $list;
    }
    public function showPackageTypeJson(){ //显示套餐种类
        $list=Loader::model("base/material/MaterialPackageType")->getList(["company_id"=>$this->member_info->company_id,'status'=>1]);

        return $list;
    }
    public function showPackageStyleJson(){ //显示套餐定额类型
        $package_id=$this->request->param('package_id');
        if(empty($package_id)){
            $list=Loader::model("base/material/MaterialPackageCategory")->getList(["company_id"=>$this->member_info->company_id,'status'=>1]);
        }else{
            $list=Loader::model("base/material/MaterialPackageCategory")->getList(["company_id"=>$this->member_info->company_id,'status'=>1,'package_id'=>$package_id]);
        }
        return $list;
    }
    public function showCategoryJson(){
        $list=Loader::model("base/MaterialCategory")->getList(["company_id"=>$this->member_info->company_id]);
        return $list;
    }
    public function showCateJson($top=0){
        $node = new MaterialCategory();
        $json = $node->getNodeListToTreeJson(["company_id"=>$this->member_info->company_id]);
//        if ($top == 1) {
//            return json($json);
//        }
//        $obj = ['id' => 0, 'text' => '总类别', 'children' => $json];
//        return json([$obj]);
        return json($json);

    }
    /*材料录入确认*/
    public function addMaterialData(){
        if($this->request->isPost()){
            $data=$this->request->post();
            $model_name="base/Material";
            $validate_name="base/Material.add";
            $data["company_id"]=$this->member_info->company_id;
            $re=$this->editData(false,$validate_name,$model_name,$data);
            return $re;

        }else{
            return self::showReturnCodeWithOutData(1003);
        }
    }
    /*主材材料列表*/
    public function materialList(){
        if($this->request->isPost()){
            $model="base/Material";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'type'=>1],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'主材列表',
                'data_url'=>url('materialList'),
                'dialog_url'=>url("materialEdit"),
                'map'=>["company_id"=>$this->member_info->company_id],
                'ma'=>["guid"=>$this->member_info->company_id],
            ];
            $this->assign($assign_data);
            return $this->fetch('material_list');
        }
    }
    /*辅材材料列表*/
    public function materialAuxiliaryList(){
        if($this->request->isPost()){
            $model="base/Material";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'type'=>2],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'辅材列表',
                'data_url'=>url('materialAuxiliaryList'),
                'dialog_url'=>url("materialEdit"),
                'map'=>["company_id"=>$this->member_info->company_id],
                'ma'=>["guid"=>$this->member_info->company_id],
            ];
            $this->assign($assign_data);
            return $this->fetch('material_list');
        }
    }
    /*主辅材修改和删除*/
    public function materialEdit(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/Material";
        $validate_name="base/Material.add";
        $view_name="material_edit";
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name);
    }

//    public function test(){
//        config(['default_ajax_return' => 'html',]);
//        $assign_data= [
//            'title'=>'测试',
//            'tree_title'=>'材料类别',
//            'list_title'=>'材料信息',
//            'tree_url'=>url('showCategoryTree'),
//            'data_url'=>url('materialList'),
//            'dialog_url'=>url("materialEdit"),
//            'load_url'=>url('materialListLoad'),
//            'map'=>["company_id"=>$this->member_info->company_id],
//            'ma'=>["guid"=>$this->member_info->company_id],
//        ];
//        $this->assign($assign_data);
//        return $this->fetch('tree');
//    }
    public function showCategoryTree($top=0){
        $node = new MaterialCategory();
        $json = $node->getToTreeJson(['company_id'=>$this->member_info->company_id]);
        return json($json);
    }
    /*已删除*/
    public function showPriceList(){
        if($this->request->isPost()){
            $model="base/Material";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'材料列表',
                'data_url'=>url('materialList'),
                'dialog_url'=>url("materialEdit"),
                'map'=>["company_id"=>$this->member_info->company_id],
                'ma'=>["guid"=>$this->member_info->company_id],
            ];
            $this->assign($assign_data);
            return $this->fetch('material_list');
        }
    }

    public function showDataStyleJson(){
        $list=Loader::model("base/MaterialDataStyle")->getList(["company_id"=>$this->member_info->company_id]);
//        array_unshift($list,['guid'=>'','categories_name'=>'--']) ;
        return $list;
    }

    //显示数据项列表(已删除)
    public function showDataItemList(){
        if($this->request->isPost()){
            $model="base/MaterialDataItem";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false);
            if(!empty($list['rows'])){
                foreach ($list['rows'] as $value){
                    $value["data_id"]=Loader::model("base/MaterialDataStyle")->getPerByGuId($value['data_id'])["categories_name"];
                    $value["base_id"]=Loader::model("base/MaterialBaseStyle")->getPerByGuId($value['base_id'])["base_name"];
                }
            }
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'数据项列表',
                'data_url'=>url('showDataItemList'),
                'dialog_url'=>url("materialDataItemEdit"),
                'map'=>["company_id"=>$this->member_info->company_id],
            ];
            $this->assign($assign_data);
            return $this->fetch('material_data_item_list');
        }
    }
    /*已删除*/
    public function materialDataItemEdit(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/MaterialDataItem";
        $validate_name="base/MaterialDataItem.add";
        $view_name="material_data_item_edit";
        $data=$this->request->post();
        $data["company_id"]=$this->member_info->company_id;
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name,$data);
    }
    /*预算空间模板*/
    public function showTemplateBudget(){
        if($this->request->isPost()){
            $model="base/MaterialTemplateBudget";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false);
            if(!empty($list['rows'])){
                foreach ($list['rows'] as $value){
                    $value["template_id"]=Loader::model("base/MaterialTemplateStyle")->getPerByGuId($value['template_id'])["template_name"];
                }
            }
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'空间列表',
                'data_url'=>url('showTemplateBudget'),
                'dialog_url'=>url("TemplateBudgetEdit"),
                'map'=>["company_id"=>$this->member_info->company_id],
            ];
            $this->assign($assign_data);
            return $this->fetch('template_budget');
        }
    }
    /*空间模板添加*/
    public function TemplateBudgetEdit(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/MaterialTemplateBudget";
        $validate_name="base/Material.budget";
        $view_name="templateBudgetEdit";
        $view="edit_template_budget";
        $data=$this->request->post();
        $data["company_id"]=$this->member_info->company_id;
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name,$data,$view);
    }
    //预算模板类型
    public function showTemplateJson(){
        $list=model("base/MaterialTemplateStyle")->getList(["company_id"=>$this->member_info->company_id]);
        return $list;
    }
    //修改模板空间内容
    public function contentEdit($id=""){
        if (empty($id)){
            return self::showJsonReturnCode("1003");
        }
        config(['default_ajax_return' => 'html',]);
        $assign_data= [
            'title'=>'空间内容',
            'guid'=>$id,
            'data_url'=>url('spatialContentList',['guid'=>$id]),
        ];
        $this->assign($assign_data);
        return $this->fetch('budget_content_edit_copy');
    }
    /*内容列表*/
    public function spatialContentList($guid=""){
        if (empty($guid)) return self::showJsonReturnCode("1003");
        $list=Loader::model('base/MaterialTemplateBudgetAccess')->getList(["budget_id"=>$guid]);
        $arr=[];
        $arr['rows']=[];
        if(!empty($list)){
            foreach ($list as $value){
                $data=Loader::model('base/MaterialListEdit')->getList(["company_id"=>$this->member_info->company_id,'guid'=>$value['material_id']]);
                if (!empty($data)){
                    $arr['rows'][]=$data[0];
                }
            }
        }
        $arr['total']=count($arr['rows']);
        return $arr;
    }
    //显示基装项数据
    public function showDataItem($guid=""){
        if (empty($guid)){
            return self::showJsonReturnCode("1003");
        }
        config(['default_ajax_return' => 'html',]);
        $assign_data= [
            'url'=>url('showDataList'),
            'guid'=>$guid,
        ];
        $this->assign($assign_data);
        return $this->fetch('show_data_item_copy');
    }
    /*显示基装项数据*/
    public function showDataList(){
        $model="base/MaterialListEdit";
        $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id]);
        if(!empty($list['rows'])){
            foreach ($list['rows'] as $value){
                $count = Loader::model('base/MaterialTemplateBudgetAccess')->getCont(['material_id'=>$value["guid"]]);
                $value["item_status"]=$count;
            }
        }
        return $list;
    }
    /*保存基装项数据*/
    public function savaData(){
        if ($this->request->isPost()){
            $data=$this->request->post();
            $model=new MaterialTemplateBudgetAccess();
            return $model->savaBudgetData($data);
        }else{
            return self::showJsonReturnCode("1003");
        }
    }

    //显示主材数据页
    public function showMaterialData($guid=''){
        if (empty($guid)){
            return self::showJsonReturnCode("1003");
        }
        config(['default_ajax_return' => 'html',]);
        $assign_data= [
            'tree_url'=>url('showCategoryTree'),
            'list_url'=>url('materialDataList'),
            'guid'=>$guid,
        ];
        $this->assign($assign_data);
        return $this->fetch('show_material_data_copy');
//        return $this->fetch('showMaterialData');  改变表
    }
    /*主材数据*/
    public function materialDataList(){
//        $model="base/Material";   换个model
        $model="base/MaterialListEdit";
        $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'type'=>2],false,false);
        if(!empty($list['rows'])){
            foreach ($list['rows'] as $value){
                $count = Loader::model('base/MaterialTemplateBudgetAccess')->getCont(['type'=>'2','material_id'=>$value["guid"]]);
                $value["item_status"]=$count;
            }
        }
        return $list;
    }
    /*删除空间内容*/
    public function delBudgetContent(){
        if ($this->request->isPost()){
            $data=$this->request->post();
            $model=new MaterialTemplateBudgetAccess();
            return $model->delBudgetData($data);
        }else{
            return self::showJsonReturnCode("1003");
        }
    }
    /*显示家装费率项列表*/
    public function showRateItemList(){
        if($this->request->isPost()){
            $model="base/MaterialRateItem";
            $budget_type=array_search("家装",FieldValue::getFieldValue("budget_type"))?:1;
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'budget_type'=>$budget_type],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'家装费率项',
                'data_url'=>url('showRateItemList'),
                'dialog_url'=>url("addRateItem"),

            ];
            $this->assign($assign_data);
            return $this->fetch('rate_item_list');
        }
    }
    /*显示工装费率项列表*/
    public function showRateItemClothesList(){
        if($this->request->isPost()){
            $model="base/MaterialRateItem";
            $budget_type=array_search("工装",FieldValue::getFieldValue("budget_type"))?:2;
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'budget_type'=>$budget_type],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'工装费率项',
                'data_url'=>url('showRateItemClothesList'),
                'dialog_url'=>url("addRateItemClothes"),

            ];
            $this->assign($assign_data);
            return $this->fetch('rate_item_list');
        }
    }
    /*显示套餐费率项列表*/
    public function showRateItemPackageList(){
        if($this->request->isPost()){
            $model="base/MaterialRateItem";
            $budget_type=array_search("套餐",FieldValue::getFieldValue("budget_type"))?:3;
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'budget_type'=>$budget_type],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'套餐费率项',
                'data_url'=>url('showRateItemPackageList'),
                'dialog_url'=>url("addRateItemPackage"),

            ];
            $this->assign($assign_data);
            return $this->fetch('rate_item_list');
        }
    }
    /*添加费率项*/
    public function addRateItem(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/MaterialRateItem";
        $validate_name="base/Material.rate";
        $view_name="rate_item_edit";
        $data=$this->request->post();

        $data["company_id"]=$this->member_info->company_id;
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name,$data);
    }
    /*添加工装费率项*/
    public function addRateItemClothes(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/MaterialRateItem";
        $validate_name="base/Material.rate";
        $view_name="rate_item_edit_clothes";
        $data=$this->request->post();
        $data["company_id"]=$this->member_info->company_id;
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name,$data);
    }
    /*添加套餐费率项*/
    public function addRateItemPackage(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/MaterialRateItem";
        $validate_name="base/Material.rate";
        $view_name="rate_item_edit_package";
        $data=$this->request->post();
        $data["company_id"]=$this->member_info->company_id;
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name,$data);
    }
    //材料行编辑(已删除)
    public function showEditList(){
        if($this->request->isPost()){
            $model="base/MaterialListEdit";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'type'=>2],false,false,true);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'主材列表',
                'data_url'=>url('showEditList'),
                'json_url'=>url("showCategoryJson"),
                'map'=>["company_id"=>$this->member_info->company_id],
            ];
            $this->assign($assign_data);
            return $this->fetch('listedit/list_edit_material');
        }
    }
    public function showUnitJson(){
        $list=new FieldValue();
        $unit=$list->unit_name;
        $arr=[];
        foreach ($unit as $key=>$value){
            $arr[]=[
                'id'=>$key,
                'name'=>$value
            ];
        }
        return json($arr);
    }
    /*装饰项目数据保存*/
    public function addDataWithMaterial($id=""){
        if($this->request->isPost()){
            $data=$this->request->post();
            $validate_name='base/Material.editlist';
            $data['company_id']=$this->member_info->company_id;
            $result = $this->validate($data, $validate_name);
            if (true !== $result) return ['code' => '1003', 'msg' => $result,];
            if(!empty($id)){
                $data['category_id']=$id;
            }
            $model=new MaterialListEdit();
            return $model->saveDate($data);
//            return Loader::model('base/MaterialListEdit')->saveDate($data);
        }else{
            return self::showJsonReturnCode("1003");
        }
    }
    /*删除装饰项目*/
    public function delDataWithMaterial(){
        if($this->request->isPost()){
            $data=$this->request->post();
            if(!isset($data['id'] )|| empty($data['id'] )){
                return self::showJsonReturnCode("1003");
            }
            $model=new MaterialListEdit();
            return $model->delData($data);
            //return Loader::model('base/MaterialListEdit')->delData($data);
        }else{
            return self::showJsonReturnCode("1003");
        }
    }
    /*显示装饰项目类型进入*/
    public function showDataStyleHtml(){
        config(['default_ajax_return' => 'html',]);
        $dataStyle=new MaterialDataStyle();
        $list=$dataStyle->getList(["company_id"=>$this->member_info->company_id]);
        $assign_data= [
            'dataStyle'=>$list,
        ];
        $this->assign($assign_data);
        return $this->fetch('listedit/show_data_style');
    }

    /*显示装修项目*/
    public function showEditListForData($id="",$type="",$package="",$package_type=""){
        $type=empty($type)?'1':$type;
        $package=empty($package)?'1':$package;
        $package_type=empty($package_type)?'1':$package_type;
        if($this->request->isPost()){
            $model="base/MaterialListEdit";
            if(empty($id)){
                $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false,['xuhao'=>'asc']);
            }else{
                $map=[
                    "company_id"=>$this->member_info->company_id,
                    'category_id'=>$id,'type'=>$type,
                    'package_id'=>$package,
                    'package_type'=>$package_type,
                ];
                $list=$this->showEasyUiJsonList($model,false,$map,false,false,['xuhao'=>'asc']);
            }
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $dataStyle=new MaterialDataStyle();

            $style=$dataStyle->getInfoByGuId($id);
            if (empty($style)){
                $assign_data= [
                    'title'=>'装饰项目列表',
                    'data_url'=>url('showEditListForData'),
                    'json_url'=>url("showDataStyleJson"),
                    'map'=>["company_id"=>$this->member_info->company_id],
                ];
                $this->assign($assign_data);
                return $this->fetch('listedit/list_edit_data_item');
            }else{
                $assign_data= [
                    'title'=>'装饰项目列表',
                    'id'=>$id,
                    'name'=>$style['categories_name'],
                    'dataTitle'=>$style['categories_name'],
                    'data_url'=>url('showEditListForData',['id'=>$id,'type'=>$type,'package'=>$package,'package_type'=>$package_type]),
                    'json_url'=>url("showDataStyleJson"),
                    'type'=>$type,
                    'package'=>$package,
                    'package_type'=>$package_type,
                    'map'=>["company_id"=>$this->member_info->company_id],
                ];
                $this->assign($assign_data);
                return $this->fetch('listedit/data_item_list_edit');
            }

        }
    }
    /*预算书页面设置*/
    public function showEditBudgetStyle(){
        if($this->request->isPost()){
            $model="base/BudgetListEdit";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false,true);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'预算书名称列表',
                'data_url'=>url('showEditBudgetStyle'),
                'json_url'=>url("showDataStyleJson"),
                'map'=>["company_id"=>$this->member_info->company_id],
            ];
            $this->assign($assign_data);
            return $this->fetch('listedit/list_edit_budget_style');
        }
    }
    /*添加预算书模板*/
    public function showAddBudgetBook($id=""){
        config(['default_ajax_return' => 'html',]);
        if(!empty($id)){
            $list=Loader::model('base/BudgetListEdit')->getList(['company_id'=>$this->member_info->company_id,'guid'=>$id]);
            if (!empty($list)){
                $assign_data= [
                    'company_name'=>$list[0]['com_name'],
                    'desc'=>$list[0]['desc'],
                    'style'=>$list[0]['style'],
                    'address'=>$list[0]['address'],
                    'telephone'=>$list[0]['telephone'],
                    'logo'=>$list[0]['logo'],
                    'guid'=>$id,
                ];
                $this->assign($assign_data);
                return $this->fetch('listedit/add_budget_style');
            }
        }else{
            $company=PersonnelCompany::quickGet($this->member_info->company_id);
            $desc=new FieldValue();
            $assign_data= [
                'company_name'=>empty($company)?"":$company['company_name'],
                'desc'=>$desc->desc,
                'style'=>'',
                'address'=>'',
                'telephone'=>'',
                'logo'=>'',
                'guid'=>'',
            ];
            $this->assign($assign_data);
            return $this->fetch('listedit/add_budget_style');
        }

    }

    public function showUploadHtml($idName=""){
        config(['default_ajax_return' => 'html',]);
        $assign_data= [
            'idName'=>$idName,
        ];
        $this->assign($assign_data);
        return $this->fetch('listedit/upload_photo_html');
    }
    /*删除费率类别*/
    public function delDataWithBudgetStyle(){
        if($this->request->isPost()){
            $data=$this->request->post();
            $model=new BudgetListEdit();
            return $model->delData($data);
//            return Loader::model('base/BudgetListEdit')->delData($data);
        }else{
            return self::showJsonReturnCode("1003");
        }
    }
    /*费率类别数据保存*/
    public function addDataWithBudgetStyle($guid=""){
        if($this->request->isPost()){
            $data=$this->request->post();
            $validate_name='base/Material.editbudgetlist';
            $result = $this->validate($data, $validate_name);
            if (true !== $result) return ['code' => '1003', 'msg' => $result,];
            $data['company_id']=$this->member_info->company_id;
            if(!empty($guid)){
               $data['guid']=$guid;
            }
            $model=new BudgetListEdit();
            return $model->saveDate($data);
//            return Loader::model('base/BudgetListEdit')->saveDate($data);
        }else{
            return self::showJsonReturnCode("1003");
        }
    }
    public function showTreeBudget(){ //显示预算类别
        $model="base/DefaultRate";
        $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false,true);
        return $list;
    }
    /*删除工程类别*/
    public function delDataStyle(){
        if ($this->request->isPost()){
            $guid=$this->request->param("id/s");
            if (empty($guid)) return self::showJsonReturnCode(1003);
            return Loader::model("base/MaterialDataStyle")->delDataStyle($guid);
        }else{
            return self::showJsonReturnCode(1003);
        }

    }
    /* 从套餐定额进入套餐类别*/
    public function showDataStylePackageList($type=""){
        if (empty($type)) return self::showReturnCode(1003);
        if($this->request->isPost()){
            $model="base/material/MaterialPackageCategory";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id,'package_id'=>$type],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'套餐类别列表',
                'data_url'=>url('showDataStylePackageList',['type'=>$type]),
                'dialog_url'=>url("showPackageStyleEdit",['type'=>$type]),
                'map'=>["company_id"=>$this->member_info->company_id],
                'ma'=>["guid"=>$this->member_info->company_id],
                'type'=>$type,
            ];
            $this->assign($assign_data);
            return $this->fetch('material_data_style_package_list');
        }
    }

    /*	添加套餐类别*/
    public function showPackageStyleEdit(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/material/MaterialPackageCategory";
        $validate_name="base/Material.packagecategorystyle";
        $view_name="material_package_style_edit";
        $data=$this->request->post();
        $type=$this->request->param('type');
        $data["company_id"]=$this->member_info->company_id;
        $data['package_id']=$type;
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name,$data);
    }



    /*从套餐类别进入工程类别*/
    public function showPackageStyleList($type="",$package=""){
        if (empty($package)) return self::showReturnCode(1003);
        if (empty($type)) return self::showReturnCode(1003);
        if($this->request->isPost()){
            $model="base/MaterialDataStyle";
            $list=$this->showEasyUiJsonList($model,false,["company_id"=>$this->member_info->company_id],false,false);
            return $list;
        }else{
            config(['default_ajax_return' => 'html',]);
            $assign_data= [
                'title'=>'工程类别列表',
                'data_url'=>url('showPackageStyleList',['type'=>$type,'package'=>$package]),
                'dialog_url'=>url("showDataStyleEdit"),
                'map'=>["company_id"=>$this->member_info->company_id],
                'ma'=>["guid"=>$this->member_info->company_id],
                'package'=>$package,
                'package_type'=>$type,
            ];
            $this->assign($assign_data);
            return $this->fetch('material_access/show_package_style_list');
        }
    }
    /*修改删除工程类别*/
    public function showDataStyleEdit(){
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        $model_name="base/MaterialDataStyle";
        $validate_name="base/Material.datastyle";
        $view_name="material_data_style_edit";
        $data=$this->request->post();
        $data["company_id"]=$this->member_info->company_id;
        return $this->editUserData($id,$action,$model_name,$validate_name,$view_name,$data);
    }

}