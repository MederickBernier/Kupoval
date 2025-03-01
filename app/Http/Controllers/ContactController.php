<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactAdminMail;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Handle contact form submission and send an email to the admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string|min:10',
            ]);

            $adminEmail = Setting::where('key', 'site_email')->value('value');

            if (!$adminEmail) {
                Log::warning("Admin email is missing in settings.");
                return back()->with('warning', __('public/contact.admin_email_missing'));
            }

            Mail::to($adminEmail)->send(new ContactAdminMail($validated));

            Log::info("Contact email sent successfully to admin at: $adminEmail");

            return back()->with('success', __('public/contact.success_message'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error("Failed to send contact email: " . $e->getMessage());
            return back()->with('error', __('public/contact.error_message'));
        }
    }
}
