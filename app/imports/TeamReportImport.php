<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeamReportImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Maps Excel headers to your Leader table (tbl_dailyreportspecial)
        return DB::table('tbl_dailyreportspecial')->insert([
            'emp_name'   => $row['name'] ?? $row['employee'], 
            'date'       => isset($row['date']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date']) : now(),
            'report'     => $row['report'] ?? '',
            'work_type'  => $row['work_type'] ?? 'Office',
            'created_at' => now(),
        ]);
    }
}