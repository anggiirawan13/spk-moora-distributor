<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SubCriteriaSheetTemplate implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return ['criteria_code', 'code', 'name', 'value'];
    }

    public function array(): array
    {
        return [
            ['C1', 'C1-001', '> 120% (Sangat Rendah)', '1'],
            ['C1', 'C1-002', '105% - 120% (Rendah)', '2'],
            ['C1', 'C1-003', '95% - 105% (Standar)', '3'],
            ['C1', 'C1-004', '80% - 95% (Tinggi)', '4'],
            ['C1', 'C1-005', '< 80% (Sangat Tinggi)', '5'],
            ['C2', 'C2-001', '> 7 hari (Sangat Lambat)', '1'],
            ['C2', 'C2-002', '5 - 7 hari (Lambat)', '2'],
            ['C2', 'C2-003', '3 - 4 hari (Normal)', '3'],
            ['C2', 'C2-004', '2 hari (Cepat)', '4'],
            ['C2', 'C2-005', '1 hari (Sangat Cepat)', '5'],
            ['C3', 'C3-001', 'PPN (Kurang Disukai)', '1'],
            ['C3', 'C3-002', 'Non-PPN (Lebih Disukai)', '2'],
            ['C4', 'C4-001', 'Produk non-brand, tanpa sertifikasi, klaim > 5% (Sangat Kurang)', '1'],
            ['C4', 'C4-002', 'Produk kurang dikenal/lokal, klaim 3% - 5% (Kurang)', '2'],
            ['C4', 'C4-003', 'Produk standar, klaim 1,5% - 3% (Cukup)', '3'],
            ['C4', 'C4-004', 'Produk branded, sertifikasi umum, klaim 0,5% - 1,5% (Baik)', '4'],
            ['C4', 'C4-005', 'Produk premium, sertifikasi lengkap, klaim < 0,5% (Sangat Baik)', '5'],
            ['C5', 'C5-001', 'Respon sangat lambat (> 1 hari), perlu follow-up berulang (Sangat Kurang)', '1'],
            ['C5', 'C5-002', 'Respon > 6 jam atau esok hari (Kurang)', '2'],
            ['C5', 'C5-003', 'Respon 3 - 6 jam, standar (Cukup)', '3'],
            ['C5', 'C5-004', 'Respon 1 - 3 jam, proaktif (Baik)', '4'],
            ['C5', 'C5-005', 'Respon < 1 jam, sangat proaktif (Sangat Baik)', '5'],
            ['C6', 'C6-001', 'Tidak ada garansi atau klaim sangat sulit diproses (Sangat Kurang)', '1'],
            ['C6', 'C6-002', 'Klaim sulit/berbelit, garansi 3 bulan atau kurang (Kurang)', '2'],
            ['C6', 'C6-003', 'Klaim standar, garansi 6 bulan, dukungan teknis terbatas (Cukup)', '3'],
            ['C6', 'C6-004', 'Klaim mudah, garansi 9-12 bulan, dukungan teknis pada jam kerja (Baik)', '4'],
            ['C6', 'C6-005', 'Klaim sangat mudah, garansi minimal 12 bulan, dukungan teknis 24/7 (Sangat Baik)', '5'],
        ];
    }

    public function title(): string
    {
        return 'Sub Kriteria';
    }
}
