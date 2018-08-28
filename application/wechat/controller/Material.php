<?php
/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/8/4
 * Time: 15:06
 */

namespace app\wechat\controller;


use app\base\model\MaterialCategory;
use app\base\model\Project as ProjectModel;
use app\erp\config\FieldValue;
use think\Db;
use think\Loader;


class Material extends Auth
{
    //材料商录入
    public function addMaterial()
    {
        if (empty($this->uuid)) {
            $this->redirect("index/myError");
        }
        if ($this->request->isPost()) {
            $listBusiness = Loader::model("base/MaterialSupplier")->getList(["company_id" => $this->member_info->company_id]);
            $field_value = new FieldValue();
            if (isset($field_value->unit_name)) {
                $array = [];
                foreach ($field_value->unit_name as $item => $value) {
                    $array[] = ['text' => $value, 'value' => $item];
                }
                $listUnit = $array;
            }
            $mode = new MaterialCategory();
            $listType = $mode->getList(["company_id" => $this->member_info->company_id, 'status' => 1]);
            $data = [
                "listBusiness" => $listBusiness,
                "list" => $listUnit,
                "listType" => $listType
            ];
            return $data;

        }

        return $this->fetch("add_material");
    }

//主材列表
    public function mainMaterialList()
    {
        if (empty($this->uuid)) {
            $this->redirect("index/myError");
        }//
        if ($this->request->isPost()) {
            $page = $this->request->param("page") ? $this->request->param("page") : 1;
            $rows = $this->request->param("rows") ? $this->request->param("rows") : 20;
            $data["rows"] = $user_array = Db::view("mk_material", "*")
                ->view("mk_material_category", "category_name", 'mk_material.category_id=mk_material_category.guid')
                ->view("mk_material_supplier", "supplier_name", 'mk_material.supplier_id=mk_material_supplier.guid')
                ->where('mk_material.company_id', $this->member_info->company_id)
                ->where('mk_material.type', 1)
                ->limit($rows)
                ->page($page)
                ->select();
            $arr = FieldValue::getFieldValue("unit_name");
            foreach ($data['rows'] as $key => $value) {
                $data['rows'][$key]["unit_name"] = $arr[$value["unit_name"]];
            }
            $data["total"] = Db::view("mk_material", "*")
                ->view("mk_material_category", "category_name", 'mk_material.category_id=mk_material_category.guid')
                ->view("mk_material_supplier", "supplier_name", 'mk_material.supplier_id=mk_material_supplier.guid')
                ->where('mk_material.company_id', $this->member_info->company_id)
                ->where('mk_material.type', 1)
                ->count();
            return $data;
        }
        return $this->fetch("main_material_list");
    }

//辅材列表  --
    public function auxiliaryList()
    {
        if (empty($this->uuid)) {
            $this->redirect("index/myError");
        }
        if ($this->request->isPost()) {

            $page = $this->request->param("page") ? $this->request->param("page") : 1;
            $rows = $this->request->param("rows") ? $this->request->param("rows") : 20;
            $data["rows"] = $user_array = Db::view("mk_material", "*")
                ->view("mk_material_category", "category_name", 'mk_material.category_id=mk_material_category.guid')
                ->view("mk_material_supplier", "supplier_name", 'mk_material.supplier_id=mk_material_supplier.guid')
                ->where('mk_material.company_id', $this->member_info->company_id)
                ->where('mk_material.type', 2)
                ->limit($rows)
                ->page($page)
                ->select();
            $arr = FieldValue::getFieldValue("unit_name");
            foreach ($data['rows'] as $key => $value) {
                $data['rows'][$key]["unit_name"] = $arr[$value["unit_name"]];
            }
            $data["total"] = Db::view("mk_material", "*")
                ->view("mk_material_category", "category_name", 'mk_material.category_id=mk_material_category.guid')
                ->view("mk_material_supplier", "supplier_name", 'mk_material.supplier_id=mk_material_supplier.guid')
                ->where('mk_material.company_id', $this->member_info->company_id)
                ->where('mk_material.type', 2)
                ->count();
            return $data;
        }                      
        return $this->fetch("auxiliary_list");
    }
}