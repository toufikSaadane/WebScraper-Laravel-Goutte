<?php

namespace App\Console\Commands;

use App\Factory\FileFactory;
use Illuminate\Console\Command;

class scraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper {argument}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arg = $this->argument('argument');
        (new FileFactory())->createFile($arg);
        return 0;
    }
}
