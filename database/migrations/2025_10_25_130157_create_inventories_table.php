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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();

            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
            
            $table->foreignId('air_waybill_id');
            $table->foreign('air_waybill_id')->references('id')->on('air_waybills')->onDelete('restrict');

            $table->boolean('is_voided')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->unsignedInteger('total_count')->default(0);
            $table->decimal('total_weight', 10, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
