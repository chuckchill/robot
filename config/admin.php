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

                ["label" => "admin.user.index", "name" => "管理员管理"],
                ["label" => "admin.user.create", "name" => "管理员添加"],
                ["label" => "admin.user.edit", "name" => "管理员编辑"],
                ["label" => "admin.user.destroy", "name" => "管理员删除"],

                ["label" => "admin.log.index", "name" => "操作日志"],
            ],
        ],
        [
            "label" => "admin.permission",
            "name" => "app管理",
            "icon" => "fa-users",
            "start" => 200,//起始id
            "children" => [
                ["label" => "admin.appuser.index", "name" => "用户管理"],
                ["label" => "admin.appuser.create", "name" => "用户添加"],
                ["label" => "admin.appuser.edit", "name" => "用户编辑"],
                ["label" => "admin.appuser.destroy", "name" => "用户删除"],

                ["label" => "admin.startup-page.index", "name" => "启动页"],
                ["label" => "admin.startup-page.edit", "name" => "修改启动页"],
                ["label" => "admin.startup-page.create", "name" => "添加启动页"],
                ["label" => "admin.startup-page.destroy", "name" => "删除启动页"],

                ["label" => "admin.link-page.index", "name" => "引导页"],
                ["label" => "admin.link-page.edit", "name" => "修改引导页"],
                ["label" => "admin.link-page.create", "name" => "添加引导页"],
                ["label" => "admin.link-page.destroy", "name" => "删除引导页"],

                ["label" => "admin.media-type.index", "name" => "媒体分类"],
                ["label" => "admin.media-type.edit", "name" => "修改媒体分类"],
                ["label" => "admin.media-type.create", "name" => "添加媒体分类"],
                ["label" => "admin.media-type.destroy", "name" => "删除媒体分类"],

                ["label" => "admin.article.index", "name" => "夜话文章"],
                ["label" => "admin.article.create", "name" => "添加夜话文章"],
                ["label" => "admin.article.edit", "name" => "修改夜话文章"],
                ["label" => "admin.article.destroy", "name" => "删除夜话文章"],

                ["label" => "admin.videos.index", "name" => "视频管理"],
                ["label" => "admin.videos.create", "name" => "添加视频"],
                ["label" => "admin.videos.edit", "name" => "修改视频"],
                ["label" => "admin.videos.destroy", "name" => "删除视频"],

                ["label" => "admin.live-videos.index", "name" => "录播视频管理"],
                ["label" => "admin.live-videos.create", "name" => "录播添加视频"],
                ["label" => "admin.live-videos.edit", "name" => "录播修改视频"],
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
            '100' => '康复医生康复过程录播',
            '200' => '残疾人居家自我康复录播',
        ]
    ]
];