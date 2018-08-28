<?php
/**
 * Created by PhpStorm.
 * Power by Mikkle
 * QQ:776329498
 * Date: 2017/4/24
 * Time: 17:36
 */

namespace app\erp\controller;


use think\Exception;
use think\Loader;

class BaseEasyUI extends Base
{
    /**
     * Power by Mikkle
     * QQ:776329498
     * @param $model_name
     * @param $validate_name
     * @param bool|false $map
     * @param bool|false $flag
     * @return mixed
     */
    protected function showEasyUiJsonList($model_name,$validate_name,$map=false,$flag=false,$data=[],$IdAsc=false,$field=''){
        $model = Loader::model($model_name);
        $data = empty($data) ? $this->request->param() :$data;
        $page = isset($data['page']) ? $data['page'] : 1;
        $rows = isset($data['rows']) ? $data['rows'] : 20;
        $field =  empty($field) ? true : $field  ;
        $sort = isset($data['sort']) ? $data['sort'] : '';
        $order = isset($data['order']) ? $data['order'] : '';
        $action = $this->request->param('action');
        $where=[];


        $order_all=[]
;        if (!empty($sort) && !empty($order) ) {
            $sort_array = explode(',', $sort);
            $order_array = explode(',', $order);
            if (count($sort_array) == count($order_array)) {
                $order_all = [];
                foreach ($sort_array as $item => $value) {
                    $order_all[$value] = $order_array[$item];
                }
            }
        }
        if(empty($order_all)){
            if($IdAsc===true){
                $order_all =  ['id'=>'asc'];
            }elseif (is_array($IdAsc)){
                $order_all =  $IdAsc;
            }
        }else{
            $order_all = ['id'=>'desc'];
        }
        if($IdAsc===true){
            $order_all =  ['id'=>'asc'];
        }elseif (is_array($IdAsc)){
            $order_all =  $IdAsc;
        }elseif(empty($order_all)){
            $order_all = ['id'=>'desc'];
        }

        $filterRules = isset($data['filterRules']) ? $data['filterRules'] : '[]';
        if (empty($filterRules) || $filterRules == '[]') {
            if(isset($action)) {

            }
        }else{
            if (is_string($filterRules)){
                try{
                    $where_rules_obj = json_decode($filterRules);
                }catch (Exception $e){
                    $where_rules_obj=[];
                }
            }elseif(is_array($filterRules)){
                $where_rules_obj = $filterRules;
            }else{
                $where_rules_obj=[];
            }

        }
        if (!empty($where_rules_obj)){
            foreach ($where_rules_obj as $rule_obj){
                if (is_object($rule_obj)){
                    $op=$rule_obj->op;
                    if(is_array($rule_obj->value)){
                        $value= $rule_obj->value;
                    }else{
                        $value = trim($rule_obj->value);
                    }
                    $search_field = trim($rule_obj->field);
                }else if(is_array($rule_obj)){
                    $op=$rule_obj['op'];
                    if(is_array($rule_obj['value'])){
                        $value= $rule_obj['value'];
                    }else{
                        $value = trim($rule_obj['value']);
                    }
                    $search_field = trim($rule_obj['field']);
                }
                if (!empty($value) || $value==0 ) {
                    switch ($op) {
                        case 'contains':
                            $where[$search_field] = ['like' , '%' . $value . '%'];
                            break;
                        case 'equal':
                            $where[$search_field] = $value;
                            break;
                        case 'in':
//                            if(is_array($value)){
//                                foreach($value as $ve){
//                                    $where[$search_field] = ['in' , $ve];
//                                }
//                            }else{
                                $where[$search_field] = ['in' , $value];
//                            }
                            break;
                        case 'betweennumber':
                            $where[$search_field] = ['between' , explode(' - ', $value)];
                            break;
                        case 'betweendate':
                            $date_list = array_unique(explode(' - ', $value));
                            if (count($date_list)==2){
                                $where[$search_field] = ['between time' , $date_list ];
                            }elseif(count($date_list)==1){
                                $time =strtotime($date_list[0]) ;
                               if  (is_numeric($time)){
                                   $where[$search_field] = ['between time' , [$time,$time+60*60*24] ];
                               }
                            }
                            break;
                        default:
                            $where[$search_field] = ['like' , '%' . $value . '%'];
                    }
                }
            }
        }

        if ($map!=false && is_array($map)){
            $where = array_merge($where,$map);
        }
        if (!isset($where['status'])) {
            $where['status'] = 1 ;
        }
        if($flag!=false){
            return $model->getEasyUiParameterJson($field ,$where ,$page,$rows , $order_all );
        }
       // dump($where);
        return $model->getEasyUiJson($field ,$where ,$page,$rows , $order_all );
    }
    protected function showEasyUiJsonListNo($model_name,$validate_name,$map=false,$flag=false,$data=[],$IdAsc=false){
        $model = Loader::model($model_name);
        $data = empty($data) ? $this->request->param() :$data;
        $page = isset($data['page']) ? $data['page'] : 1;
        $rows = isset($data['rows']) ? $data['rows'] : 20;
        $field =  true  ;
        $sort = isset($data['sort']) ? $data['sort'] : '';
        $order = isset($data['order']) ? $data['order'] : '';
        $action = $this->request->param('action');
        $where=[];


        $order_all=[]
        ;        if (!empty($sort) && !empty($order) ) {
            $sort_array = explode(',', $sort);
            $order_array = explode(',', $order);
            if (count($sort_array) == count($order_array)) {
                $order_all = [];
                foreach ($sort_array as $item => $value) {
                    $order_all[$value] = $order_array[$item];
                }
            }
        }
        if(empty($order_all)){
            if($IdAsc===true){
                $order_all =  ['id'=>'asc'];
            }elseif (is_array($IdAsc)){
                $order_all =  $IdAsc;
            }
        }else{
            $order_all = ['id'=>'desc'];
        }
        if($IdAsc===true){
            $order_all =  ['id'=>'asc'];
        }elseif (is_array($IdAsc)){
            $order_all =  $IdAsc;
        }elseif(empty($order_all)){
            $order_all = ['id'=>'desc'];
        }

        $filterRules = isset($data['filterRules']) ? $data['filterRules'] : '[]';
        if (empty($filterRules) || $filterRules == '[]') {
            if(isset($action)) {

            }
        }else{
            $where_rules_obj = json_decode($filterRules);
        }
        if (!empty($where_rules_obj)){
            foreach ($where_rules_obj as $rule_obj){
                if (is_object($rule_obj)){
                    $op=$rule_obj->op;
                    if(is_array($rule_obj->value)){
                        $value= $rule_obj->value;
                    }else{
                        $value = trim($rule_obj->value);
                    }
                    $search_field = trim($rule_obj->field);
                }else if(is_array($rule_obj)){
                    $op=$rule_obj['op'];
                    if(is_array($rule_obj['value'])){
                        $value= $rule_obj['value'];
                    }else{
                        $value = trim($rule_obj['value']);
                    }
                    $search_field = trim($rule_obj['field']);
                }
                if (!empty($value) || $value==0 ) {
                    switch ($op) {
                        case 'contains':
                            $where[$search_field] = ['like' , '%' . $value . '%'];
                            break;
                        case 'equal':
                            $where[$search_field] = $value;
                            break;
                        case 'in':
//                            if(is_array($value)){
//                                foreach($value as $ve){
//                                    $where[$search_field] = ['in' , $ve];
//                                }
//                            }else{
                            $where[$search_field] = ['in' , $value];
//                            }
                            break;
                        case 'betweennumber':
                            $where[$search_field] = ['between' , explode(' - ', $value)];
                            break;
                        case 'betweendate':
                            $date_list = array_unique(explode(' - ', $value));
                            if (count($date_list)==2){
                                $where[$search_field] = ['between time' , $date_list ];
                            }elseif(count($date_list)==1){
                                $time =strtotime($date_list[0]) ;
                                if  (is_numeric($time)){
                                    $where[$search_field] = ['between time' , [$time,$time+60*60*24] ];
                                }
                            }
                            break;
                        default:
                            $where[$search_field] = ['like' , '%' . $value . '%'];
                    }
                }
            }
        }

        if ($map!=false && is_array($map)){
            $where = array_merge($where,$map);
        }
        if (!isset($where['status'])) {
            $where['status'] = 1 ;
        }
        if($flag!=false){
            return $model->getEasyUiParameterJson($field ,$where ,$page,$rows , $order_all );
        }
        return $model->getEasyUiJsonNo($field ,$where ,$page,$rows , $order_all );
    }

