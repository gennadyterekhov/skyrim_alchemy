<?php

namespace Database\Seeders;

use App\Models\Effect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EffectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Effect::truncate();
        $csvData = fopen(base_path('database/csv/effects.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 0, ',')) !== false) {
            if (!$transRow) {
                // Name,Id,Effect,Magnitude,Value
                assert(count($data) === 5);
                Effect::create([
                    'name' => $data['0'],
                    'id' => $data['1'],
                    'text' => $data['2'],
                    'magnitude' => $data['3'],
                    'value' => $data['4'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
