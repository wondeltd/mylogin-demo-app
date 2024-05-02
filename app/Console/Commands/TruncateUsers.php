<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TruncateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:truncate-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the users table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::truncate();
    }
}
