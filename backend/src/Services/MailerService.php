<?php

namespace App\Services;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class MailerService
{
    private Mailer $mailer;

    public function __construct()
    {
        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $this->mailer = new Mailer($transport);
    }

    public function notify(string $to, string $subject, string $html): void
    {
        $email = (new Email())
            ->from('no-reply@vacation.test')
            ->to($to)
            ->subject($subject)
            ->html($html);

        $this->mailer->send($email);
    }
}
