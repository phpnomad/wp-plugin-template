<?php

namespace PHPNomad\Integrations\WordPress\Strategies;

use PHPMailer\PHPMailer\PHPMailer;
use PHPNomad\Email\Exceptions\EmailSendFailedException;
use PHPNomad\Email\Interfaces\EmailStrategy as EmailStrategyInterface;

class EmailStrategy implements EmailStrategyInterface
{
    public function send(array $to, string $subject, string $body, array $headers): void
    {
        /**
         * @var PHPMailer $phpmailer
         */
        global $phpmailer;

        $sent = wp_mail($to, $subject, $body, $headers);

        if (!$sent) {
            throw new EmailSendFailedException($phpmailer->ErrorInfo);
        }
    }
}