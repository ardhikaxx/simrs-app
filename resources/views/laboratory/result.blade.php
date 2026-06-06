@extends('layouts.app')

@section('title', 'Input Hasil Laboratorium')
@section('page-title', 'Laboratorium Patologi Klinik')
@section('page-subtitle', 'Verifikasi data hasil pemeriksaan spesimen')

@section('content')
<div class="row g-4 mb-4">
    <!-- Ringkasan Pasien -->
    <div class="col-lg-8">
        <div class="simrs-card h-100 mb-0 border-0 shadow-sm overflow-hidden bg-white">
            <div class="simrs-card-body p-0">
                <div class="d-flex align-items-center bg-primary text-white p-3 gap-3">
                    <div class="brand-icon shadow-none bg-white text-primary" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div>
                        <div class="small fw-700 text-uppercase tracking-wider opacity-75">Order Layanan</div>
                        <h6 class="fw-800 mb-0">{{ $labOrder->jenis_pemeriksaan }} <span class="badge bg-white text-primary ms-2">{{ $labOrder->no_order }}</span></h6>
                    </div>
                    <div class="ms-auto text-end">
                        <span class="badge {{ $labOrder->prioritas === 'cito' ? 'bg-danger' : 'bg-info' }} px-3">
                            {{ strtoupper($labOrder->prioritas) }}
                        </span>
                    </div>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-md-6 border-end">
                            <div class="small text-muted fw-700 text-uppercase mb-1">Identitas Pasien</div>
                            <div class="fw-800 text-simrs-gray-900">{{ $labOrder->encounter->patient->nama_pasien }}</div>
                            <div class="small text-muted text-mono">{{ $labOrder->encounter->patient->no_rkm_medis }} | {{ $labOrder->encounter->patient->age }} Th ({{ $labOrder->encounter->patient->jenis_kelamin }})</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted fw-700 text-uppercase mb-1">Pengirim</div>
                            <div class="fw-800 text-simrs-gray-900">{{ $labOrder->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                            <div class="small text-muted">{{ $labOrder->encounter->department->nama_depart }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Keamanan -->
    <div class="col-lg-4">
        <div class="simrs-card h-100 mb-0 border-0 shadow-sm bg-white">
            <div class="simrs-card-header border-0 bg-transparent pb-0">
                <div class="simrs-card-title text-simrs-primary small">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Catatan Klinis Order</span>
                </div>
            </div>
            <div class="simrs-card-body">
                <div class="p-3 rounded-3 bg-light border italic small">
                    "{{ $labOrder->catatan_klinis ?: 'Tidak ada catatan klinis tambahan.' }}"
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('lab.hasil.update', $labOrder) }}" method="POST">
    @csrf
    <div class="simrs-card shadow-sm border-0">
        <div class="simrs-card-header bg-white">
            <div class="simrs-card-title text-simrs-primary">
                <i class="fa-solid fa-list-check"></i>
                <span>Parameter & Hasil Pengukuran</span>
            </div>
            <div class="ms-auto d-flex gap-2">
                <button type="button" class="btn btn-sm btn-simrs-outline py-1 px-3 shadow-sm border-0" id="addResult">
                    <i class="fa-solid fa-plus me-1"></i>Tambah Baris
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted text-uppercase fw-bold">
                        <th class="ps-4" style="width: 30%;">Parameter Pemeriksaan</th>
                        <th style="width: 15%;">Hasil (Nilai)</th>
                        <th style="width: 10%;">Satuan</th>
                        <th style="width: 20%;">Nilai Rujukan</th>
                        <th style="width: 15%;">Flag Status</th>
                        <th class="text-center pe-4" style="width: 10%;">Kritis?</th>
                    </tr>
                </thead>
                <tbody id="resultRows">
                @php($rows = $labOrder->results->count() ? $labOrder->results : collect([(object)['parameter'=>'','nilai'=>'','satuan'=>'','nilai_rujukan'=>'','flag'=>'normal','is_critical'=>false]]))
                @foreach($rows as $index => $result)
                    <tr>
                        <td class="ps-4">
                            <input name="parameter[]" class="form-control fw-600" value="{{ $result->parameter }}" placeholder="Contoh: Hemoglobin" required>
                        </td>
                        <td>
                            <input name="nilai[]" class="form-control fw-800 text-simrs-primary" value="{{ $result->nilai }}" placeholder="Nilai" required>
                        </td>
                        <td>
                            <input name="satuan[]" class="form-control small" value="{{ $result->satuan }}" placeholder="g/dL">
                        </td>
                        <td>
                            <input name="nilai_rujukan[]" class="form-control small" value="{{ $result->nilai_rujukan }}" placeholder="12.0 - 16.0">
                        </td>
                        <td>
                            <select name="flag[]" class="form-select small fw-bold">
                                <option value="normal" @selected($result->flag === 'normal')>Normal</option>
                                <option value="rendah" @selected($result->flag === 'rendah')>Rendah (L)</option>
                                <option value="tinggi" @selected($result->flag === 'tinggi')>Tinggi (H)</option>
                                <option value="abnormal" @selected($result->flag === 'abnormal')>Abnormal</option>
                            </select>
                        </td>
                        <td class="text-center pe-4">
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input type="checkbox" name="is_critical[]" value="{{ $index }}" class="form-check-input" @checked($result->is_critical)>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="simrs-card-body bg-light border-top">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted d-none d-md-block">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> Centang kolom "Kritis" untuk memicu notifikasi <i>Critical Result</i> ke DPJP.
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('lab.antrian') }}" class="btn btn-simrs-outline fw-bold border-0">
                        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
                    </a>
                    <button class="btn btn-simrs-primary py-2 px-5 fw-800 shadow-sm border-0">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>SIMPAN & VALIDASI HASIL
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.getElementById('addResult')?.addEventListener('click', () => {
    const tbody = document.getElementById('resultRows');
    const index = tbody.children.length;
    tbody.insertAdjacentHTML('beforeend', `<tr>
        <td class="ps-4"><input name="parameter[]" class="form-control fw-600" required></td>
        <td><input name="nilai[]" class="form-control fw-800 text-simrs-primary" required></td>
        <td><input name="satuan[]" class="form-control small"></td>
        <td><input name="nilai_rujukan[]" class="form-control small"></td>
        <td><select name="flag[]" class="form-select small fw-bold"><option value="normal">Normal</option><option value="rendah">Rendah (L)</option><option value="tinggi">Tinggi (H)</option><option value="abnormal">Abnormal</option></select></td>
        <td class="text-center pe-4"><div class="form-check form-switch d-flex justify-content-center"><input type="checkbox" name="is_critical[]" value="${index}" class="form-check-input"></div></td>
    </tr>`);
});
</script>
@endsection
