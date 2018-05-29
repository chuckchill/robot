<<<<<<< HEAD
const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');
=======
var elixir = require('laravel-elixir');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

<<<<<<< HEAD
elixir(mix => {
    mix.sass('app.scss')
       .webpack('app.js');
=======
elixir(function(mix) {
    mix.sass('app.scss');
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
});
