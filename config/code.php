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
        'mobile_notnull' => ['code' => 10011, 'message' => '手机号不能为空'],
        'mobile_exist' => ['code' => 10014, 'message' => '手机号码已经被注册'],

        'email_invalid' => ['code' => 10007, 'message' => '邮箱无效'],
        'emailcode_error' => ['code' => 10008, 'message' => '邮件验证码无效'],
        'email_send_error' => ['code' => 10009, 'message' => '邮件验证码发送失败'],
        'email_notnull' => ['code' => 10012, 'message' => '邮箱不能为空'],
        'email_exist' => ['code' => 10013, 'message' => '邮箱已经被注册'],
        'code_30_valid' => ['code' => 10010, 'message' => '验证码30分钟内有效，请不要重复发送'],
    ],
    'login' => [
        'mobile_notexist' => ['code' => 20001, 'message' => '手机号码不存在'],
        'account_notexist' => ['code' => 20002, 'message' => '账号不存在'],
        'password_invalid' => ['code' => 20003, 'message' => '密码不正确'],
        'account_notnull' => ['code' => 20004, 'message' => '账号不能为空'],
        'password_notnull' => ['code' => 20005, 'message' => '密码不能为空'],
        'mobile_notnull' => ['code' => 20005, 'message' => '手机号不能为空'],
        'smscode_error' => ['code' => 20006, 'message' => '短信验证码无效'],
        'smscode_send_error' => ['code' => 20007, 'message' => '短信验证码发送失败'],
    ]
];