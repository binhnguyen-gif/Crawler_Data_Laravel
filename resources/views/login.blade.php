@extends('layouts.app')

@push('css')
    <!--  Paper Dashboard core CSS    -->
    <link href="{{asset('assets/css/paper-dashboard.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/css/demo.css')}}" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="{{asset('assets/css/themify-icons.css')}}" rel="stylesheet">
@endpush
@section('content')
    @if(session()->has('msg'))
        <div class="alert alert-danger" role="alert">
            <strong>{{session('msg')}}</strong>
        </div>
    @endif
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4" >
                <form action="{{route('check.login')}}" method="post" >
                    @csrf
                    <div class="card" data-background="color" data-color="blue">
                        <div class="card-header">
                            <h3 class="card-title">Login</h3>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="name" placeholder="Enter username" class="form-control input-no-border">
                                @error('name')
                                <div style="color: red" >
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="Password" class="form-control input-no-border">
                                @error('password')
                                <div style="color: red">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-fill btn-wd mt-3">Đăng nhập</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
