<?php

namespace App\Http\Controllers;

use Money\Money;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\Student;
use App\Models\Financial;
use App\Models\SchoolGrade;
use App\Exports\ClassDiaryExport;
use App\Exports\ClassStudentsPerClass;
use App\Exports\ClassReportFinancialByTeam;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use NumberToWords\NumberToWords;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function reportFinancial(Team $team, int $year)
    {

        $team->registrations;

        $toArray = $team->registrations->toArray();

        $newToArray = [];

        foreach($toArray as $std) {

            $financials = array_map(function($item){
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

            if(is_array($std['student'])) {

                $newToArray[] = [
                    'registration_id' => $std['id'],
                    'student' => $std['student'],
                    'financials' => $financials
                ];
            }
        }

        foreach($newToArray as &$student) {
            $student['financials'] = array_filter($student['financials'], function ($item) use ($year) {
                $dueDate = \DateTime::createFromFormat('d/m/Y', $item['due_date']);
                //! @TODO aqui pode ser dinamizado mudando o tipo de serviçõ futuramente.
                return $item['service_type_id'] == 1 && $dueDate->format('Y') == $year; // TIPO MENSALIDADE
            });
        }

        $financialReport = [];

        foreach ($newToArray as $registration) {

            $studentName = $registration['student']['name'];
            $studentId = $registration['student']['id'];
            $financials = $registration['financials'];
            
            $monthlyFinancials = array_fill(1, 12, 0);

            foreach ($financials as $finance) {

                $dueDate = \DateTime::createFromFormat('d/m/Y', $finance['due_date']);

                if ($dueDate) {
                    $month = (int) $dueDate->format('m');
                    $monthlyFinancials[$month] = [
                            'value'=> number_format($finance['value'], 2, ',', '.'), 
                            'paid' => $finance['paid'],
                            'overdue' => !$finance['paid'] && $dueDate < new \DateTime() ? 1 : 0,
                        ];
                }
            }

            array_unshift($monthlyFinancials, $studentName);

            $financialReport[$studentId] = $monthlyFinancials;
        }

        return Excel::download(new ClassReportFinancialByTeam($financialReport, $year, $team->name), __FUNCTION__ . "_" . \Str::random(10), \Maatwebsite\Excel\Excel::XLSX);
    }

    public function classDiary(Request $request)
    {
        $team = Team::findOrFail($request->team_id);
        $disciplines = $team->getDisciplines($request->team_id);
        $notes = SchoolGrade::where('team_id', $request->team_id)->get();

        return Excel::download(new ClassDiaryExport($request->team_id, $disciplines, $notes), __FUNCTION__ . "_" . \Str::random(10), \Maatwebsite\Excel\Excel::XLSX);
    }

    public function studentsPerClass(Request $request)
    {
        return Excel::download(new ClassStudentsPerClass($request->team_id, $request->extra_lines), __FUNCTION__ . "_" . \Str::random(5), \Maatwebsite\Excel\Excel::XLSX);
    }

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

    private static function calculateTypeOfServices($types, $serviceTypeId) {
        $total = 0;
        foreach ($types as $type) {
            if ($type['service_type_id'] == $serviceTypeId) 
                $total += 1;
        }
        return $total;
    }

    public function studentFinancialStatement(Student $student)
    {

        $registrations = $student->registrations;

        $pages = [];

        foreach($registrations as $registraion) {

            $aFinancials = $registraion->financials->toArray();

            $totalPaid = array_reduce($aFinancials, function($carry, $item) {
                if ($item['paid'] == 1) 
                    $carry += $item['value'];
                return $carry;
            }, 0);

            $totalNotPaid = array_reduce($aFinancials, function($carry, $item) {
                if ($item['paid'] == 0) 
                    $carry += $item['value'];
                return $carry;
            }, 0);

            $financial = array_map(function($item) use ($aFinancials){
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
        
        $pdf = Pdf::loadView('export.student-financial-statement', compact('pages','student'));

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
}
