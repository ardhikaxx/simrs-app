@extends('layouts.app')

@section('title', 'Hasil Laboratorium')
@section('page-title', 'Input Hasil Laboratorium')
@section('page-subtitle', $labOrder->no_order . ' - ' . $labOrder->encounter->patient->nama_pasien)

@section('content')
@php($rows = $labOrder->results->count() ? $labOrder->results : collect([(object)['parameter'=>'','nilai'=>'','satuan'=>'','nilai_rujukan'=>'','flag'=>'normal','is_critical'=>false]]))
<form action="{{ route('lab.hasil.update', $labOrder) }}" method="POST" class="simrs-card">
    @csrf
    <div class="simrs-card-header">
        <div>
            <div class="simrs-card-title"><i class="fa-solid fa-flask-vial"></i>{{ $labOrder->jenis_pemeriksaan }}</div>
            <div class="small text-muted">{{ $labOrder->doctor?->display_name }} - {{ strtoupper($labOrder->prioritas) }}</div>
        </div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-check me-1"></i>Verifikasi Hasil</button>
    </div>
    <div class="simrs-card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Parameter</th><th>Nilai</th><th>Satuan</th><th>Nilai Rujukan</th><th>Flag</th><th>Kritis</th></tr></thead>
                <tbody id="resultRows">
                @foreach($rows as $index => $result)
                    <tr>
                        <td><input name="parameter[]" class="form-control" value="{{ $result->parameter }}" required></td>
                        <td><input name="nilai[]" class="form-control" value="{{ $result->nilai }}" required></td>
                        <td><input name="satuan[]" class="form-control" value="{{ $result->satuan }}"></td>
                        <td><input name="nilai_rujukan[]" class="form-control" value="{{ $result->nilai_rujukan }}"></td>
                        <td>
                            <select name="flag[]" class="form-select">
                                @foreach(['normal','rendah','tinggi','abnormal'] as $flag)
                                    <option value="{{ $flag }}" @selected($result->flag === $flag)>{{ ucfirst($flag) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center"><input type="checkbox" name="is_critical[]" value="{{ $index }}" class="form-check-input" @checked($result->is_critical)></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-sm btn-simrs-outline" id="addResult"><i class="fa-solid fa-plus me-1"></i>Tambah Parameter</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.getElementById('addResult')?.addEventListener('click', () => {
    const tbody = document.getElementById('resultRows');
    const index = tbody.children.length;
    tbody.insertAdjacentHTML('beforeend', `<tr>
        <td><input name="parameter[]" class="form-control" required></td>
        <td><input name="nilai[]" class="form-control" required></td>
        <td><input name="satuan[]" class="form-control"></td>
        <td><input name="nilai_rujukan[]" class="form-control"></td>
        <td><select name="flag[]" class="form-select"><option value="normal">Normal</option><option value="rendah">Rendah</option><option value="tinggi">Tinggi</option><option value="abnormal">Abnormal</option></select></td>
        <td class="text-center"><input type="checkbox" name="is_critical[]" value="${index}" class="form-check-input"></td>
    </tr>`);
});
</script>
@endsection
