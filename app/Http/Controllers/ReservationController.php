<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function detail($id)
    {
        $event = Event::findOrFail($id);

        $reservedPeople = DB::table('reservations')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')
        ->groupBy('event_id')
        ->having('event_id', $event->id) //表示中のイベント情報？
        ->first();//1つ目のイベントを取得

        if(!is_null($reservedPeople)){ //予約がある場合
            //予約可能な人数
            $resevablePeople = $event->max_people - $reservedPeople->number_of_people;
        } else { //予約がない場合
            $resevablePeople = $event->max_people;
        } 
       
        return view('event-detail', compact('event', 'resevablePeople'));
    }

    public function reserve(Request $request)
        {
            $event = Event::findOrFail($request->id);
            $reservedPeople = DB::table('reservations')
            ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
            ->whereNull('canceled_date')
            ->groupBy('event_id')
            ->having('event_id', $request->id )
            ->first();

            // $reservedPeopleが空か、或いは、最大定員 >= 予約人数 + 入力された人数　なら予約可能
            if(is_null($reservedPeople) || 
                $event->max_people >= $reservedPeople->number_of_people + $request->reserved_people)
            { 
              Reservation::create([
                'user_id' => Auth::id(),//ログインしているid
                'event_id' => $request->id,
                'number_of_people' => $request->reserved_people,
              ]);
                session()->flash('status', '登録OKです');
                return to_route('dashboard');
            } else {
                session()->flash('status', 'この人数は予約できません。');
                return view('dashboard'); 
            } 
        } 
    
}
