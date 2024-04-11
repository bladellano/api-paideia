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
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->float('value');
            $table->date('due_date');
            $table->tinyInteger('paid')->default(0);
            $table->text('observations')->nullable();
            $table->text('gateway_response')->nullable();
            $table->unsignedBigInteger('payment_type')->nullable();
            $table->timestamps();
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('set null');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('payment_type')->references('id')->on('payment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financials');
    }
};
