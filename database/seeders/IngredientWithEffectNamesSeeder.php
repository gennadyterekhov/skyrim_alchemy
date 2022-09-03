<?php

namespace Database\Seeders;

use App\Models\Effect;
use App\Models\Ingredient;
use App\Models\IngredientWithEffectNames;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientWithEffectNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IngredientWithEffectNames::truncate();
        $csvData = fopen(base_path('database/csv/ingredients.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 0, ',')) !== false) {
            if (!$transRow) {
                // id,name,effect_1_id,effect_2_id,effect_3_id,effect_4_id,weight,value
                assert(count($data) === 8);
                IngredientWithEffectNames::create([
                    'name' => $data['0'],
                    'id' => $data['1'],
                    'effect_1_name' => $data['2'],
                    'effect_2_name' => $data['3'],
                    'effect_3_name' => $data['4'],
                    'effect_4_name' => $data['5'],

                    'weight' => $data['6'],
                    'value' => $data['7'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
