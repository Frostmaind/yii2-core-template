<?php

namespace core\listeners\User;

use core\entities\User\events\UserPasswordResetRequested;
use yii\mail\MailerInterface;

class UserPasswordResetRequestedListener
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(UserPasswordResetRequested $event): void
    {
        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $event->user]
            )
            ->setTo($event->user->email)
            ->setSubject('Password reset for ' . \Yii::$app->name)
            ->send();
        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }
}