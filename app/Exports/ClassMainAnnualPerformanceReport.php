<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClassMainAnnualPerformanceReport implements WithMultipleSheets
{
    protected $data;
    protected $teamName;

    public function __construct($grades, string $teamName)
    {
        $this->data = $grades;
        $this->teamName = $teamName;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets['ETAPAS'] = new ClassAnnualPerformanceReport($this->data, $this->teamName, 'ETAPAS');
        $sheets['RESULTADO'] = new ClassAnnualPerformanceReport($this->data, $this->teamName, 'RESULTADO', TRUE);

        return $sheets;
    }
}
