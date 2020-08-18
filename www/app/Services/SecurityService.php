<?php
declare(strict_types=1);


namespace App\Services;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Di\Injectable;

/**
 * SecurityService
 *
 * This is the security service which controls that users only have access to the modules they're assigned to
 */
class SecurityService extends Injectable
{
    public function isAllowed($component, $action){
        $user = $this->user;
        if (!$user) {
            $role = 'guest';
        } else {
            $role = 'user';
        }

        $acl = $this->getAcl();

        return $acl->isAllowed(
            $role,
            $component,
            $action
        );
    }

    protected function getAcl(): Memory
    {
        $acl = new Memory();
        $acl->addRole('user');
        $acl->addRole('guest');

        $acl->addComponent(
            'Users',
            [
                'index',
            ]
        );
        $acl->addComponent(
            'session',
            [
                'login',
                'logout',
            ]
        );

        $acl->allow('user', 'Users', '*');
        $acl->allow('*', 'session', '*');

        return $acl;
    }
}