    //不需要状态的
    protected function showEasyUiJsonListNoStatus($model_name,$validate_name,$map=false,$flag=false,$data=[],$order_all=[]){
        $model = Loader::model($model_name);
        $data =empty($data) ? $this->request->param():$data;
        $page = isset($data['page']) ? $data['page'] : 1;
        $rows = isset($data['rows']) ? $data['rows'] : 20;
        $field =  true  ;
        $sort = isset($data['sort']) ? $data['sort'] : '';
        $order = isset($data['order']) ? $data['order'] : '';
        $action = $this->request->param('action');
        $where=[];
        if(empty($order_all)){
            $order_all = ['id'=>'desc'];
        }
        if (!empty($sort) && !empty($order) ) {
            $sort_array = explode(',', $sort);
            $order_array = explode(',', $order);
            if (count($sort_array) == count($order_array)) {
                $order_all = [];
                foreach ($sort_array as $item => $value) {
                    $order_all[$value] = $order_array[$item];
                }
            }
        }

        $filterRules = isset($data['filterRules']) ? $data['filterRules'] : '[]';
        if (empty($filterRules) || $filterRules == '[]') {
            if(isset($action)) {

            }
        }else{
            $where_rules_obj = json_decode($filterRules);
        }
        if (!empty($where_rules_obj)){
            foreach ($where_rules_obj as $rule_obj){
                if (is_object($rule_obj)){
                    $op=$rule_obj->op;
                    $value = trim($rule_obj->value);
                    $search_field = trim($rule_obj->field);
                }else if(is_array($rule_obj)){
                    $op=$rule_obj['op'];
                    $value = trim($rule_obj['value']);
                    $search_field = trim($rule_obj['field']);
                }
                if (!empty($value) ||$value==0 ) {
                    switch ($op) {
                        case 'contains':
                            $where[$search_field] = ['like' , '%' . $value . '%'];
                            break;
                        case 'equal':
                            $where[$search_field] = $value;
                            break;
                        case 'betweennumber':
                            $where[$search_field] = ['between' , explode(' - ', $value)];
                            break;
                        case 'betweendate':
                            $date_list = array_unique(explode(' - ', $value));
                            if (count($date_list)==2){
                                $where[$search_field] = ['between time' , $date_list ];
                            }elseif(count($date_list)==1){
                                $time =strtotime($date_list[0]) ;
                                if  (is_numeric($time)){
                                    $where[$search_field] = ['between time' , [$time,$time+60*60*24] ];
                                }
                            }
                            break;
                        default:
                            $where[$search_field] = ['like' , '%' . $value . '%'];
                    }
                }
            }
        }

        if ($map!=false && is_array($map)){
            $where = array_merge($where,$map);
        }
        if($flag!=false){
            return $model->getEasyUiParameterJson($field ,$where ,$page,$rows , $order_all );
        }
        return $model->getEasyUiJson($field ,$where ,$page,$rows , $order_all );
    }

