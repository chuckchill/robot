<?php


$api->get('login', 'IndexController@login');
$api->get('startup-page', ['uses' => 'AppConfigController@startupPpage']);//启动页
$api->get('link-page', ['uses' => 'AppConfigController@linkPage']);//引导页
$api->group(['middleware' => ['api.auth']], function ($api) {
    $api->get('test', ['uses' => 'IndexController@index']);
});
