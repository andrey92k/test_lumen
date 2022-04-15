<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});


    $router->group([
        'middleware' => 'auth',
        'prefix' => 'api/user'
    ],
    function ($app) {
        $app->post('companies', 'CompanyController@store');
        $app->get('companies', 'CompanyController@index');
    });

    $router->group([
        'prefix' => 'api/user'
    ],
    function ($app) {
        $app->post('sign-in', 'Auth\LoginController@login');
        $app->post('register', 'Auth\RegisterController@register');
        $app->post('recover-password', 'Auth\RecoverPasswordController@recoverPasswordSendEmail');
        $app->get('recover-password', ['as' => 'recover_password_link', 'uses' => 'Auth\RecoverPasswordController@recoverPasswordLink']);
        $app->put('update-password-with-token', ['as' => 'update_password_token', 'uses' => 'Auth\RecoverPasswordController@updatePasswordWithToken']);

    });



