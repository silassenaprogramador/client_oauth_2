<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::get('/', function () { return view('welcome'); });

/**
 *  Solicitando conceção de autorizacao
 */
Route::get('/registro', function (Request $request) {
   
    $query = http_build_query([
        'client_id'=> env('CLIENT_ID'),
        'redirect_url'=>env('REDIRECT_URL'),
        'response_type'=>'code',
        'scope'=>'',
        'state'=> Str::random(40)
    ]);
    
    return redirect(env('API_URL')."oauth/authorize?".$query);
})->name('registro');

/**
 * recebe concecção de autorização, solicita e recebe o access token
 */
Route::get('/callback', function(Request $request) {    

    $response = Http::post(env('API_URL').'oauth/token',[
        'grant_type' => 'authorization_code',
        'client_id' => env('CLIENT_ID'),
        'client_secret' => env('CLIENT_SECRET'),
        'redirect_url' => env('REDIRECT_URL'),
        'code' => $request->code
    ]);
    dd($response->json());
});

/**
 * Atualizando o token
 */
Route::get('/refresh_token', function(Request $request) {    

    $response = Http::post(env('API_URL').'oauth/token', [    
        'grant_type' => 'refresh_token',
        'refresh_token' => env('REFRESH_TOKEN'),
        'client_id' => env('CLIENT_ID'),
        'client_secret' => env('CLIENT_SECRET'),
        'scope' => '',        
    ]);
    
    dd($response->json());    
});

