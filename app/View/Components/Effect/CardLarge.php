<?php

namespace App\View\Components\Effect;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardLarge extends Component
{
    public array $effect;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $effect)
    {
        $this->effect = $effect;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.effect.card-large');
    }
}
