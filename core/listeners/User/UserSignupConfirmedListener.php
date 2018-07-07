<?php

namespace core\listeners\User;

use core\entities\User\events\UserSignupConfirmed;
use yii\mail\MailerInterface;

class UserSignupConfirmedListener
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(UserSignupConfirmed $event): void
    {
        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirmed-html', 'text' => 'auth/signup/confirmed-text'],
                ['user' => $event->user]
            )
            ->setTo($event->user->email)
            ->setSubject('Welcome to ' . \Yii::$app->name . ', ' . $event->user->username)
            ->send();
        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }
}