<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Message</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <h2>New Contact Message</h2>

    <p><strong>Name:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Message:</strong></p>
    <blockquote style="border-left: 4px solid #007bff; padding-left: 10px; color: #555;">
        {!! nl2br(e($messageContent)) !!}
    </blockquote>

    <p>Reply to this email to respond.</p>

</body>
</html>
