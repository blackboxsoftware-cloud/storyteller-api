<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
</head>
<body>
    <h2>Hello, {{ $user->first_name ?? $user->email }}!</h2>

    <p>Thanks for signing up. Please verify your email address by clicking the link below:</p>

    <p>
        <a href="{{ $verificationUrl }}" style="padding: 10px 20px; background: #3490dc; color: white; text-decoration: none; border-radius: 5px;">
            Verify Email
        </a>
    </p>

    <p>If you did not sign up, you can safely ignore this message.</p>
</body>
</html>
