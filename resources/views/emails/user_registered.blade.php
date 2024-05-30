<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Application</title>
</head>
<body>
    <h1>Welcome, {{ $user->full_name }}!</h1>
    <p>Thank you for signing up on our platform and your account number is <strong>{{ $account_number }}</strong>.</p>
    <p>You can now use your account number to make your first deposit and purchase your dream sneakers</p>
    <p>We are excited to have you on board!</p>
</body>
</html>