    /**
     * Power by Mikkle
     * QQ:776329498
     * @param $validate_name
     * @param $model_name
     * @param $view_name
     * @return array|mixed
     */
    protected function easyuiEdit($validate_name, $model_name, $view_name)
    {
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        if (isset($action)) {
            switch ($action) {
                case "add": case "edit":
                    config(['default_ajax_return' => 'html',]);
                    return $this->fetch($view_name);
                    break;

                case "saveAdd":
                    return json($this->editData(false,$validate_name,$model_name));
                    break;
                case "saveEdit":
                    if (isset($id)) {
                        $save_data = $this->request->post();
                        $save_data['guid'] = $id;
                        return json($this->doModelAction($save_data, $validate_name, $model_name, 'saveInfoByGuid'));
                    } else {
                        return self::showReturnCodeWithOutData(1003);
                    }
                    break;
                case "loadData":
                    if (isset($id)) {
                        return json(Loader::model($model_name)->getInfoByGuid($id));
                    } else {
                        return json([]);
                    }
                    break;
                case "del":
                    if (isset($id)) {
                        return self::showReturnCodeWithOutData(Loader::model($model_name)->setStatusDelByGuid($id) ? 1001 : 1010);
                    } else {
                        return self::showReturnCodeWithOutData(1010);
                    }
                    break;
                default:
                    return self::showReturnCodeWithOutData(1010);
            }
        }
    }
    protected function easyuiEditByCom($validate_name, $model_name, $view_name)
    {
        $id = $this->request->param('id');
        $action = $this->request->param('action');
        if (isset($action)) {
            switch ($action) {
                case "add": case "edit":
                config(['default_ajax_return' => 'html',]);
                return $this->fetch($view_name);
                break;

                case "saveAdd":
                    $data=$this->request->post();
                    $data["company_id"]=$this->member_info->company_id;
                    return json($this->editData(false,$validate_name,$model_name,$data));
                    break;
                case "saveEdit":
                    if (isset($id)) {
                        $save_data = $this->request->post();
                        $save_data['guid'] = $id;
                        return json($this->doModelAction($save_data, $validate_name, $model_name, 'saveInfoByGuid'));
                    } else {
                        return self::showReturnCodeWithOutData(1003);
                    }
                    break;
                case "loadData":
                    if (isset($id)) {
                        return json(Loader::model($model_name)->getInfoByGuid($id));
                    } else {
                        return json([]);
                    }
                    break;
                case "del":
                    if (isset($id)) {
                        return self::showReturnCodeWithOutData(Loader::model($model_name)->setStatusDelByGuid($id) ? 1001 : 1010);
                    } else {
                        return self::showReturnCodeWithOutData(1010);
                    }
                    break;
                default:
                    return self::showReturnCodeWithOutData(1010);
            }
        }
    }

