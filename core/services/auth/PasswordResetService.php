<?php

namespace core\services\auth;

use core\repositories\UserRepository;
use Yii;
use yii\mail\MailerInterface;
use core\forms\auth\PasswordResetRequestForm;
use core\forms\auth\ResetPasswordForm;

class PasswordResetService
{
    private $mailer;
    private $users;

    public function __construct(UserRepository $users, MailerInterface $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);

        if (!$user->isActive()) {
            throw new \DomainException('User is not active.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);
    }

    public function validateToken(string $token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }

        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }

}