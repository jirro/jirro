<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Bundle\RestfulBundle\Controllers;

use Symfony\Component\HttpFoundation\Request;
use League\Route\Http\JsonResponse;
use Jirro\Component\Http\Route\Controller\RestfulController;

class AuthenticationController extends RestfulController
{
    public function loginAction(Request $request)
    {
        $loginParams = $request->getContent();

        return new JsonResponse\Ok([]);
    }
}
