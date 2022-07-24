@extends('header')

@section('script')
    <script>
        window.id = false;

        function viewCalender(e){
            let date = $(e).attr('data-date').split('-');
            $('#date').val($(e).attr('data-date'));
            $.ajax({
                url : '{{route('viewDate')}}',
                method : 'post',
                data : {
                    _token : '{{csrf_token()}}',
                    garden_id: window.id,
                    date
                },
                success : function (e){
                    let text = '';
                    for(let i = e['open']; i <= e['close'];i++){
                        let time = i+'am';
                        if(i > 12){
                            time = (i-12)+'pm';
                        }
                        let ele = e['limit'].find((ele)=>parseInt(ele.time) === i);
                        let count =  0;
                        if(ele){
                            e['limit'].forEach((element)=>{
                                if(parseInt(element.time) === i){
                                    count += element.people;
                                }
                            })
                        }

                        text += `
                        <div class="list_box d-flex justify-content-between w-100">
                            <p class="w-25">${time}</p>
                            <p>${count}명 신청함 / ${e.max - count} 신청 가능</p>

                        </div>`
                    }

                    $('#people').attr('max',e['limit']);
                    $('#example .date-calender').html(text);
                    $('#example').modal('show');

                },
                error : (res) => res.responseText,
            })
        }

        function getCalender(e){
            let month = parseInt($(e).attr('data-month'));
            let year = parseInt($(e).attr('data-year'));

            const past = $('.past');
            const next = $('.next');
            past.attr('data-month',month-1);
            past.attr('data-year',year);
            next.attr('data-month',month+1);
            next.attr('data-year',year);
            if(month == 12){
                next.attr('data-month',1);
                next.attr('data-year',year+1);
            }
            if(month == 1){
                past.attr('data-month',12);
                past.attr('data-year',year-1);
            }
            if(!window.id){
                window.id = $(e).attr('data-id');
            }
            $.ajax({
                    url : '{{route('getCalendar')}}',
                    method : 'post',
                    data: {
                        _token : '{{csrf_token()}}',
                        month,
                        year,
                        garden_id: window.id,
                    },
                    success : function(e){
                        $('.table').removeClass('d-none');
                        $('.data').html('')
                        $('.data-push').html(e);
                    }
            })
        }
    </script>
@endsection
@section('contents')

        <div class="justify-content-center d-flex align-items-center flex-column">
            <h1> 내 정원 히히 ^^</h1>
            <table class="table d-none w-50">
                <thead>
                <tr>
                    <th>일</th>
                    <th>월</th>
                    <th>화</th>
                    <th>수</th>
                    <th>목</th>
                    <th>금</th>
                    <th>토</th>
                </tr>
                </thead>
                <tbody class="data-push">
                </tbody>
            </table>

            <button class="btn btn-outline-primary calender past" onclick="getCalender(this)">이전달</button>
            <button class="btn btn-outline-primary calender next" onclick="getCalender(this)">다음달</button>
        @foreach($data['list'] as $item)
                <div class="data d-flex justify-content-between w-50 m-1">
                    <img src="{{$item['image']}}" alt="" style="width: 50px;height: 50px"  class="">
                    <p>{{$item['name']}}</p>
                    <p>{{$item['phone']}}</p>
                    <p>{{$item['open-close']}}</p>
                    <button class="btn btn-outline-primary" onclick="getCalender(this)" data-id="{{$item->id}}" data-month="{{date('m')}}" data-year="{{date('Y')}}">조회하기</button>
                </div>
            @endforeach
        </div>


        <div class="modal fade bd-example-modal-lg" id="example" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body date-calender w-75" style="margin: 0 auto">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

@endsection
