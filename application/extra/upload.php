<?php

/**
 * Created by PhpStorm.
 * Power By Mikkle
 * Email：776329498@qq.com
 * Date: 2017/7/28
 * Time: 13:42
 */
return [

    //上传目录
    'upload_path'=>'/upload',

    //上传图片文件路径
    'upload_images_path'=>'/upload/images',
    //存储上传图片文件的数据表名称
    'upload_images_table'=>'mk_common_images',
    'upload_images_validate'=>[
        'size'=>1024*1024*5,
        'ext'=>'jpg,png,gif,jpeg,psd'
    ],

    "upload_files_path"=>'/upload/files',
    //存储上传文件的数据表名称
    'upload_files_table'=>'mk_common_files',
    'upload_files_validate'=>[
        'size'=>1024*1024*5,
        'ext'=>'doc,rar,7z,xlsx,zip,xls,docx,dwg,psd,jpg,png,gif,jpeg,psd'
    ],



];