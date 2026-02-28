<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Database\Schema\ForeignKeyDefinition;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   /*  'montant',
        'date',
        'status',
        'client_id' */

    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant',10,2);
            $table->date('date_payment');
            $table->enum('status_payment',['payé','en_attente','en_retard']);
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

















































/* 
$table->decimal('montant',14,2);       
            $table->date('date');
            $table->enum('status',['payé','en attente','en retard']);
            $table->foreignId('client_id')->constrained()->onDelete('cascade');//lors de la suppression d un client ses paiements ausssi seront supprimes */