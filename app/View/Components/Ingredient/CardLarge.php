<?php

namespace App\View\Components\Ingredient;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardLarge extends Component
{

    public array $ingredient;

    public function __construct(array $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.ingredient.card-large');
    }
}
