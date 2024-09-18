<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
  public function up()
  {
    Schema::create('modules', function (Blueprint $table) {
      $table->id();
      $table->string('nm_module');
      $table->string('nm_machine'); // Vai ler o .env
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('modules');
  }
}
