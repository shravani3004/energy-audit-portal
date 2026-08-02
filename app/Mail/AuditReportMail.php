<?php

namespace App\Mail;

use App\Models\AuditReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuditReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AuditReport $report)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Energy Audit Report — {$this->report->building->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.audit_report',
            with: [
                'report' => $this->report,
                'building' => $this->report->building,
            ],
        );
    }
}
