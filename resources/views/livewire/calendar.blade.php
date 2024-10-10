<div>
    <div class="text-center text-sm">
        日付を選択してください。本日から最大30日先まで選択可能です。
    </div>
    {{-- inputタグで日付を選択 --}}
    <input id="calendar" class="block mt-1 mb-2 mx-auto" 
        type="text" name="calendar"
        value="{{ $currentDate }}"
        wire:change="getDate($event.target.value)" />
    {{--flexで横に並べ --}}
    <div class="flex border border-green-400 mx-auto"> 
        <x-calendar-time /> {{--縦軸の時間--}}
        @for($i = 0; $i < 7; $i++) {{--7日分を表示（横軸）--}}
        <div class="w-32"> {{--日付、曜日は固定で出す--}}
            <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['day'] }}</div>
            <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['dayOfWeek'] }}</div>
            @for($j = 0; $j < 21; $j++) {{--時間を表示（縦軸）--}}
                {{--一週間のうち一つでもイベントがあれば--}}
                @if($events->isNotEmpty()) 
                    {{--開始時間と同じなら、即ち、イベント開始時間(DB) と 対象時間(ユーザーが入力した日付+時間)　が同じならば--}}
                    {{--firstWhereで条件にあう1つ目を返す（第1引数、検索対象。第2引数、検索条件） --}}
                    @if (!is_null($events->firstWhere('start_date', 
                    $currentWeek[$i]['checkDay'] . " " . \Constant::EVENT_TIME[$j])))
                        @php // 開始 - 終了の差分を計算
                           $eventName = $events->firstWhere('start_date', $currentWeek[$i]['checkDay'] . " " . \Constant::EVENT_TIME[$j])->name;
                           $eventInfo = $events->firstWhere('start_date', $currentWeek[$i]['checkDay'] . " " . \Constant::EVENT_TIME[$j]);
                           $eventPeriod = \Carbon\Carbon::parse($eventInfo->start_date)->diffInMinutes($eventInfo->end_date) / 30 - 1; // 差分
                        @endphp
                        {{-- イベント名を取得--}}
                        <div class="py-1 px-2 h-8 border border-gray-200 text-xs bg-blue-100"> 
                            {{ $eventName }}
                        </div>
                        {{-- イベント時間が30分より大きければ --}}
                        @if( $eventPeriod > 0)
                            @for($k = 0; $k < $eventPeriod; $k++)
                            <div class="py-1 px-2 h-8 border border-gray-200 bg-blue-100"></div>
                            @endfor
                            {{-- 追加した分$jを増やして、divタグの数を調整 --}}
                            @php $j += $eventPeriod @endphp 
                        @endif                        
                    {{--開始時間と同じでなければ、nullを返す--}}
                    @else 
                        <div class="py-1 px-2 h-8 border border-gray-200"></div>
                    @endif
                @else
                    <div class="py-1 px-2 h-8 border border-gray-200"></div>
                @endif 
            @endfor
        </div>
        @endfor
    </div>
</div>
