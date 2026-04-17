<?php

namespace App\Http\Controllers;

use App\Models\package;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request) //menyimpan data pembayaran baru
    {
        $payment = Payment::create([
            'phone_number' => $request->phone_number,
            'type' => $request->type,
            'name' => $request->name,
            'amount' => $request->amount,
            'status' => 'pending',
            'package_id' => $request->package_id
        ]);

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    public function updateStatus($id){ //mengubah status pembayaran menjadi sukses
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'payment not found'
            ], 404);
        }

        $payment->status = 'success';
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment successfully updated'
        ]);
    }

    public function success($id){ //memproses pembayaran yang sukses dan menambahkan pulsa atau kuota ke pengguna
        $payment = Payment::findOrFail($id);

        // Hindari proses dobel jika sudah success
        if ($payment->status === 'success') {
            return response()->json([
                'success' => false,
                'message' => 'Payment already processed'
            ], 400);
        }

        $payment->status = 'success';
        $payment->save();

        $user = User::where('phone_number', $payment->phone_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($payment->type === 'pulsa') {
            // ✅ Pakai kolom 'pulsa' sesuai tabel users-mu
            $user->pulsa += $payment->amount;

        } else {
            // Ambil quota dari kolom 'quota' di tabel packages
            $package = Package::find($payment->package_id);

            if ($package) {
                $user->quota += $package->quota; // ✅ langsung dari kolom quota
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment success',
            'balance' => [
                'pulsa' => $user->pulsa,
                'quota' => $user->quota,
            ]
        ]);
    }

    public function history($phone){ //menampilkan riwayat pembayaran berdasarkan nomor telepon
        $payments = Payment::where('phone_number', $phone)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($payments);
    }
}
