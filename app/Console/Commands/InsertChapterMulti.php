<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;

class InsertChapterMulti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chapter:multi';

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
        $links = Story::query()->select('id', 'link')->get();
        foreach ($links as $link) {
            $this->ids = $link->id;
            $linkids = $link->link;
            $crawler1 = GoutteFacade::request('GET', $linkids);
            $linkChapter = $crawler1->filter('ul.list-chapter li')->each(
                function ($node) {
                    $story = $node->filter('a')->text();
                    $link = $node->filter('a')->attr('href');
                    $crawlerSub = GoutteFacade::request('GET', $link);
                    $content = $crawlerSub->filter('div#chapter-c')->text();
                    Chapter::create(
                        [
                            'stories_id' => $this->ids,
                            'title' => $story,
                            'link' => $link,
                            'description' => $content
                        ]
                    );
                }
            );
        }
        if (!empty($linkChapter)) {
            $this->table(
                ['title', 'link'],
                Chapter::query()->select('title', 'link')->limit(15)->get()->toArray()
            );
            $practice = $this->ask('Bạn muốn đọc tập nào?');
            if (!empty($practice)) {
                $this->table(
                    ['description'],
                    Chapter::query()->select('description')->get()->toArray()
                );
            }
        }
    }
}
