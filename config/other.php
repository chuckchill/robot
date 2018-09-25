<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 17:03
 */
return [
    'identity_type' => ['sys', 'qq', 'weixin', 'weibo', 'mobile', 'email'],
    'email_action' => env('EMAIL_ACTION', false),
    'mobile_action' => env('MOBILE_ACTION', false),
    'wx' => [
        "app_id" => env("WX_OPEN_APPID", "wx465d7ab2c2cca298"),
        "app_secret" => env("WX_OPEN_SECRET", "a3ba292292852255f8d4486d967d8db6")
    ],
    'feedback_timelimit' => env("FEEDBACK_TIMELIMIT", 3)
];
