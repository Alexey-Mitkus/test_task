<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DevelopmentNotice extends Component
{
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.development-notice');
    }
}
