<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReadmeSheetTemplate implements FromArray, WithTitle
{
    public function array(): array
    {
        return [
            ['Template Import SPK MOORA'],
            [''],
            ['Catatan:'],
            ['- Gunakan 1 file Excel dengan sheet sesuai nama berikut.'],
            ['- Kolom "code" di sheet distributors adalah kode distributor.'],
            ['- Kolom "code" di sheet alternatives juga memakai kode distributor.'],
            ['- NPWP boleh memakai titik/strip, sistem akan menyimpan 15 digit.'],
            ['- Data yang sudah ada akan di-skip dan dicatat di error.'],
        ];
    }

    public function title(): string
    {
        return 'README';
    }
}
