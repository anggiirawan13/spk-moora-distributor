<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SeederArraySheet implements FromArray, WithHeadings, WithTitle
{
    public function __construct(
        private readonly string $title,
        private readonly array $headings,
        private readonly array $rows
    )
    {
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function array(): array
    {
        $ordered = [];
        foreach ($this->rows as $row) {
            $ordered[] = array_map(
                fn (string $heading) => $row[$heading] ?? null,
                $this->headings
            );
        }

        return $ordered;
    }
}
