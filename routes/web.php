<?php

use App\Mail\TestMail;
use App\Models\User;
use App\Notifications\SubscribeNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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
    /*try {
        Mail::to('wall.mate@gmail.com')->send(new TestMail('It works!'));
        //dd($dd);
    } catch (\Exception $ex) {
        dd($ex);
    }*/

    //$table = User::find(3);
    //$table->notify(new SubscribeNotification()); //Notification Test

    //return view('welcome');
});
