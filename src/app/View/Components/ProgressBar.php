<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProgressBar extends Component
{
    public $steps;

    public $current;

    public function __construct(array $steps, int $current)
    {
        $this->steps = $steps;
        $this->current = $current;
    }

    public function render()
    {
        return view('components.progress-bar');
    }
}
