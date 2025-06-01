<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

JsonApiRoute::server('v1')->prefix('v1')->resources(function (ResourceRegistrar $server) {
    $server->resource('games', JsonApiController::class)
        ->readOnly()
        ->relationships(function (Relationships $relations) {
            $relations->hasMany('tags')->readOnly();
        });

    $server->resource('tags', JsonApiController::class)
        ->readOnly()
        ->relationships(function (Relationships $relations) {
            $relations->hasMany('games')->readOnly();
        });

    $server->resource('rooms', JsonApiController::class)
        ->readOnly()
        ->relationships(function (Relationships $relations) {
            $relations->hasMany('events')->readOnly();
        });

    $server->resource('events', JsonApiController::class)
        ->readOnly()
        ->relationships(function (Relationships $relations) {
            $relations->hasOne('room')->readOnly();
        });
});
