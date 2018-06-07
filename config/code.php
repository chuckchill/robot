<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 16:23
 */

return [
    'reg' => [
        'acount_notnull' => ['code' => 10000, 'message' => '账号不能为空'],
        'acount_exist' => ['code' => 10001, 'message' => '账号已经被注册'],
        'account_invalid' => ['code' => 10002, 'message' => '账号由以字母开头,长度为6-10的数字和字母组成'],
        'password_invalid' => ['code' => 10003, 'message' => '密码由长度为6-16的数字和字母组成'],

        'mobile_invalid' => ['code' => 10004, 'message' => '手机号码无效'],
        'smscode_error' => ['code' => 10005, 'message' => '短信验证码无效'],
        'smscode_send_error' => ['code' => 10006, 'message' => '短信验证码发送失败'],

        'email_invalid' => ['code' => 10007, 'message' => '邮箱无效'],
        'emailcode_error' => ['code' => 10008, 'message' => '邮件验证码无效'],
        'email_send_error' => ['code' => 10009, 'message' => '邮件验证码发送失败'],

        'code_30_valid' => ['code' => 10010, 'message' => '验证码30分钟内有效，请不要重复发送'],
    ]
];