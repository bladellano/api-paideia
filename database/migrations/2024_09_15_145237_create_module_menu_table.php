<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleMenuTable extends Migration
{
  public function up()
  {
    Schema::create('module_menu', function (Blueprint $table) {
      $table->id();
      $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
      $table->string('action');
      $table->string('path');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('module_menu');
  }
}
