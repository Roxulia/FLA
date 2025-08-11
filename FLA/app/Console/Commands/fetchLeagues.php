<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiFetcher;

class fetchLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-leagues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch League Data from Football API';

    /**
     * Execute the console command.
     */
    public function handle(ApiFetcher $fetcher)
    {
        try {
            $message = $fetcher->fetchLeagues();
            $this->info($message);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
