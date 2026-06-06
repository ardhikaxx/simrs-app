# rule-simrs.md
# Rancang Bangun & Arsitektur Implementasi SIMRS Terintegrasi
# Sistem Informasi Manajemen Rumah Sakit — Berbasis Laravel 12

---

## 1. IDENTITAS PROYEK

| Atribut | Nilai |
|---|---|
| Nama Sistem | SIMRS — Sistem Informasi Manajemen Rumah Sakit Terintegrasi |
| Framework | Laravel 12 |
| PHP Version | 8.2+ |
| Database | MySQL 8.0+ / MariaDB 10.6+ |
| Front-End | Bootstrap 5 (CDN) |
| Alert System | SweetAlert2 (CDN) |
| Icons | Font Awesome 6 Free (CDN) |
| Standar Regulasi | PMK No. 82 Tahun 2013, PMK No. 24 Tahun 2022, UU No. 27 Tahun 2022 |
| Interoperabilitas | BPJS VClaim API, E-Claim INA-CBG, SATUSEHAT FHIR R4 |

---

## 2. STRUKTUR DIREKTORI PROYEK

```
simrs-app/
├── app/
│   ├── Console/
│   │   └── Kernel.php                          # Cron jobs (backup, auto-reminder, SLA checker)
│   ├── Events/
│   │   ├── PrescriptionCreated.php
│   │   ├── LabOrderCreated.php
│   │   ├── PatientDischargeRequested.php
│   │   └── ClaimThresholdExceeded.php          # INA-CBG early warning trigger
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── StaffLoginController.php    # Guard: staff
│   │   │   │   └── PatientLoginController.php  # Guard: patient (portal mandiri)
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserManagementController.php
│   │   │   │   ├── RolePermissionController.php
│   │   │   │   └── AuditTrailController.php
│   │   │   ├── Registration/
│   │   │   │   ├── PatientController.php
│   │   │   │   ├── EncounterController.php
│   │   │   │   └── QueueController.php
│   │   │   ├── Clinical/
│   │   │   │   ├── MedicalRecordController.php
│   │   │   │   ├── NursingAssessmentController.php
│   │   │   │   ├── DiagnosisController.php
│   │   │   │   └── ReferralController.php
│   │   │   ├── Pharmacy/
│   │   │   │   ├── PrescriptionController.php
│   │   │   │   ├── DispensaryController.php
│   │   │   │   └── InventoryController.php
│   │   │   ├── Laboratory/
│   │   │   │   ├── LabOrderController.php
│   │   │   │   └── LabResultController.php
│   │   │   ├── Radiology/
│   │   │   │   ├── RadiologyOrderController.php
│   │   │   │   └── RadiologyResultController.php
│   │   │   ├── Billing/
│   │   │   │   ├── InvoiceController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   └── CasemixController.php
│   │   │   ├── BPJS/
│   │   │   │   ├── VClaimController.php
│   │   │   │   ├── EClaimController.php
│   │   │   │   └── INACBGController.php
│   │   │   ├── SATUSEHAT/
│   │   │   │   ├── FHIRPatientController.php
│   │   │   │   ├── FHIREncounterController.php
│   │   │   │   └── FHIRMedicationController.php
│   │   │   ├── Report/
│   │   │   │   ├── ClinicalReportController.php
│   │   │   │   ├── FinancialReportController.php
│   │   │   │   └── BIAnalyticsController.php
│   │   │   └── Notification/
│   │   │       └── NotificationController.php
│   │   ├── Middleware/
│   │   │   ├── AuthenticateStaff.php           # Guard: staff session check
│   │   │   ├── AuthenticatePatient.php         # Guard: patient session check
│   │   │   ├── CheckRole.php                   # Role-based route protection
│   │   │   ├── CheckPermission.php             # Granular permission check
│   │   │   ├── AuditLogger.php                 # Log every authenticated request
│   │   │   └── CheckSessionTimeout.php         # Auto-logout idle session
│   │   └── Requests/
│   │       ├── StorePatientRequest.php
│   │       ├── StoreMedicalRecordRequest.php
│   │       ├── StorePrescriptionRequest.php
│   │       └── StoreLabOrderRequest.php
│   ├── Listeners/
│   │   ├── UpdateInventoryOnDispense.php
│   │   ├── InjectBillingComponentOnService.php
│   │   ├── SendLowStockNotification.php
│   │   └── TriggerSATUSEHATSync.php
│   ├── Models/
│   │   ├── User.php                            # Staff users
│   │   ├── Patient.php
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Module.php
│   │   ├── Encounter.php
│   │   ├── MedicalRecord.php
│   │   ├── NursingAssessment.php
│   │   ├── Prescription.php
│   │   ├── PrescriptionDetail.php
│   │   ├── InventoryMedicine.php
│   │   ├── InventoryTransaction.php
│   │   ├── LabOrder.php
│   │   ├── LabResult.php
│   │   ├── RadiologyOrder.php
│   │   ├── RadiologyResult.php
│   │   ├── BillingInvoice.php
│   │   ├── BillingDetail.php
│   │   ├── Payment.php
│   │   ├── AuditLog.php
│   │   ├── BPJSSepDocument.php
│   │   ├── ICD10.php
│   │   └── Department.php
│   ├── Observers/
│   │   ├── MedicalRecordObserver.php           # Audit trail on RME changes
│   │   ├── PrescriptionObserver.php
│   │   ├── LabResultObserver.php
│   │   └── BillingInvoiceObserver.php
│   └── Services/
│       ├── BPJSVClaimService.php               # VClaim REST API integration
│       ├── BPJSEClaimService.php               # E-Claim INA-CBG integration
│       ├── SATUSEHATService.php                # FHIR R4 SATUSEHAT integration
│       ├── FHIRResourceMapper.php              # DB → FHIR JSON converter
│       ├── INACBGCalculatorService.php         # Real-time cost vs tariff engine
│       ├── QueueService.php                    # Electronic queue management
│       ├── AuditTrailService.php
│       ├── EncryptionService.php               # AES-256 for API payloads
│       └── BarcodeService.php                 # Patient ID barcode generator
├── config/
│   ├── auth.php                                # Dual guards: staff, patient
│   ├── bpjs.php                               # BPJS API credentials & endpoints
│   └── satusehat.php                          # SATUSEHAT FHIR config
├── database/
│   ├── migrations/                            # 40+ migration files
│   └── seeders/
│       ├── RoleSeeder.php
│       ├── PermissionSeeder.php
│       ├── ICD10Seeder.php
│       ├── DepartmentSeeder.php
│       └── SuperAdminSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php                  # Main authenticated layout
│   │   │   └── auth.blade.php                 # Login / public layout
│   │   ├── auth/
│   │   │   └── login.blade.php
│   │   ├── admin/
│   │   ├── registration/
│   │   ├── clinical/
│   │   ├── pharmacy/
│   │   ├── laboratory/
│   │   ├── radiology/
│   │   ├── billing/
│   │   ├── bpjs/
│   │   └── reports/
│   └── lang/
│       └── id/                                # Full Indonesian localization
├── routes/
│   ├── web.php                                # Main web routes
│   ├── api.php                                # Internal API + FHIR endpoints
│   └── channels.php
└── storage/
    ├── app/
    │   ├── medical-documents/                 # Encrypted patient documents
    │   ├── lab-results/
    │   ├── radiology-images/
    │   └── exports/
    └── logs/
        └── audit/                             # Audit trail log files
```

---

## 3. KONFIGURASI AUTENTIKASI KUSTOM (DUAL GUARD)

### 3.1 config/auth.php

```php
<?php
return [
    'defaults' => [
        'guard'     => 'staff',
        'passwords' => 'staff',
    ],

    'guards' => [
        'staff' => [
            'driver'   => 'session',
            'provider' => 'staff',
        ],
        'patient' => [
            'driver'   => 'session',
            'provider' => 'patients',
        ],
    ],

    'providers' => [
        'staff' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],
        'patients' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Patient::class,
        ],
    ],

    'passwords' => [
        'staff' => [
            'provider' => 'staff',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800, // 3 jam
];
```

### 3.2 Middleware CheckRole

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        $user = Auth::guard('staff')->user();

        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
