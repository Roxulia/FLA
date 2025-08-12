<?php

namespace App\Console\Commands;

use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class fetchTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and Store Teams Data from External API';

    /**
     * Execute the console command.
     */
    public function handle(ApiFetcher $fetcher)
    {
         try {
            $message = $fetcher->storeTeams();
            $this->info($message);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
