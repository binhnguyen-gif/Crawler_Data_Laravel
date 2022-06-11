<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Story as StoryModel;

class Story extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'story:info {id}';

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
        $storiesId = $this->argument('id');
        $this->table(
            ['title', 'image', 'info', 'description'],
            StoryModel::query()->where('id', $storiesId)->select('title', 'image', 'info', 'description')->get()->toArray()
        );
    }
}
