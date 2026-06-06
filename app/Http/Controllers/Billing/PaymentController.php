<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Support\SimrsNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request, BillingInvoice $invoice): RedirectResponse
    {
        $data = $request->validate([
            'metode_bayar' => ['required', 'in:tunai,debit,kredit,transfer,qris,bpjs,asuransi'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'referensi' => ['nullable', 'string', 'max:80'],
        ]);

        DB::transaction(function () use ($data, $invoice) {
            $invoice->payments()->create([
                'no_payment' => SimrsNumber::daily('PAY', 'payments', 'no_payment'),
                'cashier_id' => auth('staff')->id(),
                'metode_bayar' => $data['metode_bayar'],
                'jumlah_bayar' => $data['jumlah_bayar'],
                'referensi' => $data['referensi'] ?? null,
                'paid_at' => now(),
            ]);

            $paid = (float) $invoice->payments()->sum('jumlah_bayar');
            $status = $paid >= (float) $invoice->total_tagihan ? 'lunas' : 'parsial';

            $invoice->update([
                'total_dibayar' => $paid,
                'status' => $status,
                'paid_at' => $status === 'lunas' ? now() : null,
            ]);

            if ($status === 'lunas') {
                $invoice->encounter->update([
                    'status_antrian' => 'selesai',
                    'status_encounter' => 'selesai',
                    'waktu_keluar' => $invoice->encounter->waktu_keluar ?: now(),
                ]);
            }
        });

        return back()->with('swal_success', 'Pembayaran berhasil diproses.');
    }
}
