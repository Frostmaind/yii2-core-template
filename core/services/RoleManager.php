<?php

namespace core\services;


use yii\rbac\ManagerInterface;

class RoleManager
{
    private $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function assign($userId, $roleName): void
    {
        if (!$role = $this->manager->getRole($roleName)) {
            throw new \DomainException('Role "' . $roleName .  '" does not exist');
        }
        $this->manager->revokeAll($userId);
        $this->manager->assign($role, $userId);
    }
}