<x-mail::message>
    # Invitation to Participate in NESS 2026

    Dear {{ $name }},

    We are pleased to invite you to participate in the **NESS 2026 Sensitization Seminars**. Your expertise and
    participation are highly valued as we work together to advance the goals of the summit.

    You have been invited as a **{{ ucfirst($type) }}**.

    To confirm your attendance and complete your registration, please copy the unique invitation token below and enter
    it in the NEXUS Mobile App under the **"Delegate / Speaker"** section.

    <x-mail::panel>
        **Invitation Token:**
        # {{ $token }}
    </x-mail::panel>

    <x-mail::button :url="'https://nexusysng.com/app-download'">
        Download NEXUS Mobile App
    </x-mail::button>

    ### Next Steps:
    1. Open the NEXUS App.
    2. Navigate to **Register → Delegate / Speaker**.
    3. Paste the token above to verify your invitation.
    4. Complete your profile and set your password.

    If you have any questions, please reply to this email or contact us at support@nexusysng.com.

    Best regards,
    **The NESS 2026 Organizing Committee**
    {{ config('app.name') }}
</x-mail::message>