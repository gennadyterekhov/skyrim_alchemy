<?php

namespace App\View\Components\Ingredient;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommonIngredients extends Component
{
    public array $ingredient;
    public array $commonIngredients1;
    public array $commonIngredients2;
    public array $commonIngredients3;
    public array $commonIngredients4;

    public function __construct(
        array $ingredient,
        array $commonIngredients1,
        array $commonIngredients2,
        array $commonIngredients3,
        array $commonIngredients4,
    ) {
        $this->ingredient = $ingredient;
        $this->commonIngredients1 = $commonIngredients1;
        $this->commonIngredients2 = $commonIngredients2;
        $this->commonIngredients3 = $commonIngredients3;
        $this->commonIngredients4 = $commonIngredients4;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('components.ingredient.common-ingredients');
    }
}
