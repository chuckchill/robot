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
                ["label" => "admin.link-page.index", "name" => "引导页"],
                ["label" => "admin.link-page.edit", "name" => "修改引导页"],
                ["label" => "admin.link-page.create", "name" => "添加引导页"],
                ["label" => "admin.link-page.destroy", "name" => "删除引导页"],
                ["label" => "admin.videos-type.index", "name" => "视频分类"],
                ["label" => "admin.videos-type.edit", "name" => "修改视频分类"],
                ["label" => "admin.videos-type.create", "name" => "添加视频分类"],
                ["label" => "admin.videos-type.destroy", "name" => "删除视频分类"],
                ["label" => "admin.videos.index", "name" => "视频管理"],
                ["label" => "admin.videos.create", "name" => "添加视频"],
                ["label" => "admin.videos.edit", "name" => "修改视频"],
                ["label" => "admin.other.index", "name" => "其他设置"],
                ["label" => "admin.other.keyword", "name" => "热门关键词"],
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
    ],
    'videos' => [
        'type' => [
            '100' => '康复指导人员远程康复视频',
            '200' => '残疾人就业康复实用技术培训视频',
            '300' => '聋哑残疾人手语视频',
            '400' => '心理康复视频',
        ]
    ]
];