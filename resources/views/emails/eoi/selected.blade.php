<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You've been selected — NESS 2026</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #1a3a6b 0%, #2563eb 100%);
            padding: 40px 32px;
            text-align: center;
        }

        .header img {
            height: 48px;
            margin-bottom: 16px;
        }

        .header h1 {
            color: #ffffff;
            font-size: 26px;
            margin: 0 0 8px;
            font-weight: 700;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin: 0;
        }

        .badge {
            background: #22c55e;
            color: white;
            display: inline-block;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin: 24px 0 0;
        }

        .body {
            padding: 36px 32px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 16px;
        }

        .text {
            font-size: 15px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .details-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 20px 24px;
            margin: 24px 0;
        }

        .details-box p {
            margin: 6px 0;
            font-size: 14px;
            color: #166534;
        }

        .details-box strong {
            color: #14532d;
        }

        .cta-btn {
            display: block;
            width: fit-content;
            margin: 28px auto;
            background: linear-gradient(135deg, #1a3a6b, #2563eb);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            text-align: center;
        }

        .token-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .token-box p {
            margin: 0 0 8px;
            font-size: 13px;
            color: #92400e;
            font-weight: 600;
        }

        .token-box code {
            font-size: 12px;
            color: #78350f;
            word-break: break-all;
            background: #fef3c7;
            padding: 4px 8px;
            border-radius: 4px;
            display: block;
        }

        .footer {
            background: #f8fafc;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            font-size: 12px;
            color: #94a3b8;
            margin: 4px 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Congratulations!</h1>
            <p>NESS 2026 Sensitization Summit</p>
            <span class="badge">✓ Selected</span>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Dear {{ $eoi->full_name }},</p>

            <p class="text">
                We are pleased to inform you that your Expression of Interest for the
                <strong>NESS 2026 Sensitization Summit</strong> has been reviewed and
                you have been <strong>selected</strong> to participate!
            </p>

            <div class="details-box">
                <p><strong>Summit Location:</strong> {{ $eoi->summit->city ?? 'TBA' }}</p>
                <p><strong>Summit Date:</strong> {{ $eoi->summit->date ?? 'TBA' }}</p>
                <p><strong>Your Preferred Location:</strong>
                    {{ ucwords(str_replace('_', ' ', $eoi->preferred_location ?? '')) }}</p>
            </div>

            <p class="text">
                To complete your registration and secure your spot, please click the button below.
                You will be asked to set a password for your ExportHub account.
            </p>

            <a href="{{ config('app.url') }}/register/eoi?token={{ $eoi->registration_token }}" class="cta-btn">Complete
                My Registration →</a>

            <p class="text" style="font-size: 13px; color: #718096;">
                If the button doesn't work, copy and paste this link into your browser:
            </p>
            <div class="token-box">
                <p>Your Registration Token (keep this safe):</p>
                <code>{{ $eoi->registration_token }}</code>
            </div>

            <p class="text">
                This link is unique to you. Please do not share it with anyone.
                If you have any questions, reply to this email.
            </p>

            <p class="text">
                Congratulations once again, and we look forward to seeing you at NESS 2026!
            </p>

            <p class="text">
                Warm regards,<br>
                <strong>The NEXUS Team</strong><br>
                <span style="color: #94a3b8; font-size: 13px;">Nigeria Non-Oil Export Sensitization System</span>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This email was sent to {{ $eoi->email }} because you submitted an EOI for NESS 2026.</p>
            <p>© {{ date('Y') }} ExportHub / NEXUS. All rights reserved.</p>
        </div>
    </div>
</body>

</html>