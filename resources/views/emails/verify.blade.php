<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
</head>
<body style="background: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f4f4f4; min-height: 100vh;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 500px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); overflow: hidden;">
                    <tr>
                        <td style="background: #f9c406; padding: 32px 0; text-align: center;">
                            <img src="https://img.icons8.com/ios-filled/50/ffffff/open-book--v2.png" alt="Logo" width="48" style="vertical-align: middle; margin-bottom: 8px;">
                            <h1 style="color: #222; font-family: Arial, sans-serif; margin: 0; font-size: 28px; letter-spacing: 1px;">Muslim Storytellers</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px 32px 16px 32px; font-family: Arial, sans-serif; color: #222;">
                            <h2 style="margin-top: 0; font-size: 22px;">Hello, {{ $user->first_name ?? $user->email }}!</h2>
                            <p style="font-size: 16px; line-height: 1.6;">
                                <strong>Welcome to Muslim Storytellers!</strong>
                                <br><br>
                                Thank you for signing up and joining our vibrant community of storytellers and Service Providers. We're excited to have you on board and can't wait to see the amazing stories you'll share and discover.
                            </p>
                            <p style="font-size: 16px; line-height: 1.6;">
                                To get started, please verify your email address by clicking the button below. This helps us keep your account secure and ensures you receive important updates.
                            </p>
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 14px 32px; background: #f9c406; color: #222; font-weight: bold; font-size: 16px; border-radius: 6px; text-decoration: none; letter-spacing: 1px; box-shadow: 0 2px 6px rgba(0,0,0,0.07);">
                                    Verify Email
                                </a>
                            </div>
                            <p style="font-size: 15px; color: #666;">
                                If you did not sign up for this account, you can safely ignore this message.
                            </p>
                            <p style="font-size: 15px; color: #666; margin-bottom: 0;">
                                <em>Thank you for being part of our community.<br>
                                The Muslim Storytellers Team</em>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #f9c406; text-align: center; padding: 18px 0;">
                            <span style="color: #222; font-size: 13px; font-family: Arial, sans-serif;">
                                &copy; {{ date('Y') }} Muslim Storytellers. All rights reserved.
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
