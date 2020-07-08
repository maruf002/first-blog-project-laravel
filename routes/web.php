<?php

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


Route::get('/','Homecontroller@index')->name('home');
Route::get('posts','PostController@index')->name('post.index');
Route::get('post/{slug}','PostController@detailes')->name('post.detailes');

Route::get('/search','SearchController@search')->name('search');

Route::get('/profile/{username}','AuthorController@profile')->name('author.profile');

Route::get('/category/{slug}','PostController@postByCategory')->name('category.posts');
Route::get('/tag/{slug}','PostController@postByTag')->name('tag.posts');

Route::post('subscriber','SubscriberController@store')->name('subscriber.store');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>['auth']], function () {
	// favourite/{post}/add = without add it also works , its nothing for remember
	Route::post('favourite/{post}/add','FavouriteController@add')->name('post.favourite');
	  Route::post('comment/{post}/store','CommentController@store')->name('comment.store');
});

Route::group(['as'=>'admin.','prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth','admin']],function(){
	
	Route::get('dashboard','DashboardController@index')->name('dashboard');
	
	Route::resource('tag', 'TagController');
	Route::resource('category', 'CategoryController');
	Route::resource('post','PostController');
	Route::get('settings','SettingsController@index')->name('settings');
	Route::put('profile-update','SettingsController@updateprofile')->name('profile.update');
	Route::put('password-update','SettingsController@updatepassword')->name('password.update');


	Route::get('/pending/post','PostController@pending')->name('post.pending');
	Route::put('/post/{id}/approve','PostController@approval')->name('post.approve');



	Route::get('/favourite','FavouriteController@index')->name('favourite.index');

	
	Route::get('/authors','AuthorController@index')->name('author.index');
	Route::delete('/authors/{id}','AuthorController@destroy')->name('author.destroy');

	 
	Route::get('comments','CommentController@index')->name('comment.index');
	Route::delete('comments/{id}','CommentController@destroy')->name('comment.destroy');




	Route::get('/subscriber','SubscriberController@index')->name('subscriber.index');
	Route::delete('/subscriber/{any}','SubscriberController@destroy')->name('subscriber.destroy'); //{any} use any variable without space


});

Route::group(['as'=>'author.','prefix'=>'author','namespace'=>'Author','middleware'=>['auth','author']],function(){
	Route::get('dashboard','DashboardController@index')->name('dashboard');
	Route::resource('post', 'PostController');
	Route::get('settings','SettingsController@index')->name('settings');
	Route::put('profile-update','SettingsController@updateprofile')->name('profile.update');
	Route::put('password-update','SettingsController@updatepassword')->name('password.update');

	Route::get('/favourite','FavouriteController@index')->name('favourite.index');

	Route::get('comments','CommentController@index')->name('comment.index');
	Route::delete('comments/{id}','CommentController@destroy')->name('comment.destroy');

});


view()->composer('layouts.frontend.partial.footer', function ($view) {
	$categories = App\Category::all();
	$view->with('categories',$categories);
});
