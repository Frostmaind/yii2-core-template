<?php

namespace core\dispatchers;

use core\jobs\QueuedEventJob;
use yii\queue\Queue;

class EventDispatcher implements EventDispatcherInterface
{
    private $defer = false;
    private $deferredEvents = [];

    private $listeners;
    private $listenersQueued;

    private $queue;

    private $container;

    public function __construct(Queue $queue, array $listeners = [], array $listenersQueued = [])
    {
        $this->container = \Yii::$container;
        $this->queue = $queue;
        $this->listeners = $listeners;
        $this->listenersQueued = $listenersQueued;
    }

    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event, $dontQueue = false): void
    {
        if ($this->defer) {
            $this->deferredEvents[] = $event;
            return;
        }

        $eventName = get_class($event);

        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listenerClass) {
                $this->callListener($listenerClass, $event);
            }
        }

        if (array_key_exists($eventName, $this->listenersQueued)) {
            if ($dontQueue) {
                foreach ($this->listenersQueued[$eventName] as $listenerClass) {
                    $this->callListener($listenerClass, $event);
                }
            } else {
                $this->queue->push(new QueuedEventJob($event));
            }

        }
    }

    private function callListener($listenerClass, $event): void
    {
        $listenerHandle =  [$this->container->get($listenerClass), 'handle'];
        $listenerHandle($event);
    }

    public function defer(): void
    {
        $this->defer = true;
    }

    public function clean(): void
    {
        $this->deferredEvents = [];
        $this->defer = false;
    }

    public function release(): void
    {
        $this->defer = false;
        foreach ($this->deferredEvents as $i => $event) {
            $this->dispatch($event);
            unset($this->deferredEvents[$i]);
        }
    }
}