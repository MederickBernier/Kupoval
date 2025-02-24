<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;

class ContactController extends Controller
{
    public function send(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10',
        ]);

        $adminEmail = Setting::where('key', 'site_email')->value('value');

        if(!$adminEmail) return back()->with('error', __('public/contact.admin_email_missing'));

        Mail::to($adminEmail)->send(new ContactAdminMail($validated));

        return back()->with('success', __('public/contact.success_message'));
    }
}
