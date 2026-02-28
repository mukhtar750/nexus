<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NESS 2026 — Application Update</title>
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

        .header h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0 0 8px;
            font-weight: 700;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin: 0;
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

        .reason-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .reason-box p {
            margin: 0;
            font-size: 14px;
            color: #9a3412;
        }

        .next-steps {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 20px 24px;
            margin: 24px 0;
        }

        .next-steps h3 {
            font-size: 14px;
            font-weight: 700;
            color: #1e40af;
            margin: 0 0 10px;
        }

        .next-steps ul {
            margin: 0;
            padding-left: 18px;
        }

        .next-steps li {
            font-size: 13px;
            color: #1e3a8a;
            margin-bottom: 6px;
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
            <h1>NESS 2026 Application Update</h1>
            <p>Non-Oil Export Sensitization Summit</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Dear {{ $eoi->full_name }},</p>

            <p class="text">
                Thank you for submitting your Expression of Interest for the
                <strong>NESS 2026 Sensitization Summit</strong>. We truly appreciate
                your interest and the time you took to apply.
            </p>

            <p class="text">
                After careful review of all applications, we regret to inform you that
                we are unable to offer you a place at this time due to the high volume
                of applications received.
            </p>

            @if($eoi->rejection_reason)
                <div class="reason-box">
                    <p><strong>Feedback from our team:</strong><br>{{ $eoi->rejection_reason }}</p>
                </div>
            @endif

            <div class="next-steps">
                <h3>What you can do next:</h3>
                <ul>
                    <li>Continue to build your export capacity through the ExportHub app</li>
                    <li>Access free resources and training modules on NEXUS</li>
                    <li>Watch out for future NESS events and summits</li>
                    <li>Contact us if you believe this decision was made in error</li>
                </ul>
            </div>

            <p class="text">
                We encourage you to keep growing your export business and hope to see
                you at a future NESS event. Your application details remain on file
                for future consideration.
            </p>

            <p class="text">
                Thank you again for your interest in NESS 2026.
            </p>

            <p class="text">
                Warm regards,<br>
                <strong>The NEXUS Team</strong><br>
                <span style="color: #94a3b8; font-size: 13px;">Nigeria Non-Oil Export Sensitization System</span>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This email was sent to {{ $eoi->email }} regarding your NESS 2026 EOI application.</p>
            <p>© {{ date('Y') }} ExportHub / NEXUS. All rights reserved.</p>
        </div>
    </div>
</body>

</html>