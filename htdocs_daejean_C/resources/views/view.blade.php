@extends('header')
@section('script')
    <script>
        $(()=>{
            $(document)
            .on('click','.calender',makeCalender)
            .on('click','.date-view',viewCalender)
            .on('click','.view_post', function(){hide();window.time = $(this).attr('data-time')})
            .on('keydown keyup','#people',people)
            .on('click','.post_calender',postCalender)
        })

        function postCalender(){

            let user_id = $('#id').val(),
                garden_id = '{{$data['garden']['id']}}',
                day = $('#date').val().split('-'),
                time = window.time,
                people = $('#people').val();
            $.ajax({
                url : '{{route('makeCalender')}}',
                method : 'post',
                data : {
                    _token : '{{csrf_token()}}',
                    user_id,
                    garden_id,
                    'year' : day[0],
                    'month' : day[1],
                    'date' : day[2],
                    time,
                    people
                },
                success : function(e){
                    hide();
                },
                error : (res) => res.responseText,

            })
        }

        function people(){
            let value = $('#people').val();
            if(value > window.limit){
                $('#people').val(window.limit);
                alert('인원이 초과되었습니다.');
            }
        }


        function hide(){
            $('.modal').modal('hide');
        }

        function viewCalender(){
            let date = $(this).attr('data-date').split('-');
            $('#date').val($(this).attr('data-date'));
            $.ajax({
                url : '{{route('viewDate')}}',
                method : 'post',
                data : {
                    _token : '{{csrf_token()}}',
                    garden_id: '{{$data['garden']['id']}}',
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
                        let button = {{$data['garden']['limit']}} - count > 0 ? `<button class="btn btn-outline-primary view_post" data-toggle="modal" data-target="#viewPost" data-time="${i}">신청</button>` : '<p></p>';

                        text += `
                        <div class="list_box d-flex justify-content-between w-100">
                            <p class="w-25">${time}</p>
                            <p>방문 가능 인원 : ${ {{$data['garden']['limit']}} - count}</p>
                            ${button}
                        </div>`
                    }

                    window.limit = {{$data['garden']['limit']}};
                    $('#people').attr('max',e['limit']);
                    $('#example .date-calender').html(text);
                    hide();
                    $('#example').modal('show');

                },
                error : (res) => res.responseText,
            })
        }

        function makeCalender(){
            let month = parseInt($(this).attr('data-month'));
            let year = parseInt($(this).attr('data-year'));

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

            $.ajax({
                url : '{{route('calender')}}',
                method : 'post',
                data: {
                    _token : '{{csrf_token()}}',
                    month,
                    year,
                    garden_id : '{{$data['garden']['id']}}'
                },
                success : function(e){
                    $('.data-push').html(e);
                    $('#exampleModalLongTitle').html(`${year}년 ${month}월`);
                },
                error : (res) => res.responseText,
                }
            )
        }
    </script>
@endsection
@section('contents')
    <div class="justify-content-center d-flex align-items-center flex-column">
        <h1>{{$data['garden']['name']}}</h1>
        <p>{{$data['garden']['phone']}}</p>
        <p>{{$data['garden']['introduce']}}</p>
        <p>{{$data['garden']['institution']}}</p>
        <p>{{$data['garden']['open-close']}}</p>
        <p>{{$data['garden']['address']}}</p>
        <img src="{{$data['garden']['image']}}" alt="">
        <button type="button" class="btn btn-outline-primary calender" data-toggle="modal" data-target="#view_modal" data-month="{{date('m')}}" data-year="{{date('Y')}}">예약하기</button>

    </div>
    <div class="modal fade bd-example-modal-lg" id="view_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
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
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary calender past">이전달</button>
                    <button class="btn btn-outline-primary calender next">다음달</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
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

    <div class="modal fade bd-example-modal-lg" id="viewPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="m-0">날짜</p>
                        <input type="text" class="form-control" readonly id="date">
                    </div>
                    <div class="form-group">
                        <p class="m-0">예약 인원</p>
                        <input type="number" class="form-control" id="people" min="0">
                    </div>
                    <div class="form-group">
                        <p class="m-0">신청자 아이디</p>
                        <input type="text" class="form-control" readonly id="id" value="{{auth()->user()->id}}">
                    </div>
                    <div class="form-group">
                        <p class="m-0">신청자 이름</p>
                        <input type="text" class="form-control" readonly id="name" value="{{auth()->user()->name}}">
                    </div>
                    <div class="form-group">
                        <p class="m-0">전화번호</p>
                        <input type="text" class="form-control" readonly id="phone" value="{{auth()->user()->phone}}">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary post_calender">신청하기</button>
                </div>
            </div>
        </div>
    </div>
@endsection

