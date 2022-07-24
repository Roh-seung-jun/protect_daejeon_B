<?php

namespace App\Http\Controllers;

use App\Calendar;
use App\Garden;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\List_;

class UserController extends Controller
{
    public function signPage(){
        return view('sign');
    }

    public function loginPage(){
        return view('login');
    }

    public function visitPage(){
        if(!Auth()->user()) return back()->with('msg','로그인한 회원만 접근가능합니다.');
        $data = [];
        $data['garden'] = Garden::all();
        return view('visit',compact(['data']));
    }

    public function viewPage($id){
        if(!Auth()->user()) return back()->with('msg','로그인한 회원만 접근가능합니다.');
        $data = [];
        $data['garden'] = Garden::find($id);
        return view('view',compact(['data']));
    }

    public function loginAction(Request $request){
        $user = User::find($request['id']);
        if($user['password'] !== $request['password']) return back()->with('msg','이미 존재하는 아이디입니다.');
        Auth::login($user);
        return redirect('/')->with('msg','로그인이 완료되었습니다.');
    }

    public function signAction(Request $request){
        $input = $request->only('id','name','password','phone');
        if($request['password'] !== $request['password_check']) return back()->with('msg','비밀번호를 확인해주세요.');
        if(User::find($request['id'])) return back()->with('msg','이미존재하는 아이디입니다.');
        User::create($input);
        return redirect('/')->with('msg','정상적으로 완료되었습니다.');
    }

    public function logout(){
        Auth::logout();
        return redirect('/')->with('msg','로그아웃되었습니다.');
    }

    public function calender(Request $request)
    {
        $text = '';
        if ($request['month'] >= 10) {
            $date = $request['year'] . '-' . $request['month'] . '-01';
        } else {
            $date = $request['year'] . '-0' . $request['month'] . '-01';
        }
        $time = strtotime($date);
        $start_week = date('w', $time);
        $day = date('t', $time);
        $week = ceil(($day + $start_week) / 7);

        for ($n = 1, $i = 0; $i < $week; $i++) {
            $text = $text . '<tr>';
            for ($d = 0; $d < 7; $d++) {
                $text = $text . '<td>';
                if (($n > 1 || $d >= $start_week) && ($day + 1 > $n)) {
                    $data = Calendar::where('garden_id', $request['garden_id'])->where('year', $request['year'])->where('month', $request['month'])->where('date', $n)->get();
                    $garden = Garden::find($request['garden_id']);
                    $people = $this->calcPeople($garden,$data)+$garden['limit'];

                    $text = $text . $n;
                    $text = $text . '<button class="btn-outline-primary btn date-view" data-date="' . $request['year'] . '-' . $request['month'] . '-' . $n . '"> ' . $people . '명 신청가능</button>';
                    $n++;
                }
                $text = $text . '</td>';
            }
            $text = $text . '</tr>';
        }
        return $text;
    }

    public function viewDate(Request $request){

        $list = Calendar::where('year',$request['date'][0])
            ->where('month',$request['date'][1])
            ->where('date',$request['date'][2])
            ->where('garden_id',$request['garden_id'])
            ->get();

        $garden = Garden::find($request['garden_id']);

        $time = $this->calcTime($garden);
        $time['limit'] = $list;
        $time['max'] = $garden['limit'];
        return $time;
    }

    public function makeCalender(Request $request){
        $input = $request->only(['user_id','garden_id','year','month','date','time','people']);
        $input['state'] = null;
        Calendar::create($input);
    }

    public function cancel($id){
        Calendar::find($id)->delete();
        return back();
    }

    private function calcTime($garden){
        $openTime = explode('m',$garden['open']);
        $closeTime = explode('m',$garden['close']);

        $openPlus = $openTime[0] === 'a' ? 0 : 12;
        $closePlus = $closeTime[0] === 'a' ? 0 : 12;

        $open = preg_replace('/[^1-9]*/s', '', $openTime[1])+$openPlus;
        $close = preg_replace('/[^1-9]*/s', '', $closeTime[1])+$closePlus;

        return ['open'=>$open,'close'=>$close];
    }

    private function calcPeople($garden,$data){
        $time = $this->calcTime($garden);
        $people = 0;
        for($i = $time['open'];$i < $time['close'];$i++){
            $people += $garden['limit'];
        }
        foreach ($data as $item){
            $people -= $item['people'];
        }
        return $people;
    }
}
