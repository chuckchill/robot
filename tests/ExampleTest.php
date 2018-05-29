<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
<<<<<<< HEAD
             ->see('Laravel');
=======
             ->see('Laravel 5');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
    }
}
