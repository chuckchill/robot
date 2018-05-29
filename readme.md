<<<<<<< HEAD
# Larval 5.3 Rbac 后台实例

## 说明

基于laravel 5.3 与 自带的gate来做权限认证 ( 之前5.2的是用的zizaco/entrust,查询次数太多我只建议用来学习)
开箱即用的后台模板,菜单栏都是基于权限来生成
前后端用户分表分别登录
集成了laravel-debugbar 超好用调试工具
带有日志解析查看模块
###### 本项目可以用于生产

## 截图

## ![laravel rbac](http://o7ze7op4t.bkt.clouddn.com/QQ20161111-1.png)



![rbac](http://o7ze7op4t.bkt.clouddn.com/QQ20161111-2.png)



## 安装

- git clone 到本地
- 执行 `composer install`,创建好数据库
- 配置 **.env** 中数据库连接信息,没有.env请复制.env.example命名为.env
- 执行 `php artisan key:generate`
- 执行 `php artisan migrate`
- 执行 `php artisan db:seed --class=AdminInitSeeder`
- 键入 '域名/admin/login'(后台登录)
- 默认后台账号:root@admin.com 密码:root


## 使用
- 用户管理中的权限管理添加顶级权限
   比如用户管理, 'admin.user' 只有两段的做左边的菜单栏, 列表页统一为'admin.XXXX.index'
   具体部分可以参照路由与源码,也可以QQ我176608671
=======
# laravel-multi-auth-admin
> 包含前后台登录认证以及权限管理的后台系统，模板为`color admin`

![image1](http://7xuntv.com1.z0.glb.clouddn.com/zhanghaobao1.png)

![image2](http://7xuntv.com1.z0.glb.clouddn.com/zhanghaobao2.png)

![image3](http://7xuntv.com1.z0.glb.clouddn.com/zhanghaobao3.png)

# 说明
如果喜欢请点个star

需要color admin的朋友请群下载，Laravel开发交流群：658533928

![image4](http://7xuntv.com1.z0.glb.clouddn.com/658533928.JPG?imageMogr2/thumbnail/!50p)

# 安装

## 克隆资源库
```shell
git clone https://github.com/jwwb681232/laravel-multi-auth-admin.git ./
```
## 安装依赖关系
```shell
composer install
```
## 复制配置文件
```shell
cp .env.example .env
```

## 创建新的应用程序密钥
```shell
php artisan key:generate
```
## 设置数据库
编辑`.env`文件
```shell
CACHE_DRIVER=array

DB_HOST=YOUR_DATABASE_HOST
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USERNAME
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```
## 添加自动加载
```shell
composer dump-autoload
```

## 运行数据库迁移
```shell
php artisan migrate
```

## 运行数据填充
```shell
php artisan db:seed
```

## nginx rewrite配置
```shell
location / {
    index  index.html index.htm index.php;
    if (!-e $request_filename){
         rewrite ^/(.*)$ /index.php/$1 last;
    }
}
```
## 访问
[http://xxx.com/admin](http://xxx.com/admin)

后台账号:`admin@admin.com`

后台密码:`admin`
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
