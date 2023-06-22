<?php

namespace App\Exports;

use App\ExportTempExcel;
use Maatwebsite\Excel\Concerns\FromCollection;

class VerificationExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ExportTempExcel::where('author', \Auth::user()->id)->select('id', 'user', 'comment', 'date', 'start', 'end', 'result')->orderBy('date')->orderBy('start')->get();
    }
}
