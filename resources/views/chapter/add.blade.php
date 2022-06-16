@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-header clone">
                    <span class="">Trang thêm  thông tin chương</span>
                </div>
                <div class="card-body">
                    <form action="{{route('chapter.store')}}" method="post">
                        @csrf
                        <label for="">Tên truyện: </label>
                        <input type="text" name="title" id="" placeholder="Tên chương ..." class="form-control">
                        @error('title')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                        <label for="">URL truyện: </label>
                        <input type="text" name="link" id="" placeholder="Link chương ..." class="form-control">
                        @error('link')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                        <label for="">Miêu tả: </label>
                        <textarea name="description" id="" cols="30" rows="10" class="form-control"></textarea>
                        @error('description')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                        <label for="">Tên truyện: </label>
                        <input type="number" name="stories_id" id="" min="1" class="form-control">
                        <button type="submit" id="myBtn" class="btn btn-primary form-control mt-3">Thêm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){
            $("#myBtn").click(function(){
                $('.toast').toast('show');
            });
        });
    </script>
@endpush

