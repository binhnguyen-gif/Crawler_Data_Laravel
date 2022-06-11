<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;

class InsertStory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'story:insert {link}';

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
        $stories_link = $this->argument('link');
        $crawler = GoutteFacade::request('GET', $stories_link);
        $title = $crawler->filter('div.col-truyen-main h3')->text();
        $image = $crawler->filter('div.book img')->attr('src');
        $info = $crawler->filter('div.info')->text();
        $description = $crawler->filter('div.desc-text')->text();
        $data_story = Story::create(
            [
                'title' => $title,
                'image' => $image,
                'info' => $info,
                'description' => $description
            ]
        );
        if (!empty($data_story)) {
            $this->table(
                ['title', 'image', 'info', 'description'],
                Story::query()->select('title', 'image', 'info', 'description')->get()->toArray()
            );
        }
    }
}
