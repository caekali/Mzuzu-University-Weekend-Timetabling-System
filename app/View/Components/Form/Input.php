<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     */

    public $label;
    public $name;
    public $type;
    public $id;

    public $placeholder;
    public $required;

    public function __construct($label, $name, $type, $id, $placeholder, $required)
    {
        $this->label = $label;
        $this->name = $name;
        $this->type = $type;
        $this->id = $id;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}
