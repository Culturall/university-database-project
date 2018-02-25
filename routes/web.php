<?php

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

Route::view('/', 'welcome', [
    'route' => 0,
    'css_files' => ['welcome'],
    'campaigns' => [
        (object) ['title'=>'Titolo', 'description'=>'descrizione']
    ]
]);
Route::view('/explore', 'explore', [
    'route' => 1,
    'css_files' => ['explore'],
    'campaigns' => [
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione'],
        (object) ['title'=>'Titolo', 'description'=>'descrizione']
    ]
]);
Route::view('/sign', 'sign', [
    'route' => 3,
    'css_files' => ['sign']
]);