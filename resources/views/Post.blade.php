@extends('layouts.app')

@section('content')

    <div class="container">
        <h2>Nội dung trang danh mục</h2>
        <div class="row">
            @foreach($story_category as $each)
                <div class="col-md-10">
                    {{$each->chapter}}
                </div>
                <div class="col-md-2">
                        {{ $each->sidebar_content}}
                </div>
            @endforeach
        </div>
        <hr>
        <h2>Nội dung thông tin truyện</h2>
        @foreach($story_information as $each1)
            <div class="row">
                <div class="col-md-10">
                    {{$each1->story_information}}
                </div>
                <div class="col-md-2">
                    {{ $each1->sidebar_content}}
                </div>
            </div>
            <div class="footer">
                {{ $each1->footer_content}}
            </div>
        @endforeach
        <hr>
        <h2>Nội dung chapter</h2>
        <div class="row">
            @foreach($doc_story as $each2)
                <h1>{{$each2->title}}</h1>
                <p>{{ $each2->chapter}}</p>
                <p style="font-size: 16px;">
                    {{ $each2->description}}
                </p>

                <div class="footer mt-5">
                    {{ $each2->content_footer}}
                </div>
            @endforeach
        </div>
    </div>
@endsection
