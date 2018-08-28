<?php return array (
        'title'=>'装企ERP角色数据配置',
        'content'=>'是否创建以下角色：',
        'desc'=>'说明：此操作将会帮你创建系统初始角色，如需要，请点击下一步，否则点击跳过;
        也可在系统内部进行角色创建或修改角色权限。',
        'apply'=>url('install/createRoleMarket'),
        'next'=>url('install/createSuccess'),
        'skip'=>url("install/createSuccess"),
);