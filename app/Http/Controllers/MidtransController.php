<?php

namespace App\Http\Controllers;

use App\Models\RentPayment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            $notification = new Notification();
            
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $order_id = $notification->order_id;
            $fraud = $notification->fraud_status;

            // Order ID format: RENT-{payment_id}-{timestamp}
            $parts = explode('-', $order_id);
            $payment_id = $parts[1] ?? null;

            if (!$payment_id) {
                return response()->json(['message' => 'Invalid Order ID'], 400);
            }

            $payment = RentPayment::findOrFail($payment_id);

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment->update(['status' => 'pending']);
                    } else {
                        $payment->update(['status' => 'paid']);
                    }
                }
            } else if ($transaction == 'settlement') {
                $payment->update(['status' => 'paid']);
            } else if ($transaction == 'pending') {
                $payment->update(['status' => 'pending']);
            } else if ($transaction == 'deny') {
                $payment->update(['status' => 'unpaid']);
            } else if ($transaction == 'expire') {
                $payment->update(['status' => 'unpaid']);
            } else if ($transaction == 'cancel') {
                $payment->update(['status' => 'unpaid']);
            }

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
