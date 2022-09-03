<?php

use App\Models\Effect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Name,ID,PrimaryEffect,SecondaryEffect,TertiaryEffect,QuaternaryEffect,Weight,Value
        Schema::create('ingredient_with_effect_names', function (Blueprint $table) {
            $table->string('id', 16)->unique();
            $table->string('name')->unique();

//            $table->foreign('effects.name', 'effect_1_name');
//            $table->foreign('effects.name', 'effect_2_name');
//            $table->foreign('effects.name', 'effect_3_name');
//            $table->foreign('effects.name', 'effect_4_name');

            $table->string('effect_1_name');
            $table->string('effect_2_name');
            $table->string('effect_3_name');
            $table->string('effect_4_name');

            $table->foreign('effect_1_name')->references('name')->on('effects');
            $table->foreign('effect_2_name')->references('name')->on('effects');
            $table->foreign('effect_3_name')->references('name')->on('effects');
            $table->foreign('effect_4_name')->references('name')->on('effects');



            $table->double('weight');
            $table->double('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredient');
    }
};
