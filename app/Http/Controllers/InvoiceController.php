<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Elibyy\TCPDF\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class InvoiceController extends Controller
{
    public function generateInvoice($orderId)
    {
        try {
            Log::info("🔹 Invoice generation initiated for Order #{$orderId}");

            // ✅ Fetch Order
            $order = Order::with([
                'user.profile',
                'items.artwork',
                'billingAddress',
                'shippingAddress'
            ])->findOrFail($orderId);

            // ✅ Fetch site settings
            $settings = Setting::whereIn('key', [
                'site_name',
                'site_address',
                'site_email',
                'site_phone'
            ])->pluck('value', 'key');

            // ✅ Set Shipping Address (fallback to Billing if missing)
            $shippingAddress = $order->shippingAddress ?? $order->billingAddress;

            // ✅ Calculate Subtotal, Tax & Total
            $order->subtotal = $order->items->sum(fn($item) => $item->quantity * $item->unit_price);
            $order->tax_rate = $order->tax_rate ?? 0;
            $order->tax_amount = ($order->tax_rate > 0) ? $order->subtotal * ($order->tax_rate / 100) : 0;
            $order->total = $order->subtotal + $order->tax_amount;

            // ✅ Retrieve User Profile (or use fallback)
            $userProfile = $order->user->profile ?? null;
            $clientName = $userProfile
                ? trim(($userProfile->title ? "{$userProfile->title} " : '') . "{$userProfile->first_name} {$userProfile->last_name}")
                : $order->recipient_name;

            if (!$clientName) {
                Log::warning("⚠️ No client name found for Order #{$order->id}");
                return back()->with('error', __('Invoice generation failed: No client name found.'));
            }

            // ✅ Client Email and Phone
            $clientEmail = $order->user->email ?? $order->recipient_email;
            $clientPhone = $userProfile->phone ?? null;

            // ✅ Create PDF
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator(config('app.name'));
            $pdf->SetAuthor($settings['site_name'] ?? 'Kupoval');
            $pdf->SetTitle(__('invoice.title', ['order' => $order->id]));
            $pdf->SetMargins(10, 10, 10);
            $pdf->AddPage();

            // ✅ Render Blade View into HTML
            $html = view('pdf.invoice', compact('order', 'settings', 'shippingAddress', 'clientName', 'clientEmail', 'clientPhone'))->render();

            // ✅ Write HTML to PDF
            $pdf->writeHTML($html, true, false, true, false, '');

            Log::info("✅ Invoice generated successfully for Order #{$order->id}");

            // ✅ Output PDF
            return $pdf->Output("invoice_{$settings['site_name']}_{$order->id}.pdf", 'I');
        } catch (ModelNotFoundException $e) {
            Log::error("❌ Invoice Error: Order #{$orderId} not found.");
            return back()->with('error', __('Invoice generation failed: Order not found.'));
        } catch (Exception $e) {
            Log::error("❌ Unexpected Invoice Error: " . $e->getMessage());
            return back()->with('error', __('An unexpected error occurred while generating the invoice.'));
        } finally {
            Log::info("🔹 Invoice process completed for Order #{$orderId}");
        }
    }
}
