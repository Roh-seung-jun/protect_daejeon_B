@extends('header')
@section('script')
    <script>
        $(()=>{
            $(document)
                .on('keydown keyup','#open,#close',timePlus)
        })

        function timePlus(){
            this.value = this.value.replace(/[^0-9]/g, '');
            let answer = this.value;
            if( answer <= 1 ){
                this.value = 1;
            }else if(answer >= 13){
                this.value = 12;
            }
        }


    </script>
    @endsection

@section('contents')

    @if(auth()->user()->id === 'admin')
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th>이미지</th>
                <th>이름</th>
                <th>소개</th>
                <th>연락처</th>
                <th>주소</th>
                <th>관람시간</th>
                <th>시간별 입장 가능 인원</th>
                <th>유저 아이디</th>
                <th>시설 안내</th>
                <th>상태</th>
            </tr>
            </thead>
            <tbody class="">
@foreach($data['list'] as $idx => $item)
    <tr>
        <td><img src="/public/garden_image/{{$item->image}}" alt="" style="width: 50px;height: 50px;object-fit: cover"></td>
        <td>{{$item->name}}</td>
        <td>{{$item->introduce}}</td>
        <td>{{$item->phone}}</td>
        <td>{{$item->address}}</td>
        <td>{{$item->open}} ~ {{$item->close}}</td>
        <td>{{$item->people}}</td>
        <td>{{$item->user_id}}</td>
        <td>{{$item->institution}}</td>
        @if($item->state == 'wait')
            <td><button class="btn btn-outline-primary" onclick="location.href='{{route('accept',$item['id'])}}'">승인</button></td>
        @else
            <td>승인완료</td>
        @endif
    </tr>
@endforeach
            </tbody>
        </table>
        @else
    <form class="h-100 justify-content-center d-flex align-items-center flex-column" method="post" action="{{route('applyAction')}}" enctype="multipart/form-data">
        @csrf
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">정원 이름</p>
            <input type="text" class="form-control" required name="name" id="name">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">정원 소개</p>
            <input type="text" class="form-control" required name="introduce" id="introduce">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">정원 주소</p>
            <input type="text" class="form-control" required name="address" id="address">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">시설 안내</p>
            <input type="text" class="form-control" required name="institution" id="institution">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">연락처</p>
            <input type="text" class="form-control" required name="phone" id="phone">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">운영 시작 시간</p>
            <select name="open_type" id="open_type" class="custom-select w-25">
                <option value="am">am</option>
                <option value="pm">pm</option>
            </select>
            <input type="text" class="form-control" required name="open" id="open">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">운영 마감 시간</p>
            <select name="close_type" id="close_type" class="custom-select w-25">
                <option value="am">am</option>
                <option value="pm">pm</option>
            </select>
            <input type="text" class="form-control" required name="close" id="close">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">시간 별 입장 인원</p>
            <input type="number" class="form-control" required name="people" id="people" onkeyup="this.value = $(this).val() <= 0 ? 1 : this.value">
        </div>
        <div class="w-50 d-flex form-group align-items-center justify-content-center" style="height: 30px;">
            <p class="w-25">정원 이미지</p>
            <input type="file" class="form-control-file" required name="image" id="image" accept=".jpg, .png" >
        </div>
        <button class="btn btn-outline-primary" type="submit">등록</button>
    </form>
    @endif
@endsection
