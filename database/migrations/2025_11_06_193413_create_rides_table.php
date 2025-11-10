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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('passenger_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->float('distance')->nullable();
            $table->float('duration')->nullable();
            $table->json('from')->nullable();
            $table->json('to')->nullable();
            $table->json('geometry')->nullable();
            $table->integer('price')->nullable();
            $table->text('from_address')->nullable();
            $table->text('to_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
