@extends('header')


@section('contents')
    <div class="h-100 justify-content-center d-flex align-items-center flex-column">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th>정원 이름</th>
                <th>신청 인원</th>
                <th>방문일</th>
                <th>신청일</th>
                <th>취소</th>
            </tr>
            </thead>
            <tbody class="">
            @foreach($data['application'] as $idx => $item)
                <tr>
                    <td>{{$item->garden->name}}</td>
                    <td>{{$item->people}}</td>
                    <td>{{$item->year}}년 {{$item->month}}월 {{$item->date}}일 / {{$item->time}}시</td>
                    <td>{{$item->write_date}}</td>
                    <td>
                        @if(strtotime(date('Y-m-d'))-strtotime(date('Y-m-d',mktime(0,0,0,$item['month'],$item['date'],$item['year']))) > 0 )
                            취소불가
                        @else
                            <button class="btn btn-outline-primary" onclick="location.href='{{route('cancel',$item['id'])}}'">취소</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
