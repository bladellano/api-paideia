<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GridController;
use App\Http\Controllers\PoloController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeachingController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\GridTemplateController;

/** Auth */
Route::post('auth/login', [AuthController::class,'login']);
Route::post('auth/logout', [AuthController::class,'logout']);
Route::post('auth/me', [AuthController::class,'me']);

/** Middleware */
Route::group(['middleware' => ['apiJwt']], function () {
    Route::get('users', [UserController::class,'index']);
});

/** Ensinos */
Route::resource('teachings', TeachingController::class);

/** Polos */
Route::resource('polos', PoloController::class);

/** Cursos */
Route::resource('courses', CourseController::class);

/** Alunos */
Route::resource('students', StudentController::class);

/** Disciplinas */
Route::resource('disciplines', DisciplineController::class);

/** Grades */
Route::resource('grids', GridController::class);
Route::post('/history', [GridController::class, 'history']);

/** Grades Templates */
Route::resource('grid-templates', GridTemplateController::class);

/** Turmas */
Route::resource('teams', TeamController::class);
Route::get('/teams/{team}/list-grid/', [TeamController::class, 'listGrid']);

/** Etapas */
Route::resource('stages', StageController::class);


