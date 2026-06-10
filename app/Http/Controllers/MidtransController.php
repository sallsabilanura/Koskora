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

            // Order ID format: {PREFIX}-{id}-{timestamp}
            $parts = explode('-', $order_id);
            $prefix = $parts[0] ?? null;
            $id = $parts[1] ?? null;

            if (!$prefix || !$id) {
                return response()->json(['message' => 'Invalid Order ID format'], 400);
            }

            $model = null;
            $statusField = 'status';

            switch ($prefix) {
                case 'RENT':
                    $model = \App\Models\RentPayment::find($id);
                    break;
                case 'LAUNDRY':
                    $model = \App\Models\LaundryOrder::find($id);
                    $statusField = 'payment_status';
                    break;
                case 'CLEANING':
                    $model = \App\Models\CleaningOrder::find($id);
                    $statusField = 'payment_status';
                    break;
                default:
                    return response()->json(['message' => 'Unknown prefix'], 400);
            }

            if (!$model) {
                return response()->json(['message' => 'Record not found'], 404);
            }

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $model->update([$statusField => 'pending']);
                    } else {
                        $model->update([$statusField => 'paid']);
                    }
                }
            } else if ($transaction == 'settlement') {
                $model->update([$statusField => 'paid']);
            } else if ($transaction == 'pending') {
                $model->update([$statusField => 'pending']);
            } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $model->update([$statusField => 'unpaid']);
            }

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
