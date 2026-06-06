<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->string('no_surat', 50)->unique();
            $table->enum('jenis_surat', ['surat_sakit', 'surat_kontrol', 'surat_rujukan']);
            $table->date('tgl_surat');
            $table->date('tgl_kembali')->nullable(); // Untuk surat kontrol
            $table->integer('lama_istirahat')->nullable(); // Untuk surat sakit
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        
        Schema::table('medical_records', function (Blueprint $table) {
            $table->json('data_spesifik_poli')->nullable()->after('catatan_prosedur');
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn('data_spesifik_poli');
        });
        Schema::dropIfExists('medical_letters');
    }
};
