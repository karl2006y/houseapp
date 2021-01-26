<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::prefix('user')->group(function () {
        // 註冊
        Route::post('/register', ['App\Http\Controllers\userController'::class, 'Register']);
        // 登入
        Route::post('/login', ['App\Http\Controllers\userController'::class, 'Login']);
        // 取得資訊
        Route::middleware('auth:api')->get('/getuser', ['App\Http\Controllers\userController'::class, 'getUser']);
        // 驗證錯誤回傳
        Route::get('/loginerror', function () {
            return response()->json([
                'success' => false,
                'message' => "驗證錯誤，請重新登入",
            ], 500);
        })->name('login');;
    });
    Route::prefix('banners')->group(function () {
        // 取得全部Banner資料
        Route::get('/', ['App\Http\Controllers\BannerController'::class, 'index']);
        // 新增Banner
        Route::post('/create', ['App\Http\Controllers\BannerController'::class, 'store']);
        // 取得單一Banner資料
        Route::get('/{id}', ['App\Http\Controllers\BannerController'::class, 'show']);
        // 刪除
        Route::delete('delete/{id}', ['App\Http\Controllers\BannerController'::class, 'destroy']);
        // 修改
        Route::post('update/{id}', ['App\Http\Controllers\BannerController'::class, 'update']);
    });
    Route::prefix('datamanagement')->group(function () {
        Route::prefix('classification')->group(function () {
            // 取得所有類別資料
            Route::get('/', ['App\Http\Controllers\houseController'::class, 'index_classification']);
            // 取得類別下所有的房屋資料
            Route::get('/{id}', ['App\Http\Controllers\houseController'::class, 'index_by_classification']);
        });
        // 取得所有房屋資料
        Route::get('/', ['App\Http\Controllers\houseController'::class, 'index']);
        // 查看單一房屋資料
        Route::get('/{id}', ['App\Http\Controllers\houseController'::class, 'show']);
    });
    Route::prefix('admin')->group(function () {
        Route::prefix('datamanagement')->group(function () {
            Route::prefix('classification')->group(function () {
                // 取得所有類別資料
                Route::get('/', ['App\Http\Controllers\houseController'::class, 'index_classification_admin']);
                // 取得類別下所有的房屋資料
                Route::get('/{id}', ['App\Http\Controllers\houseController'::class, 'index_by_classification_admin']);
                // 新增類別
                Route::post('/create', ['App\Http\Controllers\houseController'::class, 'store_classification']);
                // 更新類別
                Route::put('/update/{id}', ['App\Http\Controllers\houseController'::class, 'update_classification']);
                // 刪除單一類別
                Route::delete('/delete/{id}', ['App\Http\Controllers\houseController'::class, 'destroy_classification']);
            });
            // 取得所有房屋資料
            Route::get('/', ['App\Http\Controllers\houseController'::class, 'index_admin']);
            // 查看單一房屋資料
            Route::get('/{id}', ['App\Http\Controllers\houseController'::class, 'show_admin']);
            // 新增房屋資料
            Route::post('/create', ['App\Http\Controllers\houseController'::class, 'store']);
            // 更新房屋資訊
            Route::post('/update/{id}', ['App\Http\Controllers\houseController'::class, 'update']);
            // 刪除單一房屋
            Route::delete('/delete/{id}', ['App\Http\Controllers\houseController'::class, 'destroy']);

        });
    });

});
