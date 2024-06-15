<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClassDiaryExport implements WithMultipleSheets
{
    protected $teamId;
    protected $disciplines;
    protected $notes;

    public function __construct($teamId, $aDisciplines, $notes)
    {
        $this->teamId = $teamId;
        $this->disciplines = $aDisciplines;
        $this->notes = $notes;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->disciplines as $aDiscipline) {

            $sheets[$aDiscipline['discipline']] = new ClassDiaryPerDisciplineSheet($this->teamId, $aDiscipline, $this->notes);
        }

        return $sheets;
    }
}
