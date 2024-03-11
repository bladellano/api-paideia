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
        Schema::create('school_grades', function (Blueprint $table) {
            $table->id();

            $table->float('grade');

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('stage_id');
            $table->unsignedBigInteger('discipline_id');
            $table->unsignedBigInteger('team_id');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
            $table->foreign('discipline_id')->references('id')->on('disciplines')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');

            // Adicionando a chave única composta
            $table->unique(['student_id', 'stage_id', 'discipline_id', 'team_id']);

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
        Schema::dropIfExists('school_grades');
    }
};
