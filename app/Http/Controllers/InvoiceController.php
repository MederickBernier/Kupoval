<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Elibyy\TCPDF\TCPDF;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generateInvoice($orderId)
    {
        $order = Order::with([
            'user.profile', // Load user profile
            'items.artwork',
            'billingAddress',
            'shippingAddress'
        ])->findOrFail($orderId);

        // Fetch site settings
        $settings = Setting::whereIn('key', [
            'site_name',
            'site_address',
            'site_email',
            'site_phone'
        ])->pluck('value', 'key');

        // If no shipping address, use the billing address
        $shippingAddress = $order->shippingAddress ?? $order->billingAddress;

        // Calculate subtotal
        $order->subtotal = $order->items->sum(fn($item) => $item->quantity * $item->unit_price);

        // Set tax only if applicable
        $order->tax_rate = $order->tax_rate ?? 0;
        $order->tax_amount = ($order->tax_rate > 0) ? $order->subtotal * ($order->tax_rate / 100) : 0;

        // Total calculation
        $order->total = $order->subtotal + $order->tax_amount;

        // Retrieve user profile details
        $userProfile = $order->user->profile ?? null;
        $clientName = $userProfile
            ? trim(($userProfile->title ? "{$userProfile->title} " : '') . "{$userProfile->first_name} {$userProfile->last_name}")
            : $order->recipient_name; // Fallback to recipient_name if no profile exists

        // Client Email and Phone
        $clientEmail = $order->user->email ?? $order->recipient_email;
        $clientPhone = $userProfile->phone ?? null;

        // Create PDF instance
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(config('app.name'));
        $pdf->SetAuthor($settings['site_name'] ?? 'Kupoval');
        $pdf->SetTitle(__('invoice.title', ['order' => $order->id]));
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        // Render HTML from Blade view
        $html = view('pdf.invoice', compact('order', 'settings', 'shippingAddress', 'clientName', 'clientEmail', 'clientPhone'))->render();

        // Write HTML to PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        return $pdf->Output("invoice_{$settings['site_name']}_{$order->id}.pdf", 'I');
    }
}
