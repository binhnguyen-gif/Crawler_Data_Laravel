<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Product;
use App\Models\Story;
use Illuminate\Http\Request;
use Weidner\Goutte\GoutteFacade;
use App\Models\Post;

class PostController extends Controller
{

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

    public function getDom()
    {
        $link_story = 'https://truyenfull.vn/the-loai/tien-hiep/';
        $link_story_detail = 'https://truyenfull.vn/cu-long-thuc-tinh/';
        $link_content = 'https://truyenfull.vn/cu-long-thuc-tinh/chuong-1/';
        $html_story = file_get_html($link_story);
        $html_story_detail = file_get_html($link_story_detail);
        $html_content = file_get_html($link_content);
//        $html_story =$html_story->find('#nav', 0);
//        $html_story_detail =$html_story_detail->find('#truyen',  0);
//        $html_content = $html_content->find('#chapter-big-container', 0);
//        $html_story =$html_story->find('head', 0);
//        $html_story_detail =$html_story_detail->find('head',  0);
//        $html_content = $html_content->find('head', 0);
        $html_content = $html_content->find('span.iads-head-title', 0);
        dd($html_content);
        echo $html_content;
//        $contents = Content::query()->insert([
//            'story' => $html_story,
//            'story_detail' => $html_story_detail,
//            'content' => $html_content
//        ]);
//        if ($contents) {
//           echo "Thêm thành công";
//        }

//        $html_content = $html_content->find('body', 0)->plaintext;
//        foreach ($html_content->find('img') as $element) {
//            echo $element->alt . '';
//        }
    }


    public function getAdvertisement()
    {
        $url = 'https://gtvseo.com/crawl-la-gi/';
        $crawler = GoutteFacade::request('GET', $url);
        $title = $this->crawlData('div.inner-wrapper-sticky a', $crawler);
        dd($title);
//        echo $title;
    }

    public function getLink()
    {
//        $crawler = GoutteFacade::request('GET', 'https://truyenfull.vn/cu-long-thuc-tinh/chuong-1/');
//        $linkPost = $crawler->filter('#iads-img a')->each(function ($node) {
//            return $node->attr("href");
//        });
//
//        foreach ($linkPost as $link) {
//            print($link . "\n");
//        }
        $crawler = GoutteFacade::request('GET', 'https://truyenfull.vn/cu-long-thuc-tinh/chuong-1/');
//        $crawler = GoutteFacade::request('GET', 'https://dantri.com.vn/lao-dong-viec-lam.htm');
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

        $title = $crawler->filter('div.col-truyen-main h3')->text();
        $image = $crawler->filter('div.book img')->attr('src');
        $info = $crawler->filter('div.info')->text();
        $description = $crawler->filter('div.desc-text')->text();
        Story::create(
            [
                'title' => $title,
                'image' => $image,
                'info' => $info,
                'description' => $description
            ]
        );

      return "Thành công";
    }

    public function updateStory() {
        Chapter::query()->where('id', '>=', 102)->update(
            ['stories_id' => 3]
        );
    }
}


