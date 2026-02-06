<?php

namespace App\Imports;

class ImportErrorBag
{
    private array $errors = [];
    private array $counts = [];

    public function add(string $sheet, int $row, string $message): void
    {
        $this->errors[] = sprintf('[%s] Row %d: %s', $sheet, $row, $message);
        $this->counts[$sheet] = ($this->counts[$sheet] ?? 0) + 1;
    }

    public function all(): array
    {
        return $this->errors;
    }

    public function has(): bool
    {
        return count($this->errors) > 0;
    }

    public function toText(): string
    {
        if (!$this->has()) {
            return '';
        }

        return implode(PHP_EOL, $this->errors) . PHP_EOL;
    }

    public function counts(): array
    {
        return $this->counts;
    }
}
