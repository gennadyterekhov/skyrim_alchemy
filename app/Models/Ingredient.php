<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ingredient extends Model
{
    use HasFactory;
    public $timestamps = false;
    public const COUNT = 91;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'id',
        'weight',
        'value',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'effect_1_id',
        'effect_2_id',
        'effect_3_id',
        'effect_4_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'weight' => 'double',
        'value' => 'int',
    ];

    public function effect_1(): HasOne
    {
        return $this->hasOne(Effect::class, 'id', 'effect_1_id');
    }

    public function effect_2()
    {
        return $this->hasOne(Effect::class, 'id', 'effect_2_id');
    }

    public function effect_3()
    {
        return $this->hasOne(Effect::class, 'id', 'effect_3_id');
    }

    public function effect_4()
    {
        return $this->hasOne(Effect::class, 'id', 'effect_4_id');
    }
}
