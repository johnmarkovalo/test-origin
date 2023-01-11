<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OccupantHistoryController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'forceJsonResponse'], function () {

    Route::group(['prefix' => 'v1'], function () {

        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'OccupantController@store');

        Route::get('/verification', 'VerificationController@verifyContactNumber');
        Route::put('/forgot-password', 'VerificationController@sendCodeToContactNumber');
        Route::put('/forgot-password/verify-code', 'VerificationController@verifyForgotPasswordCode');
        Route::put('/forgot-password/change-password', 'VerificationController@forgotPasswordChangePassword');

        Route::group(
            ['middleware' => 'auth:api'],
            function () {

                Route::post('/logout', 'AuthController@logout');

                Route::get('/user', function () {
                    return request()->user();
                });
                Route::put('/user/change-password', 'UserController@changePassword');

                Route::get('/dashboard', [UserController::class, 'dashboard']);

                Route::apiResource('occupants', 'OccupantController');
                Route::apiResource('hospitals', 'HospitalController');
                Route::apiResource('isolations', 'IsolationController');
                Route::apiResource('hospitalrooms', 'HospitalRoomController');
                Route::apiResource('isolationrooms', 'IsolationRoomController');
                Route::apiResource('roomrequests', 'RoomRequestController');
                Route::apiResource('isolationroomrequests', 'IsolationRoomRequestController');
                Route::post('/occupants/{occupant_id}/history', [OccupantHistoryController::class, 'store']);
                Route::put('/occupants/{occupant_id}/history/{id}', [OccupantHistoryController::class, 'update']);
                Route::delete('/occupants/{occupant_id}/history/{id}', [OccupantHistoryController::class, 'destroy']);

                // Route::apiResource();

            }
        );
        Route::get('nearbyhospitals', 'NearestHospitalController@nearbyHospital');
    });
});
