<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Bed;
use App\Models\BillingInvoice;
use App\Models\BPJSSepDocument;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Encounter;
use App\Models\ICD10;
use App\Models\ICD9;
use App\Models\INACBGClaim;
use App\Models\INACBGTariff;
use App\Models\InventoryBhp;
use App\Models\InventoryMedicine;
use App\Models\InventoryTransaction;
use App\Models\LabOrder;
use App\Models\LabPanel;
use App\Models\MedicalRecord;
use App\Models\Module;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Prescription;
use App\Models\RadiologyOrder;
use App\Models\Role;
use App\Models\ServiceMaster;
use App\Models\User;
use App\Services\BillingService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Model::unguarded(function (): void {
            $roles = $this->seedRoles();
            $modules = $this->seedModulesAndPermissions($roles);
            $departments = $this->seedDepartments();
            $users = $this->seedUsers($roles, $departments);
            $this->assignDepartmentHeads($departments, $users);
            $this->seedClinicalReferences($users, $departments);
            $this->seedIcd9Master();
            $this->seedBedAndBhp($departments);
            $icd10 = $this->seedIcd10AndTariffs();
            $medicines = $this->seedMedicines($users);
            $patients = $this->seedPatients();
            $this->seedOperationalData($patients, $users, $departments, $icd10, $medicines);
            $this->seedClaims();
            $this->seedSystemLogs($users, $modules);
        });
    }

    private function seedIcd9Master(): void
    {
        $rows = collect([
            ['89.03', 'Interview and evaluation, described as comprehensive'],
            ['89.07', 'Consultation, described as limited'],
            ['93.01', 'Functional evaluation'],
            ['99.18', 'Injection or infusion of electrolytes'],
            ['96.07', 'Insertion of nasogastric tube'],
            ['96.71', 'Continuous mechanical ventilation of unknown duration'],
            ['38.93', 'Venous catheterization, not elsewhere classified'],
            ['88.76', 'Diagnostic ultrasound of abdomen and retroperitoneum'],
            ['87.44', 'Routine chest x-ray, so described'],
            ['99.04', 'Transfusion of packed cells'],
        ]);

        foreach ($rows as $row) {
            ICD9::updateOrCreate(
                ['kode' => $row[0]],
                ['nama_prosedur' => $row[1]]
            );
        }
    }

    private function seedBedAndBhp(Collection $departments): void
    {
        // Seed Beds
        $inpatientDepts = $departments->filter(fn ($d) => $d->jenis === 'rawat_inap');
        foreach ($inpatientDepts as $dept) {
            for ($i = 1; $i <= 5; $i++) {
                Bed::updateOrCreate(
                    ['department_id' => $dept->id, 'bed_number' => 'B' . $i],
                    [
                        'room_name' => 'Kamar ' . ceil($i / 2),
                        'class' => $i <= 2 ? 'VIP' : 'Kelas I',
                        'status' => $i === 1 ? 'occupied' : 'available',
                        'price_per_day' => $i <= 2 ? 750000 : 450000,
                    ]
                );
            }
        }

        // Seed BHPs
        $bhps = [
            ['Abocath 20G', 'pcs', 100, 25000],
            ['Infuse Set Dewasa', 'pcs', 100, 15000],
            ['Cairan RL 500ml', 'botol', 200, 12000],
            ['Spuit 3cc', 'pcs', 500, 3500],
            ['Kasa Steril 16x16', 'box', 50, 8500],
            ['Handscone Non-Steril', 'psg', 1000, 2000],
        ];

        foreach ($bhps as $bhp) {
            InventoryBhp::updateOrCreate(
                ['nama_bhp' => $bhp[0]],
                ['satuan' => $bhp[1], 'stok' => $bhp[2], 'harga_jual' => $bhp[3], 'is_active' => true]
            );
        }
    }

    private function seedRoles(): Collection
    {
        return collect([
            ['Super Administrator', 'super-administrator', 'Akses penuh seluruh modul SIMRS.', 1],
            ['System Administrator', 'sys-admin', 'Pengelolaan akun, role, audit, dan konfigurasi sistem.', 2],
            ['Petugas Pendaftaran', 'pendaftaran', 'Registrasi pasien, kunjungan, dan antrean.', 4],
            ['Dokter Umum', 'dokter-umum', 'Pemeriksaan rawat jalan, IGD, dan input CPPT.', 4],
            ['Dokter Spesialis', 'dokter-spesialis', 'Pemeriksaan spesialistik dan verifikasi klinis.', 3],
            ['Perawat', 'perawat', 'Asesmen awal, triase, dan observasi keperawatan.', 4],
            ['Apoteker', 'apoteker', 'Verifikasi resep, dispensing, dan stok farmasi.', 4],
            ['Analis Laboratorium', 'analis-lab', 'Penerimaan sampel dan input hasil laboratorium.', 4],
            ['Radiografer', 'radiografer', 'Pemeriksaan radiologi dan unggah hasil.', 4],
            ['Kasir', 'kasir', 'Invoice, pembayaran, dan pelunasan tagihan.', 4],
            ['Casemix', 'casemix', 'Monitoring INA-CBG, SEP, dan klaim BPJS.', 3],
        ])->mapWithKeys(fn (array $role) => [
            $role[1] => Role::updateOrCreate(
                ['slug' => $role[1]],
                ['nama_peran' => $role[0], 'deskripsi' => $role[2], 'level' => $role[3]]
            ),
        ]);
    }

    private function seedModulesAndPermissions(Collection $roles): Collection
    {
        $modules = collect([
            ['Dashboard', 'dashboard', 'fa-solid fa-gauge-high', 1],
            ['Pendaftaran', 'pendaftaran', 'fa-solid fa-clipboard-list', 2],
            ['Keperawatan', 'keperawatan', 'fa-solid fa-heart-pulse', 3],
            ['Rekam Medis', 'rekam-medis', 'fa-solid fa-notes-medical', 4],
            ['Farmasi', 'farmasi', 'fa-solid fa-pills', 5],
            ['Laboratorium', 'laboratorium', 'fa-solid fa-flask-vial', 6],
            ['Radiologi', 'radiologi', 'fa-solid fa-x-ray', 7],
            ['Billing', 'billing', 'fa-solid fa-cash-register', 8],
            ['Casemix', 'casemix', 'fa-solid fa-file-invoice-dollar', 9],
            ['BPJS', 'bpjs', 'fa-solid fa-id-card-clip', 10],
            ['Laporan', 'laporan', 'fa-solid fa-chart-line', 11],
            ['Administrasi', 'admin', 'fa-solid fa-users-gear', 12],
            ['Audit Trail', 'audit', 'fa-solid fa-shield-halved', 13],
        ])->mapWithKeys(fn (array $module) => [
            $module[1] => Module::updateOrCreate(
                ['slug' => $module[1]],
                [
                    'nama_modul' => $module[0],
                    'icon_class' => $module[2],
                    'urutan_menu' => $module[3],
                    'is_active' => true,
                ]
            ),
        ]);

        $actions = [
            'view' => 'Melihat data',
            'create' => 'Membuat data',
            'update' => 'Memperbarui data',
            'delete' => 'Menghapus data',
            'approve' => 'Verifikasi atau persetujuan',
            'export' => 'Ekspor data',
        ];

        $permissions = collect();
        foreach ($modules as $module) {
            foreach ($actions as $action => $description) {
                $permissions->push(Permission::updateOrCreate(
                    ['module_id' => $module->id, 'action' => $action],
                    ['deskripsi' => "{$description} {$module->nama_modul}"]
                ));
            }
        }

        $roleModules = [
            'sys-admin' => ['dashboard', 'admin', 'audit', 'laporan'],
            'pendaftaran' => ['dashboard', 'pendaftaran', 'bpjs'],
            'dokter-umum' => ['dashboard', 'keperawatan', 'rekam-medis', 'farmasi', 'laboratorium', 'radiologi', 'laporan'],
            'dokter-spesialis' => ['dashboard', 'keperawatan', 'rekam-medis', 'farmasi', 'laboratorium', 'radiologi', 'laporan'],
            'perawat' => ['dashboard', 'keperawatan', 'rekam-medis'],
            'apoteker' => ['dashboard', 'farmasi'],
            'analis-lab' => ['dashboard', 'laboratorium'],
            'radiografer' => ['dashboard', 'radiologi'],
            'kasir' => ['dashboard', 'billing', 'laporan'],
            'casemix' => ['dashboard', 'pendaftaran', 'rekam-medis', 'billing', 'casemix', 'bpjs', 'laporan'],
        ];

        foreach ($roles as $slug => $role) {
            if ($slug === 'super-administrator') {
                $role->permissions()->sync($permissions->pluck('id')->all());
                continue;
            }

            $moduleIds = $modules->only($roleModules[$slug] ?? ['dashboard'])->pluck('id');
            $role->permissions()->sync($permissions->whereIn('module_id', $moduleIds)->pluck('id')->all());
        }

        return $modules;
    }

    private function seedDepartments(): Collection
    {
        return collect([
            ['IGD', 'Instalasi Gawat Darurat', 'igd', 1, 'Gedung A', '110'],
            ['POL-UM', 'Poliklinik Umum', 'rawat_jalan', 1, 'Gedung A', '121'],
            ['POL-PD', 'Poliklinik Penyakit Dalam', 'rawat_jalan', 2, 'Gedung A', '122'],
            ['POL-ANK', 'Poliklinik Anak', 'rawat_jalan', 2, 'Gedung A', '123'],
            ['POL-OBG', 'Poliklinik Kebidanan', 'rawat_jalan', 2, 'Gedung A', '124'],
            ['RI-MWR', 'Rawat Inap Mawar', 'rawat_inap', 3, 'Gedung B', '210'],
            ['RI-MLT', 'Rawat Inap Melati', 'rawat_inap', 4, 'Gedung B', '220'],
            ['FAR', 'Instalasi Farmasi', 'farmasi', 1, 'Gedung C', '310'],
            ['LAB', 'Laboratorium Klinik', 'laboratorium', 1, 'Gedung C', '320'],
            ['RAD', 'Radiologi', 'radiologi', 1, 'Gedung C', '330'],
            ['KAS', 'Kasir & Billing', 'keuangan', 1, 'Gedung A', '140'],
            ['RM', 'Rekam Medis', 'rekam_medis', 1, 'Gedung A', '150'],
            ['CMX', 'Unit Casemix', 'casemix', 2, 'Gedung A', '160'],
            ['IT', 'Teknologi Informasi', 'administrasi', 2, 'Gedung D', '410'],
        ])->mapWithKeys(fn (array $department) => [
            $department[0] => Department::updateOrCreate(
                ['kode_depart' => $department[0]],
                [
                    'nama_depart' => $department[1],
                    'jenis' => $department[2],
                    'lantai' => $department[3],
                    'gedung' => $department[4],
                    'telepon_ext' => $department[5],
                    'is_active' => true,
                ]
            ),
        ]);
    }

    private function seedUsers(Collection $roles, Collection $departments): Collection
    {
        $staff = collect([
            ['superadmin@simrs.test', '197901012006041001', 'dr. Andika Prasetyo, MARS', 'super-administrator', 'IT', '081210000001'],
            ['sysadmin@simrs.test', '198409172010011003', 'Rizky Adhitya', 'sys-admin', 'IT', '081210000002'],
            ['pendaftaran@simrs.test', '199103112014022001', 'Siti Rahmawati', 'pendaftaran', 'RM', '081210000003'],
            ['dokter.umum@simrs.test', '198612242012011002', 'dr. Bima Santoso', 'dokter-umum', 'POL-UM', '081210000004'],
            ['dokter.penyakitdalam@simrs.test', '197805212005012004', 'dr. Maya Lestari, Sp.PD', 'dokter-spesialis', 'POL-PD', '081210000005'],
            ['dokter.anak@simrs.test', '198101062007012006', 'dr. Nadya Permata, Sp.A', 'dokter-spesialis', 'POL-ANK', '081210000006'],
            ['dokter.obgyn@simrs.test', '198210292008012002', 'dr. Ratih Kusuma, Sp.OG', 'dokter-spesialis', 'POL-OBG', '081210000007'],
            ['perawat@simrs.test', '199403082016032002', 'Ners Dewi Kartika', 'perawat', 'IGD', '081210000008'],
            ['apoteker@simrs.test', '199001112015032001', 'apt. Laila Fitriani', 'apoteker', 'FAR', '081210000009'],
            ['lab@simrs.test', '199202142017032001', 'Agus Setiawan, A.Md.AK', 'analis-lab', 'LAB', '081210000010'],
            ['radiologi@simrs.test', '199111172016042001', 'Fajar Wibowo, A.Md.Rad', 'radiografer', 'RAD', '081210000011'],
            ['kasir@simrs.test', '199505052019032001', 'Mira Oktaviani', 'kasir', 'KAS', '081210000012'],
            ['casemix@simrs.test', '198711112011012002', 'Taufik Hidayat, SKM', 'casemix', 'CMX', '081210000013'],
            ['dokter.igd@simrs.test', '198412092010012005', 'dr. Farhan Hakim', 'dokter-umum', 'IGD', '081210000014'],
        ]);

        $users = $staff->mapWithKeys(function (array $row) use ($roles, $departments) {
            [$email, $nip, $name, $roleSlug, $departmentCode, $phone] = $row;

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'nip' => $nip,
                    'nama_lengkap' => $name,
                    'password' => 'password',
                    'no_telepon' => $phone,
                    'department_id' => $departments[$departmentCode]->id,
                    'is_active' => true,
                ]
            );

            $user->roles()->sync([
                $roles[$roleSlug]->id => [
                    'assigned_by' => null,
                    'assigned_at' => now(),
                ],
            ]);

            return [$email => $user->refresh()];
        });

        $superAdmin = $users['superadmin@simrs.test'];
        foreach ($users as $user) {
            $pivot = $user->roles->first();
            if ($pivot) {
                $user->roles()->updateExistingPivot($pivot->id, ['assigned_by' => $superAdmin->id]);
            }
        }

        return $users;
    }

    private function assignDepartmentHeads(Collection $departments, Collection $users): void
    {
        $heads = [
            'IGD' => 'dokter.igd@simrs.test',
            'POL-UM' => 'dokter.umum@simrs.test',
            'POL-PD' => 'dokter.penyakitdalam@simrs.test',
            'POL-ANK' => 'dokter.anak@simrs.test',
            'POL-OBG' => 'dokter.obgyn@simrs.test',
            'FAR' => 'apoteker@simrs.test',
            'LAB' => 'lab@simrs.test',
            'RAD' => 'radiologi@simrs.test',
            'KAS' => 'kasir@simrs.test',
            'CMX' => 'casemix@simrs.test',
            'IT' => 'sysadmin@simrs.test',
        ];

        foreach ($heads as $departmentCode => $email) {
            $departments[$departmentCode]->update(['kepala_id' => $users[$email]->id]);
        }
    }

    private function seedClinicalReferences(Collection $users, Collection $departments): void
    {
        $doctorRows = [
            ['dokter.umum@simrs.test', 'DOK-001', 'Dokter Umum', 'POL-UM'],
            ['dokter.igd@simrs.test', 'DOK-002', 'Dokter IGD', 'IGD'],
            ['dokter.penyakitdalam@simrs.test', 'DOK-003', 'Spesialis Penyakit Dalam', 'POL-PD'],
            ['dokter.anak@simrs.test', 'DOK-004', 'Spesialis Anak', 'POL-ANK'],
            ['dokter.obgyn@simrs.test', 'DOK-005', 'Spesialis Obstetri dan Ginekologi', 'POL-OBG'],
        ];

        foreach ($doctorRows as $index => $row) {
            [$email, $code, $specialty, $departmentCode] = $row;
            $doctor = Doctor::updateOrCreate(
                ['kode_dokter' => $code],
                [
                    'user_id' => $users[$email]->id,
                    'department_id' => $departments[$departmentCode]->id,
                    'spesialisasi' => $specialty,
                    'no_str' => 'STR-' . str_pad((string) ($index + 2026001), 9, '0', STR_PAD_LEFT),
                    'str_expired_at' => now()->addYears(3)->subMonths($index)->toDateString(),
                    'no_sip' => 'SIP-' . str_pad((string) ($index + 3171001), 9, '0', STR_PAD_LEFT),
                    'sip_expired_at' => now()->addYears(2)->subMonths($index)->toDateString(),
                    'is_active' => true,
                ]
            );

            foreach ([1, 3, 5] as $dayOffset => $day) {
                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'department_id' => $departments[$departmentCode]->id,
                        'day_of_week' => $day,
                        'start_time' => $dayOffset === 0 ? '08:00:00' : '13:00:00',
                    ],
                    [
                        'end_time' => $dayOffset === 0 ? '12:00:00' : '16:00:00',
                        'quota' => $departmentCode === 'IGD' ? 40 : 24,
                        'is_active' => true,
                    ]
                );
            }
        }

        foreach ([
            ['LAB-DL', 'Darah Lengkap', 'Hematologi', 150000, 125000, ['Hemoglobin', 'Leukosit', 'Trombosit']],
            ['LAB-GDS', 'Glukosa Darah Sewaktu', 'Kimia klinik', 85000, 70000, ['Glukosa darah sewaktu']],
            ['LAB-GIN', 'Fungsi Ginjal', 'Kimia klinik', 165000, 145000, ['Ureum', 'Kreatinin']],
            ['LAB-ELK', 'Elektrolit', 'Kimia klinik', 190000, 160000, ['Natrium', 'Kalium']],
            ['LAB-URI', 'Urinalisis', 'Urinologi', 90000, 75000, ['Leukosit urine', 'Nitrit']],
        ] as $panel) {
            LabPanel::updateOrCreate(
                ['kode_panel' => $panel[0]],
                [
                    'nama_panel' => $panel[1],
                    'kategori' => $panel[2],
                    'tarif_umum' => $panel[3],
                    'tarif_bpjs' => $panel[4],
                    'parameter_default' => $panel[5],
                    'is_active' => true,
                ]
            );
        }

        foreach ([
            ['REG-ADM', 'Administrasi pendaftaran pasien', 'lainnya', null, 25000, 20000],
            ['KON-UM', 'Konsultasi dokter umum', 'konsultasi', 'POL-UM', 125000, 95000],
            ['KON-SP', 'Konsultasi dokter spesialis', 'konsultasi', 'POL-PD', 185000, 150000],
            ['IGD-ADM', 'Pelayanan awal IGD', 'tindakan', 'IGD', 175000, 150000],
            ['RI-K2', 'Akomodasi rawat inap kelas II', 'rawat_inap', 'RI-MWR', 450000, 375000],
            ['RAD-THX', 'Foto Thorax PA', 'penunjang', 'RAD', 280000, 235000],
            ['RAD-CTH', 'CT Scan Kepala', 'penunjang', 'RAD', 850000, 760000],
        ] as $service) {
            ServiceMaster::updateOrCreate(
                ['kode_layanan' => $service[0]],
                [
                    'nama_layanan' => $service[1],
                    'kategori' => $service[2],
                    'department_id' => $service[3] ? $departments[$service[3]]->id : null,
                    'tarif_umum' => $service[4],
                    'tarif_bpjs' => $service[5],
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedIcd10AndTariffs(): Collection
    {
        $rows = collect([
            ['A09', 'Gastroenteritis dan kolitis infeksi', 'Infeksi saluran cerna', 'CBG-4-13-I', 3650000, 6850000],
            ['B34.9', 'Infeksi virus tidak spesifik', 'Infeksi virus', 'CBG-4-12-I', 2800000, 5100000],
            ['D50.9', 'Anemia defisiensi besi', 'Hematologi', 'CBG-3-10-I', 3250000, 5800000],
            ['E11.9', 'Diabetes melitus tipe 2 tanpa komplikasi', 'Endokrin', 'CBG-4-20-I', 4300000, 7600000],
            ['I10', 'Hipertensi esensial primer', 'Kardiovaskular', 'CBG-4-17-I', 3400000, 6250000],
            ['I21.9', 'Infark miokard akut', 'Kardiovaskular', 'CBG-1-01-II', 11800000, 24500000],
            ['J06.9', 'Infeksi saluran napas atas akut', 'Respirasi', 'CBG-4-15-I', 2650000, 4800000],
            ['J18.9', 'Pneumonia tidak spesifik', 'Respirasi', 'CBG-4-14-II', 5750000, 11200000],
            ['K29.7', 'Gastritis tidak spesifik', 'Gastroenterologi', 'CBG-4-13-II', 3100000, 5600000],
            ['N39.0', 'Infeksi saluran kemih', 'Urologi', 'CBG-4-18-I', 3500000, 6300000],
            ['O80.9', 'Persalinan spontan tunggal', 'Obstetri', 'CBG-6-01-I', 4200000, 8700000],
            ['R50.9', 'Demam tidak spesifik', 'Gejala umum', 'CBG-4-11-I', 2600000, 4700000],
            ['S52.5', 'Fraktur radius distal', 'Trauma', 'CBG-2-09-II', 6500000, 13500000],
            ['Z00.0', 'Pemeriksaan medis umum', 'Layanan preventif', 'CBG-9-01-I', 850000, 1300000],
        ]);

        $icd10 = $rows->mapWithKeys(function (array $row) {
            [$kode, $nama, $kategori] = $row;

            return [$kode => ICD10::updateOrCreate(
                ['kode' => $kode],
                ['nama_diagnosis' => $nama, 'kategori' => $kategori, 'is_active' => true]
            )];
        });

        foreach ($rows as $row) {
            [$kode, $nama, , $inacbg, $rawatJalan, $rawatInap] = $row;
            INACBGTariff::updateOrCreate(
                ['icd10_kode' => $kode, 'kelas_rs' => 'B', 'jenis_rawat' => 'rawat_jalan'],
                ['kode_inacbg' => $inacbg, 'deskripsi' => $nama, 'tarif_total' => $rawatJalan]
            );
            INACBGTariff::updateOrCreate(
                ['icd10_kode' => $kode, 'kelas_rs' => 'B', 'jenis_rawat' => 'rawat_inap'],
                ['kode_inacbg' => str_replace('-I', '-II', $inacbg), 'deskripsi' => $nama . ' rawat inap', 'tarif_total' => $rawatInap]
            );
        }

        return $icd10;
    }

    private function seedMedicines(Collection $users): Collection
    {
        $rows = collect([
            ['OBT-0001', 'Paracetamol 500 mg tablet', 'Analgesik antipiretik', 'tablet', 420, 80, 320, 650, '2027-09-30', 'Kimia Farma'],
            ['OBT-0002', 'Amoxicillin 500 mg kapsul', 'Antibiotik', 'kapsul', 260, 60, 850, 1650, '2027-06-15', 'Sanbe Farma'],
            ['OBT-0003', 'Cefixime 100 mg kapsul', 'Antibiotik', 'kapsul', 140, 40, 3100, 5800, '2027-04-20', 'Dexa Medica'],
            ['OBT-0004', 'Omeprazole 20 mg kapsul', 'Gastrointestinal', 'kapsul', 180, 50, 1200, 2400, '2027-10-01', 'Kalbe Farma'],
            ['OBT-0005', 'Metformin 500 mg tablet', 'Antidiabetik', 'tablet', 300, 70, 450, 900, '2028-01-31', 'Phapros'],
            ['OBT-0006', 'Amlodipine 5 mg tablet', 'Antihipertensi', 'tablet', 210, 60, 420, 900, '2027-12-31', 'Novell Pharma'],
            ['OBT-0007', 'Candesartan 8 mg tablet', 'Antihipertensi', 'tablet', 95, 40, 1800, 3500, '2027-11-30', 'Fahrenheit'],
            ['OBT-0008', 'Salbutamol 2 mg tablet', 'Bronkodilator', 'tablet', 65, 35, 550, 1200, '2027-08-18', 'Bernofarm'],
            ['OBT-0009', 'Cetirizine 10 mg tablet', 'Antihistamin', 'tablet', 72, 40, 500, 1100, '2027-05-25', 'Interbat'],
            ['OBT-0010', 'Oralit sachet', 'Rehidrasi oral', 'sachet', 35, 50, 850, 1600, '2027-03-10', 'Kimia Farma'],
            ['OBT-0011', 'Ringer Laktat 500 ml', 'Cairan infus', 'botol', 42, 30, 8500, 16000, '2027-07-12', 'Otsuka'],
            ['OBT-0012', 'NaCl 0.9% 500 ml', 'Cairan infus', 'botol', 28, 35, 7200, 14500, '2027-07-10', 'Widatra'],
            ['OBT-0013', 'Ondansetron 4 mg ampul', 'Antiemetik', 'ampul', 26, 25, 6200, 12500, '2027-06-01', 'Kalbe Farma'],
            ['OBT-0014', 'Ketorolac 30 mg ampul', 'Analgesik injeksi', 'ampul', 48, 25, 7800, 15000, '2027-09-05', 'Dexa Medica'],
            ['OBT-0015', 'Insulin regular 100 IU/ml', 'Antidiabetik injeksi', 'vial', 18, 10, 64000, 89000, '2027-02-28', 'Novo Nordisk'],
            ['OBT-0016', 'Simvastatin 20 mg tablet', 'Antilipidemia', 'tablet', 120, 40, 650, 1400, '2027-10-14', 'Kimia Farma'],
            ['OBT-0017', 'Furosemide 40 mg tablet', 'Diuretik', 'tablet', 88, 35, 480, 950, '2027-11-22', 'Indofarma'],
            ['OBT-0018', 'Asam Traneksamat 500 mg tablet', 'Hemostatik', 'tablet', 70, 25, 2100, 4100, '2027-08-08', 'Sanbe Farma'],
            ['OBT-0019', 'Vitamin B Complex tablet', 'Vitamin', 'tablet', 400, 100, 250, 600, '2028-05-20', 'Phapros'],
            ['OBT-0020', 'Zinc 20 mg tablet', 'Suplemen', 'tablet', 52, 60, 550, 1300, '2027-04-04', 'Novell Pharma'],
        ]);

        return $rows->mapWithKeys(function (array $row) use ($users) {
            [$code, $name, $category, $unit, $stock, $minimum, $buy, $sell, $expired, $manufacturer] = $row;

            $medicine = InventoryMedicine::updateOrCreate(
                ['kode_obat' => $code],
                [
                    'nama_obat' => $name,
                    'kategori' => $category,
                    'satuan' => $unit,
                    'stok' => $stock,
                    'stok_minimum' => $minimum,
                    'harga_beli' => $buy,
                    'harga_jual' => $sell,
                    'expired_at' => $expired,
                    'manufacturer' => $manufacturer,
                    'is_active' => true,
                ]
            );

            InventoryTransaction::firstOrCreate(
                ['inventory_medicine_id' => $medicine->id, 'referensi' => "STOK-AWAL-{$code}"],
                [
                    'user_id' => $users['apoteker@simrs.test']->id,
                    'jenis_transaksi' => 'masuk',
                    'qty' => $stock,
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $stock,
                    'catatan' => 'Saldo awal stok demo SIMRS.',
                ]
            );

            return [$code => $medicine->refresh()];
        });
    }

    private function seedPatients(): Collection
    {
        $names = [
            ['Ahmad Fauzi', 'L', 'Bandung', '1981-04-12', 'Islam', 'Menikah', 'Karyawan swasta', 'S1', 'Jl. Melati No. 12'],
            ['Nur Aisyah', 'P', 'Jakarta', '1990-10-03', 'Islam', 'Menikah', 'Guru', 'S1', 'Jl. Kenanga No. 8'],
            ['Budi Hartono', 'L', 'Bekasi', '1974-01-22', 'Kristen', 'Menikah', 'Wiraswasta', 'SMA', 'Jl. Cempaka No. 19'],
            ['Dian Permatasari', 'P', 'Depok', '1988-07-15', 'Islam', 'Menikah', 'Ibu rumah tangga', 'D3', 'Jl. Anggrek No. 22'],
            ['Hendra Wijaya', 'L', 'Tangerang', '1965-12-30', 'Buddha', 'Menikah', 'Pensiunan', 'SMA', 'Jl. Mawar No. 31'],
            ['Maya Sulastri', 'P', 'Bogor', '1994-05-19', 'Islam', 'Belum menikah', 'Pegawai administrasi', 'S1', 'Jl. Dahlia No. 7'],
            ['Rudi Kurniawan', 'L', 'Cirebon', '2002-08-09', 'Islam', 'Belum menikah', 'Mahasiswa', 'SMA', 'Jl. Flamboyan No. 14'],
            ['Sari Wulandari', 'P', 'Garut', '1998-02-28', 'Islam', 'Menikah', 'Karyawan swasta', 'D3', 'Jl. Nusa Indah No. 3'],
            ['Agus Salim', 'L', 'Tasikmalaya', '1958-11-11', 'Islam', 'Menikah', 'Petani', 'SMP', 'Jl. Veteran No. 45'],
            ['Fitri Handayani', 'P', 'Sukabumi', '1985-09-23', 'Islam', 'Menikah', 'Pedagang', 'SMA', 'Jl. Merdeka No. 11'],
            ['Eko Prasetyo', 'L', 'Semarang', '1979-06-02', 'Katolik', 'Menikah', 'Sopir', 'SMA', 'Jl. Diponegoro No. 27'],
            ['Lina Marlina', 'P', 'Yogyakarta', '1992-03-18', 'Islam', 'Belum menikah', 'Perawat klinik', 'D3', 'Jl. Kaliurang No. 18'],
            ['Teguh Santoso', 'L', 'Solo', '1983-01-09', 'Islam', 'Menikah', 'ASN', 'S1', 'Jl. Slamet Riyadi No. 9'],
            ['Yuni Astuti', 'P', 'Malang', '1971-04-25', 'Kristen', 'Menikah', 'Akuntan', 'S1', 'Jl. Ijen No. 5'],
            ['Rina Oktaviani', 'P', 'Surabaya', '2001-12-07', 'Islam', 'Belum menikah', 'Mahasiswa', 'SMA', 'Jl. Tunjungan No. 41'],
            ['Fajar Nugroho', 'L', 'Purwokerto', '1996-10-16', 'Islam', 'Menikah', 'Teknisi', 'SMK', 'Jl. Jenderal Sudirman No. 16'],
            ['Nabila Safitri', 'P', 'Padang', '2019-02-14', 'Islam', 'Belum menikah', 'Pelajar', 'TK', 'Jl. Khatib Sulaiman No. 6'],
            ['Damar Saputra', 'L', 'Medan', '2015-09-12', 'Islam', 'Belum menikah', 'Pelajar', 'SD', 'Jl. Gatot Subroto No. 17'],
            ['Sri Wahyuni', 'P', 'Palembang', '1969-06-21', 'Islam', 'Menikah', 'Pensiunan', 'SMA', 'Jl. Demang Lebar Daun No. 2'],
            ['Arif Maulana', 'L', 'Makassar', '1989-11-04', 'Islam', 'Menikah', 'Nelayan', 'SMP', 'Jl. Penghibur No. 20'],
            ['Putri Amelia', 'P', 'Denpasar', '1997-01-27', 'Hindu', 'Menikah', 'Desainer', 'S1', 'Jl. Teuku Umar No. 10'],
            ['Wahyu Ramadhan', 'L', 'Pontianak', '1991-08-13', 'Islam', 'Belum menikah', 'Programmer', 'S1', 'Jl. Ahmad Yani No. 25'],
            ['Sulastri Ningsih', 'P', 'Balikpapan', '1977-05-06', 'Islam', 'Menikah', 'Pedagang', 'SMA', 'Jl. MT Haryono No. 12'],
            ['Joko Susilo', 'L', 'Madiun', '1962-03-30', 'Islam', 'Menikah', 'Pensiunan', 'SMP', 'Jl. Pahlawan No. 33'],
            ['Melati Anggraini', 'P', 'Lampung', '2004-07-21', 'Islam', 'Belum menikah', 'Pelajar', 'SMA', 'Jl. Raden Intan No. 4'],
            ['Galih Pratama', 'L', 'Banjarmasin', '1999-05-01', 'Islam', 'Belum menikah', 'Karyawan swasta', 'SMA', 'Jl. Lambung Mangkurat No. 15'],
            ['Citra Dewi', 'P', 'Manado', '1986-02-09', 'Kristen', 'Menikah', 'Pegawai bank', 'S1', 'Jl. Sam Ratulangi No. 19'],
            ['Rangga Firmansyah', 'L', 'Pekanbaru', '1976-12-17', 'Islam', 'Menikah', 'Kontraktor', 'D3', 'Jl. Sudirman No. 88'],
            ['Intan Maharani', 'P', 'Jambi', '1995-04-04', 'Islam', 'Menikah', 'Bidan', 'D3', 'Jl. Hayam Wuruk No. 13'],
            ['Yoga Aditya', 'L', 'Serang', '2000-10-29', 'Islam', 'Belum menikah', 'Barista', 'SMA', 'Jl. Veteran No. 71'],
        ];

        return collect($names)->mapWithKeys(function (array $row, int $index) {
            [$name, $gender, $birthPlace, $birthDate, $religion, $marital, $job, $education, $address] = $row;
            $sequence = $index + 1;
            $hasBpjs = $sequence % 4 !== 0;

            $patient = Patient::updateOrCreate(
                ['no_rkm_medis' => 'RM-' . str_pad((string) $sequence, 8, '0', STR_PAD_LEFT)],
                [
                    'nik' => '3276' . str_pad((string) (810000000000 + $sequence), 12, '0', STR_PAD_LEFT),
                    'no_bpjs' => $hasBpjs ? '000' . str_pad((string) (1961000000000 + $sequence), 13, '0', STR_PAD_LEFT) : null,
                    'nama_pasien' => $name,
                    'email' => 'pasien' . str_pad((string) $sequence, 2, '0', STR_PAD_LEFT) . '@example.test',
                    'password' => 'password',
                    'jenis_kelamin' => $gender,
                    'tgl_lahir' => $birthDate,
                    'tempat_lahir' => $birthPlace,
                    'golongan_darah' => ['A', 'B', 'AB', 'O'][$index % 4],
                    'agama' => $religion,
                    'status_perkawinan' => $marital,
                    'pekerjaan' => $job,
                    'pendidikan' => $education,
                    'alamat_lengkap' => $address,
                    'kelurahan' => ['Sukamaju', 'Cempaka Putih', 'Mekarsari', 'Kebon Jeruk'][$index % 4],
                    'kecamatan' => ['Cilodong', 'Menteng', 'Bekasi Selatan', 'Kebayoran Baru'][$index % 4],
                    'kota' => ['Depok', 'Jakarta Pusat', 'Bekasi', 'Jakarta Selatan'][$index % 4],
                    'provinsi' => 'DKI Jakarta dan Jawa Barat',
                    'no_telp_pasien' => '0813' . str_pad((string) (20000000 + $sequence), 8, '0', STR_PAD_LEFT),
                    'kontak_darurat_nama' => 'Keluarga ' . explode(' ', $name)[0],
                    'kontak_darurat_telp' => '0821' . str_pad((string) (30000000 + $sequence), 8, '0', STR_PAD_LEFT),
                    'alergi' => $sequence % 7 === 0 ? 'Alergi penisilin' : null,
                    'is_active' => true,
                ]
            );

            return [$sequence => $patient->refresh()];
        });
    }

    private function seedOperationalData(Collection $patients, Collection $users, Collection $departments, Collection $icd10, Collection $medicines): void
    {
        $doctorByDepartment = [
            'IGD' => $users['dokter.igd@simrs.test'],
            'POL-UM' => $users['dokter.umum@simrs.test'],
            'POL-PD' => $users['dokter.penyakitdalam@simrs.test'],
            'POL-ANK' => $users['dokter.anak@simrs.test'],
            'POL-OBG' => $users['dokter.obgyn@simrs.test'],
            'RI-MWR' => $users['dokter.penyakitdalam@simrs.test'],
            'RI-MLT' => $users['dokter.penyakitdalam@simrs.test'],
        ];
        $departmentCodes = array_keys($doctorByDepartment);
        $diagnoses = $icd10->keys()->values();
        $labItems = ['Darah Lengkap', 'Glukosa Darah Sewaktu', 'Fungsi Ginjal', 'Elektrolit', 'Urinalisis'];
        $radiologyItems = ['Thorax PA', 'USG Abdomen', 'Foto Ekstremitas', 'CT Scan Kepala'];

        foreach ($patients as $index => $patient) {
            $i = $index - 1;
            $departmentCode = $departmentCodes[$i % count($departmentCodes)];
            $department = $departments[$departmentCode];
            $doctor = $doctorByDepartment[$departmentCode];
            $stage = $i % 8;
            $visitDate = now()->subDays(($i * 2) % 34)->setTime(7 + ($i % 8), 10 + ($i % 40), 0);
            $isInpatient = $department->jenis === 'rawat_inap';
            $jenisKunjungan = $isInpatient ? 'rawat_inap' : ($department->jenis === 'igd' ? 'igd' : 'rawat_jalan');
            $caraBayar = $patient->no_bpjs && $i % 3 !== 1 ? 'bpjs' : (['umum', 'asuransi'][$i % 2]);
            $statusAntrian = ['terdaftar', 'pemeriksaan_dokter', 'menunggu_kasir', 'menunggu_farmasi', 'menunggu_lab', 'menunggu_rad', 'menunggu_kasir', 'selesai'][$stage];
            $statusEncounter = ['terdaftar', 'dalam_perawatan', 'diperiksa', 'diperiksa', 'diperiksa', 'diperiksa', 'diperiksa', 'selesai'][$stage];

            $encounter = Encounter::updateOrCreate(
                ['no_registrasi' => 'REG-' . $visitDate->format('Ymd') . '-' . str_pad((string) $index, 5, '0', STR_PAD_LEFT)],
                [
                    'patient_id' => $patient->id,
                    'department_id' => $department->id,
                    'doctor_id' => $doctor->id,
                    'jenis_kunjungan' => $jenisKunjungan,
                    'cara_masuk' => $i % 5 === 0 ? 'rujukan_puskesmas' : 'datang_sendiri',
                    'cara_bayar' => $caraBayar,
                    'no_antrian' => $department->kode_depart . '-' . str_pad((string) (($i % 32) + 1), 3, '0', STR_PAD_LEFT),
                    'status_antrian' => $statusAntrian,
                    'status_encounter' => $statusEncounter,
                    'waktu_masuk' => $visitDate,
                    'waktu_keluar' => $stage === 7 ? $visitDate->copy()->addHours($isInpatient ? 52 : 3) : null,
                    'keluhan_awal' => $this->initialComplaint($diagnoses[$i % $diagnoses->count()]),
                    'rujukan_dari' => $i % 5 === 0 ? 'Puskesmas Kecamatan' : null,
                    'kelas_rawat' => $isInpatient ? ['Kelas I', 'Kelas II', 'Kelas III'][$i % 3] : null,
                    'kamar' => $isInpatient ? ($departmentCode === 'RI-MWR' ? 'Mawar ' : 'Melati ') . (($i % 6) + 1) : null,
                    'bed' => $isInpatient ? 'B' . (($i % 4) + 1) : null,
                    'metadata' => [
                        'sumber_data' => 'seeder_demo',
                        'triase_awal' => ['hijau', 'kuning', 'merah'][$i % 3],
                    ],
                ]
            );

            if ($stage >= 1) {
                $this->seedNursingAssessment($encounter, $users['perawat@simrs.test'], $i);
            }

            if ($stage >= 2) {
                $diagnosisCode = $diagnoses[$i % $diagnoses->count()];
                $this->seedMedicalRecord($encounter, $doctor, $diagnosisCode, $i);
            }

            if (in_array($stage, [3, 6, 7], true)) {
                $this->seedPrescription($encounter, $doctor, $users['apoteker@simrs.test'], $medicines, $stage, $i);
            }

            if (in_array($stage, [4, 6, 7], true)) {
                $this->seedLabOrder($encounter, $doctor, $users['lab@simrs.test'], $labItems[$i % count($labItems)], $stage, $i);
            }

            if (in_array($stage, [5, 6, 7], true)) {
                $this->seedRadiologyOrder($encounter, $doctor, $users['radiologi@simrs.test'], $radiologyItems[$i % count($radiologyItems)], $stage, $i);
            }

            if ($caraBayar === 'bpjs' && $stage >= 2 && $encounter->medicalRecord) {
                BPJSSepDocument::updateOrCreate(
                    ['encounter_id' => $encounter->id],
                    [
                        'no_sep' => '0301R001' . $visitDate->format('ymd') . str_pad((string) $index, 6, '0', STR_PAD_LEFT),
                        'no_kartu_bpjs' => $patient->no_bpjs,
                        'diagnosis_awal' => $encounter->medicalRecord->icd10_primer,
                        'status' => 'aktif',
                        'request_payload' => ['source' => 'demo-seeder', 'diagnosis' => $encounter->medicalRecord->icd10_primer],
                        'response_payload' => ['metaData' => ['code' => '200', 'message' => 'OK'], 'response' => ['noSep' => 'simulated']],
                        'issued_at' => $visitDate->copy()->addMinutes(25),
                    ]
                );
            }

            $invoice = app(BillingService::class)->ensureInvoice($encounter->refresh());
            $this->seedPaymentState($invoice, $users['kasir@simrs.test'], $stage);
        }
    }

    private function seedNursingAssessment(Encounter $encounter, User $nurse, int $index): void
    {
        $encounter->nursingAssessment()->updateOrCreate(
            ['encounter_id' => $encounter->id],
            [
                'nurse_id' => $nurse->id,
                'tekanan_darah_sistolik' => 108 + (($index * 7) % 42),
                'tekanan_darah_diastolik' => 68 + (($index * 5) % 25),
                'nadi' => 72 + (($index * 3) % 28),
                'suhu_tubuh' => 36.2 + (($index % 8) * 0.2),
                'pernapasan' => 18 + ($index % 8),
                'saturasi_oksigen' => $index % 11 === 0 ? 91 : 96 + ($index % 4),
                'skala_nyeri' => $index % 10,
                'berat_badan' => 48 + (($index * 3) % 38),
                'tinggi_badan' => 148 + (($index * 2) % 32),
                'triase' => ['hijau', 'kuning', 'merah', 'hijau'][$index % 4],
                'catatan_keperawatan' => 'Pasien sadar, kooperatif, edukasi awal diberikan kepada pasien dan keluarga.',
                'assessed_at' => $encounter->waktu_masuk->copy()->addMinutes(12),
            ]
        );
    }

    private function seedMedicalRecord(Encounter $encounter, User $doctor, string $diagnosisCode, int $index): void
    {
        MedicalRecord::updateOrCreate(
            ['encounter_id' => $encounter->id],
            [
                'doctor_id' => $doctor->id,
                'keluhan_utama' => $encounter->keluhan_awal ?: 'Keluhan utama sesuai anamnesis pasien.',
                'riwayat_penyakit_sekarang' => 'Keluhan dirasakan sejak ' . (($index % 5) + 1) . ' hari terakhir, memberat saat aktivitas.',
                'riwayat_penyakit_dahulu' => $index % 4 === 0 ? 'Riwayat hipertensi terkontrol dengan obat rutin.' : 'Tidak ada riwayat penyakit berat yang dilaporkan.',
                'pemeriksaan_fisik' => 'Keadaan umum cukup, kesadaran compos mentis, pemeriksaan fisik sesuai diagnosis kerja.',
                'diagnosis_kerja' => ICD10::where('kode', $diagnosisCode)->value('nama_diagnosis'),
                'icd10_primer' => $diagnosisCode,
                'icd10_sekunder' => $index % 3 === 0 ? ['I10'] : null,
                'rencana_terapi' => 'Terapi medikamentosa, edukasi tanda bahaya, kontrol sesuai jadwal, dan evaluasi penunjang bila diperlukan.',
                'kondisi_saat_pulang' => $encounter->status_encounter === 'selesai' ? 'membaik' : null,
                'signed_at' => $encounter->waktu_masuk->copy()->addMinutes(38),
            ]
        );
    }

    private function seedPrescription(Encounter $encounter, User $doctor, User $pharmacist, Collection $medicines, int $stage, int $index): void
    {
        $prescription = Prescription::updateOrCreate(
            ['no_resep' => 'RX-' . $encounter->waktu_masuk->format('Ymd') . '-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT)],
            [
                'encounter_id' => $encounter->id,
                'doctor_id' => $doctor->id,
                'pharmacist_id' => $stage === 3 ? null : $pharmacist->id,
                'status' => $stage === 3 ? 'baru' : 'selesai',
                'catatan' => 'Resep elektronik dari pelayanan rawat jalan/rawat inap.',
                'verified_at' => $stage === 3 ? null : $encounter->waktu_masuk->copy()->addMinutes(80),
                'dispensed_at' => $stage === 3 ? null : $encounter->waktu_masuk->copy()->addMinutes(92),
            ]
        );

        $prescription->details()->delete();
        $selected = $medicines->values()->slice($index % 10, 2)->values();
        foreach ($selected as $offset => $medicine) {
            $qty = [6, 10, 12, 15][$offset + ($index % 2)];
            $prescription->details()->create([
                'inventory_medicine_id' => $medicine->id,
                'nama_obat' => $medicine->nama_obat,
                'jumlah' => $qty,
                'satuan' => $medicine->satuan,
                'aturan_pakai' => $offset === 0 ? '3x1 sesudah makan' : '2x1 bila perlu',
                'rute' => str_contains(strtolower($medicine->nama_obat), 'ampul') ? 'injeksi' : 'oral',
                'harga_satuan' => $medicine->harga_jual,
                'subtotal' => $qty * (float) $medicine->harga_jual,
            ]);
        }
    }

    private function seedLabOrder(Encounter $encounter, User $doctor, User $analyst, string $item, int $stage, int $index): void
    {
        $order = LabOrder::updateOrCreate(
            ['no_order' => 'LAB-' . $encounter->waktu_masuk->format('Ymd') . '-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT)],
            [
                'encounter_id' => $encounter->id,
                'doctor_id' => $doctor->id,
                'analyst_id' => $stage === 4 ? null : $analyst->id,
                'jenis_pemeriksaan' => $item,
                'prioritas' => $index % 6 === 0 ? 'cito' : 'rutin',
                'status' => $stage === 4 ? 'order' : 'selesai',
                'catatan_klinis' => $encounter->medicalRecord?->diagnosis_kerja,
                'ordered_at' => $encounter->waktu_masuk->copy()->addMinutes(45),
                'sample_received_at' => $stage === 4 ? null : $encounter->waktu_masuk->copy()->addMinutes(70),
                'completed_at' => $stage === 4 ? null : $encounter->waktu_masuk->copy()->addMinutes(110),
            ]
        );

        if ($stage !== 4) {
            $order->results()->delete();
            $isCritical = $index % 13 === 0;
            foreach ($this->labParametersFor($item, $isCritical) as $parameter) {
                $order->results()->create([
                    'parameter' => $parameter[0],
                    'nilai' => $parameter[1],
                    'satuan' => $parameter[2],
                    'nilai_rujukan' => $parameter[3],
                    'flag' => $parameter[4],
                    'is_critical' => $parameter[5],
                    'verified_by' => $analyst->id,
                    'verified_at' => $encounter->waktu_masuk->copy()->addMinutes(112),
                ]);
            }
        }

        $panel = LabPanel::where('nama_panel', $item)->first();
        if ($panel) {
            $order->panels()->sync([
                $panel->id => ['tarif' => $panel->tarif_umum, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    private function seedRadiologyOrder(Encounter $encounter, User $doctor, User $radiographer, string $item, int $stage, int $index): void
    {
        $order = RadiologyOrder::updateOrCreate(
            ['no_order' => 'RAD-' . $encounter->waktu_masuk->format('Ymd') . '-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT)],
            [
                'encounter_id' => $encounter->id,
                'doctor_id' => $doctor->id,
                'radiographer_id' => $stage === 5 ? null : $radiographer->id,
                'jenis_pemeriksaan' => $item,
                'prioritas' => $index % 5 === 0 ? 'cito' : 'rutin',
                'status' => $stage === 5 ? 'order' : 'selesai',
                'indikasi_klinis' => $encounter->medicalRecord?->diagnosis_kerja,
                'ordered_at' => $encounter->waktu_masuk->copy()->addMinutes(50),
                'completed_at' => $stage === 5 ? null : $encounter->waktu_masuk->copy()->addMinutes(130),
            ]
        );

        if ($stage !== 5) {
            $order->result()->updateOrCreate(
                ['radiology_order_id' => $order->id],
                [
                    'temuan' => $this->radiologyFinding($item),
                    'kesan' => 'Tidak tampak tanda kegawatan radiologis. Korelasikan dengan kondisi klinis.',
                    'image_path' => 'demo/radiology/' . strtolower(str_replace(' ', '-', $item)) . '.jpg',
                    'verified_by' => $radiographer->id,
                    'verified_at' => $encounter->waktu_masuk->copy()->addMinutes(135),
                ]
            );
        }
    }

    private function seedPaymentState(BillingInvoice $invoice, User $cashier, int $stage): void
    {
        $invoice->payments()->delete();

        if ($stage === 7) {
            $invoice->payments()->create([
                'no_payment' => 'PAY-' . now()->format('Ymd') . '-' . str_pad((string) $invoice->id, 5, '0', STR_PAD_LEFT),
                'cashier_id' => $cashier->id,
                'metode_bayar' => $invoice->metode_penjamin === 'bpjs' ? 'bpjs' : (['tunai', 'debit', 'qris'][$invoice->id % 3]),
                'jumlah_bayar' => $invoice->total_tagihan,
                'referensi' => $invoice->metode_penjamin === 'bpjs' ? 'KLAIM-BPJS-DEMO' : 'RCPT-' . str_pad((string) $invoice->id, 6, '0', STR_PAD_LEFT),
                'paid_at' => Carbon::parse($invoice->issued_at)->addHours(3),
            ]);
            $invoice->update([
                'total_dibayar' => $invoice->total_tagihan,
                'status' => 'lunas',
                'paid_at' => Carbon::parse($invoice->issued_at)->addHours(3),
            ]);
            $invoice->encounter->update([
                'status_antrian' => 'selesai',
                'status_encounter' => 'selesai',
                'waktu_keluar' => $invoice->encounter->waktu_keluar ?: Carbon::parse($invoice->encounter->waktu_masuk)->addHours(3),
            ]);
            return;
        }

        if ($stage === 6 && $invoice->id % 2 === 0) {
            $partial = round((float) $invoice->total_tagihan * 0.45, -2);
            $invoice->payments()->create([
                'no_payment' => 'PAY-' . now()->format('Ymd') . '-' . str_pad((string) $invoice->id, 5, '0', STR_PAD_LEFT),
                'cashier_id' => $cashier->id,
                'metode_bayar' => 'debit',
                'jumlah_bayar' => $partial,
                'referensi' => 'PARTIAL-' . str_pad((string) $invoice->id, 6, '0', STR_PAD_LEFT),
                'paid_at' => Carbon::parse($invoice->issued_at)->addHours(1),
            ]);
            $invoice->update(['total_dibayar' => $partial, 'status' => 'parsial', 'paid_at' => null]);
            return;
        }

        $invoice->update(['total_dibayar' => 0, 'status' => 'draft', 'paid_at' => null]);
    }

    private function seedClaims(): void
    {
        Encounter::with(['sepDocument', 'billingInvoice', 'medicalRecord'])
            ->whereHas('sepDocument')
            ->whereHas('billingInvoice')
            ->each(function (Encounter $encounter): void {
                $invoice = $encounter->billingInvoice;
                $tariff = INACBGTariff::where('icd10_kode', $encounter->medicalRecord?->icd10_primer)
                    ->where('kelas_rs', 'B')
                    ->where('jenis_rawat', $encounter->jenis_kunjungan === 'rawat_inap' ? 'rawat_inap' : 'rawat_jalan')
                    ->first();

                INACBGClaim::updateOrCreate(
                    ['encounter_id' => $encounter->id],
                    [
                        'bpjs_sep_document_id' => $encounter->sepDocument->id,
                        'billing_invoice_id' => $invoice->id,
                        'no_klaim' => 'CLM-' . now()->format('Ymd') . '-' . str_pad((string) $encounter->id, 6, '0', STR_PAD_LEFT),
                        'kode_inacbg' => $tariff?->kode_inacbg,
                        'tarif_rs' => $invoice->total_tagihan,
                        'tarif_klaim' => $tariff?->tarif_total,
                        'status_klaim' => $invoice->status === 'lunas' ? 'disetujui' : 'draft',
                        'payload' => [
                            'no_sep' => $encounter->sepDocument->no_sep,
                            'diagnosis' => $encounter->medicalRecord?->icd10_primer,
                            'total_tagihan' => $invoice->total_tagihan,
                        ],
                        'response_payload' => $invoice->status === 'lunas'
                            ? ['metadata' => ['code' => 200, 'message' => 'Klaim simulasi disetujui']]
                            : null,
                        'submitted_at' => $invoice->status === 'lunas' ? now()->subHours(2) : null,
                        'verified_at' => $invoice->status === 'lunas' ? now()->subHour() : null,
                    ]
                );
            });
    }

    private function seedSystemLogs(Collection $users, Collection $modules): void
    {
        $admin = $users['superadmin@simrs.test'];
        $targets = [
            ['akses_halaman', 'GET', '/dashboard', 'Akses dashboard operasional.'],
            ['mutasi_data', 'POST', '/pendaftaran/pasien', 'Registrasi pasien baru.'],
            ['mutasi_data', 'POST', '/rekam-medis/1', 'Input rekam medis elektronik.'],
            ['mutasi_data', 'POST', '/keuangan/invoice/1/payment', 'Pembayaran tagihan pasien.'],
            ['akses_halaman', 'GET', '/laporan/kunjungan', 'Akses laporan kunjungan.'],
        ];

        foreach ($targets as $index => $row) {
            AuditLog::firstOrCreate(
                ['action' => $row[0], 'url' => config('app.url') . $row[2], 'description' => $row[3]],
                [
                    'user_id' => $admin->id,
                    'method' => $row[1],
                    'ip_address' => '127.0.0.' . ($index + 1),
                    'model_type' => null,
                    'model_id' => null,
                    'new_values' => ['module' => $modules->keys()->get($index, 'dashboard')],
                ]
            );
        }

        $criticalResult = \App\Models\LabResult::where('is_critical', true)->latest()->first();
        if ($criticalResult) {
            Notification::firstOrCreate(
                ['user_id' => $users['dokter.umum@simrs.test']->id, 'tipe' => 'lab_kritis', 'judul' => 'Nilai kritis laboratorium'],
                [
                    'pesan' => "{$criticalResult->parameter}: {$criticalResult->nilai} perlu ditindaklanjuti.",
                    'url' => route('lab.hasil.edit', $criticalResult->lab_order_id),
                    'is_read' => false,
                ]
            );
        }
    }

    private function initialComplaint(string $icd10): string
    {
        return match ($icd10) {
            'A09' => 'Diare cair disertai mual sejak dua hari.',
            'B34.9' => 'Demam, nyeri otot, dan badan lemas.',
            'D50.9' => 'Mudah lelah dan sering pusing.',
            'E11.9' => 'Kontrol gula darah, sering haus dan buang air kecil.',
            'I10' => 'Nyeri kepala dan tekanan darah meningkat.',
            'I21.9' => 'Nyeri dada kiri menjalar ke lengan.',
            'J06.9' => 'Batuk pilek dan tenggorokan nyeri.',
            'J18.9' => 'Batuk berdahak, demam, dan sesak.',
            'K29.7' => 'Nyeri ulu hati dan mual.',
            'N39.0' => 'Nyeri saat buang air kecil.',
            'O80.9' => 'Kontraksi teratur menjelang persalinan.',
            'R50.9' => 'Demam naik turun tanpa fokus jelas.',
            'S52.5' => 'Nyeri pergelangan tangan setelah jatuh.',
            default => 'Pemeriksaan kesehatan berkala.',
        };
    }

    private function labParametersFor(string $item, bool $critical): array
    {
        return match ($item) {
            'Darah Lengkap' => [
                ['Hemoglobin', $critical ? '6.8' : '13.4', 'g/dL', '12.0-16.0', $critical ? 'rendah' : 'normal', $critical],
                ['Leukosit', '8.600', '/uL', '4.000-10.000', 'normal', false],
                ['Trombosit', '256.000', '/uL', '150.000-400.000', 'normal', false],
            ],
            'Glukosa Darah Sewaktu' => [
                ['Glukosa darah sewaktu', $critical ? '412' : '138', 'mg/dL', '<200', $critical ? 'tinggi' : 'normal', $critical],
            ],
            'Fungsi Ginjal' => [
                ['Ureum', '32', 'mg/dL', '10-50', 'normal', false],
                ['Kreatinin', $critical ? '4.1' : '1.0', 'mg/dL', '0.6-1.3', $critical ? 'tinggi' : 'normal', $critical],
            ],
            'Elektrolit' => [
                ['Natrium', '139', 'mmol/L', '135-145', 'normal', false],
                ['Kalium', $critical ? '2.6' : '4.1', 'mmol/L', '3.5-5.1', $critical ? 'rendah' : 'normal', $critical],
            ],
            default => [
                ['Leukosit urine', '3-5', '/LPB', '0-5', 'normal', false],
                ['Nitrit', 'Negatif', null, 'Negatif', 'normal', false],
            ],
        };
    }

    private function radiologyFinding(string $item): string
    {
        return match ($item) {
            'Thorax PA' => 'Cor dan pulmo dalam batas normal, tidak tampak infiltrat aktif.',
            'USG Abdomen' => 'Hepar, lien, pankreas, dan ginjal dalam batas sonografi normal.',
            'Foto Ekstremitas' => 'Alignment tulang baik, tidak tampak dislokasi.',
            default => 'Tidak tampak perdarahan intrakranial akut atau efek massa bermakna.',
        };
    }
}
