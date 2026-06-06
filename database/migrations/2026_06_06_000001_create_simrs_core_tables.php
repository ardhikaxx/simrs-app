<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('kode_depart', 10)->unique();
            $table->string('nama_depart', 150);
            $table->string('jenis', 30);
            $table->foreignId('kepala_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('lantai')->nullable();
            $table->string('gedung', 50)->nullable();
            $table->string('telepon_ext', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 20)->nullable()->unique()->after('id');
            $table->string('nama_lengkap', 150)->nullable()->after('name');
            $table->string('foto_profil')->nullable()->after('password');
            $table->string('no_telepon', 20)->nullable()->after('foto_profil');
            $table->foreignId('department_id')->nullable()->after('no_telepon')->constrained('departments')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('department_id');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peran', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->unsignedTinyInteger('level')->default(5);
            $table->timestamps();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('nama_modul', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('icon_class', 50)->nullable();
            $table->unsignedTinyInteger('urutan_menu')->default(99);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->string('action', 20);
            $table->string('deskripsi', 200)->nullable();
            $table->timestamps();
            $table->unique(['module_id', 'action']);
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('kode_dokter', 20)->unique();
            $table->string('spesialisasi', 120)->nullable();
            $table->string('no_str', 80)->nullable();
            $table->date('str_expired_at')->nullable();
            $table->string('no_sip', 80)->nullable();
            $table->date('sip_expired_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('quota')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['doctor_id', 'department_id', 'day_of_week', 'start_time']);
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('no_rkm_medis', 20)->unique();
            $table->string('nik', 20)->unique();
            $table->string('no_bpjs', 20)->nullable()->unique();
            $table->string('nama_pasien', 150);
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('jenis_kelamin', 1);
            $table->date('tgl_lahir');
            $table->string('tempat_lahir', 80)->nullable();
            $table->string('golongan_darah', 3)->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('status_perkawinan', 30)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('pendidikan', 50)->nullable();
            $table->text('alamat_lengkap');
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('no_telp_pasien', 20)->nullable();
            $table->string('kontak_darurat_nama', 150)->nullable();
            $table->string('kontak_darurat_telp', 20)->nullable();
            $table->text('alergi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('icd10', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama_diagnosis', 255);
            $table->string('kategori', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi', 30)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('jenis_kunjungan', 30);
            $table->string('cara_masuk', 40)->default('datang_sendiri');
            $table->string('cara_bayar', 30);
            $table->string('no_antrian', 20)->nullable();
            $table->string('status_antrian', 30)->default('menunggu');
            $table->string('status_encounter', 30)->default('terdaftar');
            $table->timestamp('waktu_masuk');
            $table->timestamp('waktu_keluar')->nullable();
            $table->text('keluhan_awal')->nullable();
            $table->string('rujukan_dari', 150)->nullable();
            $table->string('kelas_rawat', 20)->nullable();
            $table->string('kamar', 30)->nullable();
            $table->string('bed', 20)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['status_encounter', 'status_antrian']);
        });

        Schema::create('nursing_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->unique()->constrained('encounters')->cascadeOnDelete();
            $table->foreignId('nurse_id')->constrained('users')->restrictOnDelete();
            $table->unsignedSmallInteger('tekanan_darah_sistolik')->nullable();
            $table->unsignedSmallInteger('tekanan_darah_diastolik')->nullable();
            $table->unsignedSmallInteger('nadi')->nullable();
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->unsignedSmallInteger('pernapasan')->nullable();
            $table->unsignedSmallInteger('saturasi_oksigen')->nullable();
            $table->unsignedTinyInteger('skala_nyeri')->nullable();
            $table->decimal('berat_badan', 6, 2)->nullable();
            $table->decimal('tinggi_badan', 6, 2)->nullable();
            $table->string('triase', 20)->nullable();
            $table->text('catatan_keperawatan')->nullable();
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->unique()->constrained('encounters')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete();
            $table->text('keluhan_utama');
            $table->text('riwayat_penyakit_sekarang')->nullable();
            $table->text('riwayat_penyakit_dahulu')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->text('diagnosis_kerja');
            $table->string('icd10_primer', 10);
            $table->json('icd10_sekunder')->nullable();
            $table->text('rencana_terapi');
            $table->string('kondisi_saat_pulang', 40)->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_medicines', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat', 30)->unique();
            $table->string('nama_obat', 150);
            $table->string('kategori', 80);
            $table->string('satuan', 30);
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(10);
            $table->decimal('harga_beli', 14, 2)->default(0);
            $table->decimal('harga_jual', 14, 2)->default(0);
            $table->date('expired_at')->nullable();
            $table->string('manufacturer', 120)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('no_resep', 30)->unique();
            $table->foreignId('encounter_id')->constrained('encounters')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('pharmacist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 30)->default('baru');
            $table->text('catatan')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('dispensed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('prescription_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->foreignId('inventory_medicine_id')->nullable()->constrained('inventory_medicines')->nullOnDelete();
            $table->string('nama_obat', 150);
            $table->decimal('jumlah', 10, 2);
            $table->string('satuan', 30);
            $table->string('aturan_pakai', 150);
            $table->string('rute', 50)->nullable();
            $table->decimal('harga_satuan', 14, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_medicine_id')->constrained('inventory_medicines')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('jenis_transaksi', 30);
            $table->integer('qty');
            $table->integer('stok_sebelum')->default(0);
            $table->integer('stok_sesudah')->default(0);
            $table->string('referensi', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('lab_panels', function (Blueprint $table) {
            $table->id();
            $table->string('kode_panel', 30)->unique();
            $table->string('nama_panel', 150);
            $table->string('kategori', 80)->nullable();
            $table->decimal('tarif_umum', 14, 2)->default(0);
            $table->decimal('tarif_bpjs', 14, 2)->nullable();
            $table->json('parameter_default')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_order', 30)->unique();
            $table->foreignId('encounter_id')->constrained('encounters')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('analyst_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('jenis_pemeriksaan', 150);
            $table->string('prioritas', 20)->default('rutin');
            $table->string('status', 30)->default('order');
            $table->text('catatan_klinis')->nullable();
            $table->timestamp('ordered_at');
            $table->timestamp('sample_received_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('lab_order_panels', function (Blueprint $table) {
            $table->foreignId('lab_order_id')->constrained('lab_orders')->cascadeOnDelete();
            $table->foreignId('lab_panel_id')->constrained('lab_panels')->restrictOnDelete();
            $table->decimal('tarif', 14, 2)->default(0);
            $table->timestamps();
            $table->primary(['lab_order_id', 'lab_panel_id']);
        });

        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained('lab_orders')->cascadeOnDelete();
            $table->string('parameter', 120);
            $table->string('nilai', 80);
            $table->string('satuan', 40)->nullable();
            $table->string('nilai_rujukan', 120)->nullable();
            $table->string('flag', 20)->default('normal');
            $table->boolean('is_critical')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('radiology_orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_order', 30)->unique();
            $table->foreignId('encounter_id')->constrained('encounters')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('radiographer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('jenis_pemeriksaan', 150);
            $table->string('prioritas', 20)->default('rutin');
            $table->string('status', 30)->default('order');
            $table->text('indikasi_klinis')->nullable();
            $table->timestamp('ordered_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('radiology_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_order_id')->unique()->constrained('radiology_orders')->cascadeOnDelete();
            $table->text('temuan');
            $table->text('kesan');
            $table->string('image_path')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('service_master', function (Blueprint $table) {
            $table->id();
            $table->string('kode_layanan', 30)->unique();
            $table->string('nama_layanan', 160);
            $table->string('kategori', 50);
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->decimal('tarif_umum', 14, 2)->default(0);
            $table->decimal('tarif_bpjs', 14, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('billing_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice', 30)->unique();
            $table->foreignId('encounter_id')->unique()->constrained('encounters')->cascadeOnDelete();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('diskon', 14, 2)->default(0);
            $table->decimal('total_tagihan', 14, 2)->default(0);
            $table->decimal('total_dibayar', 14, 2)->default(0);
            $table->string('status', 30)->default('draft');
            $table->string('metode_penjamin', 30);
            $table->decimal('tarif_ina_cbg', 14, 2)->nullable();
            $table->string('status_utilisasi', 30)->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('billing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_invoice_id')->constrained('billing_invoices')->cascadeOnDelete();
            $table->string('kategori', 50);
            $table->string('deskripsi', 180);
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('harga_satuan', 14, 2);
            $table->decimal('subtotal', 14, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('no_payment', 30)->unique();
            $table->foreignId('billing_invoice_id')->constrained('billing_invoices')->cascadeOnDelete();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('metode_bayar', 40);
            $table->decimal('jumlah_bayar', 14, 2);
            $table->string('referensi', 80)->nullable();
            $table->timestamp('paid_at');
            $table->timestamps();
        });

        Schema::create('bpjs_sep_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->unique()->constrained('encounters')->cascadeOnDelete();
            $table->string('no_sep', 40)->unique();
            $table->string('no_kartu_bpjs', 20);
            $table->string('diagnosis_awal', 10);
            $table->string('status', 30)->default('aktif');
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('issued_at');
            $table->timestamps();
        });

        Schema::create('ina_cbg_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->unique()->constrained('encounters')->cascadeOnDelete();
            $table->foreignId('bpjs_sep_document_id')->nullable()->constrained('bpjs_sep_documents')->nullOnDelete();
            $table->foreignId('billing_invoice_id')->nullable()->constrained('billing_invoices')->nullOnDelete();
            $table->string('no_klaim', 40)->unique();
            $table->string('kode_inacbg', 20)->nullable();
            $table->decimal('tarif_rs', 14, 2)->default(0);
            $table->decimal('tarif_klaim', 14, 2)->nullable();
            $table->string('status_klaim', 40)->default('draft');
            $table->json('payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ina_cbg_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('icd10_kode', 10);
            $table->string('kode_inacbg', 20);
            $table->string('deskripsi', 180);
            $table->string('kelas_rs', 5)->default('B');
            $table->string('jenis_rawat', 20);
            $table->decimal('tarif_total', 14, 2);
            $table->timestamps();
            $table->unique(['icd10_kode', 'kelas_rs', 'jenis_rawat']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('tipe', 50);
            $table->string('judul', 150);
            $table->text('pesan');
            $table->string('url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 80);
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'audit_logs',
            'notifications',
            'ina_cbg_tariffs',
            'ina_cbg_claims',
            'bpjs_sep_documents',
            'payments',
            'billing_details',
            'billing_invoices',
            'service_master',
            'radiology_results',
            'radiology_orders',
            'lab_results',
            'lab_order_panels',
            'lab_orders',
            'lab_panels',
            'inventory_transactions',
            'prescription_details',
            'prescriptions',
            'inventory_medicines',
            'medical_records',
            'nursing_assessments',
            'encounters',
            'icd10',
            'patients',
            'doctor_schedules',
            'doctors',
            'role_permissions',
            'user_roles',
            'permissions',
            'modules',
            'roles',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                foreach (['department_id'] as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropConstrainedForeignId($column);
                    }
                }

                foreach (['nip', 'nama_lengkap', 'foto_profil', 'no_telepon', 'is_active', 'last_login_at', 'last_login_ip'] as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('departments');
        Schema::enableForeignKeyConstraints();
    }
};
