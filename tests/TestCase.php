<?php

<<<<<<< HEAD
abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
=======
class TestCase extends Illuminate\Foundation\Testing\TestCase
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
