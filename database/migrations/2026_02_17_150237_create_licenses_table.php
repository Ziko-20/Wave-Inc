<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            
            $table->id();
            $table->string('nom');
            $table->integer('quantiter_disponible');
            $table->foreignId('client_id');
            $table->date('date_d\'assignation');


            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
