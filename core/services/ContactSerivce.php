<?php

namespace core\services;


use core\forms\ContactForm;
use yii\mail\MailerInterface;

class ContactSerivce
{

    private $adminEmail;
    private $mailer;

    public function __construct(string $adminEmail, MailerInterface $mailer)
    {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    public function send(ContactForm $form): void
    {
        $sent = $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }
    }

}