    /**
     * Power by Mikkle
     * QQ:776329498
     * @param $action
     * @return mixed
     */
    public function showEasyUiList($action){
        $model_name = $this->config_list[$action]['model_name'];
        $validate_name = $this->config_list[$action]['validate_name'] ;
        $template_name = $this->config_list[$action]['template_name'] ;
        $map =  isset($this->config_list[$action]['map'])?$this->config_list[$action]['map']:false;
        $assign_data = [
            'title'=>$this->config_list[$action]['assign_data']['title'],
            'dialog_url'=>url($this->config_list[$action]['assign_data']['dialog_url']),
            'data_url'=>url($this->config_list[$action]['assign_data']['data_url']),
        ];
        if ( $this->request->isPost() ) {
            return $this->showEasyUiJsonList($model_name,$validate_name,$map);
        } else {
            $this->assign($assign_data);
            return $this->fetch($template_name);
        }
    }
    public function showEasyUiParameterList($action){
        $model_name = $this->config_list[$action]['model_name'];
        $validate_name = $this->config_list[$action]['validate_name'] ;
        $template_name = $this->config_list[$action]['template_name'] ;
        $map =  isset($this->config_list[$action]['map'])?$this->config_list[$action]['map']:false;
        $parameter_name=isset($this->config_list[$action]['parameter_name']) ?$this->config_list[$action]['parameter_name']:false ;
        if($map!=false && isset($map[$parameter_name]) ){
            $map[$parameter_name]=$this->member_info->$parameter_name;
            $flag=true;
        }else{
            $map["guid"]=$this->member_info->$parameter_name;
            $flag=false;
        }
        $assign_data =[
            'title'=>$this->config_list[$action]['assign_data']['title'],
            'dialog_url'=>url($this->config_list[$action]['assign_data']['dialog_url']),
            'data_url'=>url($this->config_list[$action]['assign_data']['data_url']),
        ];
        if ( $this->request->isPost() ) {
            return $this->showEasyUiJsonList($model_name,$validate_name,$map,$flag);
        } else {
            $this->assign($assign_data);
            return $this->fetch($template_name);
        }
    }
    public function showEasyUiListLog($action){
        $model_name = $this->config_list[$action]['model_name'];
        $validate_name = $this->config_list[$action]['validate_name'] ;
        $template_name = $this->config_list[$action]['template_name'] ;
        $assign_data =[
            'title'=>$this->config_list[$action]['assign_data']['title'],
            'dialog_url'=>url($this->config_list[$action]['assign_data']['dialog_url']),
            'data_url'=>url($this->config_list[$action]['assign_data']['data_url']),
        ];
        if ( $this->request->isPost() ) {
            return $this->showEasyUiJsonList($model_name,$validate_name,false,true);
        } else {
            $this->assign($assign_data);
            return $this->fetch($template_name);
        }
    }


