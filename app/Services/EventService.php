<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventService
{
  //重複チェック
  public static function checkEventDuplication($eventDate, $startTime, $endTime)
  {
    return DB::table('events')
    ->whereDate('start_date', $eventDate)
    ->whereTime('end_date' ,'>',$startTime)
    ->whereTime('start_date', '<', $endTime)
    ->exists();
  }
  //重複件数を数える
  public static function countEventDuplication($eventDate, $startTime, $endTime)
  {
    return DB::table('events')
    ->whereDate('start_date', $eventDate)
    ->whereTime('end_date' ,'>',$startTime)
    ->whereTime('start_date', '<', $endTime)
    ->count();
  }

  //日付と時間を結合
  public static function joinDateAndTime($date, $time)
  {
    $join = $date . " " . $time;
    return Carbon::createFromFormat('Y-m-d H:i',$join);
  }

  public static function getWeekEvents($startDate, $endDate)
  {
    $reservedPeople = DB::table('reservations')
        ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        ->whereNull('canceled_date')      
        ->groupBy('event_id');

        return DB::table('events')
        ->leftJoinSub($reservedPeople, 'reservedPeople', function($join){
            $join->on('events.id', '=', 'reservedPeople.event_id');
         })
        ->whereBetween('start_date', [$startDate, $endDate])
        ->orderBy('start_date', 'desc')
        ->get();

  }
}