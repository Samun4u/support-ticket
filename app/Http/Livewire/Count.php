<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Count extends Component
{
    public $counter = 1;
    public function increment()
    {
       return  $this->counter++;
    }

    public function decrement()
    {
       return  $this->counter--;
    }

    

    public function render()
    {
        return view('livewire.count',['counter' => $this->counter]);
    }
}
