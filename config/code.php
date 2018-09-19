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
        'mobile_notnull' => ['code' => 10007, 'message' => '手机号不能为空'],
        'mobile_exist' => ['code' => 10008, 'message' => '手机号码已经被注册'],

        'email_invalid' => ['code' => 10009, 'message' => '邮箱无效'],
        'emailcode_error' => ['code' => 10010, 'message' => '邮件验证码无效'],
        'email_send_error' => ['code' => 10011, 'message' => '邮件验证码发送失败'],
        'email_notnull' => ['code' => 10012, 'message' => '邮箱不能为空'],
        'email_exist' => ['code' => 10013, 'message' => '邮箱已经被注册'],
        'code_30_valid' => ['code' => 10014, 'message' => '验证码30分钟内有效，请不要重复发送'],
        'account_cannot_change' => ['code' => 10015, 'message' => '您的账号已经设置'],
    ],
    'login' => [
        'mobile_notexist' => ['code' => 10016, 'message' => '手机号码不存在'],
        'account_notexist' => ['code' => 10017, 'message' => '账号不存在'],
        'password_invalid' => ['code' => 10018, 'message' => '密码不正确'],
        'account_notnull' => ['code' => 10019, 'message' => '账号不能为空'],
        'password_notnull' => ['code' => 10020, 'message' => '密码不能为空'],
        'mobile_notnull' => ['code' => 10021, 'message' => '手机号不能为空'],
        'smscode_error' => ['code' => 10022, 'message' => '短信验证码无效'],
        'smscode_send_error' => ['code' => 10023, 'message' => '短信验证码发送失败'],
        'device_sno_notnull' => ['code' => 10024, 'message' => '设备序列号不能为空'],
        'device_sno_notexist' => ['code' => 10025, 'message' => '设备序列号不存在'],
        'device_name_notnull' => ['code' => 10026, 'message' => '设备名称不能为空'],
        'device_bindforyou' => ['code' => 10027, 'message' => '您已经绑定该设备'],
        'device_bindforother' => ['code' => 10028, 'message' => '该设备已经被其他用户绑定'],
        'device_notmaster_change' => ['code' => 10029, 'message' => '非主监护人不允许修改绑定状态'],
        'device_unbind_all' => ['code' => 10030, 'message' => '您为主监护人,已经解除该设备的所有绑定'],
        'device_unbind_auth' => ['code' => 10031, 'message' => '绑定解除完成（主监护人解除他人的绑定关系）'],
        'device_unbind_own' => ['code' => 10032, 'message' => '绑定解除完成(非主监护人解除自己的绑定关系)'],
        'device_connot_unbind' => ['code' => 10033, 'message' => '非主监护人不允许解除其他人的绑定'],
        'device_unbind' => ['code' => 10034, 'message' => '你没有绑定该设备'],
        'device_master_connot_change' => ['code' => 10035, 'message' => '主监护人的状态不允许被修改'],
        'device_require_account' => ['code' => 10036, 'message' => '绑定设备前请设置账号'],
        'wechat_auth_error' => ['code' => 10037, 'message' => '微信授权登录失败'],
        'mobile_error' => ['code' => 10038, 'message' => '请填写正确的手机号码'],
        'contacts_must_distinct' => ['code' => 10039, 'message' => '不能添加与当前账号同一类型用户为联系人(医生/患者)'],
        'contacts_exist' => ['code' => 10040, 'message' => '联系人已经存在'],
    ],
    'device' => [
        'article_notfound' => ['code' => 10041, 'message' => '夜话文章不存在'],
    ],
    'common' => [
        'api_blockup' => ['code' => 10042, 'message' => '接口已停用'],
        'not_null' => ['code' => 10043, 'message' => '请填写完整信息'],
        'sicker_notnull' => ['code' => 10044, 'message' => '请选择病人信息'],
        'item_notexist' => ['code' => 10044, 'message' => '该信息不存在'],
        'idcard_invalid' => ['code' => 10044, 'message' => '身份证格式错误'],
    ]
];