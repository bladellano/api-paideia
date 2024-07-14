<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    BookController,
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
    ExportController,
    FinancialController,
    GridTemplateController,
    ReportController,
    MailController,
    PaymentTypeController,
    RegistrationController,
    SchoolGradeController,
    ServiceTypeController,
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
    Route::put('/{document}', [DocumentController::class, 'update']);
});

/** Exportacao */
Route::get('exports/class-diary', [ExportController::class, 'classDiary']);
Route::get('exports/students-per-class', [ExportController::class, 'studentsPerClass']);
Route::get('exports/receipt/{financial}', [ExportController::class, 'receipt']);
Route::get('exports/student-financial-history/{student}', [ExportController::class, 'studentFinancialStatement']);
Route::get('exports/report-of-student-data-by-class/{team}', [ExportController::class, 'reportOfStudentDataByClass']);
Route::get('exports/report-financial-by-team/{team}', [ExportController::class, 'reportFinancial']);
Route::get('exports/certificate-of-completion/{student}/team/{team}', [ExportController::class, 'certificateOfCompletion']);
Route::get('exports/registration-statement/{student}/team/{team}', [ExportController::class, 'registrationStatement']);
Route::get('exports/student-report-card/{student}/team/{team}', [ExportController::class, 'studentReportCard']);
Route::get('exports/transfer-report/{team}/start_date/{start_date}/end_date/{end_date}', [ExportController::class, 'transferReport']);

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
    Route::get('students/{student}/get-image', [StudentController::class, 'getImage']);

    /** Disciplinas */
    Route::resource('disciplines', DisciplineController::class);

    /** Turmas */
    Route::resource('teams', TeamController::class);
    Route::post('/teams/register-student', [TeamController::class, 'registerStudent']);
    Route::get('/teams/{team}/students', [TeamController::class, 'studentsByTeam']);
    Route::get('/teams/{team}/disciplines', [TeamController::class, 'disciplinesByTeam']);
    Route::get('/teams/{team}/grades', [TeamController::class, 'gradesByTeam']);

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

    /** Matriculas */
    Route::apiResource('registrations', RegistrationController::class);

    /** Financeiro */
    Route::apiResource('financials', FinancialController::class);

    /** Forma de Pagamento */
    Route::apiResource('payment-types', PaymentTypeController::class);

    /** Tipo de Serviço */
    Route::apiResource('service-types', ServiceTypeController::class);

    /** Livro */
    Route::apiResource('books', BookController::class);
});
