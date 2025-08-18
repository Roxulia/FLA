<?php

namespace App\Console\Commands;

use App\Services\LiveDataFetcher;
use Illuminate\Console\Command;

class fetchLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-live';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Live Data From Live API';

    /**
     * Execute the console command.
     */
    public function handle(LiveDataFetcher $fetcher)
    {
        try {
            $message = $fetcher->storeLiveData();
            $this->info($message);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
