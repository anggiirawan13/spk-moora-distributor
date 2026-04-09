<?php

namespace App\Support;

class ImportBatchState
{
    private static ?int $currentBatchId = null;

    public static function activate(int $batchId): void
    {
        self::$currentBatchId = $batchId;
    }

    public static function clear(): void
    {
        self::$currentBatchId = null;
    }

    public static function currentBatchId(): ?int
    {
        return self::$currentBatchId;
    }
}
