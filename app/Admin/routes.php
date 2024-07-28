<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->get('users', 'UsersController@index');
    $router->get('topics', 'TopicsController@index');

    $router->get('categories', 'CategoriesController@index');
    $router->get('categories/create', 'CategoriesController@create');
    $router->get('categories/{id}/edit', 'CategoriesController@edit');
    $router->post('categories', 'CategoriesController@store');
    $router->put('categories/{id}', 'CategoriesController@update');
    $router->delete('categories/{id}', 'CategoriesController@destroy');
    $router->get('api/categories', 'CategoriesController@apiIndex');


    $router->get('topics/{id}/edit', 'TopicsController@edit');
    $router->put('topics/{id}', 'TopicsController@update');

    $router->get('topics/{topic}', 'TopicsController@show')->name('topics.show');

    $router->get('links', 'LinksController@index');

    $router->get('links/create', 'LinksController@create');
    $router->post('links', 'LinksController@store');

    $router->get('links/{id}/edit', 'LinksController@edit');
    $router->put('links/{id}', 'LinksController@update');
    $router->delete('links/{id}', 'LinksController@destroy');


    $router->get('replies', 'RepliesController@index');

    $router->get('replies/{id}/edit', 'RepliesController@edit');
    $router->put('replies/{id}', 'RepliesController@update');
    // $router->delete('replies/{id}', 'RepliesController@destroy');

});
