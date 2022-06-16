<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Product;
use App\Models\Story;
use Illuminate\Http\Request;
use Weidner\Goutte\GoutteFacade;
use App\Models\Post;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class PostController extends Controller
{

    public $ids;

    public function __construct()
    {
        $url='https://truyenfull.vn/the-loai/ngon-tinh/';
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $links_count = $crawler->filter('a')->count();
        $all_links = [];
        if($links_count > 0){
            $links = $crawler->filter('li a:last-child')->links();
            foreach ($links as $link) {
                $all_links[] = $link->getURI();
            }
            $all_links = array_unique($all_links);
            $all_links= array_reverse($all_links);
            $url_links = explode('trang-', $all_links[1]);
            $url_links = explode('/', array_reverse($url_links)[0]);

            echo "All Avialble Links From this page $url Page<pre>"; print_r($url_links[0]);echo "</pre>";
        } else {
            echo "No Links Found";
        }
    }

    public function storyCategory()
    {
        $url = 'https://truyenfull.vn/the-loai/tien-hiep/';
        $crawler = GoutteFacade::request('GET', $url);
        $chapter = $this->crawlData('div#list-page', $crawler);
        $sidebar_content = $this->crawlData('div.col-truyen-side', $crawler);
        $dataCategory = [
            'chapter' => $chapter,
            'sidebar_content' => $sidebar_content,
        ];
        Category::create($dataCategory);
        echo "Thêm thành công";
    }

    public function storyInformation()
    {
        $url = 'https://truyenfull.vn/cu-long-thuc-tinh/';
        $crawler = GoutteFacade::request('GET', $url);
        $story_information = $this->crawlData('div.col-truyen-main', $crawler);
        $sidebar_content = $this->crawlData('div.col-truyen-side', $crawler);
        $footer_content = $this->crawlData('div#footer', $crawler);
        $dataProduct = [
            'story_information' => $story_information,
            'sidebar_content' => $sidebar_content,
            'footer_content' => $footer_content
        ];
        Product::create($dataProduct);
        echo "Thêm thành công";
    }

    public function docStory()
    {
        $url = 'https://truyenfull.vn/cu-long-thuc-tinh/chuong-330/';
        $crawler = GoutteFacade::request('GET', $url);
        $title = $this->crawlData('a.truyen-title', $crawler);
        $chapter = $this->crawlData('a.chapter-title', $crawler);
        $description = $this->crawlData('div.chapter-c', $crawler);
        $content_footer = $this->crawlData('div#footer', $crawler);
        $dataPost = [
            'title' => $title,
            'chapter' => $chapter,
            'description' => $description,
            'content_footer' => $content_footer
        ];
        Post::create($dataPost);
        echo "Thêm thành công";
    }


    protected function crawlData(string $type, $crawler)
    {
        $result = $crawler->filter($type)->each(function ($node) {
            return $node->text();
        });
        if (!empty($result)) {
            return $result[0];
        }
        return '';
    }

    public function getData($id)
    {
        $story_category = Category::query()->where('id', $id)->get();
        $story_information = Product::query()->where('id', $id)->get();
        $doc_story = Post::query()->where('id', $id)->get();
        return view('Post', compact('story_category', 'story_information', 'doc_story'));
    }

    public function getDomHtml()
    {
        $link_story = 'https://truyenfull.vn/the-loai/tien-hiep/';
        $link_story_detail = 'https://truyenfull.vn/cu-long-thuc-tinh/';
        $link_content = 'https://truyenfull.vn/cu-long-thuc-tinh/chuong-1/';
        $html_story = file_get_html($link_story);
        $html_story_detail = file_get_html($link_story_detail);
        $html_content = file_get_html($link_content);
        $html_story_detail = $html_story_detail->find('#truyen', 0);
        $html_content = $html_content->find('#chapter-big-container', 0);
        $html_story = $html_story->find('head', 0);
        $html_story_detail = $html_story_detail->find('head', 0);
        $html_content = $html_content->find('head', 0);
        $html_content = $html_content->find('span.iads-head-title', 0);
        $contents = Content::query()->insert([
            'story' => $html_story,
            'story_detail' => $html_story_detail,
            'content' => $html_content
        ]);
        if ($contents) {
            echo "Thêm thành công";
        }

        $html_content = $html_content->find('body', 0)->plaintext;
        foreach ($html_content->find('img') as $element) {
            echo $element->alt . '';
        }
    }


    public function getAdvertisement()
    {
        $url = 'https://gtvseo.com/crawl-la-gi/';
        $crawler = GoutteFacade::request('GET', $url);
        $title = $this->crawlData('div.inner-wrapper-sticky a', $crawler);
    }

    public function getLink()
    {
        $crawler = GoutteFacade::request('GET', 'https://truyenfull.vn/cu-long-thuc-tinh/chuong-1/');
        $linkPost = $crawler->filter('div.iads-title a')->each(function ($node) {
            return $node->attr("href");
        });
        foreach ($linkPost as $link) {
            print($link . "\n");
        }
    }

    public function getStory()
    {
        $crawler = GoutteFacade::request('GET', 'https://truyenfull.vn/long-than-khi/');
        $linkChapter = $crawler->filter('ul.list-chapter li')->each(
            function ($node) {
                $story = $node->filter('a')->text();
                $link = $node->filter('a')->attr('href');
                $crawlerSub = GoutteFacade::request('GET', $link);
                $content = $crawlerSub->filter('div#chapter-c')->text();
                Chapter::create(
                    [
                        'stories_id' => 1,
                        'title' => $story,
                        'link' => $link,
                        'description' => $content
                    ]
                );
            }
        );
        if ($linkChapter) {
            echo "thanh công";
        }
    }

    public function insertStory()
    {
        $crawler = GoutteFacade::request('GET', 'https://truyenfull.vn/long-than-khi/');
//        $title = $crawler->filter('div.col-truyen-main h3')->text();
//        $image = $crawler->filter('div.book img')->attr('src');
        $info = $crawler->filter('div.info a')->text();
//        $description = $crawler->filter('div.desc-text')->text();
//        $links = $crawler->filter('ul')->each(function ($node) {
//            $link = $node->filter('li')->text();
//            return $link;
//        });
        dd($info);
//        Story::create(
//            [
//                'title' => $title,
//                'image' => $image,
//                'info' => $info,
//                'description' => $description
//            ]
//        );
//        return "Thành công";
    }

    public function updateStory()
    {
        Chapter::query()->where('id', '>=', 102)->update(
            ['stories_id' => 3]
        );
    }

    public function insertStoryMulti()
    {
        $crawler_one = GoutteFacade::request('GET', 'https://truyenfull.vn/the-loai/tien-hiep/');
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
                $data_story = Story::create(
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
        if (!empty($data_story)) {
            for ($i = 2; $i <= 39; $i++) {
                $url = 'https://truyenfull.vn/the-loai/tien-hiep/trang-' . $i;
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
    }

    public function insertChapterMulti()
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
            echo "Thành công";
        }




    }
}


