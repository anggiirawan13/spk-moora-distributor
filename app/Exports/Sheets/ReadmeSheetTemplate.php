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
            ['- Nama sheet: Skala Bisnis, Metode Pengiriman, Termin Pembayaran, Distributor, Produk, Distributor Produk, Kriteria, Sub Kriteria, Alternatif.'],
            ['- Kolom "code" di sheet Distributor adalah kode distributor.'],
            ['- Kolom "code" di sheet Alternatif juga memakai kode distributor.'],
            ['- Kolom "sub_criteria_code" di sheet Alternatif harus sesuai code sub kriteria.'],
            ['- Kolom "code" di sheet Sub Kriteria opsional, jika kosong akan dibuat otomatis.'],
            ['- Kolom "code" di sheet Produk wajib unik.'],
            ['- Kolom "product_code" di sheet Distributor Produk mengacu ke code Produk.'],
            ['- NPWP boleh memakai titik/strip, sistem akan menyimpan 15 digit.'],
            ['- Data yang sudah ada akan di-skip dan dicatat di error.'],
            ['- Urutan sheet wajib: Skala Bisnis, Metode Pengiriman, Termin Pembayaran, Distributor, Produk, Distributor Produk, Kriteria, Sub Kriteria, Alternatif.'],
        ];
    }

    public function title(): string
    {
        return 'README';
    }
}
