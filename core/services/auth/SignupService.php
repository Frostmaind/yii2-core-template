<?php

namespace core\services\auth;

use core\access\Rbac;
use core\entities\User\User;
use core\repositories\UserRepository;
use core\forms\auth\SignupForm;
use core\services\RoleManager;
use core\services\TransactionManager;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $users;
    private $roles;
    private $transaction;

    public function __construct(UserRepository $users, MailerInterface $mailer, RoleManager $roles, TransactionManager $transaction)
    {
        $this->users = $users;
        $this->mailer = $mailer;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function signup(SignupForm $form): void
    {
        /*$user = $this->users->getByEmail($form->email);

        if ($user) {
            throw new \DomainException('Email is already exists.');
        }*/

        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });

        /*$sent = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Signup confirm for ' . \Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }*/
    }

    public function confirm($token): void
    {
        if (empty($token)) {
            throw new \DomainException('Empty confirm token.');
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }

}