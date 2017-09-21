<?php 

Route::any('{path}', 'SystemController@anyLogin')->where('path','.*');


Route::any('{path}', function () {
    return view('welcome');
})->where('path','.*');

?>