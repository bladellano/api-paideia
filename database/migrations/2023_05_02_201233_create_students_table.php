<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('name',100);
            $table->string('email')->nullable();
            $table->string('phone',14)->nullable();
            $table->string('rg',15)->nullable();
            $table->string('cpf',11);
            $table->string('expedient_body',25)->nullable();
            $table->string('nationality',40);
            $table->string('naturalness',40);
            $table->string('name_mother',100);
            $table->date('birth_date');
            $table->enum('gender',['F','M']);

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
        Schema::dropIfExists('students');
    }
};
