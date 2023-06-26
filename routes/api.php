<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GridController;
use App\Http\Controllers\PoloController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TeachingController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\GridTemplateController;

/** Auth */
Route::post('auth/login', [AuthController::class,'login']);
Route::post('auth/logout', [AuthController::class,'logout']);
Route::post('auth/me', [AuthController::class,'me']);

/** Middleware */
Route::group(['middleware' => ['apiJwt']], function () {
    //add rotas para proteger
});

Route::get('users', [UserController::class,'index']);
Route::post('users', [UserController::class,'store']);

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

/** Turmas */
Route::resource('teams', TeamController::class);

/** Grades Templates */
Route::resource('grid-templates', GridTemplateController::class);

/** Etapas */
Route::resource('stages', StageController::class);

/** Grades */
Route::resource('grids', GridController::class);
Route::get('/grids/{team}/get-full-grid/', [GridController::class, 'getFullGrid']);
Route::get('/grids/get-grid-template/{grid}', [GridController::class, 'getGridTemplate']);
Route::get('/grids/remove-template-from-grid/{grid}', [GridController::class, 'removeTemplatesFromGrid']);

/** Documentos */
Route::prefix('documents')->group(function () {
    Route::post('/{student}/store-document', [DocumentController::class, 'storeDocument']);
    Route::get('/storage/{folder}/{filename}', [DocumentController::class, 'verifyBlobDocumentPDF']);
    Route::get('/{folder}/{filename}/remove', [DocumentController::class, 'destroy']);
    Route::get('/has-document/{code}', [DocumentController::class, 'hasDocument']);
});



