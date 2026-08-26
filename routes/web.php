<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::controller(PublicSiteController::class)->group(function () {
    Route::get('/', 'home')->name('home'); Route::get('/projects', 'projects')->name('projects.index'); Route::get('/projects/{project:slug}', 'project')->name('projects.show');
    Route::get('/blog', 'blog')->name('blog.index'); Route::get('/blog/{post:slug}', 'post')->name('blog.show'); Route::get('/about', 'about')->name('about'); Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'storeContact')->middleware('throttle:5,10')->name('contact.store'); Route::get('/sitemap.xml', 'sitemap')->name('sitemap'); Route::get('/feed.xml', 'feed')->name('feed');
});
Route::middleware('guest')->prefix('admin')->group(function () { Route::get('/login',[AuthController::class,'create'])->name('admin.login'); Route::post('/login',[AuthController::class,'store'])->middleware('throttle:5,1')->name('admin.login.store'); });
Route::middleware(['auth','can:access-admin'])->prefix('admin')->name('admin.')->group(function () { Route::get('/',DashboardController::class)->name('dashboard'); Route::resource('posts',PostController::class)->except('show'); Route::resource('projects',ProjectController::class)->except('show'); Route::post('/logout',[AuthController::class,'destroy'])->name('logout'); });
