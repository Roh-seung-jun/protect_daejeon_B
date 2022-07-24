@extends('header')
@section('contents')
    <div class="h-100 justify-content-center d-flex align-items-center flex-column">
        <form class="w-25 justify-content-center d-flex align-items-center flex-column" method="POST" action="{{route('user_sign')}}">
            @csrf
            <input type="text" placeholder="id" class="form-control" name="id">
            <input type="text" placeholder="password" class="form-control" name="password">
            <input type="text" placeholder="password_check" class="form-control" name="password_check">
            <input type="text" placeholder="name" class="form-control" name="name">
            <input type="text" placeholder="phone" class="form-control" name="phone">
            <button class="btn btn-outline-primary">회원가입</button>
        </form>
    </div>
@endsection
