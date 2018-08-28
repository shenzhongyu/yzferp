<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[upload]' => [
        'show_images/:md5/:size' => [
            'api/file/showPicture', ['method' => 'get'], ['size' => '\d+_\d+', 'md5' => '(\w+)'], ['ext' => ['jpg', 'png']],
        ],
        'down_images/:md5/[:size]' => [
            'api/file/downloadPicture', ['method' => 'get'], ['size' => '\d+_\d+', 'md5' => '(\w+)'], ['ext' => ['jpg', 'png']],
        ],
        'down_files/:md5' => [
            'api/file/downloadFiles', ['method' => 'get'], [ 'md5' => '(\w+)'], ['ext' => ['jpg', 'png']],
        ],

    ],

];
