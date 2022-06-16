<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Story;
use Goutte\Client;
use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;
use Illuminate\Support\Facades\Log;

class InsertStory extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'story:insert {number?} {link?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'story:insert tham số thứ nhất số đầu truyện hiển thị khi insert thành công tham số thứ 2 là url muốn lấy dữ liệu';

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
        $client = new Client();
        $url = $this->argument('link');
        $crawler = $client->request('GET', $url);
        $links_count = $crawler->filter('a')->count();
        $all_links = [];
        if ($links_count > 0) {
            $links = $crawler->filter('li a:last-child')->links();
            foreach ($links as $link) {
                $all_links[] = $link->getURI();
            }
            $all_links = array_unique($all_links);
            $all_links = array_reverse($all_links);
            $url_links = explode('trang-', $all_links[1]);
            $url_links = explode('/', array_reverse($url_links)[0]);
        }
        //        Muilti story
        $url_story = $this->argument('link');
        $number = $this->argument('number');
        $crawler_one = GoutteFacade::request('GET', $url_story);
        $links_one = $crawler_one->filter('h3.truyen-title')->each(function ($node_one) {
            return $node_one->filter('a')->attr('href');
        });
        if (!empty($links_one)) {
            foreach ($links_one as $link_one) {
                $crawler_one = GoutteFacade::request('GET', $link_one);
                $title_one = $crawler_one->filter('div.col-truyen-main h3')->text();
                $image_one = $crawler_one->filter('div.book img')->attr('src');
                $info_one = $crawler_one->filter('div.info')->text();
                $description_one = $crawler_one->filter('div.desc-text')->text();
                $data_story_one = Story::create(
                    [
                        'title' => $title_one,
                        'image' => $image_one,
                        'link' => $link_one,
                        'info' => $info_one,
                        'description' => $description_one
                    ]
                );
            }
        }
        if (!empty($data_story_one)) {
            for ($i = 2; $i <= $url_links; $i++) {
                $url = $url . 'trang-' . $i;
                $crawlerMuilt = GoutteFacade::request('GET', $url);
                $links = $crawlerMuilt->filter('h3.truyen-title')->each(function ($node) {
                    return $node->filter('a')->attr('href');
                });
                if (!empty($links)) {
                    foreach ($links as $link) {
                        $crawler = GoutteFacade::request('GET', $link);
                        $title = $crawler->filter('div.col-truyen-main h3')->text();
                        $image = $crawler->filter('div.book img')->attr('src');
                        $info = $crawler->filter('div.info')->text();
                        $description = $crawler->filter('div.desc-text')->text();
                        $data_story = Story::create(
                            [
                                'title' => $title,
                                'image' => $image,
                                'link' => $link,
                                'info' => $info,
                                'description' => $description
                            ]
                        );
                    }
                }
            }
        }
        //        End


        if (!empty($data_story)) {
            $this->table(
                ['id', 'title', 'link'],
                Story::query()->orderByDesc('id')->latest()->select('id', 'title', 'link')->limit($number)->get()->toArray()
            );
        }

        try {
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
