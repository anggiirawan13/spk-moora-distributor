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
            ['- Nama sheet: Skala_Bisnis, Metode_Pengiriman, Termin_Pembayaran, Distributor, Produk, Distributor_Produk, Kriteria, Sub_Kriteria, Alternatif.'],
            ['- Kolom "code" di sheet Distributor adalah kode distributor.'],
            ['- Kolom "code" di sheet Alternatif juga memakai kode distributor.'],
            ['- Kolom "sub_criteria_code" di sheet Alternatif harus sesuai code sub kriteria.'],
            ['- Kolom "code" di sheet Sub_Kriteria opsional, jika kosong akan dibuat otomatis.'],
            ['- Kolom "code" di sheet Produk wajib unik.'],
            ['- Kolom "product_code" di sheet Distributor_Produk mengacu ke code Produk.'],
            ['- NPWP boleh memakai titik/strip, sistem akan menyimpan 15 digit.'],
            ['- Data yang sudah ada akan di-skip dan dicatat di error.'],
            ['- Urutan sheet wajib: Skala_Bisnis, Metode_Pengiriman, Termin_Pembayaran, Distributor, Produk, Distributor_Produk, Kriteria, Sub_Kriteria, Alternatif.'],
        ];
    }

    public function title(): string
    {
        return 'README';
    }
}
