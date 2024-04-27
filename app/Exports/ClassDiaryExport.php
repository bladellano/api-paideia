<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClassDiaryExport implements WithMultipleSheets
{
    protected $teamId;
    protected $disciplines;

    public function __construct($teamId, $aDisciplines)
    {
        $this->teamId = $teamId;
        $this->disciplines = $aDisciplines;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->disciplines as $discipline)
            $sheets[$discipline] = new ClassDiaryPerDisciplineSheet($this->teamId, $discipline);

        return $sheets;
    }
}
