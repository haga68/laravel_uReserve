<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\CarbonImmutable;
use App\Services\EventService;

class Calendar extends Component
{
    public $currentDate; 
    public $currentWeek;
    public $day;
    public $sevenDaysLater;
    public $events;

    //画面を表示したときの初期値
    public function mount()
    {
        $this->currentDate = CarbonImmutable::today();
        //Immutable(不変)にすることで、初期表示で7日増えていた問題を解決
        $this->sevenDaysLater = $this->currentDate->addDays(7);
        $this->currentWeek = [];

        $this->events = EventService::getWeekEvents(
            $this->currentDate->format('Y-m-d'),
            $this->sevenDaysLater->format('Y-m-d')
        );
        
        for($i = 0; $i < 7; $i++ ){
            $this->day = CarbonImmutable::today()->addDays($i)->format('m月d日');
            array_push($this->currentWeek, $this->day );
        }
        // dd($this->currentWeek);
    }

    public function getDate($date)
    {
        $this->currentDate = $date; //文字列
        $this->currentWeek = [];
        $this->sevenDaysLater = CarbonImmutable::parse($this->currentDate)->addDays(7);
        
        $this->events = EventService::getWeekEvents(
            $this->currentDate,
            $this->sevenDaysLater->format('Y-m-d')
        );

        for($i = 0; $i < 7; $i++ )
        {
            // parseでCarbonインスタンスに変換後 日付を加算
            $this->day = CarbonImmutable::parse($this->currentDate)
            ->addDays($i)->format('m月d日'); 
            array_push($this->currentWeek, $this->day );
        } 
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
