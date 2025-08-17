<?php

namespace App\Console\Commands;

use App\Services\ApiFetcher;
use Illuminate\Console\Command;

class fetchMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-matches {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Matches Data From External API';

    /**
     * Execute the console command.
     */
    public function handle(ApiFetcher $fetcher)
    {
        $date = $this->argument('date');
        try {
            $message = $fetcher->storeMatches($date);
            $this->info($message);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
