<!DOCTYPE html>
<html>
<head>
    <title>Your OTP</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
    <div style="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 style="color: #0F2C59;">Password Reset Request</h2>
        <p>You requested to reset your password. Use the following OTP code to complete the process. This code will expire in 15 minutes.</p>
        
        <div style="background-color: #f3f4f6; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; margin: 20px 0;">
            {{ $otp }}
        </div>
        
        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Best regards,<br>The NESS Seminars Team</p>
    </div>
</body>
</html>
