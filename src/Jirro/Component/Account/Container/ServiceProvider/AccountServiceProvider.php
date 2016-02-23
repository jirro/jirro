<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Account\Container\ServiceProvider;

use Jirro\Component\Account\Auth;
use Jirro\Component\Http\Request\RequestAwareInterface;
use Jirro\Component\ORM\ObjectManager\ObjectManagerAwareInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AccountServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'service.account.auth',
    ];

    public function register()
    {
        $this->container->add(
            'service.account.auth',
            function () {
                $auth = new Auth();

                if ($auth instanceof RequestAwareInterface) {
                    $auth->setRequest($this->container->get('service.http.request'));
                }

                if ($auth instanceof ObjectManagerAwareInterface) {
                    $auth->setObjectManager($this->container->get('service.orm.object_manager'));
                }

                return $auth;
            }
        );
    }
}
