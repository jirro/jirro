<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http\View\Extension;

use Jirro\Bundle\AccountBundle\Domain\User;
use League\Plates\Engine as View;
use League\Plates\Extension\ExtensionInterface;

class AuthenticatedUserExtension implements ExtensionInterface
{
    private $authenticatedUser;

    public function __construct(User $authenticatedUser)
    {
        $this->setAuthenticatedUser($authenticatedUser);
    }

    public function setAuthenticatedUser(User $authenticatedUser)
    {
        $this->authenticatedUser = $authenticatedUser;
    }

    public function getAuthenticatedUser()
    {
        return $this->authenticatedUser;
    }

    public function register(View $view)
    {
        $view->registerFunction('authenticatedUser', [$this, 'authenticatedUser']);
    }

    public function authenticatedUser()
    {
        return $this->authenticatedUser;
    }
}
