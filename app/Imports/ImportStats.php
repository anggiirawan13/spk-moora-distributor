<?php

namespace App\Imports;

class ImportStats
{
    private array $stats = [];
    private array $samples = [];

    public function addCreated(string $sheet): void
    {
        $this->init($sheet);
        $this->stats[$sheet]['created']++;
    }

    public function addSkipped(string $sheet): void
    {
        $this->init($sheet);
        $this->stats[$sheet]['skipped']++;
    }

    public function addWouldCreate(string $sheet): void
    {
        $this->init($sheet);
        $this->stats[$sheet]['would_create']++;
    }

    public function addSample(string $sheet, array $row, int $limit = 5): void
    {
        if (!isset($this->samples[$sheet])) {
            $this->samples[$sheet] = [];
        }

        if (count($this->samples[$sheet]) >= $limit) {
            return;
        }

        $this->samples[$sheet][] = $row;
    }

    public function all(): array
    {
        return $this->stats;
    }

    public function samples(): array
    {
        return $this->samples;
    }

    private function init(string $sheet): void
    {
        if (!isset($this->stats[$sheet])) {
            $this->stats[$sheet] = [
                'created' => 0,
                'skipped' => 0,
                'would_create' => 0,
            ];
        }
    }
}
