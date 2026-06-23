<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IssueCommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueMemberController;
use App\Http\Controllers\IssueTagController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/issues/search', [IssueController::class, 'search'])->name('issues.search');

Route::middleware('auth')->group(function (): void {
	Route::resource('projects', ProjectController::class)->except(['index', 'show']);
	Route::resource('issues', IssueController::class)->except(['index', 'show']);
	Route::post('/tags', [TagController::class, 'store'])->name('tags.store');

	Route::post('/issues/{issue}/comments', [IssueCommentController::class, 'store'])->name('issues.comments.store');
	Route::post('/issues/{issue}/tags', [IssueTagController::class, 'store'])->name('issues.tags.store');
	Route::delete('/issues/{issue}/tags/{tag}', [IssueTagController::class, 'destroy'])->name('issues.tags.destroy');

	Route::post('/issues/{issue}/members', [IssueMemberController::class, 'store'])->name('issues.members.store');
	Route::delete('/issues/{issue}/members/{user}', [IssueMemberController::class, 'destroy'])->name('issues.members.destroy');
});

Route::resource('projects', ProjectController::class)->only(['index', 'show']);
Route::resource('issues', IssueController::class)->only(['index', 'show']);
Route::resource('tags', TagController::class)->only(['index']);

Route::get('/issues/{issue}/comments', [IssueCommentController::class, 'index'])->name('issues.comments.index');
