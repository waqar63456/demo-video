<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\services_performance;

class ManageServicePerformances extends Command
{
    protected $signature = 'service:manage {action} {--id=}';
    protected $description = 'Manage service performances';

    public function handle()
    {
        $action = $this->argument('action');
        $id = $this->option('id');

        switch ($action) {
            case 'list':
                $performances = services_performance::all();
                $this->info($performances);
                break;

            case 'view':
                if ($id) {
                    $performance = services_performance::find($id);
                    $this->info($performance);
                } else {
                    $this->error('Please provide an ID');
                }
                break;

            default:
                $this->error('Invalid action');
        }
    }
}
