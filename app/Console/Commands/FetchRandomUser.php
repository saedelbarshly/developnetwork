<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FetchRandomUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-random-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch random user every 6 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://randomuser.me/api/');
        if ($response->successful()) {
            $result = $response->json('results'); 
            Log::channel('random_user')->info('Random User Data:', $result);
        } else {
            Log::channel('random_user')->error('Failed to fetch random user. Status: ' . $response->status());
        }
    }
}
