<?php

namespace Database\Seeders;

use App\Models\Effect;
use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingredient::truncate();
        $csvData = fopen(base_path('database/csv/ingredients_with_foreign_keys.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 0, ',')) !== false) {
            if (!$transRow) {
                // id,name,effect_1_id,effect_2_id,effect_3_id,effect_4_id,weight,value
                assert(count($data) === 8);
                Ingredient::create([
                    'id' => $data['0'],
                    'name' => $data['1'],
                    'effect_1_id' => $data['2'],
                    'effect_2_id' => $data['3'],
                    'effect_3_id' => $data['4'],
                    'effect_4_id' => $data['5'],

                    'weight' => $data['6'],
                    'value' => $data['7'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
