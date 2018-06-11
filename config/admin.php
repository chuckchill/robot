<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/11
 * Time: 10:02
 */

return [
    'permission_seeder' => [
        [
            "label" => "admin.permission",
            "name" => "系统管理",
            "icon" => "fa-users",
            "start" => 100,//起始id
            "children" => [
                ["label" => "admin.permission.index", "name" => "权限列表"],
                ["label" => "admin.permission.create", "name" => "权限添加"],
                ["label" => "admin.permission.edit", "name" => "权限修改"],
                ["label" => "admin.permission.destroy", "name" => "权限删除"],
                ["label" => "admin.role.index", "name" => "角色列表"],
                ["label" => "admin.role.create", "name" => "角色添加"],
                ["label" => "admin.role.edit", "name" => "角色修改"],
                ["label" => "admin.role.destroy", "name" => "角色删除"],
                ["label" => "admin.user.index", "name" => "用户管理"],
                ["label" => "admin.user.create", "name" => "用户添加"],
                ["label" => "admin.user.edit", "name" => "用户编辑"],
                ["label" => "admin.user.destroy", "name" => "用户删除"],
                ["label" => "admin.log.index", "name" => "操作日志"],
            ],
        ],
        [
            "label" => "admin.permission",
            "name" => "app管理",
            "icon" => "fa-users",
            "start" => 200,//起始id
            "children" => [
                ["label" => "admin.startup-page.index", "name" => "启动页"],
                ["label" => "admin.startup-page.edit", "name" => "修改启动页"],
                ["label" => "admin.startup-page.create", "name" => "添加启动页"],
                ["label" => "admin.startup-page.destroy", "name" => "删除启动页"],
                ["label" => "admin.boot-page.index", "name" => "引导页"],
                ["label" => "admin.boot-page.edit", "name" => "修改引导页"],
                ["label" => "admin.boot-page.create", "name" => "添加引导页"],
                ["label" => "admin.boot-page.destroy", "name" => "删除引导页"],
            ],
        ],
        /* [
             "label" => "admin.permission"
         ],
         [
             "label" => "admin.permission"
         ],
         [
             "label" => "admin.permission"
         ],*/
    ]
];