    public function showEasyUiEdit($action){
        $model_name = $this->config_list[$action]['model_name'];
        $validate_name = $this->config_list[$action]['validate_name'] ;
        $template_name = $this->config_list[$action]['template_name'] ;
        return $this->easyuiEdit($validate_name,$model_name,$template_name);
    }
    public function showEasyUiEditByCom($action){
        $model_name = $this->config_list[$action]['model_name'];
        $validate_name = $this->config_list[$action]['validate_name'] ;
        $template_name = $this->config_list[$action]['template_name'] ;
        return $this->easyuiEditByCom($validate_name,$model_name,$template_name);
    }

    public function editUserData($id,$action,$model_name,$validate_name,$view_name,$data=[],$view=""){
        if (isset($action)) {
            switch ($action) {
                case "add":
                    config(['default_ajax_return' => 'html',]);
                    return $this->fetch( $view_name);
                    break;
                case "edit":
                    config(['default_ajax_return' => 'html',]);
                    if(empty($view)){
                        return $this->fetch($view_name);
                    }else{
                        return $this->fetch($view);
                    }

                    break;
                case "saveAdd":
                    if(empty($data)){
                        return json($this->editData(false,$validate_name,$model_name));
                    }else{
                        return json($this->editData(false,$validate_name,$model_name,$data));
                    }
                    break;
                case "saveEdit":
                    if (isset($id)) {
                        $save_data = $this->request->post();
                        $save_data['guid'] = $id;
                        return json($this->doModelAction($save_data, $validate_name, $model_name, 'saveInfoByGuid'));
                    } else {
                        return self::showReturnCodeWithOutData(1003);
                    }
                    break;
                case "loadData":
                    if (isset($id)) {
                        return json(Loader::model($model_name)->getInfoByGuid($id));
                    } else {
                        return json([]);
                    }
                    break;
                case "del":
                    if (isset($id)) {
                        return self::showReturnCodeWithOutData(Loader::model($model_name)->setStatusDelByGuid($id) ? 1001 : 1010);
                    } else {
                        return self::showReturnCodeWithOutData(1010);
                    }
                    break;
                default:
                    return self::showReturnCodeWithOutData(1010);
            }
        }
    }



}