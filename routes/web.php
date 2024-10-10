<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivewireTestController;
use App\Http\Controllers\AlpineTestController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('calendar');
});

// 以下、今回、API認証を使用しないので、コメントアウト
// ログイン後、ダッシュボードに移動
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::prefix('manager')
->middleware('can:manager-higher')
->group(function(){ 
    Route::get('events/past', [EventController::class, 'past'])->name('events.past');
    Route::resource('events', EventController::class); //urlはmanager/events/indexのような形になる
// ルーティングは上から処理される
// Route::get('events/past',....を、Route::resource...の下に書くと 
// /past部分がパラメータと勘違いされるのでリソースの上に書く
});

// ログインしていたら見れる
Route::middleware('can:user-higher')
->group(function(){
 Route::get('/dashboard', [ReservationController::class, 'dashboard' ])->name('dashboard');
 Route::get('/{id}', [ReservationController::class, 'detail' ])->name('events.detail');
 Route::post('/{id}', [ReservationController::class, 'reserve' ])->name('events.reserve');
}); 

Route::controller(LivewireTestController::class)
->prefix('livewire-test')->name('livewire-test.')->group(function(){
    Route::get('index', 'index')->name('index');
    Route::get('register', 'register')->name('register');
});

Route::get('alpine-test/index',[AlpineTestController::class, 'index']);