<?php
/**
 * Created by PhpStorm.
 * User: Mikkle
 * Email:776329498@qq.com
 * Date: 2016/12/15
 * Time: 12:01
 */
return [
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',

    'erp_admin'=>['admin',],
    //模板参数替换
    'view_replace_str'       => [


        '__EASYUI__'    => 'http://wechat.yzferp.com/static/easyui',
        '__EASYUI151__'     => 'http://wechat.yzferp.com/static/easyui_151',
        '__LAYER__'    => 'http://wechat.yzferp.com/static/layer',
        '__LAYUI__'   => 'http://wechat.yzferp.com/static/layui',
        '__ERP__'   => 'http://wechat.yzferp.com/static/erp',
        '__STATIC__'   => 'http://wechat.yzferp.com/static',
        '__MUI__'   => 'http://wechat.yzferp.com/static/mui',
        '__WEUI__'   => 'http://wechat.yzferp.com/static/weui',
        '__LRIMG__'   => 'http://wechat.yzferp.com/static/localResizeIMG',
    ],
];