<?php 

/**
*  注意使用限制条件，如果没限制好，也可能匹配不了
*/



// 个股新闻列表
Route::any('/news/{code}/{page}.htm', 'StockInfoController@newsList')->where('page','[0-9]+')->where('code','[0-9]+');
Route::any('/news/{code}/', 'StockInfoController@newsList')->where('code','[0-9]+');

//个股详情	---> 这个如果放在前面，则列表路由会被覆盖
Route::any('/{code}.htm', ['as' => 'market_gp_detail', 'uses' => 'MarketController@stockDetail'])->where('code','.*');            


?>