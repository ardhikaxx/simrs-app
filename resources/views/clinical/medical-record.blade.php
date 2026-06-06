@extends('layouts.app')

@section('title', 'Rekam Medis Elektronik')
@section('page-title', 'Pemeriksaan Medis (RME)')
@section('page-subtitle', $encounter->patient->nama_pasien . ' [' . $encounter->patient->no_rkm_medis . ']')

@section('content')
@php($record = $encounter->medicalRecord)
<div class="row g-4 mb-4">
    <!-- Ringkasan Pasien & Tanda Vital -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
            <div class="card-body p-0">
                <div class="d-flex flex-column flex-md-row h-100">
                    <div class="bg-primary bg-gradient text-white p-4 d-flex flex-column justify-content-center align-items-center text-center" style="min-width: 200px;">
                        <div class="d-flex align-items-center justify-content-center bg-white text-primary rounded-circle shadow-sm mb-3 fw-bolder" style="width: 70px; height: 70px; font-size: 1.75rem;">
                            {{ strtoupper(substr($encounter->patient->nama_pasien, 0, 1)) }}
                        </div>
                        <h5 class="fw-bold mb-1 text-white">{{ $encounter->patient->nama_pasien }}</h5>
                        <div class="small opacity-75 font-monospace mb-2">{{ $encounter->patient->no_rkm_medis }}</div>
                        <span class="badge bg-white text-primary px-3 py-1 shadow-sm mt-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ strtoupper($encounter->cara_bayar) }}</span>
                    </div>
                    <div class="flex-grow-1 p-4 bg-white d-flex flex-column justify-content-center">
                        <div class="row g-3 mb-3 pb-3 border-bottom border-light-subtle">
                            <div class="col-6 col-md-3">
                                <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Usia / JK</div>
                                <div class="fw-bolder text-dark">{{ $encounter->patient->age }} Th / {{ $encounter->patient->jenis_kelamin }}</div>
                            </div>
                            <div class="col-6 col-md-3 border-start-md">
                                <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Tensi / Nadi</div>
                                @if($encounter->nursingAssessment)
                                    <div class="fw-bolder text-primary font-monospace">{{ $encounter->nursingAssessment->tekanan_darah_sistolik }}/{{ $encounter->nursingAssessment->tekanan_darah_diastolik }} <small class="fw-medium text-muted">({{ $encounter->nursingAssessment->nadi }})</small></div>
                                @else
                                    <div class="text-muted small fst-italic">Belum diukur</div>
                                @endif
                            </div>
                            <div class="col-6 col-md-3 border-start-md">
                                <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Suhu / SpO2</div>
                                @if($encounter->nursingAssessment)
                                    <div class="fw-bolder text-warning font-monospace">{{ $encounter->nursingAssessment->suhu_tubuh }}°C <small class="fw-medium text-muted">({{ $encounter->nursingAssessment->saturasi_oksigen }}%)</small></div>
                                @else
                                    <div class="text-muted small fst-italic">Belum diukur</div>
                                @endif
                            </div>
                            <div class="col-6 col-md-3 border-start-md">
                                <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Triase</div>
                                @if($encounter->nursingAssessment)
                                    @php
                                        $triaseBg = match($encounter->nursingAssessment->triase) {
                                            'merah' => 'bg-danger text-white',
                                            'kuning' => 'bg-warning text-dark',
                                            'hijau' => 'bg-success text-white',
                                            'hitam' => 'bg-dark text-white',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $triaseBg }} px-2 py-1 rounded-pill" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        {{ strtoupper($encounter->nursingAssessment->triase) }}
                                    </span>
                                @else
                                    <div class="text-muted small fst-italic">N/A</div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Keluhan Utama (Asesmen Perawat)</div>
                            <p class="mb-0 text-dark fw-medium lh-sm">{{ $encounter->keluhan_awal ?: 'Tidak ada keluhan spesifik saat pendaftaran.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert & Safety -->
    <div class="col-lg-4 d-flex flex-column gap-3">
        <div class="card border-0 shadow-sm rounded-4 flex-grow-1 bg-white">
            <div class="card-header bg-white border-0 pb-0 pt-3 px-4">
                <div class="d-flex align-items-center gap-2 text-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span class="fw-bold small text-uppercase tracking-wider" style="font-size: 0.7rem;">Patient Safety Alert</span>
                </div>
            </div>
            <div class="card-body p-4 pt-2">
                @if($encounter->patient->alergi)
                    <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 mb-3">
                        <div class="fw-bolder text-danger mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">RIWAYAT ALERGI:</div>
                        <div class="fw-bold text-danger">{{ $encounter->patient->alergi }}</div>
                    </div>
                @else
                    <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 mb-3 text-center">
                        <div class="fw-bold text-success small mb-0"><i class="fa-solid fa-shield-check me-1"></i> TIDAK ADA RIWAYAT ALERGI</div>
                    </div>
                @endif
                <div class="p-3 rounded-3 bg-light border border-light-subtle">
                    <div class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.65rem;">Riwayat Penyakit Terakhir</div>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-1 d-flex gap-2 align-items-start"><i class="fa-solid fa-clock-rotate-left mt-1 text-primary opacity-50"></i><span class="fw-medium text-dark">22/05/2026: Gastritis Akut (POL-PD)</span></li>
                        <li class="d-flex gap-2 align-items-start"><i class="fa-solid fa-clock-rotate-left mt-1 text-primary opacity-50"></i><span class="fw-medium text-dark">10/04/2026: ISPA (IGD)</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('rekam-medis.update', $encounter) }}" method="POST" class="needs-validation" novalidate>
    @csrf
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-bottom p-0">
                    <div class="d-flex align-items-center gap-3 p-4 pb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-signature fs-6"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark">Integrasi Catatan Perkembangan (CPPT)</h5>
                    </div>
                    <ul class="nav nav-tabs nav-fill bg-light border-bottom-0 custom-clinical-tabs px-2 pt-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active py-3 border-0 rounded-top-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-soap">
                                <i class="fa-solid fa-stethoscope me-2"></i>SOAP (Medis)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link py-3 border-0 rounded-top-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-rx">
                                <i class="fa-solid fa-prescription me-2"></i>E-Prescription
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link py-3 border-0 rounded-top-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-support">
                                <i class="fa-solid fa-microscope me-2"></i>Penunjang
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link py-3 border-0 rounded-top-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-bhp">
                                <i class="fa-solid fa-box-archive me-2"></i>Pemakaian BHP
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link py-3 border-0 rounded-top-3 fw-bold" data-bs-toggle="tab" data-bs-target="#tab-poli">
                                <i class="fa-solid fa-folder-tree me-2"></i>Data Spesifik
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content p-4">
                        <!-- Tab SOAP -->
                        <div class="tab-pane fade show active" id="tab-soap" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small text-uppercase tracking-wider mb-2">S (Subjective) - Keluhan & Riwayat</label>
                                    <textarea name="keluhan_utama" class="form-control mb-3 shadow-none border-light-subtle bg-light" rows="3" placeholder="Keluhan utama saat ini..." required>{{ old('keluhan_utama', $record?->keluhan_utama ?? $encounter->keluhan_awal) }}</textarea>
                                    <textarea name="riwayat_penyakit_sekarang" class="form-control shadow-none border-light-subtle bg-light" rows="3" placeholder="Riwayat penyakit sekarang (RPS)...">{{ old('riwayat_penyakit_sekarang', $record?->riwayat_penyakit_sekarang) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small text-uppercase tracking-wider mb-2">O (Objective) - Pemeriksaan Fisik</label>
                                    <textarea name="pemeriksaan_fisik" class="form-control h-100 shadow-none border-light-subtle bg-light" placeholder="Hasil pemeriksaan fisik, tanda vital tambahan, dll...">{{ old('pemeriksaan_fisik', $record?->pemeriksaan_fisik) }}</textarea>
                                </div>
                                <div class="col-12"><hr class="border-secondary opacity-10 my-1"></div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-bold small text-uppercase tracking-wider mb-2">A (Assessment) - Diagnosis</label>
                                    <textarea name="diagnosis_kerja" class="form-control mb-3 shadow-none border-light-subtle bg-light fw-semibold" rows="2" placeholder="Diagnosis kerja / Kesimpulan klinis..." required>{{ old('diagnosis_kerja', $record?->diagnosis_kerja) }}</textarea>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label text-muted fw-semibold small mb-1">ICD-10 Primer (Wajib)</label>
                                            <select name="icd10_primer" class="form-select select2-init" required>
                                                <option value="">Cari kode atau nama diagnosis...</option>
                                                @foreach($icd10 as $code)
                                                    <option value="{{ $code->kode }}" @selected(old('icd10_primer', $record?->icd10_primer) === $code->kode)>{{ $code->kode }} - {{ $code->nama_diagnosis }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted fw-semibold small mb-1">ICD-9 Prosedur / Tindakan</label>
                                            <select name="icd9_prosedur" class="form-select select2-init">
                                                <option value="">Cari kode atau nama prosedur...</option>
                                                @foreach($icd9 as $code)
                                                    <option value="{{ $code->kode }}" @selected(old('icd9_prosedur', $record?->icd9_prosedur) === $code->kode)>{{ $code->kode }} - {{ $code->nama_prosedur }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label class="form-label text-muted fw-bold small text-uppercase tracking-wider mb-2">P (Plan) - Rencana Terapi & Tindakan</label>
                                    <textarea name="rencana_terapi" class="form-control flex-grow-1 mb-3 shadow-none border-light-subtle bg-light" placeholder="Rencana pengobatan, tindakan, edukasi, dll..." required>{{ old('rencana_terapi', $record?->rencana_terapi) }}</textarea>

                                    <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3">
                                        <label class="form-label text-primary fw-bold small text-uppercase tracking-wider mb-2">Kondisi Saat Selesai</label>
                                        <select name="kondisi_saat_pulang" class="form-select shadow-none border-primary border-opacity-25 fw-semibold text-primary">
                                            <option value="">Belum selesai pemeriksaan</option>
                                            @foreach(['membaik','sembuh','dirujuk','meninggal'] as $condition)
                                                <option value="{{ $condition }}" @selected(old('kondisi_saat_pulang', $record?->kondisi_saat_pulang) === $condition)>{{ strtoupper($condition) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab E-Prescription -->
                        <div class="tab-pane fade" id="tab-rx" role="tabpanel">
                            <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 d-flex align-items-center gap-3 p-3 mb-4 rounded-3">
                                <i class="fa-solid fa-circle-info text-info fs-4"></i>
                                <div class="small text-info fw-medium">Pilih obat dari inventori farmasi untuk pembuatan resep elektronik (e-Prescribing). Stok akan dicek oleh farmasi.</div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle">
                                    <thead>
                                        <tr class="small text-muted text-uppercase fw-bold" style="letter-spacing: 0.5px;">
                                            <th style="width: 50%;" class="ps-2">Nama Obat (Inventori)</th>
                                            <th style="width: 15%;">Jumlah</th>
                                            <th style="width: 35%;">Aturan Pakai / Signa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 0; $i < 5; $i++)
                                            <tr>
                                                <td class="pb-3 ps-2">
                                                    <select name="medicine_id[]" class="form-select select2-init">
                                                        <option value="">Pilih Obat...</option>
                                                        @foreach($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}">{{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }} {{ $medicine->satuan }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="pb-3">
                                                    <input type="number" step="0.01" name="jumlah[]" class="form-control text-center fw-bold shadow-none bg-light border-light-subtle" value="10">
                                                </td>
                                                <td class="pb-3">
                                                    <input name="aturan_pakai[]" class="form-control shadow-none bg-light border-light-subtle" placeholder="Contoh: 3 x 1 tab sesudah makan">
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Penunjang -->
                        <div class="tab-pane fade" id="tab-support" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="p-4 rounded-4 border border-light-subtle bg-light h-100 transition-hover">
                                        <div class="d-flex align-items-center gap-3 mb-4 text-primary">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-flask-vial"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">Order Laboratorium Klinik</h6>
                                        </div>
                                        <div class="row g-3 mb-4">
                                            @foreach(['Darah Lengkap','Glukosa Darah Sewaktu','Fungsi Ginjal','Elektrolit','Urinalisis','HBsAg'] as $item)
                                                <div class="col-6">
                                                    <div class="form-check custom-check bg-white p-2 rounded-3 border shadow-sm">
                                                        <input class="form-check-input ms-1 mt-1 shadow-none" type="checkbox" name="lab_items[]" value="{{ $item }}" id="lab-{{ $loop->index }}">
                                                        <label class="form-check-label small fw-semibold ms-2" for="lab-{{ $loop->index }}">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div>
                                            <label class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.65rem;">Prioritas Lab</label>
                                            <select name="lab_prioritas" class="form-select shadow-none border-light-subtle fw-semibold">
                                                <option value="rutin">RUTIN</option>
                                                <option value="cito" class="text-danger">CITO (URGENT)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="p-4 rounded-4 border border-light-subtle bg-light h-100 transition-hover">
                                        <div class="d-flex align-items-center gap-3 mb-4 text-primary">
                                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-x-ray"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 text-dark">Order Radiologi / Imaging</h6>
                                        </div>
                                        <div class="row g-3 mb-4">
                                            @foreach(['Thorax PA','USG Abdomen','Foto Ekstremitas','CT Scan Kepala','MRI Otak'] as $item)
                                                <div class="col-6">
                                                    <div class="form-check custom-check bg-white p-2 rounded-3 border shadow-sm">
                                                        <input class="form-check-input ms-1 mt-1 shadow-none" type="checkbox" name="radiology_items[]" value="{{ $item }}" id="rad-{{ $loop->index }}">
                                                        <label class="form-check-label small fw-semibold ms-2" for="rad-{{ $loop->index }}">{{ $item }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div>
                                            <label class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.65rem;">Prioritas Radiologi</label>
                                            <select name="radiology_prioritas" class="form-select shadow-none border-light-subtle fw-semibold">
                                                <option value="rutin">RUTIN</option>
                                                <option value="cito" class="text-danger">CITO (URGENT)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Pemakaian BHP -->
                        <div class="tab-pane fade" id="tab-bhp" role="tabpanel">
                            <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 d-flex align-items-center gap-3 p-3 mb-4 rounded-3">
                                <i class="fa-solid fa-box-archive text-warning fs-4"></i>
                                <div class="small text-dark fw-medium">Catat penggunaan Barang Habis Pakai (BHP) medis selama tindakan. Stok akan otomatis berkurang dari gudang dan tagihan akan terinput ke kasir.</div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle">
                                    <thead>
                                        <tr class="small text-muted text-uppercase fw-bold" style="letter-spacing: 0.5px;">
                                            <th style="width: 70%;" class="ps-2">Nama Alat/BHP Medis</th>
                                            <th style="width: 30%;">Jumlah (Qty)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bhpRows">
                                        @for($i = 0; $i < 3; $i++)
                                            <tr>
                                                <td class="pb-3 ps-2">
                                                    <select name="bhp_id[]" class="form-select select2-init">
                                                        <option value="">Pilih BHP...</option>
                                                        @foreach($bhps as $bhp)
                                                            <option value="{{ $bhp->id }}">{{ $bhp->nama_bhp }} (Stok: {{ $bhp->stok }} {{ $bhp->satuan }}) - Rp {{ number_format($bhp->harga_jual) }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="pb-3">
                                                    <div class="input-group shadow-none">
                                                        <input type="number" name="bhp_jumlah[]" class="form-control text-center fw-bold bg-light border-light-subtle shadow-none" value="1">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Data Khusus Poli -->
                        <div class="tab-pane fade" id="tab-poli" role="tabpanel">
                            <div class="p-4 bg-light rounded-4 border border-light-subtle">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-folder-tree"></i>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-dark">Data Spesifik Unit: <span class="text-primary">{{ $encounter->department->nama_depart }}</span></h6>
                                </div>
                                <div class="row g-4">
                                    @php($dept = $encounter->department->kode_depart)
                                    @php($poliData = $record?->data_spesifik_poli ?? [])

                                    @if(str_contains($dept, 'POL-UM'))
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input name="data_spesifik_poli[pekerjaan]" class="form-control shadow-none border-light-subtle" id="poliPekerjaan" value="{{ $poliData['pekerjaan'] ?? '' }}" placeholder="Status Pekerjaan">
                                                <label for="poliPekerjaan" class="text-muted fw-semibold">Status Pekerjaan Pasien</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" name="data_spesifik_poli[lama_keluhan]" class="form-control shadow-none border-light-subtle fw-bold" id="poliLama" value="{{ $poliData['lama_keluhan'] ?? '' }}" placeholder="Hari">
                                                <label for="poliLama" class="text-muted fw-semibold">Lama Keluhan (Hari)</label>
                                            </div>
                                        </div>
                                    @elseif(str_contains($dept, 'POL-OBG'))
                                        <div class="col-md-3">
                                            <div class="form-floating">
                                                <input type="text" name="data_spesifik_poli[g]" class="form-control shadow-none border-light-subtle fw-bold" id="poliG" value="{{ $poliData['g'] ?? '' }}" placeholder="G">
                                                <label for="poliG" class="text-muted fw-semibold">G (Gravida)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating">
                                                <input type="text" name="data_spesifik_poli[p]" class="form-control shadow-none border-light-subtle fw-bold" id="poliP" value="{{ $poliData['p'] ?? '' }}" placeholder="P">
                                                <label for="poliP" class="text-muted fw-semibold">P (Para)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating">
                                                <input type="text" name="data_spesifik_poli[a]" class="form-control shadow-none border-light-subtle fw-bold" id="poliA" value="{{ $poliData['a'] ?? '' }}" placeholder="A">
                                                <label for="poliA" class="text-muted fw-semibold">A (Abortus)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating">
                                                <input type="date" name="data_spesifik_poli[hpht]" class="form-control shadow-none border-light-subtle" id="poliHPHT" value="{{ $poliData['hpht'] ?? '' }}">
                                                <label for="poliHPHT" class="text-muted fw-semibold">Hari Pertama Haid Terakhir (HPHT)</label>
                                            </div>
                                        </div>
                                    @elseif(str_contains($dept, 'POL-ANK'))
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input name="data_spesifik_poli[lingkar_kepala]" class="form-control shadow-none border-light-subtle fw-bold" id="poliLK" value="{{ $poliData['lingkar_kepala'] ?? '' }}" placeholder="cm">
                                                <label for="poliLK" class="text-muted fw-semibold">Lingkar Kepala (cm)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input name="data_spesifik_poli[imunisasi]" class="form-control shadow-none border-light-subtle" id="poliImunisasi" value="{{ $poliData['imunisasi'] ?? '' }}" placeholder="Imunisasi">
                                                <label for="poliImunisasi" class="text-muted fw-semibold">Imunisasi Terakhir</label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 text-center py-5 opacity-50">
                                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 80px; height: 80px;">
                                                <i class="fa-solid fa-folder-open fs-2 text-muted"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark">Data Spesifik Belum Dikonfigurasi</h6>
                                            <p class="text-muted small mb-0">Form khusus untuk unit <b>{{ $encounter->department->nama_depart }}</b> belum tersedia dalam sistem.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Aksi & Riwayat -->
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 90px; z-index: 10;">
                <div class="card-body p-4 bg-light border-bottom rounded-top-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-pill mb-3 transition-hover" style="background: linear-gradient(135deg, var(--simrs-primary), var(--simrs-primary-light)); border: none;">
                        <i class="fa-solid fa-signature me-2"></i>SIMPAN & T.T.
                    </button>
                    <button type="button" class="btn btn-light w-100 fw-semibold border border-light-subtle text-muted rounded-pill shadow-sm transition-hover">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Draft
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 bg-white border-bottom">
                        <div class="small fw-bold text-muted text-uppercase tracking-wider mb-3" style="font-size: 0.7rem;">Histori Pemeriksaan</div>
                        <button type="button" class="btn btn-sm btn-info bg-gradient text-white w-100 fw-semibold shadow-sm mb-4 rounded-pill transition-hover" data-bs-toggle="modal" data-bs-target="#modalTrendVitals">
                            <i class="fa-solid fa-chart-line me-2"></i>Lihat Tren Vitals
                        </button>
                        <div class="timeline-clinical small">
                            <div class="border-start border-2 border-primary ps-3 pb-3 position-relative ms-1">
                                <i class="fa-solid fa-circle text-primary position-absolute start-0 translate-middle-x bg-white" style="font-size: 0.7rem; top: 5px;"></i>
                                <div class="fw-bold text-dark mb-1">Hari Ini, {{ now()->format('H:i') }}</div>
                                <div class="text-muted fw-medium">Input CPPT Medis sedang berlangsung</div>
                            </div>
                            @forelse($encounter->prescriptions as $rx)
                                <div class="border-start border-2 border-success ps-3 pb-3 position-relative ms-1">
                                    <i class="fa-solid fa-circle text-success position-absolute start-0 translate-middle-x bg-white" style="font-size: 0.7rem; top: 5px;"></i>
                                    <div class="fw-bold text-dark mb-1">Resep Terbit <span class="font-monospace text-primary">({{ $rx->no_resep }})</span></div>
                                    <div class="text-muted fw-medium">{{ $rx->details->count() }} item obat diresepkan</div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                    <div class="p-4 bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-bold rounded-pill" onclick="window.history.back()">
                            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Antrean
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important; }
    .tracking-wider { letter-spacing: 0.1em; }
    .border-start-md { border-left: 1px solid var(--simrs-gray-200); }

    .custom-clinical-tabs .nav-link {
        color: var(--simrs-gray-500);
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        background-color: transparent;
    }
    .custom-clinical-tabs .nav-link:hover {
        color: var(--simrs-primary);
        background-color: rgba(11, 100, 119, 0.05);
    }
    .custom-clinical-tabs .nav-link.active {
        background-color: white !important;
        color: var(--simrs-primary) !important;
        box-shadow: 0 -3px 0 0 var(--simrs-primary) inset;
        border-radius: 0;
    }

    .form-floating > label { font-size: 0.85rem; padding-left: 1.25rem; }
    .form-control:focus, .form-select:focus { border-color: var(--simrs-primary); box-shadow: 0 0 0 0.25rem rgba(11, 100, 119, 0.1) !important; }

    @media (max-width: 767.98px) {
        .border-start-md { border-left: none; border-top: 1px solid var(--simrs-gray-200); padding-top: 10px; }
    }
</style>

@section('scripts')
<div class="modal fade" id="modalTrendVitals" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-info bg-gradient text-white border-0 p-4">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2"><i class="fa-solid fa-chart-line opacity-75"></i> Tren Tanda Vital Pasien</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div style="height: 350px;">
                            <canvas id="chartVitals"></canvas>
                        </div>
                    </div>
                </div>
                <div class="mt-4 small text-muted text-center fw-medium">
                    <i class="fa-solid fa-circle-info me-1 text-info"></i> Data grafik diambil dari 5 kunjungan terakhir pasien.
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
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4, pointHoverRadius: 6
                        },
                        {
                            label: 'Diastolik',
                            data: [80, 85, {{ $encounter->nursingAssessment?->tekanan_darah_diastolik ?? 0 }}],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4, pointHoverRadius: 6
                        },
                        {
                            label: 'Nadi',
                            data: [88, 92, {{ $encounter->nursingAssessment?->nadi ?? 0 }}],
                            borderColor: '#10B981',
                            borderWidth: 3,
                            borderDash: [5, 5],
                            tension: 0.4,
                            pointRadius: 4, pointHoverRadius: 6
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
                        y: { beginAtZero: false, grid: { color: 'rgba(226, 232, 240, 0.6)' }, border: { dash: [4, 4] } },
                        x: { grid: { display: false } }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        }
    });
</script>
@endsection

