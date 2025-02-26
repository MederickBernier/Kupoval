<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; }
        h2 { color: #333; }
        p { margin: 10px 0; }
        .footer { margin-top: 20px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Message from {{ $name }}</h2>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Subject:</strong> {{ $subject }}</p>
        <p><strong>Message:</strong></p>
        <p>{!! nl2br(e($messageContent)) !!}</p>

        <p class="footer">This message was sent from the contact form on your artist profile.</p>
    </div>
</body>
</html>
