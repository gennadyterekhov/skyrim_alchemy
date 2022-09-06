<?php

namespace App\View\Components\Ingredient\List;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardMicroList extends Component
{
    public array $ingredients;
    public array $originalIngredient;

    public function __construct(array $ingredients, array $originalIngredient)
    {
        $this->ingredients = $ingredients;
        $this->originalIngredient = $originalIngredient;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.ingredient.list.card-micro-list');
    }
}
