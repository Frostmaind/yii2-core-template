<?php

namespace common\bootstrap;

use core\dispatchers\QueuedEventDispatcher;
use core\dispatchers\EventDispatcherInterface;
use core\dispatchers\EventDispatcher;
use core\entities\User\events\UserPasswordResetRequested;
use core\entities\User\events\UserSignupConfirmed;
use core\entities\User\events\UserSignupRequested;
use core\listeners\User\UserPasswordResetRequestedListener;
use core\listeners\User\UserSignupConfirmedListener;
use core\listeners\User\UserSignupRequestedListener;
use core\services\auth\PasswordResetService;
use core\services\ContactSerivce;
use yii\base\BootstrapInterface;

use yii\mail\MailerInterface;
use yii\queue\Queue;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{

    public function bootstrap($app)
    {

        $container = \Yii::$container;

        $container->setSingleton(Queue::class, function () use ($app) {
            return $app->get('queue');
        });

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

        $container->setSingleton(EventDispatcherInterface::class, EventDispatcher::class);

        $container->setSingleton(EventDispatcher::class, function () use ($app) {
            return new EventDispatcher(
                $app->get('queue'),
                [],
                [
                    UserSignupRequested::class => [UserSignupRequestedListener::class],
                    UserSignupConfirmed::class => [UserSignupConfirmedListener::class],
                    UserPasswordResetRequested::class => [UserPasswordResetRequestedListener::class],
                ]
            );
        });

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(PasswordResetService::class);

        $container->setSingleton(ContactSerivce::class, [], [
            $app->params['adminEmail']
        ]);

    }
}