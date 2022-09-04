<?php

namespace App\View\Components\Effect;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardSmall extends Component
{
    public array $effect;
    public bool $onlyName = false;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $effect, bool $onlyName = false)
    {
        $this->effect = $effect;
        $this->onlyName = $onlyName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.effect.card-small');
    }
}
