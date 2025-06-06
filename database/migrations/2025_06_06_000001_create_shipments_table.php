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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 20)->unique();
            $table->string('sender_name', 100);
            $table->text('sender_address');
            $table->string('sender_phone', 20);
            $table->string('recipient_name', 100);
            $table->text('recipient_address');
            $table->string('recipient_phone', 20);
            $table->decimal('weight', 10, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_transit', 'delivered'])->default('pending');
            $table->timestamps();
            
            $table->index(['status']);
            $table->index(['tracking_number']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
