<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chapter\InsertRequest;
use App\Http\Requests\Chapter\UpdateRequest;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Http\Request;
use Weidner\Goutte\GoutteFacade;

class ChapterController extends Controller
{

    public $stories_id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Chapter::query()->join('stories', 'stories.id', '=', 'chapters.stories_id')
            ->select('stories.title as stories_title', 'chapters.id', 'chapters.title', 'chapters.link', 'chapters.description')->paginate(5);
        return view('chapter.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('chapter.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsertRequest $request)
    {

        $stories = Story::find($request->stories_id);
        $chapters = new Chapter();
        $chapters->fill($request->validated());
        $stories->story()->save($chapters);
        return redirect()->route('chapter.index')->with('msg', 'Thêm thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Chapter::query()->where('id', $id)->first();
        return view('chapter.edit', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
//        dd($request->validated());
        Chapter::query()->where('id', $id)->update($request->validated());
        return redirect()->route('chapter.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Chapter::destroy($id);
        return redirect()->back();
    }

    public function addLink(Request $request)
    {
        $link = $request->input('link');
        $this->stories_id = $request->stories_id;
        $crawler = GoutteFacade::request('GET', $link);
        $linkChapter = $crawler->filter('ul.list-chapter li')->each(
            function ($node) {
                $story = $node->filter('a')->text();
                $link = $node->filter('a')->attr('href');
                $crawlerSub = GoutteFacade::request('GET', $link);
                $content = $crawlerSub->filter('div#chapter-c')->text();
                Chapter::create(
                    [
                        'stories_id' => $this->stories_id,
                        'title' => $story,
                        'link' => $link,
                        'description' => $content
                    ]
                );
            }
        );
        return redirect()->route('chapter.index');
    }
}
