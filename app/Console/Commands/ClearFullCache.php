<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearFullCache extends Command
{
    protected $signature = 'cache:fullclear';
    protected $description = 'Remove all cache';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('event:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('permission:cache-reset');
        // Artisan::call('queue:clear');
        Artisan::call('debugbar:clear');
        Artisan::call('clear-compiled');
    }
}