```

### 3.3 Middleware CheckPermission (Granular)

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action): mixed
    {
        $user = Auth::guard('staff')->user();

        if (!$user || !$user->hasPermission($module, $action)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Izin tidak mencukupi untuk operasi ini.'
                ], 403);
            }
            return back()->with('swal_error', 'Akses Ditolak: Anda tidak memiliki izin untuk ' . $action . ' pada modul ' . $module . '.');
        }

        return $next($request);
    }
}
```

### 3.4 Session Timeout Middleware

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    protected int $timeout = 1800; // 30 menit idle

    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::guard('staff')->check()) {
            $lastActivity = session('last_activity_time');

            if ($lastActivity && (time() - $lastActivity) > $this->timeout) {
                Auth::guard('staff')->logout();
                session()->invalidate();
                session()->regenerateToken();
                return redirect()->route('login')
                    ->with('swal_warning', 'Sesi otomatis diakhiri karena tidak aktif selama 30 menit.');
            }

            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
```

---

## 4. SKEMA BASIS DATA LENGKAP (40 TABEL)

### 4.1 Tabel Manajemen Pengguna & RBAC

```sql
-- Tabel staf/pengguna sistem
CREATE TABLE users (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nip             VARCHAR(20) UNIQUE NOT NULL,           -- Nomor Induk Pegawai
    nama_lengkap    VARCHAR(150) NOT NULL,
    email           VARCHAR(100) UNIQUE NOT NULL,
    password        VARCHAR(255) NOT NULL,
    foto_profil     VARCHAR(255) NULL,
    no_telepon      VARCHAR(20) NULL,
    department_id   BIGINT UNSIGNED NOT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    last_login_at   TIMESTAMP NULL,
    last_login_ip   VARCHAR(45) NULL,
    remember_token  VARCHAR(100) NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Tabel peran jabatan
CREATE TABLE roles (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_peran  VARCHAR(100) UNIQUE NOT NULL,    -- e.g. 'Dokter Spesialis', 'Perawat IGD'
    slug        VARCHAR(100) UNIQUE NOT NULL,    -- e.g. 'dokter-spesialis'
    deskripsi   TEXT NULL,
    level       TINYINT UNSIGNED DEFAULT 5,      -- 1=SuperAdmin, 2=SysAdmin, 3=Manager, 4=Staff, 5=Limited
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel modul aplikasi
CREATE TABLE modules (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_modul      VARCHAR(100) UNIQUE NOT NULL,
    slug            VARCHAR(100) UNIQUE NOT NULL,
    icon_class      VARCHAR(50) NULL,
    urutan_menu     TINYINT UNSIGNED DEFAULT 99,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel izin operasi
CREATE TABLE permissions (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_id       BIGINT UNSIGNED NOT NULL,
    action          ENUM('read','create','update','delete','export','approve') NOT NULL,
    deskripsi       VARCHAR(200) NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    UNIQUE KEY uq_module_action (module_id, action)
);

-- Relasi user ↔ role (many-to-many)
CREATE TABLE user_roles (
    user_id     BIGINT UNSIGNED NOT NULL,
    role_id     BIGINT UNSIGNED NOT NULL,
    assigned_by BIGINT UNSIGNED NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Relasi role ↔ permission (many-to-many)
CREATE TABLE role_permissions (
    role_id         BIGINT UNSIGNED NOT NULL,
    permission_id   BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
```

### 4.2 Tabel Departemen & Struktur Organisasi

```sql
CREATE TABLE departments (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_depart     VARCHAR(10) UNIQUE NOT NULL,
    nama_depart     VARCHAR(150) NOT NULL,
    jenis           ENUM('rawat_jalan','rawat_inap','igd','penunjang','administrasi','manajemen') NOT NULL,
    kepala_id       BIGINT UNSIGNED NULL,
    lantai          TINYINT UNSIGNED NULL,
    gedung          VARCHAR(50) NULL,
    telepon_ext     VARCHAR(10) NULL,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE doctors (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NOT NULL UNIQUE,
    no_str          VARCHAR(50) UNIQUE NOT NULL,    -- Nomor STR (Surat Tanda Registrasi)
    no_sip          VARCHAR(50) UNIQUE NOT NULL,    -- Nomor SIP (Surat Izin Praktik)
    spesialisasi    VARCHAR(150) NOT NULL,
    gelar_depan     VARCHAR(50) NULL,
    gelar_belakang  VARCHAR(100) NULL,
    tgl_str_exp     DATE NOT NULL,
    tgl_sip_exp     DATE NOT NULL,
    digital_sign    TEXT NULL,                      -- Kunci kriptografi tanda tangan
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE doctor_schedules (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id   BIGINT UNSIGNED NOT NULL,
    department_id BIGINT UNSIGNED NOT NULL,
    hari        TINYINT UNSIGNED NOT NULL,          -- 1=Senin..7=Minggu
    jam_mulai   TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    kuota       TINYINT UNSIGNED DEFAULT 20,
    is_active   TINYINT(1) DEFAULT 1,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (department_id) REFERENCES departments(id)
);
```

### 4.3 Tabel Pasien & Kunjungan

```sql
CREATE TABLE patients (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_rkm_medis    VARCHAR(10) UNIQUE NOT NULL,    -- Nomor RM unik seumur hidup (format: RM-XXXXXXXX)
    nik             VARCHAR(16) UNIQUE NOT NULL,    -- NIK KTP
    no_bpjs         VARCHAR(20) UNIQUE NULL,
    nama_pasien     VARCHAR(150) NOT NULL,
    nama_ibu_kandung VARCHAR(100) NOT NULL,
    tgl_lahir       DATE NOT NULL,
    jenis_kelamin   ENUM('L','P') NOT NULL,
    gol_darah       ENUM('A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-','tidak_diketahui') DEFAULT 'tidak_diketahui',
    agama           ENUM('islam','kristen','katolik','hindu','budha','konghucu','lainnya') NOT NULL,
    status_nikah    ENUM('belum_menikah','menikah','cerai','duda','janda') NOT NULL,
    pendidikan      ENUM('tidak_sekolah','sd','smp','sma','d3','s1','s2','s3','lainnya') NULL,
    pekerjaan       VARCHAR(100) NULL,
    alamat_lengkap  TEXT NOT NULL,
    rt              VARCHAR(5) NULL,
    rw              VARCHAR(5) NULL,
    kelurahan       VARCHAR(100) NULL,
    kecamatan       VARCHAR(100) NULL,
    kabupaten       VARCHAR(100) NULL,
    provinsi        VARCHAR(100) NULL,
    kode_pos        VARCHAR(10) NULL,
    no_telp_pasien  VARCHAR(20) NULL,
    no_telp_keluarga VARCHAR(20) NULL,
    nama_keluarga   VARCHAR(150) NULL,
    hubungan_keluarga VARCHAR(50) NULL,
    foto_pasien     VARCHAR(255) NULL,
    alergi_obat     TEXT NULL,                      -- JSON: [{nama, reaksi, tingkat_keparahan}]
    alergi_lainnya  TEXT NULL,
    catatan_khusus  TEXT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    created_by      BIGINT UNSIGNED NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE encounters (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_registrasi       VARCHAR(20) UNIQUE NOT NULL,    -- REG-YYYYMMDD-XXXXX
    patient_id          BIGINT UNSIGNED NOT NULL,
    doctor_id           BIGINT UNSIGNED NOT NULL,
    department_id       BIGINT UNSIGNED NOT NULL,
    no_antrian          VARCHAR(10) NOT NULL,
    tgl_registrasi      DATE NOT NULL,
    waktu_masuk         DATETIME NOT NULL,
    waktu_keluar        DATETIME NULL,
    jenis_kunjungan     ENUM('rawat_jalan','rawat_inap','igd','one_day_care') NOT NULL,
    jenis_pembayaran    ENUM('umum','bpjs','asuransi_swasta','gratis') NOT NULL,
    status_encounter    ENUM('menunggu','dipanggil','dalam_pemeriksaan','selesai_dokter','menunggu_farmasi','selesai_farmasi','menunggu_kasir','selesai','dibatalkan') DEFAULT 'menunggu',
    no_sep              VARCHAR(30) NULL,               -- Nomor SEP BPJS
    no_kartu_bpjs       VARCHAR(20) NULL,
    kelas_rawat         ENUM('1','2','3','vip','vvip') NULL,
    rujukan_dari        VARCHAR(200) NULL,
    no_surat_rujukan    VARCHAR(50) NULL,
    tgl_rujukan         DATE NULL,
    catatan_registrasi  TEXT NULL,
    created_by          BIGINT UNSIGNED NOT NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### 4.4 Tabel Rekam Medis Elektronik

```sql
CREATE TABLE medical_records (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    encounter_id        BIGINT UNSIGNED NOT NULL UNIQUE,
    -- Anamnesis Subjektif
    keluhan_utama       TEXT NOT NULL,
    riwayat_penyakit_sekarang TEXT NULL,
    riwayat_penyakit_dahulu   TEXT NULL,
    riwayat_keluarga    TEXT NULL,
    riwayat_alergi_aktual TEXT NULL,
    -- Pemeriksaan Objektif
    berat_badan         DECIMAL(5,2) NULL,          -- kg
    tinggi_badan        DECIMAL(5,2) NULL,          -- cm
    tekanan_darah_sistolik  SMALLINT UNSIGNED NULL,
    tekanan_darah_diastolik SMALLINT UNSIGNED NULL,
    nadi                TINYINT UNSIGNED NULL,      -- bpm
    suhu_tubuh          DECIMAL(4,1) NULL,          -- Celsius
    pernapasan          TINYINT UNSIGNED NULL,      -- rpm
    saturasi_oksigen    TINYINT UNSIGNED NULL,      -- %
    skala_nyeri         TINYINT UNSIGNED NULL,      -- 0-10
    gcs_e               TINYINT UNSIGNED NULL,
    gcs_v               TINYINT UNSIGNED NULL,
    gcs_m               TINYINT UNSIGNED NULL,
    pemeriksaan_fisik   TEXT NULL,
    -- Asesmen
    diagnosis_sementara TEXT NULL,
    diagnosis_kerja     TEXT NOT NULL,
    icd10_primer        VARCHAR(10) NOT NULL,
    icd10_sekunder      VARCHAR(500) NULL,          -- JSON array kode ICD-10
    derajat_keparahan   ENUM('ringan','sedang','berat','kritis') NULL,
    -- Rencana
    rencana_terapi      TEXT NOT NULL,
    instruksi_khusus    TEXT NULL,
    diet                VARCHAR(200) NULL,
    -- Outcome
    kondisi_saat_pulang ENUM('sembuh','membaik','tidak_ada_perubahan','memburuk','meninggal','dirujuk') NULL,
    instruksi_pulang    TEXT NULL,
    -- Meta
    is_locked           TINYINT(1) DEFAULT 0,       -- Dikunci setelah verifikasi
    locked_at           TIMESTAMP NULL,
    locked_by           BIGINT UNSIGNED NULL,
    verified_at         TIMESTAMP NULL,
    verified_by         BIGINT UNSIGNED NULL,
    created_by          BIGINT UNSIGNED NOT NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE nursing_assessments (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    encounter_id        BIGINT UNSIGNED NOT NULL,
    nurse_id            BIGINT UNSIGNED NOT NULL,
    waktu_pengkajian    DATETIME NOT NULL,
    -- Vital signs awal
    tekanan_darah       VARCHAR(20) NULL,           -- e.g. '120/80'
    nadi                TINYINT UNSIGNED NULL,
    suhu                DECIMAL(4,1) NULL,
    pernapasan          TINYINT UNSIGNED NULL,
    saturasi_o2         TINYINT UNSIGNED NULL,
    berat_badan         DECIMAL(5,2) NULL,
    tinggi_badan        DECIMAL(5,2) NULL,
    -- Triase IGD
    kategori_triase     ENUM('p1_merah','p2_kuning','p3_hijau','p4_hitam') NULL,
    -- Keluhan
    keluhan_subjektif   TEXT NOT NULL,
    skala_nyeri         TINYINT UNSIGNED NULL,
    lokasi_nyeri        VARCHAR(200) NULL,
    sifat_nyeri         VARCHAR(200) NULL,
    riwayat_alergi      TEXT NULL,
    kondisi_umum        ENUM('baik','sedang','lemah','kritis') NULL,
    tingkat_kesadaran   ENUM('compos_mentis','apatis','somnolen','stupor','koma') NULL,
    catatan_perawat     TEXT NULL,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id),
    FOREIGN KEY (nurse_id) REFERENCES users(id)
);
```

### 4.5 Tabel Farmasi & Inventaris

```sql
CREATE TABLE inventory_medicines (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_barang     VARCHAR(20) UNIQUE NOT NULL,
    nama_obat       VARCHAR(200) NOT NULL,
    nama_generik    VARCHAR(200) NULL,
    kode_bpjs       VARCHAR(20) NULL,              -- Kode formularium nasional
    kategori        ENUM('obat','alkes','bahan_habis_pakai','reagen') NOT NULL,
    golongan        ENUM('bebas','bebas_terbatas','keras','narkotika','psikotropika') DEFAULT 'bebas',
    sediaan         VARCHAR(100) NOT NULL,          -- e.g. 'Tablet 500mg', 'Ampul 2ml'
    satuan          VARCHAR(20) NOT NULL,           -- e.g. 'Tablet', 'Botol', 'Ampul'
    stok_sistem     INT DEFAULT 0,
    stok_minimum    INT DEFAULT 10,
    harga_beli      DECIMAL(15,2) NOT NULL,
    harga_jual_umum DECIMAL(15,2) NOT NULL,
    harga_jual_bpjs DECIMAL(15,2) NULL,
    tgl_kedaluwarsa DATE NULL,
    lokasi_simpan   VARCHAR(100) NULL,
    pabrik          VARCHAR(150) NULL,
    distributor     VARCHAR(150) NULL,
    is_formularium  TINYINT(1) DEFAULT 0,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE pharmacy_prescriptions (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_resep        VARCHAR(20) UNIQUE NOT NULL,    -- RX-YYYYMMDD-XXXXX
    encounter_id    BIGINT UNSIGNED NOT NULL,
    doctor_id       BIGINT UNSIGNED NOT NULL,
    waktu_resep     DATETIME NOT NULL,
    status          ENUM('menunggu_verifikasi','disetujui','sedang_disiapkan','siap_diserahkan','diserahkan','dibatalkan') DEFAULT 'menunggu_verifikasi',
    catatan_dokter  TEXT NULL,
    catatan_apoteker TEXT NULL,
    verified_by     BIGINT UNSIGNED NULL,
    verified_at     TIMESTAMP NULL,
    dispensed_by    BIGINT UNSIGNED NULL,
    dispensed_at    TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

CREATE TABLE prescription_details (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prescription_id BIGINT UNSIGNED NOT NULL,
    medicine_id     BIGINT UNSIGNED NOT NULL,
    jumlah          DECIMAL(10,2) NOT NULL,
    signa           VARCHAR(200) NOT NULL,          -- e.g. '3x1 sesudah makan'
    durasi_hari     TINYINT UNSIGNED NULL,
    catatan_khusus  VARCHAR(500) NULL,
    harga_satuan    DECIMAL(15,2) NOT NULL,
    subtotal        DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (prescription_id) REFERENCES pharmacy_prescriptions(id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES inventory_medicines(id)
);

CREATE TABLE inventory_transactions (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    medicine_id     BIGINT UNSIGNED NOT NULL,
    jenis           ENUM('masuk','keluar','retur','penyesuaian','expired_disposal') NOT NULL,
    jumlah          INT NOT NULL,
    stok_sebelum    INT NOT NULL,
    stok_sesudah    INT NOT NULL,
    referensi_id    BIGINT UNSIGNED NULL,           -- FK ke prescription_detail atau purchase_order
    referensi_tipe  VARCHAR(100) NULL,
    keterangan      TEXT NULL,
    created_by      BIGINT UNSIGNED NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES inventory_medicines(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### 4.6 Tabel Laboratorium & Radiologi

```sql
CREATE TABLE lab_panels (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_panel  VARCHAR(20) UNIQUE NOT NULL,
    nama_panel  VARCHAR(200) NOT NULL,
    kategori    VARCHAR(100) NOT NULL,
    harga       DECIMAL(15,2) NOT NULL,
    waktu_tunggu_menit SMALLINT UNSIGNED DEFAULT 60,
    is_active   TINYINT(1) DEFAULT 1
);

CREATE TABLE lab_orders (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_lab          VARCHAR(20) UNIQUE NOT NULL,    -- LAB-YYYYMMDD-XXXXX
    encounter_id    BIGINT UNSIGNED NOT NULL,
    doctor_id       BIGINT UNSIGNED NOT NULL,
    waktu_order     DATETIME NOT NULL,
    prioritas       ENUM('rutin','cito','emergensi') DEFAULT 'rutin',
    catatan_klinis  TEXT NULL,
    status          ENUM('diterima','pengambilan_sampel','dalam_analisis','selesai','dibatalkan') DEFAULT 'diterima',
    analyst_id      BIGINT UNSIGNED NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

CREATE TABLE lab_order_panels (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lab_order_id BIGINT UNSIGNED NOT NULL,
    panel_id    BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (lab_order_id) REFERENCES lab_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (panel_id) REFERENCES lab_panels(id)
);

CREATE TABLE lab_results (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lab_order_id    BIGINT UNSIGNED NOT NULL,
    panel_id        BIGINT UNSIGNED NOT NULL,
    nilai_hasil     VARCHAR(200) NOT NULL,
    satuan          VARCHAR(50) NULL,
    nilai_normal_min VARCHAR(50) NULL,
    nilai_normal_max VARCHAR(50) NULL,
    flag            ENUM('normal','rendah','tinggi','kritis_rendah','kritis_tinggi') DEFAULT 'normal',
    interpretasi    TEXT NULL,
    is_kritis       TINYINT(1) DEFAULT 0,           -- Nilai kritis wajib notifikasi dokter
    waktu_keluar    DATETIME NULL,
    verified_by     BIGINT UNSIGNED NULL,
    verified_at     TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lab_order_id) REFERENCES lab_orders(id),
    FOREIGN KEY (panel_id) REFERENCES lab_panels(id)
);

CREATE TABLE radiology_orders (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_radiologi    VARCHAR(20) UNIQUE NOT NULL,    -- RAD-YYYYMMDD-XXXXX
    encounter_id    BIGINT UNSIGNED NOT NULL,
    doctor_id       BIGINT UNSIGNED NOT NULL,
    jenis_pemeriksaan VARCHAR(200) NOT NULL,
    klinis_indikasi TEXT NULL,
    prioritas       ENUM('rutin','cito') DEFAULT 'rutin',
    status          ENUM('menunggu','dalam_proses','selesai','dibatalkan') DEFAULT 'menunggu',
    radiographer_id BIGINT UNSIGNED NULL,
    waktu_order     DATETIME NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id)
);

CREATE TABLE radiology_results (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    radiology_order_id BIGINT UNSIGNED NOT NULL,
    expertise_text  TEXT NULL,                     -- Interpretasi/expertise dokter radiologi
    file_path       VARCHAR(500) NULL,             -- Path file DICOM/JPEG
    format_file     ENUM('dicom','jpeg','png','pdf') NULL,
    radiologist_id  BIGINT UNSIGNED NULL,
    waktu_expertise DATETIME NULL,
    verified_at     TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (radiology_order_id) REFERENCES radiology_orders(id)
);
```

### 4.7 Tabel Billing & Keuangan

```sql
CREATE TABLE service_master (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_layanan    VARCHAR(20) UNIQUE NOT NULL,
    nama_layanan    VARCHAR(200) NOT NULL,
    kategori        ENUM('konsultasi','tindakan','rawat_inap','penunjang','operasi','lainnya') NOT NULL,
    tarif_umum      DECIMAL(15,2) NOT NULL,
    tarif_bpjs      DECIMAL(15,2) NULL,
    kode_ina_cbg    VARCHAR(20) NULL,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE billing_invoices (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_invoice      VARCHAR(25) UNIQUE NOT NULL,   -- INV-YYYYMMDD-XXXXX
    encounter_id    BIGINT UNSIGNED NOT NULL UNIQUE,
    total_tagihan   DECIMAL(15,2) DEFAULT 0,
    total_jasa_dokter DECIMAL(15,2) DEFAULT 0,
    total_obat      DECIMAL(15,2) DEFAULT 0,
    total_tindakan  DECIMAL(15,2) DEFAULT 0,
    total_penunjang DECIMAL(15,2) DEFAULT 0,
    total_kamar     DECIMAL(15,2) DEFAULT 0,
    total_lainnya   DECIMAL(15,2) DEFAULT 0,
    diskon_nominal  DECIMAL(15,2) DEFAULT 0,
    diskon_persen   DECIMAL(5,2) DEFAULT 0,
    total_dibayar   DECIMAL(15,2) DEFAULT 0,
    sisa_tagihan    DECIMAL(15,2) DEFAULT 0,
    -- INA-CBG Fields
    tarif_ina_cbg   DECIMAL(15,2) NULL,
    selisih_klaim   DECIMAL(15,2) NULL,            -- total_tagihan - tarif_ina_cbg
    persen_utilisasi DECIMAL(5,2) NULL,            -- % pemanfaatan dari ceiling INA-CBG
    status          ENUM('draft','final','lunas','dibatalkan','klaim_diajukan','klaim_disetujui') DEFAULT 'draft',
    catatan_kasir   TEXT NULL,
    created_by      BIGINT UNSIGNED NOT NULL,
    finalized_at    TIMESTAMP NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE billing_details (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_id      BIGINT UNSIGNED NOT NULL,
    kategori        ENUM('jasa_dokter','obat','tindakan','laboratorium','radiologi','kamar','lainnya') NOT NULL,
    nama_item       VARCHAR(200) NOT NULL,
    kode_item       VARCHAR(20) NULL,
    jumlah          DECIMAL(10,2) NOT NULL,
    satuan          VARCHAR(20) NOT NULL,
    harga_satuan    DECIMAL(15,2) NOT NULL,
    subtotal        DECIMAL(15,2) NOT NULL,
    referensi_id    BIGINT UNSIGNED NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES billing_invoices(id) ON DELETE CASCADE
);

CREATE TABLE payments (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    no_pembayaran   VARCHAR(25) UNIQUE NOT NULL,   -- PAY-YYYYMMDD-XXXXX
    invoice_id      BIGINT UNSIGNED NOT NULL,
    jumlah_bayar    DECIMAL(15,2) NOT NULL,
    metode_bayar    ENUM('tunai','transfer','debit','kredit','bpjs','asuransi','deposit') NOT NULL,
    no_referensi    VARCHAR(100) NULL,             -- Nomor transaksi bank
    kembalian       DECIMAL(15,2) DEFAULT 0,
    kasir_id        BIGINT UNSIGNED NOT NULL,
    waktu_bayar     DATETIME NOT NULL,
    keterangan      TEXT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES billing_invoices(id),
    FOREIGN KEY (kasir_id) REFERENCES users(id)
);
```

### 4.8 Tabel BPJS & Klaim

```sql
CREATE TABLE bpjs_sep_documents (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    encounter_id    BIGINT UNSIGNED NOT NULL,
    no_sep          VARCHAR(30) UNIQUE NOT NULL,
    no_kartu_bpjs   VARCHAR(20) NOT NULL,
    nama_peserta    VARCHAR(150) NOT NULL,
    tgl_sep         DATE NOT NULL,
    jenis_pelayanan VARCHAR(50) NOT NULL,
    poli_tujuan     VARCHAR(100) NULL,
    diagnosa_sep    VARCHAR(10) NULL,              -- ICD-10
    kelas_rawat     ENUM('1','2','3') NULL,
    dpjp            VARCHAR(150) NULL,
    pro_lanis       TINYINT(1) DEFAULT 0,
    flagprocedure   TINYINT(1) DEFAULT 0,
    raw_response    JSON NULL,                     -- Response mentah dari VClaim API
    created_by      BIGINT UNSIGNED NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id)
);

CREATE TABLE ina_cbg_claims (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    encounter_id        BIGINT UNSIGNED NOT NULL,
    no_sep              VARCHAR(30) NOT NULL,
    kode_ina_cbg        VARCHAR(20) NOT NULL,
    nama_grouper        VARCHAR(200) NULL,
    tarif_rs            DECIMAL(15,2) NULL,        -- Tarif rumah sakit
    tarif_klaim         DECIMAL(15,2) NULL,        -- Tarif INA-CBG dibayar
    status_klaim        ENUM('draft','pending','diajukan','disetujui','ditolak','pending_verifikasi') DEFAULT 'draft',
    catatan_verifikator TEXT NULL,
    tgl_pengajuan       DATE NULL,
    tgl_verifikasi      DATE NULL,
    coder_id            BIGINT UNSIGNED NULL,       -- Tim casemix
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (encounter_id) REFERENCES encounters(id)
);
```

### 4.9 Tabel Audit Trail & Log

```sql
CREATE TABLE audit_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT UNSIGNED NULL,
    user_name       VARCHAR(150) NULL,
    user_role       VARCHAR(100) NULL,
    action          ENUM('create','read','update','delete','login','logout','export','print','approve','reject') NOT NULL,
    model_type      VARCHAR(100) NOT NULL,
    model_id        BIGINT UNSIGNED NULL,
    tabel_terdampak VARCHAR(100) NULL,
    data_lama       JSON NULL,
    data_baru       JSON NULL,
    ip_address      VARCHAR(45) NULL,
    user_agent      VARCHAR(500) NULL,
    url_request     VARCHAR(500) NULL,
    method          VARCHAR(10) NULL,
    keterangan      TEXT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_model (model_type, model_id),
    INDEX idx_created (created_at)
);
```

---

## 5. MODEL ELOQUENT & RELASI

### 5.1 Model User (Staff)

```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guard = 'staff';

    protected $fillable = [
        'nip', 'nama_lengkap', 'email', 'password',
        'foto_profil', 'no_telepon', 'department_id', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password'  => 'hashed',
        'is_active' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasAnyRole(array $slugs): bool
    {
        return $this->roles()->whereIn('slug', $slugs)->exists();
    }

    public function hasPermission(string $moduleSlug, string $action): bool
    {
        if ($this->hasRole('super-administrator')) {
            return true;
        }

        return $this->roles()
            ->with(['permissions.module'])
            ->get()
            ->flatMap(fn($role) => $role->permissions)
            ->filter(fn($perm) => $perm->module->slug === $moduleSlug && $perm->action === $action)
            ->isNotEmpty();
    }
}
```

### 5.2 Model Patient

```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    protected $guard = 'patient';

    protected $fillable = [
        'no_rkm_medis', 'nik', 'no_bpjs', 'nama_pasien', 'nama_ibu_kandung',
        'tgl_lahir', 'jenis_kelamin', 'gol_darah', 'agama', 'status_nikah',
        'alamat_lengkap', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi',
        'no_telp_pasien', 'no_telp_keluarga', 'nama_keluarga', 'alergi_obat',
        'foto_pasien', 'catatan_khusus',
    ];

    protected $casts = [
        'tgl_lahir'      => 'date',
        'alergi_obat'    => 'array',
        'alergi_lainnya' => 'array',
        'is_active'      => 'boolean',
    ];

    public function encounters()
    {
        return $this->hasMany(Encounter::class);
    }

    public function latestEncounter()
    {
        return $this->hasOne(Encounter::class)->latestOfMany();
    }

    public function getUmurAttribute(): int
    {
        return $this->tgl_lahir->diffInYears(now());
    }

    // Auto-generate nomor RM
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patient) {
            $lastId = static::max('id') ?? 0;
            $patient->no_rkm_medis = 'RM-' . str_pad($lastId + 1, 8, '0', STR_PAD_LEFT);
        });
    }
}
```

### 5.3 Model MedicalRecord dengan Observer

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\MedicalRecordObserver;

class MedicalRecord extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::observe(MedicalRecordObserver::class);
    }

    protected $fillable = [
        'encounter_id', 'keluhan_utama', 'riwayat_penyakit_sekarang',
        'berat_badan', 'tinggi_badan', 'tekanan_darah_sistolik',
        'tekanan_darah_diastolik', 'nadi', 'suhu_tubuh', 'pernapasan',
        'saturasi_oksigen', 'skala_nyeri', 'pemeriksaan_fisik',
        'diagnosis_kerja', 'icd10_primer', 'icd10_sekunder',
        'rencana_terapi', 'kondisi_saat_pulang', 'instruksi_pulang',
        'is_locked', 'locked_at', 'locked_by',
    ];

    protected $casts = [
        'icd10_sekunder' => 'array',
        'is_locked'      => 'boolean',
        'locked_at'      => 'datetime',
    ];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function lockRecord(int $userId): bool
    {
        if ($this->is_locked) return false;

        return $this->update([
            'is_locked' => true,
            'locked_at' => now(),
            'locked_by' => $userId,
        ]);
    }
}
```

### 5.4 Observer Audit Trail

```php
<?php
namespace App\Observers;

use App\Models\MedicalRecord;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class MedicalRecordObserver
{
    public function created(MedicalRecord $record): void
    {
        $this->log('create', $record, null, $record->toArray());
    }

    public function updated(MedicalRecord $record): void
    {
        if ($record->getOriginal('is_locked')) {
            throw new \RuntimeException('Rekam medis yang sudah dikunci tidak dapat diubah.');
        }
        $this->log('update', $record, $record->getOriginal(), $record->getDirty());
    }

    public function deleted(MedicalRecord $record): void
    {
        $this->log('delete', $record, $record->toArray(), null);
    }

    private function log(string $action, MedicalRecord $record, ?array $old, ?array $new): void
    {
        $user = Auth::guard('staff')->user();

        AuditLog::create([
            'user_id'         => $user?->id,
            'user_name'       => $user?->nama_lengkap,
            'user_role'       => $user?->roles->first()?->nama_peran,
            'action'          => $action,
            'model_type'      => 'MedicalRecord',
            'model_id'        => $record->id,
            'tabel_terdampak' => 'medical_records',
            'data_lama'       => $old ? json_encode($old) : null,
            'data_baru'       => $new ? json_encode($new) : null,
            'ip_address'      => Request::ip(),
            'user_agent'      => Request::userAgent(),
            'url_request'     => Request::fullUrl(),
            'method'          => Request::method(),
        ]);
    }
}
```

---

## 6. ROUTING LENGKAP

```php
<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Admin\{DashboardController, UserManagementController, RolePermissionController, AuditTrailController};
use App\Http\Controllers\Registration\{PatientController, EncounterController, QueueController};
use App\Http\Controllers\Clinical\{MedicalRecordController, NursingAssessmentController, DiagnosisController};
use App\Http\Controllers\Pharmacy\{PrescriptionController, DispensaryController, InventoryController};
use App\Http\Controllers\Laboratory\{LabOrderController, LabResultController};
use App\Http\Controllers\Radiology\{RadiologyOrderController, RadiologyResultController};
use App\Http\Controllers\Billing\{InvoiceController, PaymentController, CasemixController};
use App\Http\Controllers\BPJS\{VClaimController, EClaimController};
use App\Http\Controllers\Report\{ClinicalReportController, FinancialReportController};

// ============================================================
// PUBLIC — AUTH ROUTES
// ============================================================
Route::middleware('guest:staff')->group(function () {
    Route::get('/login', [StaffLoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [StaffLoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [StaffLoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth:staff');

// ============================================================
// PROTECTED — STAFF ROUTES
// ============================================================
Route::middleware(['auth:staff', 'check.session.timeout', 'audit.logger'])->group(function () {

    // ─── DASHBOARD ──────────────────────────────────────────
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ─── ADMIN PANEL ────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('role:super-administrator,system-administrator')->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::resource('roles', RolePermissionController::class);
        Route::get('audit-trail', [AuditTrailController::class, 'index'])->name('audit.index');
        Route::get('audit-trail/{id}', [AuditTrailController::class, 'show'])->name('audit.show');
        Route::get('audit-trail/export', [AuditTrailController::class, 'export'])->name('audit.export');
    });

    // ─── PENDAFTARAN ────────────────────────────────────────
    Route::prefix('pendaftaran')->name('pendaftaran.')->middleware('role:super-administrator,system-administrator,petugas-pendaftaran')->group(function () {
        Route::resource('pasien', PatientController::class);
        Route::get('pasien/search', [PatientController::class, 'search'])->name('pasien.search');
        Route::get('pasien/{id}/riwayat', [PatientController::class, 'history'])->name('pasien.history');
        Route::resource('kunjungan', EncounterController::class);
        Route::post('kunjungan/{id}/cancel', [EncounterController::class, 'cancel'])->name('kunjungan.cancel');
        Route::get('antrian', [QueueController::class, 'index'])->name('antrian.index');
        Route::post('antrian/{id}/panggil', [QueueController::class, 'call'])->name('antrian.call');
    });

    // ─── KEPERAWATAN ────────────────────────────────────────
    Route::prefix('keperawatan')->name('keperawatan.')->middleware('role:super-administrator,perawat-triase,perawat-poliklinik,perawat-rawat-inap')->group(function () {
        Route::get('antrian', [NursingAssessmentController::class, 'queue'])->name('antrian');
        Route::get('pengkajian/{encounter_id}', [NursingAssessmentController::class, 'create'])->name('pengkajian.create');
        Route::post('pengkajian/{encounter_id}', [NursingAssessmentController::class, 'store'])->name('pengkajian.store');
        Route::get('pengkajian/{id}/edit', [NursingAssessmentController::class, 'edit'])->name('pengkajian.edit');
        Route::put('pengkajian/{id}', [NursingAssessmentController::class, 'update'])->name('pengkajian.update');
    });

    // ─── REKAM MEDIS (DOKTER) ───────────────────────────────
    Route::prefix('rekam-medis')->name('rekam-medis.')->middleware('role:super-administrator,dokter-umum,dokter-spesialis')->group(function () {
        Route::get('antrian', [MedicalRecordController::class, 'queue'])->name('antrian');
        Route::get('{encounter_id}', [MedicalRecordController::class, 'show'])->name('show');
        Route::post('{encounter_id}', [MedicalRecordController::class, 'store'])->name('store');
        Route::put('{id}', [MedicalRecordController::class, 'update'])->name('update');
        Route::post('{id}/lock', [MedicalRecordController::class, 'lock'])->name('lock');
        Route::post('{encounter_id}/rujuk', [DiagnosisController::class, 'createReferral'])->name('rujuk');
    });

    // ─── FARMASI ────────────────────────────────────────────
    Route::prefix('farmasi')->name('farmasi.')->group(function () {
        Route::middleware('role:super-administrator,apoteker,asisten-apoteker')->group(function () {
            Route::get('antrian-resep', [DispensaryController::class, 'queue'])->name('antrian-resep');
            Route::get('resep/{id}', [DispensaryController::class, 'show'])->name('resep.show');
            Route::post('resep/{id}/verifikasi', [DispensaryController::class, 'verify'])->name('resep.verify');
            Route::post('resep/{id}/serahkan', [DispensaryController::class, 'dispense'])->name('resep.dispense');
        });
        Route::middleware('role:super-administrator,apoteker')->group(function () {
            Route::resource('inventaris', InventoryController::class);
            Route::get('inventaris/stok-rendah', [InventoryController::class, 'lowStock'])->name('inventaris.low-stock');
            Route::post('inventaris/{id}/penyesuaian', [InventoryController::class, 'adjust'])->name('inventaris.adjust');
        });
    });

    // ─── LABORATORIUM ───────────────────────────────────────
    Route::prefix('laboratorium')->name('lab.')->middleware('role:super-administrator,analis-laboratorium')->group(function () {
        Route::get('antrian', [LabOrderController::class, 'queue'])->name('antrian');
        Route::get('order/{id}', [LabOrderController::class, 'show'])->name('order.show');
        Route::post('order/{id}/proses', [LabOrderController::class, 'process'])->name('order.process');
        Route::post('hasil/{lab_order_id}', [LabResultController::class, 'store'])->name('hasil.store');
        Route::put('hasil/{id}', [LabResultController::class, 'update'])->name('hasil.update');
        Route::post('hasil/{id}/verifikasi', [LabResultController::class, 'verify'])->name('hasil.verify');
        Route::get('hasil/{id}/cetak', [LabResultController::class, 'print'])->name('hasil.print');
    });

    // ─── RADIOLOGI ──────────────────────────────────────────
    Route::prefix('radiologi')->name('rad.')->middleware('role:super-administrator,radiografer,dokter-radiologi')->group(function () {
        Route::get('antrian', [RadiologyOrderController::class, 'queue'])->name('antrian');
        Route::post('order/{id}/proses', [RadiologyOrderController::class, 'process'])->name('order.process');
        Route::post('hasil/{order_id}', [RadiologyResultController::class, 'store'])->name('hasil.store');
        Route::put('hasil/{id}', [RadiologyResultController::class, 'update'])->name('hasil.update');
    });

    // ─── BILLING & KASIR ────────────────────────────────────
    Route::prefix('keuangan')->name('keuangan.')->middleware('role:super-administrator,kasir,admin-keuangan')->group(function () {
        Route::get('antrian-kasir', [InvoiceController::class, 'queue'])->name('antrian-kasir');
        Route::get('invoice/{encounter_id}', [InvoiceController::class, 'show'])->name('invoice.show');
        Route::post('invoice/{encounter_id}/finalize', [InvoiceController::class, 'finalize'])->name('invoice.finalize');
        Route::post('bayar/{invoice_id}', [PaymentController::class, 'store'])->name('bayar.store');
        Route::get('kwitansi/{payment_id}', [PaymentController::class, 'receipt'])->name('kwitansi');
        Route::get('laporan/harian', [FinancialReportController::class, 'daily'])->name('laporan.harian');
        Route::get('laporan/bulanan', [FinancialReportController::class, 'monthly'])->name('laporan.bulanan');
    });

    // ─── CASEMIX ────────────────────────────────────────────
    Route::prefix('casemix')->name('casemix.')->middleware('role:super-administrator,tim-casemix')->group(function () {
        Route::get('/', [CasemixController::class, 'index'])->name('index');
        Route::get('klaim/{encounter_id}', [CasemixController::class, 'show'])->name('show');
        Route::post('klaim/{encounter_id}/coding', [CasemixController::class, 'coding'])->name('coding');
        Route::post('klaim/{id}/ajukan', [CasemixController::class, 'submit'])->name('submit');
    });

    // ─── BPJS INTEGRATION ───────────────────────────────────
    Route::prefix('bpjs')->name('bpjs.')->middleware('role:super-administrator,petugas-pendaftaran,tim-casemix')->group(function () {
        Route::post('vclaim/sep', [VClaimController::class, 'createSEP'])->name('sep.create');
        Route::get('vclaim/cek-peserta/{no_kartu}', [VClaimController::class, 'checkPeserta'])->name('peserta.cek');
        Route::get('vclaim/rujukan/{no_kartu}', [VClaimController::class, 'getRujukan'])->name('rujukan.get');
        Route::get('eclaim/simulasi/{encounter_id}', [EClaimController::class, 'simulate'])->name('eclaim.simulate');
        Route::post('eclaim/ajukan/{encounter_id}', [EClaimController::class, 'submit'])->name('eclaim.submit');
    });

    // ─── LAPORAN ────────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->middleware('role:super-administrator,admin-keuangan,dokter-umum,dokter-spesialis')->group(function () {
        Route::get('morbiditas', [ClinicalReportController::class, 'morbidity'])->name('morbiditas');
        Route::get('kunjungan', [ClinicalReportController::class, 'visits'])->name('kunjungan');
        Route::get('pendapatan', [FinancialReportController::class, 'revenue'])->name('pendapatan');
        Route::get('export/{type}', [ClinicalReportController::class, 'export'])->name('export');
    });
});
```

---

## 7. INTEGRASI BPJS VCLAIM & E-CLAIM

### 7.1 config/bpjs.php

```php
<?php
return [
    'vclaim' => [
        'base_url'     => env('BPJS_VCLAIM_URL', 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest'),
        'sandbox_url'  => env('BPJS_VCLAIM_SANDBOX_URL', 'https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev'),
        'cons_id'      => env('BPJS_CONS_ID'),
        'secret_key'   => env('BPJS_SECRET_KEY'),
        'user_key'     => env('BPJS_USER_KEY'),
        'is_sandbox'   => env('BPJS_SANDBOX', true),
    ],
    'eclaim' => [
        'base_url'     => env('BPJS_ECLAIM_URL', 'https://apijkn.bpjs-kesehatan.go.id/eclaim'),
        'kode_rs'      => env('BPJS_KODE_RS'),
    ],
];
```

### 7.2 BPJSVClaimService

```php
<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BPJSVClaimService
{
    protected string $baseUrl;
    protected string $consId;
    protected string $secretKey;
    protected string $userKey;

    public function __construct()
    {
        $cfg = config('bpjs.vclaim');
        $this->baseUrl   = $cfg['is_sandbox'] ? $cfg['sandbox_url'] : $cfg['base_url'];
        $this->consId    = $cfg['cons_id'];
        $this->secretKey = $cfg['secret_key'];
        $this->userKey   = $cfg['user_key'];
    }

    protected function buildHeaders(): array
    {
        $timestamp = Carbon::now()->timestamp;
        $signature = base64_encode(hash_hmac('sha256', $this->consId . '&' . $timestamp, $this->secretKey, true));

        return [
            'X-cons-id'  => $this->consId,
            'X-timestamp'=> (string) $timestamp,
            'X-signature'=> $signature,
            'user_key'   => $this->userKey,
            'Content-Type' => 'application/json',
        ];
    }

    public function cekPeserta(string $noKartu, string $tanggal): array
    {
        $response = Http::withHeaders($this->buildHeaders())
            ->get("{$this->baseUrl}/peserta/nokartu/{$noKartu}/tglSEP/{$tanggal}");

        return $response->json();
    }

    public function buatSEP(array $data): array
    {
        $payload = [
            't_sep' => [
                'noKartu'      => $data['no_kartu_bpjs'],
                'tglSep'       => $data['tgl_sep'],
                'ppkPelayanan' => config('bpjs.vclaim.kode_rs'),
                'jnsPelayanan' => $data['jenis_pelayanan'], // 1=RJ, 2=RI
                'poli'         => $data['kode_poli'],
                'klsRawat'     => $data['kelas_rawat'],
                'diagAwal'     => $data['diagnosis_awal'],
                'dpjp'         => $data['kode_dokter_dpjp'],
                'noMR'         => $data['no_rkm_medis'],
                'user'         => auth('staff')->user()->nip,
            ],
        ];

        $response = Http::withHeaders($this->buildHeaders())
            ->post("{$this->baseUrl}/sep/2.0/insert", $payload);

        return $response->json();
    }

    public function getRujukan(string $noKartu, string $asal): array
    {
        $response = Http::withHeaders($this->buildHeaders())
            ->get("{$this->baseUrl}/rujukan/nokartu/{$noKartu}/asal/{$asal}");

        return $response->json();
    }
}
```

### 7.3 INACBGCalculatorService (Early Warning System)

```php
<?php
namespace App\Services;

use App\Models\BillingInvoice;
use App\Models\Encounter;

class INACBGCalculatorService
{
    protected float $warningThreshold = 0.80;  // 80% dari ceiling
    protected float $criticalThreshold = 0.95; // 95% dari ceiling

    /**
     * Hitung utilisasi biaya terhadap tarif INA-CBG secara real-time.
     */
    public function calculateUtilization(int $encounterId): array
    {
        $encounter = Encounter::with([
            'medicalRecord',
            'billingInvoice.billingDetails',
            'prescriptions.details',
            'labOrders',
            'radiologyOrders',
        ])->findOrFail($encounterId);

        $totalRiil = $encounter->billingInvoice?->total_tagihan ?? 0;
        $tarifINACBG = $this->getTarifINACBG($encounter->medicalRecord?->icd10_primer);

        if (!$tarifINACBG || $tarifINACBG == 0) {
            return ['status' => 'no_tarif', 'message' => 'Tarif INA-CBG tidak ditemukan untuk diagnosis ini.'];
        }

        $persen = ($totalRiil / $tarifINACBG) * 100;

        $status = match(true) {
            $persen >= $this->criticalThreshold * 100 => 'kritis',
            $persen >= $this->warningThreshold * 100  => 'peringatan',
            default                                    => 'aman',
        };

        return [
            'status'        => $status,
            'total_riil'    => $totalRiil,
            'tarif_ina_cbg' => $tarifINACBG,
            'selisih'       => $tarifINACBG - $totalRiil,
            'persen'        => round($persen, 2),
            'icd10'         => $encounter->medicalRecord?->icd10_primer,
            'pesan'         => $this->buildWarningMessage($status, $persen, $tarifINACBG - $totalRiil),
        ];
    }

    protected function getTarifINACBG(string $icd10Code): ?float
    {
        // Query tabel tarif INA-CBG (import dari Kemkes)
        return \DB::table('ina_cbg_tariffs')
            ->where('icd10_kode', $icd10Code)
            ->where('kelas_rs', config('simrs.kelas_rs', 'B'))
            ->value('tarif_total');
    }

    protected function buildWarningMessage(string $status, float $persen, float $selisih): string
    {
        return match($status) {
            'kritis'    => "⚠️ KRITIS: Biaya riil telah mencapai {$persen}% dari ceiling INA-CBG. Sisa anggaran: Rp " . number_format(abs($selisih), 0, ',', '.'),
            'peringatan'=> "⚡ PERINGATAN: Biaya riil {$persen}% dari ceiling. Pertimbangkan efisiensi tindakan selanjutnya.",
            default     => "✅ Biaya masih dalam batas aman ({$persen}% dari ceiling INA-CBG).",
        };
    }
}
```

---

## 8. INTEGRASI SATUSEHAT FHIR R4

### 8.1 FHIRResourceMapper

```php
<?php
namespace App\Services;

use App\Models\Patient;
use App\Models\Encounter;
use App\Models\MedicalRecord;

class FHIRResourceMapper
{
    /**
     * Map data pasien lokal ke FHIR Patient Resource.
     */
    public function mapPatient(Patient $patient): array
    {
        return [
            'resourceType' => 'Patient',
            'id'           => $patient->no_rkm_medis,
            'identifier'   => [
                [
                    'system' => 'https://fhir.kemkes.go.id/id/nik',
                    'value'  => $patient->nik,
                ],
                [
                    'system' => 'https://fhir.kemkes.go.id/id/ihs-number',
                    'value'  => $patient->no_bpjs ?? '',
                ],
            ],
            'name' => [
                [
                    'use'  => 'official',
                    'text' => $patient->nama_pasien,
                ],
            ],
            'birthDate'    => $patient->tgl_lahir->format('Y-m-d'),
            'gender'       => $patient->jenis_kelamin === 'L' ? 'male' : 'female',
            'address'      => [
                [
                    'use'     => 'home',
                    'text'    => $patient->alamat_lengkap,
                    'city'    => $patient->kecamatan,
                    'state'   => $patient->provinsi,
                    'country' => 'ID',
                ],
            ],
            'telecom' => $patient->no_telp_pasien ? [
                ['system' => 'phone', 'value' => $patient->no_telp_pasien, 'use' => 'mobile'],
            ] : [],
        ];
    }

    /**
     * Map encounter ke FHIR Encounter Resource.
     */
    public function mapEncounter(Encounter $encounter): array
    {
        return [
            'resourceType' => 'Encounter',
            'id'           => $encounter->no_registrasi,
            'status'       => $encounter->status_encounter === 'selesai' ? 'finished' : 'in-progress',
            'class'        => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code'   => $encounter->jenis_kunjungan === 'rawat_inap' ? 'IMP' : 'AMB',
            ],
            'subject' => [
                'reference' => 'Patient/' . $encounter->patient->no_rkm_medis,
                'display'   => $encounter->patient->nama_pasien,
            ],
            'participant' => [
                [
                    'individual' => [
                        'reference' => 'Practitioner/' . $encounter->doctor->user->nip,
                        'display'   => $encounter->doctor->user->nama_lengkap,
                    ],
                ],
            ],
            'period' => [
                'start' => $encounter->waktu_masuk->toIso8601String(),
                'end'   => $encounter->waktu_keluar?->toIso8601String(),
            ],
            'serviceProvider' => [
                'reference' => 'Organization/' . config('satusehat.organization_id'),
            ],
        ];
    }

    /**
     * Map diagnosis ke FHIR Condition Resource.
     */
    public function mapCondition(MedicalRecord $record): array
    {
        return [
            'resourceType'      => 'Condition',
            'clinicalStatus'    => [
                'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical', 'code' => 'active']],
            ],
            'code' => [
                'coding' => [
                    [
                        'system'  => 'http://hl7.org/fhir/sid/icd-10',
                        'code'    => $record->icd10_primer,
                        'display' => $record->diagnosis_kerja,
                    ],
                ],
            ],
            'subject'  => ['reference' => 'Patient/' . $record->encounter->patient->no_rkm_medis],
            'encounter'=> ['reference' => 'Encounter/' . $record->encounter->no_registrasi],
        ];
    }
}
```

---

## 9. KONVENSI PENOMORAN DOKUMEN

| Prefix | Modul | Format | Contoh |
|--------|-------|--------|--------|
| `RM` | Nomor Rekam Medis | `RM-XXXXXXXX` | `RM-00012345` |
| `REG` | Registrasi/Encounter | `REG-YYYYMMDD-XXXXX` | `REG-20250601-00042` |
| `RX` | Resep | `RX-YYYYMMDD-XXXXX` | `RX-20250601-00015` |
| `LAB` | Order Laboratorium | `LAB-YYYYMMDD-XXXXX` | `LAB-20250601-00008` |
| `RAD` | Order Radiologi | `RAD-YYYYMMDD-XXXXX` | `RAD-20250601-00003` |
| `INV` | Invoice/Tagihan | `INV-YYYYMMDD-XXXXX` | `INV-20250601-00030` |
| `PAY` | Pembayaran | `PAY-YYYYMMDD-XXXXX` | `PAY-20250601-00025` |

---

## 10. VALIDASI REQUEST

```php
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check() &&
               auth('staff')->user()->hasAnyRole(['dokter-umum', 'dokter-spesialis', 'super-administrator']);
    }

    public function rules(): array
    {
        return [
            'keluhan_utama'              => 'required|string|min:5|max:1000',
            'tekanan_darah_sistolik'     => 'nullable|integer|between:50,300',
            'tekanan_darah_diastolik'    => 'nullable|integer|between:30,200',
            'nadi'                       => 'nullable|integer|between:20,300',
            'suhu_tubuh'                 => 'nullable|numeric|between:30,45',
            'pernapasan'                 => 'nullable|integer|between:5,80',
            'saturasi_oksigen'           => 'nullable|integer|between:0,100',
            'skala_nyeri'                => 'nullable|integer|between:0,10',
            'berat_badan'                => 'nullable|numeric|between:0.5,500',
            'tinggi_badan'               => 'nullable|numeric|between:20,300',
            'diagnosis_kerja'            => 'required|string|min:3|max:500',
            'icd10_primer'               => 'required|string|regex:/^[A-Z][0-9]{2}(\.[0-9]{1,2})?$/|exists:icd10,kode',
            'icd10_sekunder'             => 'nullable|array|max:10',
            'icd10_sekunder.*'           => 'string|regex:/^[A-Z][0-9]{2}(\.[0-9]{1,2})?$/|exists:icd10,kode',
            'rencana_terapi'             => 'required|string|min:5|max:2000',
            'kondisi_saat_pulang'        => 'nullable|in:sembuh,membaik,tidak_ada_perubahan,memburuk,meninggal,dirujuk',
        ];
    }

    public function messages(): array
    {
        return [
            'icd10_primer.regex'  => 'Format kode ICD-10 tidak valid. Contoh: A09, J18.0',
            'icd10_primer.exists' => 'Kode ICD-10 tidak ditemukan dalam direktori.',
        ];
    }
}
```

---

## 11. CRON JOBS & SCHEDULED TASKS

```php
<?php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule): void
{
    // Backup database harian pukul 02:00
    $schedule->command('db:backup')->dailyAt('02:00')->withoutOverlapping();

    // Cek STR/SIP dokter mendekati kedaluwarsa (H-30, H-7, H-1)
    $schedule->command('simrs:check-license-expiry')->daily()->withoutOverlapping();

    // Cek stok obat minimum setiap 4 jam
    $schedule->command('simrs:check-low-stock')->everyFourHours()->withoutOverlapping();

    // Cek obat mendekati kedaluwarsa (30 hari ke depan)
    $schedule->command('simrs:check-medicine-expiry')->weeklyOn(1, '08:00')->withoutOverlapping();

    // Generate laporan keuangan harian otomatis pukul 23:59
    $schedule->command('simrs:generate-daily-report')->dailyAt('23:59')->withoutOverlapping();

    // Sync data SATUSEHAT untuk encounter yang selesai kemarin
    $schedule->command('simrs:sync-satusehat')->dailyAt('01:00')->withoutOverlapping();

    // Bersihkan session yang kedaluwarsa
    $schedule->command('session:gc')->hourly()->withoutOverlapping();

    // Rotasi log audit trail (kompres log > 30 hari)
    $schedule->command('simrs:rotate-audit-logs')->monthly()->withoutOverlapping();
}
```

---

## 12. MATRIKS HAK AKSES LENGKAP

| Modul | Super Admin | Sys Admin | DB Admin | Pendaftaran | Perawat | Dokter | Apoteker | Analis Lab | Radiografer | Kasir | Casemix |
|-------|:-----------:|:---------:|:--------:|:-----------:|:-------:|:------:|:--------:|:----------:|:-----------:|:-----:|:-------:|
| Manajemen User | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Pendaftaran Pasien | ✅ | ❌ | ❌ | ✅ | R | R | ❌ | ❌ | ❌ | ❌ | R |
| Asuhan Keperawatan | ✅ | ❌ | ❌ | ❌ | ✅ | R | ❌ | ❌ | ❌ | ❌ | R |
| Rekam Medis Dokter | ✅ | ❌ | ❌ | ❌ | R | ✅ | ❌ | ❌ | ❌ | ❌ | R |
| Resep Elektronik | ✅ | ❌ | ❌ | ❌ | R | ✅ | R | ❌ | ❌ | ❌ | R |
| Dispensing Farmasi | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Inventaris Obat | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| Order Lab | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | R | ❌ | ❌ | ❌ |
| Hasil Lab | ✅ | ❌ | ❌ | ❌ | R | R | ❌ | ✅ | ❌ | ❌ | R |
| Order Radiologi | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | R | ❌ | ❌ |
| Hasil Radiologi | ✅ | ❌ | ❌ | ❌ | R | R | ❌ | ❌ | ✅ | ❌ | R |
| Billing/Invoice | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | R |
| Kasir/Pembayaran | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Casemix/Coding | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| BPJS VClaim | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| Audit Trail | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Laporan Keuangan | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ |
| Laporan Klinis | ✅ | ❌ | ❌ | ❌ | ❌ | R | ❌ | ❌ | ❌ | ❌ | ✅ |
| Konfigurasi Sistem | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |

> **Keterangan:** ✅ = Akses penuh (CRUD) | R = Read only | ❌ = Tidak ada akses

---

## 13. ATURAN BISNIS & SLA

| Proses | SLA Target | Trigger | Eskalasi |
|--------|-----------|---------|---------|
| Verifikasi SEP BPJS | < 30 detik | Saat pendaftaran BPJS | Timeout: fallback manual |
| Verifikasi resep apoteker | < 15 menit | Resep masuk dari dokter | > 15 menit: notif kepala farmasi |
| Penyelesaian hasil lab rutin | < 2 jam | Order diterima | > 2 jam: notif dokter DPJP |
| Hasil lab CITO | < 30 menit | Order CITO | > 30 menit: notif dokter + kepala lab |
| Nilai kritis lab | < 5 menit | Hasil kritis terdeteksi | Notif langsung via push + SMS |
| Expertisi radiologi | < 3 jam | Order selesai diproses | > 3 jam: notif dokter DPJP |
| Billing final pasien pulang | < 1 jam | Dokter TTD pulang | > 1 jam: notif supervisor kasir |
| Sync SATUSEHAT | < 24 jam | Pasien pulang (selesai) | Gagal: antrian retry 3x |
| Backup database | Harian 02:00 | Terjadwal (cron) | Gagal: email sysadmin |

---

## 14. KEAMANAN & ENKRIPSI

- **Password Hashing**: `bcrypt` dengan cost factor 12
- **Enkripsi data sensitif** (NIK, diagnosis, foto): AES-256-GCM via `App\Services\EncryptionService`
- **API BPJS Payload**: HMAC-SHA256 signature per request
- **HTTPS**: Wajib di production (enforce `APP_ENV=production` → HSTS header)
- **CSRF Protection**: `@csrf` di semua form (default Laravel)
- **SQL Injection**: Eloquent ORM + prepared statements (tidak ada raw query tanpa binding)
- **XSS**: Blade `{{ }}` auto-escape (tidak menggunakan `{!! !!}` untuk data user)
- **File Upload**: Validasi MIME + ekstensi + ukuran, simpan di luar `public/`
- **Rate Limiting**: Login: 5 percobaan/menit per IP, API: 60 req/menit
- **Session**: Driver `database`, timeout 30 menit, regenerate token setelah login
- **Audit Trail**: Setiap operasi CRUD pada tabel vital tercatat otomatis via Observer

---

*rule-simrs.md — SIMRS Laravel 12 | Versi 1.0 | Dibuat untuk implementasi rumah sakit kelas B/C Indonesia*
