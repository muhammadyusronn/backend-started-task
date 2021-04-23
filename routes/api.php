<?php

use App\Http\Controllers\API\C_event;
use App\Http\Controllers\API\C_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Event
    Route::get('/event', [C_event::class, 'event']); // get all event
    Route::post('/event/create', [C_event::class, 'create']); // creating event
    Route::get('/event/detail/{id}', [C_event::class, 'detail']); // get event's detail
    Route::get('/event/update/{id}', [C_event::class, 'detail']); // get event's detail
    Route::post('/event/update', [C_event::class, 'update']); // get event's detail
    Route::get('/event/delete/{id}', [C_event::class, 'delete']); // get event's detail

    Route::get('/logout', [C_user::class, 'logout']); // Logout and deleting token
    Route::get('/user', [C_user::class, 'user']); // get all user
});
Route::post('/login', [C_user::class, 'login']); // login
Route::post('/register', [C_user::class, 'register']); // register
