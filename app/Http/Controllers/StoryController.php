<?php

namespace App\Http\Controllers;

use App\Http\Requests\Story\AddRequest;
use App\Http\Requests\Story\UpdateRequest;
use App\Models\Story;
use Facade\Ignition\Http\Controllers\StyleController;
use Illuminate\Http\Request;
use Weidner\Goutte\GoutteFacade;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Story::query()->paginate(6);
        return  view('story.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('story.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddRequest $request)
    {
        $stories = new Story();
        $stories->fill($request->validated());
        $stories->save();
        return redirect()->route('story.index')->with('msg', 'Thêm thành công');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Story::query()->where('id', $id)->first();
        return view('story.edit', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $stories = Story::query()->where('id', $id)->update($request->validated());
        return redirect()->route('story.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Story::destroy($id);
        return redirect()->back();
    }

    public function addLink(Request $request) {
        $link = $request->input('link');
        if (!empty($link)) {
            $crawler_one = GoutteFacade::request('GET', $link);
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
        }
        return redirect()->route('story.index');

    }
}
