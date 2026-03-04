<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation to NESS 2026</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 40px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .header {
            background-color: #0f172a;
            padding: 32px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            font-size: 26px;
            margin: 0;
            letter-spacing: 4px;
            font-weight: 800;
        }

        .header p {
            color: #94a3b8;
            margin: 6px 0 0;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .body {
            padding: 40px 48px;
            color: #374151;
        }

        .body h2 {
            font-size: 20px;
            color: #0f172a;
            margin-top: 0;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 12px;
        }

        .body p {
            line-height: 1.7;
            font-size: 15px;
            color: #4b5563;
        }

        .token-box {
            background-color: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 24px 0;
            text-align: center;
        }

        .token-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin: 0 0 8px;
        }

        .token-value {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 2px;
            word-break: break-all;
            margin: 0;
        }

        .badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 99px;
            margin-bottom: 16px;
        }

        .steps {
            background-color: #f0fdf4;
            border-left: 4px solid #16a34a;
            border-radius: 4px;
            padding: 16px 20px;
            margin: 24px 0;
        }

        .steps h3 {
            font-size: 14px;
            color: #15803d;
            margin: 0 0 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .steps ol {
            margin: 0;
            padding-left: 20px;
            color: #374151;
            font-size: 14px;
            line-height: 2;
        }

        .btn-container {
            text-align: center;
            margin: 28px 0;
        }

        .btn {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 14px 32px;
            border-radius: 8px;
            letter-spacing: 1px;
        }

        .footer {
            background-color: #f8fafc;
            padding: 24px 48px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            color: #94a3b8;
            font-size: 12px;
            margin: 4px 0;
        }

        .footer a {
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>NEXUS</h1>
                <p>NESS 2026 — Nigerian Export Summit</p>
            </div>

            <!-- Body -->
            <div class="body">
                <h2>Invitation to Participate in NESS 2026</h2>

                <p>Dear <strong>{{ $name }}</strong>,</p>

                <p>We are pleased to invite you to participate in the <strong>NESS 2026 Sensitization Seminars</strong>.
                    Your expertise and participation are highly valued as we work together to advance the goals of the
                    summit.</p>

                <span class="badge">You have been invited as a {{ ucfirst($type) }}</span>

                <p>To confirm your attendance and complete your registration, use the unique invitation token below in
                    the NEXUS Mobile App:</p>

                <!-- Token Box -->
                <div class="token-box">
                    <p class="token-label">Your Invitation Token</p>
                    <p class="token-value">{{ $token }}</p>
                </div>

                <!-- CTA Button -->
                <div class="btn-container">
                    <a href="https://nexusysng.com/app-download" class="btn">Download NEXUS Mobile App</a>
                </div>

                <!-- Steps -->
                <div class="steps">
                    <h3>Next Steps</h3>
                    <ol>
                        <li>Open the NEXUS App.</li>
                        <li>Navigate to <strong>Register → {{ ucfirst($type) }}</strong>.</li>
                        <li>Paste the token above to verify your invitation.</li>
                        <li>Complete your profile and set your password.</li>
                    </ol>
                </div>

                <p>If you have any questions, please reply to this email or contact us at <a
                        href="mailto:hello@nexusysng.com">hello@nexusysng.com</a>.</p>

                <p>Best regards,<br><strong>The NESS 2026 Organizing Committee</strong></p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p><strong>NEXUS — Nigerian Export Summit 2026</strong></p>
                <p>This email was sent to you because you were invited to participate in NESS 2026.</p>
                <p><a href="mailto:hello@nexusysng.com">hello@nexusysng.com</a></p>
            </div>
        </div>
    </div>
</body>

</html>