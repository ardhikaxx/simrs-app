@extends('layouts.app')

@section('title', 'Input Hasil Laboratorium')
@section('page-title', 'Laboratorium Patologi Klinik')
@section('page-subtitle', 'Validasi dan penginputan parameter hasil pemeriksaan spesimen')

@section('content')
<div class="row g-4 mb-4">
    <!-- Ringkasan Pasien -->
    <div class="col-lg-8">
        <div class="simrs-card mb-0 border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
            <div class="card-body p-0">
                <div class="d-flex align-items-stretch bg-primary bg-gradient text-white">
                    <div class="p-4 d-flex align-items-center justify-content-center bg-white bg-opacity-10 border-end border-white border-opacity-10" style="width: 100px;">
                        <i class="fa-solid fa-flask-vial fs-1"></i>
                    </div>
                    <div class="p-4 grow">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="badge bg-white text-primary fw-800">{{ $labOrder->no_order }}</span>
                            <h5 class="fw-800 mb-0">{{ $labOrder->jenis_pemeriksaan }}</h5>
                        </div>
                        <div class="d-flex align-items-center gap-4 small opacity-75 fw-bold">
                            <span><i class="fa-regular fa-calendar-check me-1"></i>{{ $labOrder->ordered_at?->format('d/m/Y H:i') }}</span>
                            <span><i class="fa-solid fa-user-doctor me-1"></i>{{ $labOrder->doctor?->display_name ?? 'Dokter Jaga' }}</span>
                        </div>
                    </div>
                    <div class="p-4 text-end d-flex flex-column justify-content-center">
                        <div class="small fw-bold text-uppercase tracking-wider opacity-75 mb-1" style="font-size: 0.6rem;">Status Prioritas</div>
                        @if($labOrder->prioritas === 'cito')
                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm fw-800 animate-pulse">
                                <i class="fa-solid fa-bolt-lightning me-1"></i>CITO
                            </span>
                        @else
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-800">
                                RUTIN
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="small text-muted fw-bold text-uppercase tracking-wider mb-2" style="font-size: 0.65rem;">Identitas Pasien</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-800" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    {{ strtoupper(substr($labOrder->encounter->patient->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-800 text-dark fs-5">{{ $labOrder->encounter->patient->nama_pasien }}</div>
                                    <div class="small text-muted fw-medium">
                                        {{ $labOrder->encounter->patient->no_rkm_medis }} <span class="mx-2 opacity-25">|</span> {{ $labOrder->encounter->patient->age }} Tahun <span class="mx-2 opacity-25">|</span> {{ $labOrder->encounter->patient->jenis_kelamin }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 border-start ps-md-4">
                            <div class="small text-muted fw-bold text-uppercase tracking-wider mb-2" style="font-size: 0.65rem;">Unit / Departemen Asal</div>
                            <div class="fw-800 text-dark mb-1">{{ $labOrder->encounter->department->nama_depart }}</div>
                            <div class="small text-muted fw-medium d-flex align-items-center gap-2">
                                <i class="fa-solid fa-bed-pulse text-primary opacity-50"></i>
                                {{ $labOrder->encounter->jenis_kunjungan === 'rawat_inap' ? 'Rawat Inap' : 'Rawat Jalan' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clinical Notes -->
    <div class="col-lg-4">
        <div class="simrs-card mb-0 border-0 shadow-sm rounded-4 bg-white h-100 overflow-hidden">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <div class="d-flex align-items-center gap-2 text-primary">
                    <i class="fa-solid fa-clipboard-question"></i>
                    <span class="fw-800 small text-uppercase tracking-wider" style="font-size: 0.7rem;">Indikasi & Catatan Klinis</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="p-3 rounded-4 bg-light border-0 italic small text-muted lh-base position-relative" style="min-height: 100px;">
                    <i class="fa-solid fa-quote-left position-absolute top-0 start-0 m-2 opacity-10 fs-2"></i>
                    <span class="position-relative">{{ $labOrder->catatan_klinis ?: 'Tidak ada catatan klinis tambahan dari dokter pengirim.' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('lab.hasil.update', $labOrder) }}" method="POST">
    @csrf
    <div class="simrs-card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-list-check fs-6"></i>
                    </div>
                    <h5 class="fw-800 mb-0">Parameter & Hasil Laboratorium</h5>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-4 fw-bold rounded-pill shadow-sm" id="addResult">
                    <i class="fa-solid fa-plus me-1"></i>TAMBAH BARIS
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted text-uppercase fw-800 tracking-wider">
                        <th class="ps-4 py-3 border-0" style="width: 30%;">Parameter Pemeriksaan</th>
                        <th class="py-3 border-0" style="width: 15%;">Hasil Ukur</th>
                        <th class="py-3 border-0" style="width: 10%;">Satuan</th>
                        <th class="py-3 border-0" style="width: 20%;">Nilai Rujukan</th>
                        <th class="py-3 border-0" style="width: 15%;">Status (Flag)</th>
                        <th class="text-center pe-4 py-3 border-0" style="width: 10%;">Kritis?</th>
                    </tr>
                </thead>
                <tbody id="resultRows">
                @php($rows = $labOrder->results->count() ? $labOrder->results : collect([(object)['parameter'=>'','nilai'=>'','satuan'=>'','nilai_rujukan'=>'','flag'=>'normal','is_critical'=>false]]))
                @foreach($rows as $index => $result)
                    <tr class="result-row border-bottom border-light">
                        <td class="ps-4">
                            <input name="parameter[]" class="form-control border-0 bg-transparent fw-bold shadow-none" value="{{ $result->parameter }}" placeholder="Nama Parameter..." required>
                        </td>
                        <td>
                            <input name="nilai[]" class="form-control border-0 bg-primary bg-opacity-10 text-primary fw-800 shadow-none text-center rounded-3" value="{{ $result->nilai }}" placeholder="Nilai" required>
                        </td>
                        <td>
                            <input name="satuan[]" class="form-control border-0 bg-transparent small shadow-none" value="{{ $result->satuan }}" placeholder="Satuan">
                        </td>
                        <td>
                            <input name="nilai_rujukan[]" class="form-control border-0 bg-transparent small shadow-none font-monospace" value="{{ $result->nilai_rujukan }}" placeholder="Contoh: 12.0 - 16.0">
                        </td>
                        <td>
                            <select name="flag[]" class="form-select border-0 bg-light small fw-800 rounded-3 shadow-none flag-selector">
                                <option value="normal" @selected($result->flag === 'normal') class="text-success">NORMAL</option>
                                <option value="rendah" @selected($result->flag === 'rendah') class="text-info">RENDAH (L)</option>
                                <option value="tinggi" @selected($result->flag === 'tinggi') class="text-danger">TINGGI (H)</option>
                                <option value="abnormal" @selected($result->flag === 'abnormal') class="text-warning">ABNORMAL</option>
                            </select>
                        </td>
                        <td class="text-center pe-4">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input type="checkbox" name="is_critical[]" value="{{ $index }}" class="form-check-input critical-toggle" @checked($result->is_critical)>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="card-body bg-light border-top p-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 d-flex align-items-center gap-3 p-3 mb-0 rounded-4">
                        <i class="fa-solid fa-triangle-exclamation text-warning fs-4"></i>
                        <div class="small text-dark fw-medium lh-sm">
                            <strong class="d-block mb-1">Perhatian Keamanan Pasien:</strong>
                            Gunakan tanda <strong>"Kritis"</strong> hanya untuk hasil yang masuk dalam kategori *Panic Value*. Sistem akan mengirim notifikasi darurat kepada DPJP setelah validasi.
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('lab.antrian') }}" class="btn btn-light px-4 fw-bold rounded-pill border">
                            <i class="fa-solid fa-arrow-left me-2"></i>BATAL
                        </a>
                        <button class="btn btn-primary px-5 py-2 fw-800 rounded-pill shadow-sm">
                            <i class="fa-solid fa-check-double me-2"></i>SIMPAN & VALIDASI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .result-row:focus-within { background-color: var(--simrs-gray-50); }
    .form-control::placeholder { color: var(--simrs-gray-300); font-weight: 500; }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
    
    .flag-selector[value="tinggi"] { color: var(--simrs-danger) !important; }
    .flag-selector[value="rendah"] { color: var(--simrs-info) !important; }
    .flag-selector[value="normal"] { color: var(--simrs-success) !important; }
</style>
@endsection

@section('scripts')
<script>
document.getElementById('addResult')?.addEventListener('click', () => {
    const tbody = document.getElementById('resultRows');
    const index = tbody.children.length;
    const row = `
    <tr class="result-row border-bottom border-light">
        <td class="ps-4">
            <input name="parameter[]" class="form-control border-0 bg-transparent fw-bold shadow-none" placeholder="Nama Parameter..." required>
        </td>
        <td>
            <input name="nilai[]" class="form-control border-0 bg-primary bg-opacity-10 text-primary fw-800 shadow-none text-center rounded-3" placeholder="Nilai" required>
        </td>
        <td>
            <input name="satuan[]" class="form-control border-0 bg-transparent small shadow-none" placeholder="Satuan">
        </td>
        <td>
            <input name="nilai_rujukan[]" class="form-control border-0 bg-transparent small shadow-none font-monospace" placeholder="Nilai Rujukan">
        </td>
        <td>
            <select name="flag[]" class="form-select border-0 bg-light small fw-800 rounded-3 shadow-none">
                <option value="normal" class="text-success">NORMAL</option>
                <option value="rendah" class="text-info">RENDAH (L)</option>
                <option value="tinggi" class="text-danger">TINGGI (H)</option>
                <option value="abnormal" class="text-warning">ABNORMAL</option>
            </select>
        </td>
        <td class="text-center pe-4">
            <div class="form-check form-switch d-flex justify-content-center">
                <input type="checkbox" name="is_critical[]" value="${index}" class="form-check-input">
            </div>
        </td>
    </tr>`;
    tbody.insertAdjacentHTML('beforeend', row);
});

// Color logic for flag selector (optional refinement)
document.addEventListener('change', (e) => {
    if (e.target.classList.contains('flag-selector')) {
        const val = e.target.value;
        e.target.className = 'form-select border-0 bg-light small fw-800 rounded-3 shadow-none flag-selector';
        if (val === 'tinggi') e.target.classList.add('text-danger');
        else if (val === 'rendah') e.target.classList.add('text-info');
        else if (val === 'normal') e.target.classList.add('text-success');
        else if (val === 'abnormal') e.target.classList.add('text-warning');
    }
});
</script>
@endsection
