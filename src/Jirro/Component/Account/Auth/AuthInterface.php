<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Account\Auth;

use Jirro\Component\Http\Request\RequestAwareInterface;
use Jirro\Component\ORM\ObjectManager\ObjectManagerAwareInterface;
use Jirro\Bundle\AccountBundle\Domain\User;

interface AuthInterface extends RequestAwareInterface, ObjectManagerAwareInterface
{
    public function setAuthenticatedUser(User $authenticatedUser);

    public function getAuthenticatedUser();

    public function hasAuthenticatedUser();

    public function authenticate($identity, $credential);

    public function deauthenticate();

    public function isAuthenticatedUserAuthorized($route);
}
