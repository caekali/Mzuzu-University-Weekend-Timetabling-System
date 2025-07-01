<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public $headers;
    public $rows;
    public $actions;
    public $paginate;
    public $customCell;

    public function __construct($headers = [], $rows = [], $actions = false, $paginate = false, $customCell = null)
    {
        $this->headers = $headers;
        $this->rows = $rows;
        $this->actions = $actions;
        $this->paginate = $paginate;
        $this->customCell = $customCell;
    }

    public function render(): View|Closure|string
    {
        return view('components.table');
    }
}
