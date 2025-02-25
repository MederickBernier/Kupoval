<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\AccountVerificationMail;
use App\Mail\PasswordResetMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\PaymentReceiptMail;
use App\Mail\ShippingNotificationMail;
use App\Mail\RefundConfirmationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Exception;

class EmailController extends Controller
{
    /**
     * Send account verification email.
     */
    public function sendVerificationEmail(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning("⚠️ User not found for verification email.");
                return back()->with('error', __('User not found.'));
            }

            $verificationUrl = URL::signedRoute('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);
            Mail::to($user->email)->send(new AccountVerificationMail($user, $verificationUrl));

            Log::info("✅ Verification email sent to: {$user->email}");
            return back()->with('success', __('Verification email sent.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to send verification email: " . $e->getMessage());
            return back()->with('error', __('Failed to send verification email.'));
        }
    }

    /**
     * Send password reset email.
     */
    public function sendPasswordResetEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email|exists:users,email']);

            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                Log::info("✅ Password reset email sent to: {$request->input('email')}");
                return back()->with('success', __('Password reset email sent.'));
            }

            Log::warning("⚠️ Password reset email failed for: {$request->input('email')}");
            return back()->withErrors(['email' => __('Unable to send reset email.')]);
        } catch (Exception $e) {
            Log::error("❌ Failed to send password reset email: " . $e->getMessage());
            return back()->with('error', __('Failed to send password reset email.'));
        }
    }

    /**
     * Send order confirmation email.
     */
    public function sendOrderConfirmationEmail(Order $order)
    {
        try {
            if (!$order) {
                Log::warning("⚠️ Order not found for confirmation email.");
                return back()->with('error', __('Order not found.'));
            }

            Mail::to($order->recipient_email)->send(new OrderConfirmationMail($order));

            Log::info("✅ Order confirmation email sent for Order #{$order->id}.");
            return back()->with('success', __('Order confirmation email sent.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to send order confirmation email: " . $e->getMessage());
            return back()->with('error', __('Failed to send order confirmation email.'));
        }
    }

    /**
     * Send payment receipt email.
     */
    public function sendPaymentReceiptEmail(Payment $payment)
    {
        try {
            if (!$payment || !$payment->order) {
                Log::warning("⚠️ Payment or order not found for receipt email.");
                return back()->with('error', __('Payment or order not found.'));
            }

            $order = $payment->order; // ✅ Retrieve the related order

            Mail::to($order->recipient_email)->send(new PaymentReceiptMail($order, $payment));

            Log::info("✅ Payment receipt email sent for Payment ID: {$payment->id}, Order ID: {$order->id}");
            return back()->with('success', __('Payment receipt email sent.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to send payment receipt email: " . $e->getMessage());
            return back()->with('error', __('Failed to send payment receipt email.'));
        }
    }

    /**
     * Send shipping notification email.
     */
    public function sendShippingNotificationEmail(Order $order)
    {
        try {
            if (!$order) {
                Log::warning("⚠️ Order not found for shipping notification email.");
                return back()->with('error', __('Order not found.'));
            }

            $trackingUrl = route('orders.tracking', ['order' => $order->id]);
            Mail::to($order->recipient_email)->send(new ShippingNotificationMail($order, $trackingUrl));

            Log::info("✅ Shipping notification email sent for Order #{$order->id}.");
            return back()->with('success', __('Shipping notification email sent.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to send shipping notification email: " . $e->getMessage());
            return back()->with('error', __('Failed to send shipping notification email.'));
        }
    }

    /**
     * Send refund confirmation email.
     */
    public function sendRefundConfirmationEmail(Payment $payment)
    {
        try {
            if (!$payment || !$payment->order) {
                Log::warning("⚠️ Payment or order not found for refund confirmation email.");
                return back()->with('error', __('Payment or order not found.'));
            }

            Mail::to($payment->order->recipient_email)->send(new RefundConfirmationMail($payment));

            Log::info("✅ Refund confirmation email sent for Payment ID: {$payment->id}");
            return back()->with('success', __('Refund confirmation email sent.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to send refund confirmation email: " . $e->getMessage());
            return back()->with('error', __('Failed to send refund confirmation email.'));
        }
    }
}
