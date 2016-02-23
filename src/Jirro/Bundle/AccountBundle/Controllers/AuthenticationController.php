<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Bundle\AccountBundle\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Jirro\Component\Http\Controller\StandardController;
use Jirro\Component\Auth;

class AuthenticationController extends StandardController
{
    public function indexAction(Request $request)
    {
        return new Response($this->getView()->render('Account::login/index'));
    }

    public function loginAction(Request $request)
    {
        if (! $request->isMethod(Request::METHOD_POST)) {
            if ($this->getAuth()->hasAuthenticatedUser()) {
                return new RedirectResponse($request->getBasePath() . '/');
            }

            return new Response($this->getView()->render('Account::login/index'));
        }

        $loginParams = $request->request->all();
        $redirectUri = $request->server->get('HTTP_REFERER');

        $this->getAuth()->authenticate(
            Auth::METHOD_PASSWORD,
            [
                'identity'   => $loginParams['identity'],
                'credential' => $loginParams['credential'],
            ]
        );

        if (! $this->getAuth()->hasAuthenticatedUser()) {
            $request->getSession()->getFlashBag()->add('error', 'Username or Password doesn\'t match any user!');

            return new RedirectResponse($redirectUri);
        } else {
            $authenticatedUser = $this->getAuth()->getAuthenticatedUser();
            if (! $authenticatedUser->isActive()) {
                $this->getAuth()->deauthenticate();
                $request->getSession()->getFlashBag()->add('error', 'User account has not been verified!');

                return new RedirectResponse($redirectUri);
            }

            $continue = $request->query->get('continue');
            if (empty($continue)) {
                $admin = $this
                    ->getObjectManager()
                    ->getRepository('Jirro\Bundle\AccountBundle\Domain\Group')
                    ->findOneByCode('ADMIN')
                ;

                $continue = $request->getBasePath() . '/';
                if ($authenticatedUser->hasGroup($admin)) {
                    $continue .= 'admin';
                }

                return new RedirectResponse($continue);
            }
        }
    }

    public function logoutAction(Request $request)
    {
        if ($this->getAuth()->hasAuthenticatedUser()) {
            $this->getAuth()->deauthenticate();
        }

        return new RedirectResponse($request->getBasePath() . '/account/login');
    }
}
