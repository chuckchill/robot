<?php


$api->get('login', 'IndexController@login');
$api->group(['middleware' => ['api.auth']], function ($api) {
    $api->get('test', ['uses' => 'IndexController@index']);
});
