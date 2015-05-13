<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Default response.
$app->get('/', function() use ($app) {
    return $app->welcome();
});

// Get.
$app->get('comments', 'App\Http\Controllers\CommentController@index');
$app->get('comments/{id}', 'App\Http\Controllers\CommentController@getComment');
$app->get('comments/post/{slug}', 'App\Http\Controllers\CommentController@getCommentsForPost');

// Post.
$app->post('comments/new', 'App\Http\Controllers\CommentController@saveComment');
