<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\AccountVerificationMail;
use App\Mail\PasswordResetMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\PaymentReceiptMail;
use App\Mail\ShippingNotificationMail;
use App\Mail\RefundConfirmationMail;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Send account verification email.
     */
    public function sendVerificationEmail(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', __('User not found.'));
        }

        Mail::to($user->email)->send(new AccountVerificationMail($user));

        return back()->with('success', __('Verification email sent.'));
    }

    /**
     * Send password reset email.
     */
    public function sendPasswordResetEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __('Password reset email sent.'))
            : back()->withErrors(['email' => __('Unable to send reset email.')]);
    }

    /**
     * Send order confirmation email.
     */
    public function sendOrderConfirmationEmail(Order $order)
    {
        if (!$order) {
            return back()->with('error', __('Order not found.'));
        }

        Mail::to($order->recipient_email)->send(new OrderConfirmationMail($order));

        return back()->with('success', __('Order confirmation email sent.'));
    }

    /**
     * Send payment receipt email.
     */
    public function sendPaymentReceiptEmail(Payment $payment)
    {
        if (!$payment || !$payment->order) {
            return back()->with('error', __('Payment or order not found.'));
        }

        Mail::to($payment->order->recipient_email)->send(new PaymentReceiptMail($payment));

        return back()->with('success', __('Payment receipt email sent.'));
    }

    /**
     * Send shipping notification email.
     */
    public function sendShippingNotificationEmail(Order $order)
    {
        if (!$order) {
            return back()->with('error', __('Order not found.'));
        }

        Mail::to($order->recipient_email)->send(new ShippingNotificationMail($order));

        return back()->with('success', __('Shipping notification email sent.'));
    }

    /**
     * Send refund confirmation email.
     */
    public function sendRefundConfirmationEmail(Payment $payment)
    {
        if (!$payment || !$payment->order) {
            return back()->with('error', __('Payment or order not found.'));
        }

        Mail::to($payment->order->recipient_email)->send(new RefundConfirmationMail($payment));

        return back()->with('success', __('Refund confirmation email sent.'));
    }
}
