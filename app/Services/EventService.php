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
  //日付と時間を結合
  public static function joinDateAndTime($date, $time)
  {
    $join = $date . " " . $time;
    return Carbon::createFromFormat('Y-m-d H:i',$join);
  } 
}