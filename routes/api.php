<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    GridController,
    PoloController,
    TeamController,
    UserController,
    StageController,
    CourseController,
    StudentController,
    DocumentController,
    TeachingController,
    DisciplineController,
    GridTemplateController,
    ReportController,
    MailController,
    SchoolGradeController,
    TextDocumentController
};

Route::get('send-mail', [MailController::class, 'index']);

/** Auth */
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/me', [AuthController::class, 'me']);

/** Reports */
/** @todo VERIFICAR PORQUE NAO FUNCIONA DENTRO DO MIDDLEWARE */
Route::get('reports/general-report-of-students', [ReportController::class, 'generalReportOfStudents']);

/** Documentos */
Route::prefix('documents')->group(function () {
    Route::post('/{student}/store-document', [DocumentController::class, 'storeDocument']);
    Route::get('/storage/{folder}/{filename}', [DocumentController::class, 'verifyBlobDocumentPDF']);
    Route::get('/{folder}/{filename}/remove', [DocumentController::class, 'destroy']);
    Route::get('/has-document/{code}', [DocumentController::class, 'hasDocument']);
});

/** Middleware */
Route::group(['middleware' => ['apiJwt']], function () {
    //add rotas para proteger

    /** Cursos */
    Route::resource('courses', CourseController::class);

    /** Usuarios */
    Route::resource('users', UserController::class);

    /** Ensinos */
    Route::resource('teachings', TeachingController::class);

    /** Polos */
    Route::resource('polos', PoloController::class);

    /** Alunos */
    Route::resource('students', StudentController::class);

    /** Disciplinas */
    Route::resource('disciplines', DisciplineController::class);

    /** Turmas */
    Route::resource('teams', TeamController::class);
    Route::post('/teams/register-student', [TeamController::class, 'registerStudent']);

    /** Grades Templates */
    Route::resource('grid-templates', GridTemplateController::class);

    /** Etapas */
    Route::resource('stages', StageController::class);

    /** Grades */
    Route::resource('grids', GridController::class);
    Route::get('/grids/{team}/get-full-grid/', [GridController::class, 'getFullGrid']);
    Route::get('/grids/get-grid-template/{grid}', [GridController::class, 'getGridTemplate']);
    Route::get('/grids/remove-template-from-grid/{grid}', [GridController::class, 'removeTemplatesFromGrid']);

    /** Notas */
    Route::resource('grades', SchoolGradeController::class);
    Route::get('/grades/{studentId}/get-grade-by-student', [SchoolGradeController::class, 'getGradeByStudent']);

    /** Texto de Documentos */
    Route::resource('text-documents', TextDocumentController::class);

});
