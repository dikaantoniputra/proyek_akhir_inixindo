<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/user', function () {
        return redirect()->route('dashboard');
    })->name('user.dashboard');
    Route::get('/user/dashboard', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/user/tasks', function () {
        return redirect()->route('tasks.index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/tasks/export', [TaskController::class, 'export'])->name('tasks.export');
    Route::get('/tasks/kanban', [TaskController::class, 'kanban'])->name('tasks.kanban');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');

    Route::post('/tasks/{task}/checklists', [TaskController::class, 'storeChecklist'])->name('tasks.checklists.store');
    Route::patch('/tasks/checklists/{checklist}/toggle', [TaskController::class, 'toggleChecklist'])->name('tasks.checklists.toggle');
    Route::delete('/tasks/checklists/{checklist}', [TaskController::class, 'destroyChecklist'])->name('tasks.checklists.destroy');

    Route::post('/tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');
    Route::delete('/tasks/comments/{comment}', [TaskController::class, 'destroyComment'])->name('tasks.comments.destroy');

    Route::resource('tasks', TaskController::class);

    Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.tasks');
        });
        Route::get('/dashboard', function () {
            return redirect()->route('dashboard');
        });
        Route::get('/all-tasks/export', [AdminController::class, 'exportTasks'])->name('tasks.export');
        Route::get('/all-tasks', [AdminController::class, 'allTasks'])->name('tasks');
        Route::get('/tasks', [AdminController::class, 'allTasks']);

        Route::get('/users-summary/export', [AdminController::class, 'exportUsersSummary'])->name('users.export');
        Route::get('/users-summary', [AdminController::class, 'usersSummary'])->name('users');
        Route::get('/users', [AdminController::class, 'usersSummary']);

        Route::get('/admin/all-tasks', [AdminController::class, 'allTasks']);
        Route::get('/admin/users-summary', [AdminController::class, 'usersSummary']);
    });
});
