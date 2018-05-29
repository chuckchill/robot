<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Driver
    |--------------------------------------------------------------------------
    |
    | The Laravel queue API supports a variety of back-ends via an unified
    | API, giving you convenient access to each back-end using the same
    | syntax for each one. Here you may set the default queue driver.
    |
<<<<<<< HEAD
    | Supported: "sync", "database", "beanstalkd", "sqs", "redis", "null"
=======
    | Supported: "null", "sync", "database", "beanstalkd",
    |            "sqs", "redis"
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    |
    */

    'default' => env('QUEUE_DRIVER', 'sync'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    */

    'connections' => [

        'sync' => [
            'driver' => 'sync',
        ],

        'database' => [
            'driver' => 'database',
<<<<<<< HEAD
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
=======
            'table'  => 'jobs',
            'queue'  => 'default',
            'expire' => 60,
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        ],

        'beanstalkd' => [
            'driver' => 'beanstalkd',
<<<<<<< HEAD
            'host' => 'localhost',
            'queue' => 'default',
            'retry_after' => 90,
=======
            'host'   => 'localhost',
            'queue'  => 'default',
            'ttr'    => 60,
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        ],

        'sqs' => [
            'driver' => 'sqs',
<<<<<<< HEAD
            'key' => 'your-public-key',
            'secret' => 'your-secret-key',
            'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
            'queue' => 'your-queue-name',
=======
            'key'    => 'your-public-key',
            'secret' => 'your-secret-key',
            'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
            'queue'  => 'your-queue-name',
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
            'region' => 'us-east-1',
        ],

        'redis' => [
<<<<<<< HEAD
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'default',
            'retry_after' => 90,
=======
            'driver'     => 'redis',
            'connection' => 'default',
            'queue'      => 'default',
            'expire'     => 60,
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'database' => env('DB_CONNECTION', 'mysql'),
<<<<<<< HEAD
        'table' => 'failed_jobs',
=======
        'table'    => 'failed_jobs',
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    ],

];
