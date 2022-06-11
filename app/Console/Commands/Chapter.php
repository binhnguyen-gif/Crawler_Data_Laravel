<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Chapter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'story:chapter {id}';

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
//        return 0;
        $userId = $this->argument('id');
        $this->table(
            ['title', 'link', 'description'],
            Chapter::query()->where('id', $userId)->select('title', 'link', 'description')->get()->toArray()
        );
    }
}
