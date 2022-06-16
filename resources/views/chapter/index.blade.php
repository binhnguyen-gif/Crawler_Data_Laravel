@extends('layouts.master')
@push('css')
    <style>
        .clone {
            height: 45px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form_center {
            display: flex;
            margin-top: 12px ;
            justify-content: space-between;
            align-items: center;
        }
        .wrap-description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            text-overflow: ellipse;
            overflow: hidden;
        }

        .sroll_description {
            overflow: scroll;
        }
    </style>
@endpush
@section('content')
    <div class="data__content">
        <div class="row">
            <div class="col-12">
                <div class="card mt-3">
                    <div class="card-header clone">
                        <span class="">Quản trị truyện</span>
                        <a href="{{route('chapter.create')}}" class="btn btn-primary">Thêm chương</a>
                        <form action="{{route('chapter.link')}}">
                            <div class="form_center">
                                <input type="text" name="link" class="form-control dropdown-toggle" placeholder="Link..." id="top-search">
                                <input type="number" name="stories_id" id="" class="form-control w-25" min="1" value="1">
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered sroll_description" style="width: 100%" >
                            <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="12%">Tên sách</th>
                                <th>Link</th>
                                <th>Miêu tả</th>
                                <th width="12%">Tên truyện</th>
                                <th width="12%">Hành động</th>
                            </tr>
                            </thead>
                            <tbody >
                            @foreach($data as $each)
                                <tr>
                                    <td>{{$each->id}}</td>
                                    <td>{{$each->title}}</td>
                                    <td>{{$each->link}}</td>
                                    <td><p class="wrap-description">{{$each->description}}</p></td>
                                    <td >{{$each->stories_title}}</td>
                                    <td colspan="2">
                                        <a href="{{route('chapter.show', $each->id)}}" class="btn btn-success">Sửa</a>
{{--                                        <a href="{{route('chapter.destroy', $each->id)}}" class="btn btn-danger">Xóa</a>--}}
                                        <form action="{{route('chapter.destroy', $each->id)}}" method="post" class="float-right">
                                            @csrf
                                            @method('DELETE')
                                        <button type="submit" onclick="return confirm('Bạn có chắc xóa không?');" class="btn btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <nav>
                            <div class="pagination">
                                {{ $data->links()}}
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')

@endpush
