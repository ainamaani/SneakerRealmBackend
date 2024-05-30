<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Request</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    <p>Hello {{ $user->full_name }},</p>
    <p>You requested a password reset and the code is:</p>
    <p>
        <strong>{{ $reset_code }}</strong>
    </p>
    <p>If you did not request this, please ignore this email.</p>
    <p>Thank you,</p>
    <p>Your Application Team</p>
</body>
</html>
