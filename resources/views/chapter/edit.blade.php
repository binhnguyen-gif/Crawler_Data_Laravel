@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-header clone">
                    <span class="">Trang chỉnh sửa thông tin chương từng truyện</span>
                </div>
                <div class="card-body">
                    <form action="{{route('chapter.update', $data->id)}}" method="post">
                        @csrf
                        <label for="">Tên chương: </label>
                        <input type="text" name="title" id="" placeholder="Tên truyện ..." @if(!empty($data)) value="{{$data->title}}"  @endif class="form-control">
                        @error('title')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                        <label for="">URL chương: </label>
                        <input type="text" name="link" id="" placeholder="Link truyện ..." @if(!empty($data)) value="{{$data->link}}"  @endif  class="form-control">
                        @error('link')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                        <label for="">Miêu tả: </label>
                        <textarea name="description" id="" cols="30" rows="10"   class="form-control">@if(!empty($data)) {{$data->description}} @endif</textarea>
                        @error('description')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                        <label for="">Tên truyện: </label>
                        <input type="number" name="stories_id" id="" min="1" @if(!empty($data)) value="{{$data->stories_id}}" @endif  class="form-control">
                        <button type="submit" class="btn btn-primary form-control mt-3" >Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
