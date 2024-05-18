<?php

namespace App\Http\Controllers;

use Money\Money;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\Financial;
use App\Exports\ClassDiaryExport;
use App\Exports\ClassStudentsPerClass;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use NumberToWords\NumberToWords;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function classDiary(Request $request)
    {
        $team = Team::findOrFail($request->team_id);
        $disciplines = $team->getDisciplines($request->team_id);

        return Excel::download(new ClassDiaryExport($request->team_id, $disciplines), __FUNCTION__ . "_" . \Str::random(10), \Maatwebsite\Excel\Excel::XLSX);
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
        $financial->valueFormated =  number_format($financial->value, 2, ',', '.');;
        $financial->inFull = $this->convertToWords($financial->value);

        $pdf = Pdf::loadView('export.receipt', compact('financial'));

        return $pdf->download('recibo.pdf');
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
