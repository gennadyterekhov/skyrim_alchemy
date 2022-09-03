<?php

use App\Models\Effect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->string('id', 16)->unique();
            $table->string('name')->unique();
            $table->foreignIdFor(Effect::class, 'effect_1_id')->type('string');
            $table->foreignIdFor(Effect::class, 'effect_2_id')->type('string');
            $table->foreignIdFor(Effect::class, 'effect_3_id')->type('string');
            $table->foreignIdFor(Effect::class, 'effect_4_id')->type('string');
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
