<?php

namespace App\Mail;

use App\Models\Proyek;
use App\Models\RekananProyek;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenawaranStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $rekanan,
        public Proyek $proyek,
        public RekananProyek $penawaran,
        public string $status
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->status) {
            'diterima' => 'Penawaran Proyek Disetujui',
            'dipertimbangkan' => 'Penawaran Proyek Sedang Dipertimbangkan',
            'ditolak' => 'Penawaran Proyek Ditolak',
            default => 'Pembaruan Status Penawaran Proyek',
        };

        return new Envelope(
            from: config('mail.from.address'),
            replyTo: config('mail.from.address'),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penawaran-status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}