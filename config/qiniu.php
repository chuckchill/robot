<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/5/31
 * Time: 11:28
 */
return [
    'accessKey' => env('QINIU_ACCESS_KEY', 'RvHinMYEkzrAkFsxZf5k_xaC4zs0LykUrgBRWdOL'),
    'secretKey' => env('QINIU_SECRET_KEY', 'BPyyroKdKjvh-agWvBgmBVsJ2Fh-l6w14JzgidSD'),
    'bucket'=>[
        'videos'=>env('QINIU_SECRET_KEY', 'videos'),
        'app'=>env('QINIU_SECRET_KEY', 'videos'),
    ]
];