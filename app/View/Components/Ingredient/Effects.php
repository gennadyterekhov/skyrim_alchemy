<?php

namespace App\View\Components\Ingredient;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Effects extends Component
{
    public array $effect1;
    public array $effect2;
    public array $effect3;
    public array $effect4;

    public function __construct(
        array $effect1,
        array $effect2,
        array $effect3,
        array $effect4,
    ) {
        $this->effect1 = $effect1;
        $this->effect2 = $effect2;
        $this->effect3 = $effect3;
        $this->effect4 = $effect4;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.ingredient.effects');
    }
}
