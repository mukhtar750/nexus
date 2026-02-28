<?php

namespace App\Mail;

use App\Models\SummitEoi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EoiSelected extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SummitEoi $eoi)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 You have been selected for NESS 2026 — Complete Your Registration',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.eoi.selected',
        );
    }
}
