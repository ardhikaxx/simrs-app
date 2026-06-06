@extends('layouts.app')

@section('title', 'Rekam Medis Elektronik')
@section('page-title', 'Pemeriksaan Medis (RME)')
@section('page-subtitle', $encounter->patient->nama_pasien . ' [' . $encounter->patient->no_rkm_medis . ']')

@section('content')
@php($record = $encounter->medicalRecord)
<div class="row g-4 mb-4">
    <!-- Ringkasan Pasien & Tanda Vital -->
    <div class="col-lg-8">
        <div class="simrs-card h-100 mb-0 border-0 shadow-sm overflow-hidden">
            <div class="simrs-card-body p-0">
                <div class="d-flex flex-column flex-md-row">
                    <div class="bg-primary text-white p-4 d-flex flex-column justify-content-center align-items-center text-center" style="min-width: 180px;">
                        <div class="user-avatar-sm mb-3 bg-white text-primary" style="width: 64px; height: 64px; font-size: 1.5rem;">
                            {{ strtoupper(substr($encounter->patient->nama_pasien, 0, 1)) }}
                        </div>
                        <h6 class="fw-800 mb-1">{{ $encounter->patient->nama_pasien }}</h6>
                        <div class="small opacity-75 text-mono">{{ $encounter->patient->no_rkm_medis }}</div>
                        <span class="badge bg-white text-primary mt-2 px-3 py-1 shadow-sm">{{ strtoupper($encounter->cara_bayar) }}</span>
                    </div>
                    <div class="flex-grow-1 p-4 bg-white">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Usia / JK</div>
                                <div class="fw-800 text-simrs-gray-900">{{ $encounter->patient->age }} Th / {{ $encounter->patient->jenis_kelamin }}</div>
                            </div>
                            <div class="col-6 col-md-3 border-start-md">
                                <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Tensi / Nadi</div>
                                @if($encounter->nursingAssessment)
                                    <div class="fw-800 text-simrs-primary">{{ $encounter->nursingAssessment->tekanan_darah_sistolik }}/{{ $encounter->nursingAssessment->tekanan_darah_diastolik }} <small class="fw-normal text-muted">({{ $encounter->nursingAssessment->nadi }})</small></div>
                                @else
                                    <div class="text-muted small italic">Belum diukur</div>
                                @endif
                            </div>
                            <div class="col-6 col-md-3 border-start-md">
                                <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Suhu / SpO2</div>
                                @if($encounter->nursingAssessment)
                                    <div class="fw-800 text-simrs-primary">{{ $encounter->nursingAssessment->suhu_tubuh }}°C <small class="fw-normal text-muted">({{ $encounter->nursingAssessment->saturasi_oksigen }}%)</small></div>
                                @else
                                    <div class="text-muted small italic">Belum diukur</div>
                                @endif
                            </div>
                            <div class="col-6 col-md-3 border-start-md">
                                <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Triase</div>
                                @if($encounter->nursingAssessment)
                                    <span class="badge-status status-{{ $encounter->nursingAssessment->triase }} px-3">
                                        {{ strtoupper($encounter->nursingAssessment->triase) }}
                                    </span>
                                @else
                                    <div class="text-muted small italic">N/A</div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-top">
                            <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Keluhan Utama (Asesmen Perawat)</div>
                            <p class="mb-0 text-simrs-gray-800 lh-sm">{{ $encounter->keluhan_awal ?: 'Tidak ada keluhan spesifik saat pendaftaran.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert & Safety -->
    <div class="col-lg-4">
        <div class="simrs-card h-100 mb-0 border-0 shadow-sm bg-white">
            <div class="simrs-card-header border-0 bg-transparent pb-0">
                <div class="simrs-card-title text-danger small">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>PATIENT SAFETY ALERT</span>
                </div>
            </div>
            <div class="simrs-card-body">
                @if($encounter->patient->alergi)
                    <div class="p-3 rounded-3 bg-danger-subtle border border-danger-subtle mb-3">
                        <div class="fw-800 text-danger mb-1">RIWAYAT ALERGI:</div>
                        <div class="h5 fw-800 text-danger mb-0">{{ $encounter->patient->alergi }}</div>
                    </div>
                @else
                    <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle mb-3">
                        <div class="fw-800 text-success mb-0 text-center small">TIDAK ADA RIWAYAT ALERGI</div>
                    </div>
                @endif
                <div class="p-3 rounded-3 bg-light border">
                    <div class="small fw-700 text-muted text-uppercase tracking-wider mb-2">Riwayat Penyakit Terakhir</div>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-1"><i class="fa-solid fa-clock-rotate-left me-2 opacity-50"></i>22/05/2026: Gastritis Akut (POL-PD)</li>
                        <li><i class="fa-solid fa-clock-rotate-left me-2 opacity-50"></i>10/04/2026: ISPA (IGD)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('rekam-medis.update', $encounter) }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="simrs-card">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary">
                        <i class="fa-solid fa-signature"></i>
                        <span>Integrasi Catatan Perkembangan (CPPT)</span>
                    </div>
                </div>
                <div class="simrs-card-body p-0">
                    <ul class="nav nav-tabs nav-fill bg-light border-bottom-0 custom-clinical-tabs" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active py-3 border-0 rounded-0" data-bs-toggle="tab" data-bs-target="#tab-soap">
                                <i class="fa-solid fa-stethoscope me-2"></i>SOAP (Medis)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link py-3 border-0 rounded-0" data-bs-toggle="tab" data-bs-target="#tab-rx">
                                <i class="fa-solid fa-prescription me-2"></i>E-Prescription
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link py-3 border-0 rounded-0" data-bs-toggle="tab" data-bs-target="#tab-support">
                                <i class="fa-solid fa-microscope me-2"></i>Order Penunjang
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link py-3 border-0 rounded-0" data-bs-toggle="tab" data-bs-target="#tab-bhp">
                                <i class="fa-solid fa-box-archive me-2"></i>Pemakaian BHP
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link py-3 border-0 rounded-0" data-bs-toggle="tab" data-bs-target="#tab-poli">
                                <i class="fa-solid fa-folder-tree me-2"></i>Data Khusus Poli
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content p-4">
                        <!-- Tab SOAP -->
                        <div class="tab-pane fade show active" id="tab-soap">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label-custom">S (Subjective) - Keluhan & Riwayat</label>
                                    <textarea name="keluhan_utama" class="form-control mb-3" rows="3" placeholder="Keluhan utama saat ini..." required>{{ old('keluhan_utama', $record?->keluhan_utama ?? $encounter->keluhan_awal) }}</textarea>
                                    <textarea name="riwayat_penyakit_sekarang" class="form-control" rows="3" placeholder="Riwayat penyakit sekarang (RPS)...">{{ old('riwayat_penyakit_sekarang', $record?->riwayat_penyakit_sekarang) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">O (Objective) - Pemeriksaan Fisik</label>
                                    <textarea name="pemeriksaan_fisik" class="form-control" rows="7" placeholder="Hasil pemeriksaan fisik, tanda vital tambahan, dll...">{{ old('pemeriksaan_fisik', $record?->pemeriksaan_fisik) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">A (Assessment) - Diagnosis</label>
                                    <textarea name="diagnosis_kerja" class="form-control mb-3" rows="2" placeholder="Diagnosis kerja / Kesimpulan klinis..." required>{{ old('diagnosis_kerja', $record?->diagnosis_kerja) }}</textarea>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label class="form-label-custom small">ICD-10 Primer (Wajib)</label>
                                            <select name="icd10_primer" class="form-select select2-init" required>
                                                <option value="">Cari kode atau nama diagnosis...</option>
                                                @foreach($icd10 as $code)
                                                    <option value="{{ $code->kode }}" @selected(old('icd10_primer', $record?->icd10_primer) === $code->kode)>{{ $code->kode }} - {{ $code->nama_diagnosis }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <label class="form-label-custom small">ICD-9 Prosedur / Tindakan</label>
                                            <select name="icd9_prosedur" class="form-select select2-init">
                                                <option value="">Cari kode atau nama prosedur...</option>
                                                @foreach($icd9 as $code)
                                                    <option value="{{ $code->kode }}" @selected(old('icd9_prosedur', $record?->icd9_prosedur) === $code->kode)>{{ $code->kode }} - {{ $code->nama_prosedur }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">P (Plan) - Rencana Terapi & Tindakan</label>
                                    <textarea name="rencana_terapi" class="form-control" rows="5" placeholder="Rencana pengobatan, tindakan, edukasi, dll..." required>{{ old('rencana_terapi', $record?->rencana_terapi) }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Kondisi Saat Selesai</label>
                                    <select name="kondisi_saat_pulang" class="form-select border-primary-subtle">
                                        <option value="">Belum selesai pemeriksaan</option>
                                        @foreach(['membaik','sembuh','dirujuk','meninggal'] as $condition)
                                            <option value="{{ $condition }}" @selected(old('kondisi_saat_pulang', $record?->kondisi_saat_pulang) === $condition)>{{ strtoupper($condition) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tab E-Prescription -->
                        <div class="tab-pane fade" id="tab-rx">
                            <div class="alert alert-info border-0 shadow-sm small py-2 mb-4">
                                <i class="fa-solid fa-circle-info me-2"></i>Pilih obat dari inventori farmasi untuk pembuatan resep elektronik (e-Prescribing).
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle">
                                    <thead>
                                        <tr class="small text-muted text-uppercase fw-bold">
                                            <th style="width: 50%;">Nama Obat (Inventori)</th>
                                            <th style="width: 15%;">Jumlah</th>
                                            <th style="width: 35%;">Aturan Pakai / Signa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 0; $i < 5; $i++)
                                            <tr>
                                                <td class="pb-3">
                                                    <select name="medicine_id[]" class="form-select select2-init">
                                                        <option value="">Pilih Obat...</option>
                                                        @foreach($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}">{{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }} {{ $medicine->satuan }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="pb-3">
                                                    <input type="number" step="0.01" name="jumlah[]" class="form-control text-center fw-bold" value="10">
                                                </td>
                                                <td class="pb-3">
                                                    <input name="aturan_pakai[]" class="form-control" placeholder="Contoh: 3 x 1 tab sesudah makan">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Penunjang -->
                        <div class="tab-pane fade" id="tab-support">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="p-4 rounded-3 border bg-light h-100">
                                        <div class="d-flex align-items-center gap-2 mb-3 text-simrs-primary">
                                            <i class="fa-solid fa-flask-vial fs-4"></i>
                                            <h6 class="fw-800 mb-0">Order Laboratorium Klinik</h6>
                                        </div>
                                        <div class="row">
                                            @foreach(['Darah Lengkap','Glukosa Darah Sewaktu','Fungsi Ginjal','Elektrolit','Urinalisis','HBsAg'] as $item)
                                                <div class="col-6 mb-2">
                                                    <div class="form-check custom-check">
                                                        <input class="form-check-input" type="checkbox" name="lab_items[]" value="{{ $item }}" id="lab-{{ $loop->index }}">
                                                        <label class="form-check-label small fw-600" for="lab-{{ $loop->index }}">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3">
                                            <label class="small fw-700 text-muted mb-1">Prioritas Lab</label>
                                            <select name="lab_prioritas" class="form-select form-select-sm"><option value="rutin">RUTIN</option><option value="cito">CITO (URGENT)</option></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="p-4 rounded-3 border bg-light h-100">
                                        <div class="d-flex align-items-center gap-2 mb-3 text-simrs-primary">
                                            <i class="fa-solid fa-x-ray fs-4"></i>
                                            <h6 class="fw-800 mb-0">Order Radiologi / Imaging</h6>
                                        </div>
                                        <div class="row">
                                            @foreach(['Thorax PA','USG Abdomen','Foto Ekstremitas','CT Scan Kepala','MRI Otak'] as $item)
                                                <div class="col-6 mb-2">
                                                    <div class="form-check custom-check">
                                                        <input class="form-check-input" type="checkbox" name="radiology_items[]" value="{{ $item }}" id="rad-{{ $loop->index }}">
                                                        <label class="form-check-label small fw-600" for="rad-{{ $loop->index }}">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3">
                                            <label class="small fw-700 text-muted mb-1">Prioritas Radiologi</label>
                                            <select name="radiology_prioritas" class="form-select form-select-sm"><option value="rutin">RUTIN</option><option value="cito">CITO (URGENT)</option></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Pemakaian BHP -->
                        <div class="tab-pane fade" id="tab-bhp">
                            <div class="alert alert-info border-0 shadow-sm small py-2 mb-4">
                                <i class="fa-solid fa-circle-info me-2"></i>Catat penggunaan Barang Habis Pakai (BHP) medis selama tindakan. Stok akan berkurang dan tagihan akan terinput otomatis.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle">
                                    <thead>
                                        <tr class="small text-muted text-uppercase fw-bold">
                                            <th style="width: 70%;">Nama Alat/BHP Medis</th>
                                            <th style="width: 30%;">Jumlah (Qty)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bhpRows">
                                        @for($i = 0; $i < 3; $i++)
                                            <tr>
                                                <td class="pb-3">
                                                    <select name="bhp_id[]" class="form-select select2-init">
                                                        <option value="">Pilih BHP...</option>
                                                        @foreach($bhps as $bhp)
                                                            <option value="{{ $bhp->id }}">{{ $bhp->nama_bhp }} (Stok: {{ $bhp->stok }} {{ $bhp->satuan }}) - Rp {{ number_format($bhp->harga_jual) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="pb-3">
                                                    <div class="input-group">
                                                        <input type="number" name="bhp_jumlah[]" class="form-control text-center fw-bold" value="1">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Data Khusus Poli -->
                        <div class="tab-pane fade" id="tab-poli">
                            <div class="row g-4">
                                @php($dept = $encounter->department->kode_depart)
                                @php($poliData = $record?->data_spesifik_poli ?? [])
                                
                                @if(str_contains($dept, 'POL-UM'))
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Status Pekerjaan Pasien</label>
                                        <input name="data_spesifik_poli[pekerjaan]" class="form-control" value="{{ $poliData['pekerjaan'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Lama Keluhan (Hari)</label>
                                        <input type="number" name="data_spesifik_poli[lama_keluhan]" class="form-control" value="{{ $poliData['lama_keluhan'] ?? '' }}">
                                    </div>
                                @elseif(str_contains($dept, 'POL-OBG'))
                                    <div class="col-md-3">
                                        <label class="form-label-custom">G (Gravida)</label>
                                        <input type="text" name="data_spesifik_poli[g]" class="form-control" value="{{ $poliData['g'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">P (Para)</label>
                                        <input type="text" name="data_spesifik_poli[p]" class="form-control" value="{{ $poliData['p'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">A (Abortus)</label>
                                        <input type="text" name="data_spesifik_poli[a]" class="form-control" value="{{ $poliData['a'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label-custom">HPHT</label>
                                        <input type="date" name="data_spesifik_poli[hpht]" class="form-control" value="{{ $poliData['hpht'] ?? '' }}">
                                    </div>
                                @elseif(str_contains($dept, 'POL-ANK'))
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Lingkar Kepala (cm)</label>
                                        <input name="data_spesifik_poli[lingkar_kepala]" class="form-control" value="{{ $poliData['lingkar_kepala'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Imunisasi Terakhir</label>
                                        <input name="data_spesifik_poli[imunisasi]" class="form-control" value="{{ $poliData['imunisasi'] ?? '' }}">
                                    </div>
                                @else
                                    <div class="col-12 text-center py-5 opacity-50">
                                        <i class="fa-solid fa-folder-open fs-1 mb-3"></i>
                                        <p>Data spesifik untuk unit <b>{{ $encounter->department->nama_depart }}</b> belum dikonfigurasi.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Aksi & Riwayat -->
        <div class="col-xl-3">
            <div class="simrs-card sticky-top" style="top: 80px; z-index: 100;">
                <div class="simrs-card-body border-bottom bg-light">
                    <button class="btn btn-simrs-primary w-100 py-3 fw-800 shadow-sm border-0 mb-2">
                        <i class="fa-solid fa-signature me-2"></i>SIMPAN & TANDA TANGAN
                    </button>
                    <button type="button" class="btn btn-simrs-outline w-100 fw-bold border-0 shadow-none">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Draft
                    </button>
                </div>
                <div class="simrs-card-body p-0">
                    <div class="p-3 bg-white border-bottom">
                        <div class="small fw-800 text-muted text-uppercase tracking-wider mb-2">Histori Pemeriksaan</div>
                        <button type="button" class="btn btn-sm btn-info text-white w-100 fw-bold shadow-sm mb-3" data-bs-toggle="modal" data-bs-target="#modalTrendVitals">
                            <i class="fa-solid fa-chart-line me-2"></i>Lihat Tren Vitals
                        </button>
                        <div class="timeline-clinical small">
                            <div class="border-start ps-3 pb-3 position-relative">
                                <i class="fa-solid fa-circle text-primary position-absolute start-0 translate-middle-x bg-white" style="font-size: 0.6rem; top: 5px;"></i>
                                <div class="fw-800">Hari Ini, {{ now()->format('H:i') }}</div>
                                <div class="text-muted">Input CPPT Medis sedang berlangsung</div>
                            </div>
                            @forelse($encounter->prescriptions as $rx)
                                <div class="border-start ps-3 pb-3 position-relative">
                                    <i class="fa-solid fa-circle text-success position-absolute start-0 translate-middle-x bg-white" style="font-size: 0.6rem; top: 5px;"></i>
                                    <div class="fw-800">Resep Terbit ({{ $rx->no_resep }})</div>
                                    <div class="text-muted">{{ $rx->details->count() }} item obat diresepkan</div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                    <div class="p-3">
                        <button type="button" class="btn btn-sm btn-light w-100 fw-600 text-simrs-primary shadow-sm border" onclick="window.history.back()">
                            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Antrean
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .custom-clinical-tabs .nav-link {
        color: #64748B;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    .custom-clinical-tabs .nav-link:hover {
        background-color: rgba(11, 100, 119, 0.05);
        color: var(--simrs-primary);
    }
    .custom-clinical-tabs .nav-link.active {
        background-color: white !important;
        color: var(--simrs-primary) !important;
        border-bottom: 3px solid var(--simrs-primary) !important;
    }
    .tracking-wider { letter-spacing: 0.05em; }
    .border-start-md { border-left: 1px solid #E2E8F0; }
    @media (max-width: 767.98px) {
        .border-start-md { border-left: none; border-top: 1px solid #E2E8F0; padding-top: 10px; }
    }
</style>
@section('scripts')
<div class="modal fade" id="modalTrendVitals" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-800"><i class="fa-solid fa-chart-line me-2"></i>Tren Tanda Vital Pasien</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div style="height: 350px;">
                    <canvas id="chartVitals"></canvas>
                </div>
                <div class="mt-3 small text-muted text-center italic">
                    <i class="fa-solid fa-circle-info me-1"></i> Data grafik diambil dari 5 kunjungan terakhir pasien.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartVitals');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['10/04', '22/05', 'Hari Ini'],
                    datasets: [
                        {
                            label: 'Sistolik',
                            data: [120, 130, {{ $encounter->nursingAssessment?->tekanan_darah_sistolik ?? 0 }}],
                            borderColor: '#C5372C',
                            backgroundColor: 'rgba(197, 55, 44, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Diastolik',
                            data: [80, 85, {{ $encounter->nursingAssessment?->tekanan_darah_diastolik ?? 0 }}],
                            borderColor: '#1678B4',
                            backgroundColor: 'rgba(22, 120, 180, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Nadi',
                            data: [88, 92, {{ $encounter->nursingAssessment?->nadi ?? 0 }}],
                            borderColor: '#1A8754',
                            borderDash: [5, 5],
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { family: 'Plus Jakarta Sans', weight: '700' } } }
                    },
                    scales: {
                        y: { beginAtZero: false, grid: { color: '#F1F5F9' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endsection
