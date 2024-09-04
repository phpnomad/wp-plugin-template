<?php

namespace PHPNomad\Email\Interfaces;

use PHPNomad\Email\Exceptions\EmailSendFailedException;

interface EmailStrategy
{
    /**
     * @param array $to
     * @param string $subject
     * @param string $body
     * @param array $headers
     * @return mixed
     * @throws EmailSendFailedException
     */
    public function send(array $to, string $subject, string $body, array $headers): void;
}