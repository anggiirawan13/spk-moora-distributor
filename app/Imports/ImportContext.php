<?php

namespace App\Imports;

class ImportContext
{
    public array $businessScales = [];
    public array $deliveryMethods = [];
    public array $paymentTerms = [];
    public array $distributors = [];
    public array $products = [];
    public array $distributorProducts = [];
    public array $criterias = [];
    public array $subCriteriaCodes = [];
    public bool $abort = false;
    public string $abortReason = '';
}
