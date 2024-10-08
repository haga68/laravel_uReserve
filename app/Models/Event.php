<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use App\Models\User;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'information',
        'max_people',
        'start_date',
        'end_date',
        'is_visible'
    ];

    protected function eventDate(): Attribute   // 戻り値の型
    {
        return new Attribute(
            get: fn () => Carbon::parse($this->start_date)->format('Y年m月d日')// アクセサ
        );
    }

    protected function editEventDate(): Attribute   // 戻り値の型
    {
        return new Attribute(
            get: fn () => Carbon::parse($this->start_date)->format('Y-m-d')
        );
    }

    protected function startTime(): Attribute
    {
        return new Attribute(
            get: fn () => Carbon::parse($this->start_date)->format('H時i分') 
        );
    }
    
    protected function endTime(): Attribute
    {
        return new Attribute(
            get: fn () => Carbon::parse($this->end_date)->format('H時i分') 
        );
    }    
    //　アクセサ、ミューテタ・・DBに情報保存時やDBから情報取得時にデータを加工する機能

    // イベント側からユーザーを見れるように設定（リレーションの設定）
    public function users()
    {
        return $this->belongsToMany(User::class, 'reservations')
        ->withPivot('id', 'number_of_people', 'canceled_date');
        // belongsToMany・・多対多のリレーション、第２引数は中間テーブル名
        // withPivotで中間テーブル内の取得したい情報を指定
    }
}
