<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportMembers implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Member([
            'nisn' => $row['nisn'],
            'nipd' => $row['nipd'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }
}