@extends('header')


@section('contents')
    <div class="justify-content-center d-flex align-items-center flex-column">
    @foreach($data['garden'] as $item)
            <div class="data d-flex justify-content-between w-50 m-1">
                <img src="{{$item['image']}}" alt="" style="width: 50px;height: 50px"  class="">
                <p>{{$item['name']}}</p>
                <p>{{$item['phone']}}</p>
                <p>{{$item['open-close']}}</p>
                <button class="btn btn-outline-primary" onclick="location.href='{{route('view',$item['id'])}}'">예약하기</button>
            </div>
    @endforeach
    </div>

@endsection
