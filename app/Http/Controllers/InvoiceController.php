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
    /**
     * Generate an invoice for a given order.
     *
     * This method retrieves the order details, calculates the subtotal, tax, and total amounts,
     * and generates a PDF invoice using TCPDF. The generated invoice is then returned as a PDF response.
     *
     * @param int $orderId The ID of the order for which the invoice is to be generated.
     * @return \Illuminate\Http\Response The generated PDF invoice.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the order is not found.
     * @throws \Exception If an unexpected error occurs during invoice generation.
     */
    public function generateInvoice($orderId)
    {
        try {
            Log::info("🔹 Invoice generation initiated for Order #{$orderId}");

            $order = Order::with([
                'user.profile',
                'items.artwork',
                'billingAddress',
                'shippingAddress'
            ])->findOrFail($orderId);

            $settings = Setting::whereIn('key', [
                'site_name',
                'site_address',
                'site_email',
                'site_phone'
            ])->pluck('value', 'key');

            $shippingAddress = $order->shippingAddress ?? $order->billingAddress;

            $order->subtotal = $order->items->sum(fn($item) => $item->quantity * $item->unit_price);
            $order->tax_rate = $order->tax_rate ?? 0;
            $order->tax_amount = ($order->tax_rate > 0) ? $order->subtotal * ($order->tax_rate / 100) : 0;
            $order->total = $order->subtotal + $order->tax_amount;

            $userProfile = $order->user->profile ?? null;
            $clientName = $userProfile
                ? trim(($userProfile->title ? "{$userProfile->title} " : '') . "{$userProfile->first_name} {$userProfile->last_name}")
                : $order->recipient_name;

            if (!$clientName) {
                Log::warning("⚠️ No client name found for Order #{$order->id}");
                return back()->with('error', __('Invoice generation failed: No client name found.'));
            }

            $clientEmail = $order->user->email ?? $order->recipient_email;
            $clientPhone = $userProfile->phone ?? null;

            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator(config('app.name'));
            $pdf->SetAuthor($settings['site_name'] ?? 'Kupoval');
            $pdf->SetTitle(__('invoice.title', ['order' => $order->id]));
            $pdf->SetMargins(10, 10, 10);
            $pdf->AddPage();

            $html = view('pdf.invoice', compact('order', 'settings', 'shippingAddress', 'clientName', 'clientEmail', 'clientPhone'))->render();

            $pdf->writeHTML($html, true, false, true, false, '');

            Log::info("✅ Invoice generated successfully for Order #{$order->id}");

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
