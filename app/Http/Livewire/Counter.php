<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;

    public $name = '';

    // render描画前に実行( constructorのように)
    public function mount()
    {
        $this->name = 'mount';
    }
    
    public function updated() // データ更新毎
    {
        $this->name = 'updated'; 
    } 
    
    public function mouseOver()
    {
        $this->name = 'mouseover';
    }

    public function increment()
    { 
        $this->count++; 
    } 

    public function render()
    {
        return view('livewire.counter');
    }
}
