<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\User;
use App\Services\BillingService;
use App\Support\SimrsNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EncounterController extends Controller
{
    public function create(Request $request): View
    {
        return view('registration.encounters.create', [
            'patients' => Patient::orderBy('nama_pasien')->limit(200)->get(),
            'departments' => Department::where('is_active', true)->orderBy('nama_depart')->get(),
            'doctors' => User::whereHas('roles', fn ($query) => $query->whereIn('slug', ['dokter-umum', 'dokter-spesialis']))
                ->where('is_active', true)
                ->orderBy('nama_lengkap')
                ->get(),
            'selectedPatient' => $request->integer('patient_id'),
        ]);
    }

    public function store(Request $request, BillingService $billingService): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'doctor_id' => ['nullable', 'exists:users,id'],
            'jenis_kunjungan' => ['required', 'in:rawat_jalan,rawat_inap,igd'],
            'cara_bayar' => ['required', 'in:umum,bpjs,asuransi'],
            'cara_masuk' => ['nullable', 'string', 'max:40'],
            'keluhan_awal' => ['nullable', 'string'],
            'rujukan_dari' => ['nullable', 'string', 'max:150'],
            'kelas_rawat' => ['nullable', 'string', 'max:20'],
            'kamar' => ['nullable', 'string', 'max:30'],
            'bed' => ['nullable', 'string', 'max:20'],
        ]);

        $department = Department::findOrFail($data['department_id']);
        $queueCount = Encounter::whereDate('waktu_masuk', now()->toDateString())
            ->where('department_id', $department->id)
            ->count() + 1;

        $encounter = Encounter::create($data + [
            'no_registrasi' => SimrsNumber::daily('REG', 'encounters', 'no_registrasi'),
            'no_antrian' => $department->kode_depart . '-' . str_pad((string) $queueCount, 3, '0', STR_PAD_LEFT),
            'status_antrian' => 'menunggu',
            'status_encounter' => 'terdaftar',
            'waktu_masuk' => now(),
        ]);

        $billingService->ensureInvoice($encounter);

        return redirect()->route('pendaftaran.antrian')
            ->with('swal_success', 'Kunjungan berhasil didaftarkan.');
    }
}
