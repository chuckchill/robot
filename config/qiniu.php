<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/5/31
 * Time: 11:28
 */
return [
    'accessKey' => env('QINIU_ACCESS_KEY', ''),
    'secretKey' => env('QINIU_SECRET_KEY', ''),
    'bucket' => [
        'videos' => [
            'bucket' => env('QINIU_VIDEO_BUCKET', ''),
            'private_url' => env('QINIU_VIDEO_URL', 'http://7xlb8s.com1.z0.glb.clouddn.com'),
        ],
        'article' => [
            'bucket' => env('QINIU_APP_BUCKET', 'article'),
            'private_url' => env('QINIU_VIDEO_URL', 'http://7xlb8s.com1.z0.glb.clouddn.com')
        ],
        'app' => [
            'bucket' => env('QINIU_APP_BUCKET', 'app'),
            'private_url' => env('QINIU_APP_URL', '')
        ]
    ]
];