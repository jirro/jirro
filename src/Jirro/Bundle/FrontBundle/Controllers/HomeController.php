<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Bundle\FrontBundle\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Jirro\Component\Http\Controller\StandardController;

class HomeController extends StandardController
{
    public function indexAction(Request $request)
    {
        return new Response($this->getView()->render('Front::home/index'));
    }
}
