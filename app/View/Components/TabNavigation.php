<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TabNavigation extends Component
{
    public $tabs;
    public $activeTab;
    public $clickHandler;

    public function __construct($tabs, $activeTab, $clickHandler = 'setActiveTab')
    {
        $this->tabs = $tabs;
        $this->activeTab = $activeTab;
        $this->clickHandler = $clickHandler;
    }

    public function render()
    {
        return view('components.tab-navigation');
    }
}
