@extends('header')


@section('contents')
    <div class="h-100 justify-content-center d-flex align-items-center flex-column">
        <form class="w-25 justify-content-center d-flex align-items-center flex-column" method="POST" action="{{route('user_login')}}">
            @csrf
            <input type="text" placeholder="id" class="form-control" name="id">
            <input type="text" placeholder="password" class="form-control" name="password">
            <button class="btn btn-outline-primary">로그인</button>
        </form>
    </div>
@endsection
