<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportMembers implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Member::select(
            'nisn', 'nipd', 'nama', 'jenis_kelamin'
        )->get();
    }
}