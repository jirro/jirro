<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Account;

use Doctrine\ORM\UnitOfWork;
use Jirro\Component\Account\Auth\AuthInterface;
use Jirro\Component\Http\Request\RequestAwareTrait;
use Jirro\Component\ORM\ObjectManager\ObjectManagerAwareTrait;
use Jirro\Bundle\AccountBundle\Domain\User;

class Auth implements AuthInterface
{
    const METHOD_PASSWORD = 0;

    const METHOD_TOKEN = 1;

    use RequestAwareTrait;

    use ObjectManagerAwareTrait;

    protected $authenticatedUser;

    public function setSessionToken($token)
    {
        $this->request->getSession()->set('session.token', $token);
    }

    public function clearSessionToken()
    {
        $this->request->getSession()->remove('session.token');
    }

    public function getSessionToken()
    {
        return $this->request->getSession()->get('session.token');
    }

    public function setAuthenticatedUser(User $authenticatedUser)
    {
        $state = $this->objectManager->getUnitOfWork()->getEntityState($authenticatedUser);
        if ($state === UnitOfWork::STATE_DETACHED) {
            $authenticatedUser = $this->objectManager->merge($authenticatedUser);
        }

        $this->setSessionToken($authenticatedUser->getToken());
        $this->authenticatedUser = $authenticatedUser;
    }

    public function clearAuthenticatedUser()
    {
        $this->clearSessionToken();
        $this->authenticatedUser = null;
    }

    public function getAuthenticatedUser()
    {
        return $this->authenticatedUser;
    }

    public function hasAuthenticatedUser()
    {
        return $this->getAuthenticatedUser() !== null;
    }

    public function authenticate($method, $data = [])
    {
        $userRepository = $this->objectManager->getRepository('Jirro\Bundle\AccountBundle\Domain\User');
        $user           = null;

        switch ($method) {
            case self::METHOD_PASSWORD:
                $user = $userRepository->findOneByUsername($data['identity']);
                if (! $user) {
                    $user = $userRepository->findOneByEmail($data['identity']);
                }

                if ($user && $user->isPasswordMatch($data['credential'])) {
                    $token = $user->generateToken();
                    $this->objectManager->persist($user);
                    $this->objectManager->flush();

                    $this->setAuthenticatedUser($user);
                }

                break;
            case self::METHOD_TOKEN:
                if (! isset($data['credential'])) {
                    $data['credential'] = $this->getSessionToken();
                }

                $user = $userRepository->findOneByToken($data['credential']);
                if ($user)  {
                    $this->setAuthenticatedUser($user);
                }

                break;
        }
    }

    public function deauthenticate()
    {
        $token = $this->request->getSession()->get('session.token', null);

        if ($token) {
            $user = $this
                ->objectManager
                ->getRepository('Jirro\Bundle\Account\Domain\User')
                ->findOneByToken($token);

            if ($user) {
                $user->clearToken();
                $this->obejctManager()->persist($user);
                $this->objectManager()->flush();
                $this->clearAuthenticatedUser();
            }
        }
    }

    public function isAuthenticatedUserAuthorized($route)
    {
        if (! $this->hasAuthenticatedUser()) {
            return false;
        }

        list($resource, $action) = explode('::', $route);
        $authenticatedUser       = $this->getAuthenticatedUser();

        $accountControl = $this
            ->objectManager
            ->createQueryBuilder()
            ->select('accountControl')
            ->from('Jirro\Bundle\AccountBundle\Domain\AccountControl', 'accountControl')
            ->leftJoin('accountControl.resource', 'resource')
            ->where('accountControl.user = :user OR accountControl.group IN (:groups)')
            ->andWhere('resource.name = :resourceName AND accountControl.action = :action')
            ->setParameters([
                'user'         => $authenticatedUser,
                'groups'       => $authenticatedUser->getGroups(),
                'resourceName' => $resource,
                'action'       => $action,
            ])
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;

        if (empty($accountControl)) {
            return false;
        }

        return $accountControl->isAuthorized();
    }
}
