<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FileInput extends Component
{
    public $disabled;
    public $name;
    public $required;
    public $accept;

    public function __construct($name = 'file', $disabled = false, $required = false, $accept = 'application/pdf')
    {
        $this->name = $name;
        $this->disabled = $disabled;
        $this->required = $required;
        $this->accept = $accept;
    }

    public function render()
    {
        return view('components.file-input');
    }
}
