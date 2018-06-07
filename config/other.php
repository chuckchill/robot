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
    'mobile_action' => env('MOBILE_ACTION', false)
];
