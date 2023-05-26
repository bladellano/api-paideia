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
        Schema::create('polos', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('city',50);
            $table->string('uf',2);
            $table->string('responsible',100);
            $table->string('address',150);
            $table->string('email',150)->nullable();
            $table->string('phone',14);
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
        Schema::dropIfExists('polos');
    }
};
