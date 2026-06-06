<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('room_name', 100);
            $table->string('bed_number', 20);
            $table->enum('class', ['Kelas I', 'Kelas II', 'Kelas III', 'VIP', 'VVIP', 'ICU', 'ICCU', 'NICU']);
            $table->enum('status', ['available', 'occupied', 'cleaning', 'broken'])->default('available');
            $table->decimal('price_per_day', 15, 2);
            $table->timestamps();
            
            $table->unique(['department_id', 'room_name', 'bed_number']);
        });

        Schema::table('encounters', function (Blueprint $table) {
            $table->foreignId('bed_id')->nullable()->after('doctor_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('encounters', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bed_id');
        });
        Schema::dropIfExists('beds');
    }
};
