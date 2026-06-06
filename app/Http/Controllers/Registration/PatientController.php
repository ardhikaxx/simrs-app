<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Support\SimrsNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $patients = Patient::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where('nama_pasien', 'like', $term)
                    ->orWhere('no_rkm_medis', 'like', $term)
                    ->orWhere('nik', 'like', $term)
                    ->orWhere('no_bpjs', 'like', $term);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('registration.patients.index', compact('patients'));
    }

    public function create(): View
    {
        return view('registration.patients.create', ['noRm' => SimrsNumber::medicalRecord()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'no_rkm_medis' => ['required', 'string', 'max:20', 'unique:patients,no_rkm_medis'],
            'nik' => ['required', 'string', 'min:16', 'max:20', 'unique:patients,nik'],
            'no_bpjs' => ['nullable', 'string', 'max:20', 'unique:patients,no_bpjs'],
            'nama_pasien' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tgl_lahir' => ['required', 'date'],
            'tempat_lahir' => ['nullable', 'string', 'max:80'],
            'golongan_darah' => ['nullable', 'string', 'max:3'],
            'agama' => ['nullable', 'string', 'max:30'],
            'status_perkawinan' => ['nullable', 'string', 'max:30'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'pendidikan' => ['nullable', 'string', 'max:50'],
            'alamat_lengkap' => ['required', 'string'],
            'kelurahan' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kota' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'no_telp_pasien' => ['nullable', 'string', 'max:20'],
            'kontak_darurat_nama' => ['nullable', 'string', 'max:150'],
            'kontak_darurat_telp' => ['nullable', 'string', 'max:20'],
            'alergi' => ['nullable', 'string'],
        ]);

        $patient = Patient::create($data + ['is_active' => true]);

        return redirect()->route('pendaftaran.pasien.show', $patient)
            ->with('swal_success', 'Data pasien berhasil disimpan.');
    }

    public function show(Patient $patient): View
    {
        $patient->load(['encounters.department', 'encounters.doctor', 'encounters.billingInvoice']);

        return view('registration.patients.show', compact('patient'));
    }
}
