<?php

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
        Schema::create('grid_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grid_id')->constrained();
            $table->integer('workload');
            $table->foreignId('course_id')->constrained();
            $table->foreignId('stage_id')->constrained();
            $table->foreignId('discipline_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grid_templates');
    }
};
