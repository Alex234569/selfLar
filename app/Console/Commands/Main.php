<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use JetBrains\PhpStorm\NoReturn;

class Main extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'main';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For different tests';

    /**
     * Execute the console command.
     *
     * @return void
     */
    #[NoReturn] public function handle(): void
    {
    }
}
