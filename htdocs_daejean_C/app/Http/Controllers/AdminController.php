<?php

namespace App\Http\Controllers;

use App\Apply_garden;
use App\Calendar;
use App\Garden;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function list_check(){
        if(!auth()->user()) return;
        $data = [];
        $data['list'] = Garden::where('user_id',auth()->user()->id)->get();
        $data['application'] = [];
        foreach($data['list'] as $item){
            $obj = $item->calendars;
            array_push($data['application'],...$obj);
        }
        return view('list',compact(['data']));
    }

    public function apply(){
        if(!auth()->user()) return back();
        $data = [];
        $data['list'] = Apply_garden::all();
        return view('apply',compact(['data']));
    }
    public function applyAction(Request $request){
        $input = $request->only(['name','introduce','address','institution','phone','people']);
        $input['open'] = $request['open_type'].$request['open'].':00';
        $input['close'] = $request['close_type'].$request['close'].':00';
        $input['user_id'] = auth()->user()->id;

        $url = '/public/garden_image';
        $name = time().'.jpg';
        $request->image->move(base_path($url),$name);
        $input['image'] = $name;

        Apply_garden::create($input);
        return redirect('/');
    }

    public function accept($id){
        $garden = Apply_garden::find($id);
        $input = [
            'image' => './public/garden_image/'.$garden['image'],
            'name' => $garden['name'],
            'introduce' => $garden['introduce'],
            'institution' => $garden['institution'],
            'phone' => $garden['phone'],
            'open' => $garden['open'],
            'close' => $garden['close'],
            'address' => $garden['address'],
            'limit' => $garden['people'],
            'user_id' =>$garden['user_id']
        ];
        Garden::create($input);
        $garden['state'] = 'accept';
        $garden->update();
        return back();
    }
    public function management(){
        if(!auth()->user())return back();
        $data = [];
        $data['list'] = Garden::where('user_id',auth()->user()->id)->get();


        return view('management',compact(['data']));
    }

    public function getCalendar(Request $request){
        $user_get_garden = Garden::where('user_id',auth()->user()->id)->get();
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
                    $text = $text . $n;
                    $people = 0;
                    foreach ($user_get_garden as $get){
                        $data = Calendar::where('garden_id', $get['id'])->where('year', $request['year'])->where('month', $request['month'])->where('date', $n)->get();
                        $garden = Garden::find($request['garden_id']);
                        $people += $this->calcPeople($garden,$data);
                    }
                    $text = $text . '<button class="btn-outline-primary btn date-view" onclick="viewCalender(this)" data-date="' . $request['year'] . '-' . $request['month'] . '-' . $n . '"> ' . $people . '명 신청</button>';
                    $n++;
                }
                $text = $text . '</td>';
            }
            $text = $text . '</tr>';
        }
        return $text;
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
        foreach ($data as $item){
            $people += $item['people'];
        }
        return $people;
    }
}
