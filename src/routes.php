<?php

use Illuminate\Support\Facades\Route;

/*
 * WEB
 */

Route::group([
    "prefix" => ADMIN,
    "middleware" => ["web", "auth:backend"],
    "namespace" => "Benaaacademy\\Media\\Controllers"
], function ($route) {
    $route->group(["prefix" => "media"], function ($route) {
        $route->any('/get/{offset?}/{type?}/{q?}', ["as" => "admin.media.index", "uses" => "MediaController@index"]);
        $route->any('/save', ["as" => "admin.media.save", "uses" => "MediaController@save"]);
        $route->any('/delete', ["as" => "admin.media.delete", "uses" => "MediaController@delete"]);
        $route->any('/upload', ["as" => "admin.media.upload", "uses" => "MediaController@upload"]);
        $route->any('/download', ["as" => "admin.media.download", "uses" => "MediaController@download"]);
        $route->any('/link', ["as" => "admin.media.link", "uses" => "MediaController@link"]);
        $route->any('/crop', ["as" => "admin.media.crop", "uses" => "MediaController@crop"]);
    });
});
