<?php

namespace core\services;

use core\dispatchers\EventDispatcherInterface;

class TransactionManager
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function wrap(callable $function): void
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $this->dispatcher->defer();
            $function();
            $transaction->commit();
            $this->dispatcher->release();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->dispatcher->clean();
            throw $e;
        }
    }
}