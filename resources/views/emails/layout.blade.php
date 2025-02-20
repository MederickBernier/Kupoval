<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', __('Email Notification'))</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #444;
        }
        .email-content {
            padding: 20px;
            text-align: left;
        }
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            {{ config('app.name') }}
        </div>
        <div class="email-content">
            @yield('content')
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
        </div>
    </div>
</body>
</html>
