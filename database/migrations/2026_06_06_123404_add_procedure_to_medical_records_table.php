<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->string('icd9_prosedur', 20)->nullable()->after('icd10_primer');
            $table->text('catatan_prosedur')->nullable()->after('icd9_prosedur');
        });
        
        Schema::create('icd9_master', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama_prosedur', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icd9_master');
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn(['icd9_prosedur', 'catatan_prosedur']);
        });
    }
};
