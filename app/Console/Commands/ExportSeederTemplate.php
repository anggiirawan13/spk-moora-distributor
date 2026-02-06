<?php

namespace App\Console\Commands;

use App\Exports\SeederTemplateExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportSeederTemplate extends Command
{
    protected $signature = 'template:seeder-excel {--path=templates/template-import-seeder.xlsx}';

    protected $description = 'Generate Excel template from seeder data';

    public function handle(): int
    {
        $path = (string) $this->option('path');

        Excel::store(new SeederTemplateExport(), $path, 'local');

        $this->info('Template seeder tersimpan di storage/app/' . $path);

        return self::SUCCESS;
    }
}
