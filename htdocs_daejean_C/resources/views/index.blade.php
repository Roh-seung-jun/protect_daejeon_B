@extends('header')


@section('contents')
    <div class="h-100 justify-content-center d-flex align-items-center">
        @if(auth()->user())
        <button class="btn btn-outline-primary m-2" onclick="location.href='{{route('logout')}}'">로그아웃</button>
        @else
            <button class="btn btn-outline-primary m-2" onclick="location.href='{{route('sign')}}'">회원가입</button>
            <button class="btn btn-outline-primary m-2" onclick="location.href='{{route('login')}}'">로그인</button>
        @endif
        <button class="btn btn-outline-primary m-2" onclick="location.href='{{route('visit')}}'">방문신청</button>
        <button class="btn btn-outline-primary m-2" onclick="location.href='{{route('list_check')}}'">방문 신청 정보</button>
        <button class="btn btn-outline-primary m-2" onclick="location.href='{{route('apply')}}'">정원 등록</button>
        <button class="btn btn-outline-primary m-2" onclick="location.href='{{route('management')}}'">민간정원 관리</button>
    </div>
@endsection
