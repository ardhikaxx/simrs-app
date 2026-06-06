<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_bhps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bhp', 150);
            $table->string('satuan', 30);
            $table->integer('stok')->default(0);
            $table->decimal('harga_jual', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('medical_record_bhp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_bhp_id')->constrained()->cascadeOnDelete();
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_record_bhp');
        Schema::dropIfExists('inventory_bhps');
    }
};
