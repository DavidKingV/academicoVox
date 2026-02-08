<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class SyncLeadStatuses extends Command
{
    protected $signature = 'academico:resync-statuses';

    protected $description = 'Student have a computed status, which is stored in the DB for performance reasons. In case statuses get out of sync, this command will resync them.';

    public function handle(): int
    {
        $this->info('Resetting all lead statuses...');

        foreach (Student::where('lead_type_id', 1)->get() as $student) {
            $student->update(['lead_type_id' => null]);
        }

        $this->info('Setting active student statuses...');

        foreach (Student::enrolled()->get() as $student) {
            $student->update(['lead_type_id' => 1]);
        }

        $this->info('Done!');

        return Command::SUCCESS;
    }
}
