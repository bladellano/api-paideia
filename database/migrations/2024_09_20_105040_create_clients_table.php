<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
  public function up()
  {
    Schema::create('clients', function (Blueprint $table) {
      $table->id();
      $table->string('school_name');
      $table->string('email');
      $table->string('cnpj');
      $table->string('address');
      $table->json('phones'); // array of phone numbers
      $table->string('owner');
      $table->string('slogan');
      $table->string('main_service');
      $table->string('website_name');
      $table->string('colored_logo')->nullable(); // URL for colored logo
      $table->string('black_white_logo')->nullable(); // URL for black/white logo
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('clients');
  }
}
