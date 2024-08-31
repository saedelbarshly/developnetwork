<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class ForceDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:force-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force delete all soft delete posts after 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thirtyDaysAgo = now()->subDays(30);
        Post::onlyTrashed()
            ->where('deleted_at', '<', $thirtyDaysAgo)
            ->forceDelete();
    }
}
