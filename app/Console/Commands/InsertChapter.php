<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;

class InsertChapter extends Command
{

    public $id;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chapter:insert {link} {stories_id}';

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
        $chapter_link = $this->argument('link');
        $ids = $this->argument('stories_id');
        $this->id  = (int)$ids;
        $crawler = GoutteFacade::request('GET', $chapter_link);
        $linkChapter = $crawler->filter('ul.list-chapter li')->each(
            function ($node) {
                $story = $node->filter('a')->text();
                $link = $node->filter('a')->attr('href');
                $crawlerSub = GoutteFacade::request('GET', $link);
                $content = $crawlerSub->filter('div#chapter-c')->text();
                Chapter::create(
                    [
                        'stories_id' => $this->id,
                        'title' => $story,
                        'link' => $link,
                        'description' => $content
                    ]
                );
            }
        );
        if (!empty($linkChapter)) {
            $this->table(
                ['title', 'link', 'description'],
                Chapter::query()->select('title', 'link', 'description')->get()->toArray()
            );
        }
    }
}
