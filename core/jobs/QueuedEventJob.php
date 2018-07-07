<?php

namespace core\jobs;

use core\dispatchers\EventDispatcher;

class QueuedEventJob implements \yii\queue\JobInterface
{
    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function execute($queue): void
    {
        $dispatcher = \Yii::$container->get(EventDispatcher::class);
        $dispatcher->dispatch($this->event, true);
    }
}