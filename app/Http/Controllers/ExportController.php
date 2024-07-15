<?php

namespace App\Http\Controllers;

use Exception;
use Money\Money;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\Student;
use App\Models\Financial;
use App\Models\SchoolGrade;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use NumberToWords\NumberToWords;
use App\Exports\ClassDiaryExport;
use App\Exports\ClassTransferReport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassStudentsPerClass;
use App\Exports\ClassStudentReportCard;
use App\Helpers\GenerateReportFinancial;
use App\Exports\ClassReportFinancialByTeam;
use App\Exports\ClassReportOfStudentDataByClass;

class ExportController extends Controller
{
    /** Relatório financeiro por turma */
    public function reportFinancial(Team $team)
    {

        $team->registrations;

        $toArray = $team->registrations->toArray();

        $toArray = array_filter($toArray, fn ($item) => isset($item['student']['name']));

        usort($toArray, [GenerateReportFinancial::class, 'compareStudents']);

        $newToArray = [];

        foreach ($toArray as $std) {

            $financials = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'registration_id' => $item['registration_id'],
                    'service_type_id' => $item['service_type_id'],
                    'quota' => $item['quota'],
                    'value' => $item['value'],
                    'due_date' => $item['due_date'],
                    'paid' => $item['paid'],
                    'pay_day' => $item['pay_day'],
                    'payment_type_name' => $item['payment_type']['name'],
                    'service_type_name' => $item['service_type']['name'],
                ];
            }, $std['financials']);

            if (is_array($std['student'])) {

                $newToArray[] = [
                    'registration_id' => $std['id'],
                    'student' => $std['student'],
                    'financials' => $financials
                ];
            }
        }

        foreach ($newToArray as &$student) {
            $student['financials'] = array_filter($student['financials'], function ($item) {
                return $item['service_type_id'] == 1; // TIPO MENSALIDADE
            });
        }

        $generateReport = (new GenerateReportFinancial())->execute($newToArray);

        return Excel::download(new ClassReportFinancialByTeam($generateReport, $team->name), __FUNCTION__ . "_" . \Str::random(10), \Maatwebsite\Excel\Excel::XLSX);
    }

    /** Diário de classe */
    public function classDiary(Request $request)
    {
        $team = Team::findOrFail($request->team_id);
        $disciplines = $team->getDisciplines($request->team_id);
        $notes = SchoolGrade::where('team_id', $request->team_id)->get();

        return Excel::download(new ClassDiaryExport($request->team_id, $disciplines, $notes), __FUNCTION__ . "_" . \Str::random(10), \Maatwebsite\Excel\Excel::XLSX);
    }

    /** Relatório de alunos por turma */
    public function studentsPerClass(Request $request)
    {
        return Excel::download(new ClassStudentsPerClass($request->team_id, $request->extra_lines), __FUNCTION__ . "_" . \Str::random(5), \Maatwebsite\Excel\Excel::XLSX);
    }

    /** Relatório de dados completos do aluno por turma */
    public function reportOfStudentDataByClass(Team $team)
    {
        return Excel::download(new ClassReportOfStudentDataByClass($team), __FUNCTION__ . "_" . \Str::random(5), \Maatwebsite\Excel\Excel::XLSX);
    }

    /** Relatório de repasse */
    public function transferReport(Team $team, string $start_date, string $end_date)
    {
        $team->registrations;

        $toArray = $team->registrations->toArray();

        $registrations = array_filter($toArray, fn ($item) => isset($item['student']['name']));

        $start_date = (new \DateTime($start_date))->format('d/m/Y');
        $end_date = (new \DateTime($end_date))->format('d/m/Y');

        $report = \App\Helpers\GenerateReportFinancial::organizesInTheFormOfTransfer($registrations, $team, $start_date, $end_date);

        if(!count($report)) 
            return response()->json(['error' => true, 'message' => 'Esta turma não possui dados financeiros ou o período selecionado não retornou pendências financeiras.'], Response::HTTP_OK);

        return Excel::download(new ClassTransferReport($report, $team, $start_date, $end_date), __FUNCTION__ . "_" . \Str::random(5), \Maatwebsite\Excel\Excel::XLSX);
    }

    /** Gera o boletim do aluno */
    public function studentReportCard(Student $student, Team $team)
    {

        $grades = SchoolGrade::getGrade($student->id);
        $arGrades = $grades->toArray();

        if(!count($arGrades)) 
            return response()->json(['error' => true, 'message' => 'Esse aluno não possui notas para poder gerar um boletim.'], Response::HTTP_OK);

        $arGrades = array_map(function ($item) {
            $item['discipline_name'] = $item['discipline']['name'];
            return $item;
        }, $arGrades);

        $arGrades = \App\Helpers\GenerateReportFinancial::organizeNotesForBulletin($arGrades);

        return Excel::download(new ClassStudentReportCard($arGrades, $student, $team), __FUNCTION__ . "_" . \Str::random(5), \Maatwebsite\Excel\Excel::XLSX);
    }

    /** Atestado de conclusão */
    public function certificateOfCompletion(Student $student, Team $team)
    {
        Carbon::setLocale('pt_BR');

        $student->course = $team->getStudentsByTeam($team->id)[0]->course;
        $pdf = Pdf::loadView('export.certificate-of-completion', compact('student'));

        return $pdf->download('atestado-de-conclusao.pdf');
    }

    /** Declaração de matrícula */
    public function registrationStatement(Student $student, Team $team)
    {
        Carbon::setLocale('pt_BR');

        $student->course = $team->getStudentsByTeam($team->id)[0]->course;
        $pdf = Pdf::loadView('export.registration-statement', compact('student'));

        return $pdf->download('declaracao-de-matricula.pdf');
    }

    /** Recibo */
    public function receipt(Financial $financial)
    {
        //? Configura a localidade para PT-BR
        Carbon::setLocale('pt_BR');

        $financial->registration;
        $financial->serviceType;

        $getCourse = Team::getStudentsByTeam($financial->registration->team->id);

        $financial->course = mb_strtoupper($getCourse[0]->course);
        $financial->currentDate = Carbon::now();
        $financial->valueFormated = number_format($financial->value, 2, ',', '.');
        $financial->inFull = $this->convertToWords($financial->value);

        $pdf = Pdf::loadView('export.receipt', compact('financial'));

        return $pdf->download('recibo.pdf');
    }

    /** Extrato financeiro do aluno */
    public function studentFinancialStatement(Student $student)
    {

        $registrations = $student->registrations;

        $pages = [];

        foreach ($registrations as $registraion) {

            $aFinancials = $registraion->financials->toArray();

            $totalPaid = array_reduce($aFinancials, function ($carry, $item) {
                if ($item['paid'] == 1)
                    $carry += $item['value'];
                return $carry;
            }, 0);

            $totalNotPaid = array_reduce($aFinancials, function ($carry, $item) {
                if ($item['paid'] == 0)
                    $carry += $item['value'];
                return $carry;
            }, 0);

            $financial = array_map(function ($item) use ($aFinancials) {
                $item['total_by_service'] = self::calculateTypeOfServices($aFinancials, $item['service_type_id']);
                $item['quota'] = $item['quota'] ?? 0;
                return $item;
            }, $aFinancials);

            $page = [
                'team_name' => $registraion->team->name,
                'student_name' => $registraion->student->name,
                'financials' => $financial,
                'total_paid' => $totalPaid,
                'total_not_paid' => $totalNotPaid
            ];

            $pages[] = $page;
        }

        $pdf = Pdf::loadView('export.student-financial-statement', compact('pages', 'student'));

        return $pdf->download('extrato-financeiro.pdf');
    }

    private function convertToWords($amount)
    {
        $amountInCents = (int)($amount * 100);

        $money = Money::BRL($amountInCents);
        $numberToWords = new NumberToWords();

        //? Cria o transformer para português
        $currencyTransformer = $numberToWords->getCurrencyTransformer('pt_BR');
        $amountInWords = $currencyTransformer->toWords($money->getAmount(), 'BRL');

        return $amountInWords;
    }

    private static function calculateTypeOfServices($types, $serviceTypeId)
    {
        $total = 0;
        foreach ($types as $type) {
            if ($type['service_type_id'] == $serviceTypeId)
                $total += 1;
        }
        return $total;
    }
}
