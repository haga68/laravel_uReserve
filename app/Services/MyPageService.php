<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MyPageService
{
  public static function reservedEvent($events, $string){
    $reservedEvents = [];
    // 今日を含む未来のイベント
    if($string === 'fromToday')
    { 
      foreach($events->sortBy('start_date') as $event) // 昇順(古い順)
      {
        // キャンセルがなく、開始日時が今日を含む未来であれば
        if(is_null($event->pivot->canceled_date) &&
        $event->start_date >= Carbon::now()->format('Y-m-d 00:00:00'))
        { 
          $eventInfo = [
            'id' => $event->id,
            'name' => $event->name,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'number_of_people' => $event->pivot->number_of_people,
          ];
          array_push($reservedEvents, $eventInfo); 
        }
      }
    }
    // 過去のイベント
    if($string === 'past')
    {
      foreach($events->sortByDesc('start_date') as $event) // 降順(新しい順)
      {
        if(is_null($event->pivot->canceled_date) &&
        $event->start_date < Carbon::now()->format('Y-m-d 00:00:00'))
        { 
          $eventInfo = [
            'id' => $event->id,
            'name' => $event->name,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'number_of_people' => $event->pivot->number_of_people,
          ];
          array_push($reservedEvents, $eventInfo); 
        }
      }
    }
    return $reservedEvents;
  }
}