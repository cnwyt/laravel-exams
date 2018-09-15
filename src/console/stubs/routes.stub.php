
// posts
Route::group(['prefix' => 'posts'], function (){
    Route::get('/', 'PostsController@showList');
    Route::get('/category/{$categoryName}', 'PostsController@showCategory');
    Route::get('/detail-{id}.html', 'PostsController@showDetail');

});