<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Bundle\AdminBundle\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Jirro\Component\Http\Controller\StandardController;
use Jirro\Component\ORM\Tools\Pagination\Paginator;

class GroupsController extends StandardController
{
    public function indexAction(Request $request)
    {
        $page        = $request->query->get('page', 1);
        $maxResults  = 10;
        $firstResult = $maxResults * ($page - 1);

        if ($request->isMethod(Request::METHOD_POST)) {
            switch (strtoupper($request->request->get('formAction'))) {
                case 'FILTER':
                    $request->getSession()->set(__METHOD__, $request->request);
                    break;
                default :
                    $request->getSession()->set(__METHOD__, new ParameterBag());
            }
        }

        $queryBuilder = $this
            ->getObjectManager()
            ->createQueryBuilder()
            ->select('groups')
            ->from('Jirro\Bundle\AccountBundle\Domain\Group', 'groups')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
        ;

        $filters = $request->getSession()->get(__METHOD__, new ParameterBag());
        if ($filters) {
            if ($filters->get('code') !== null && $filters->get('code') !== '') {
                $queryBuilder
                    ->andWhere('UPPER(groups.code) = :code')
                    ->setParameter('code', strtoupper($filters->get('code')))
                ;
            }

            if ($filters->get('name') !== null && $filters->get('name') !== '') {
                $queryBuilder
                    ->andWhere('UPPER(groups.name) = :name')
                    ->setParameter('name', strtoupper($filters->get('name')))
                ;
            }
        }

        $paginator = new Paginator($queryBuilder->getQuery(), $page);

        return new Response($this->getView()->render('Admin::groups/index', [
            'paginator' => $paginator,
            'filters'   => $filters,
        ]));
    }

    public function addAction(Request $request)
    {
        if (! $request->isMethod(Request::METHOD_POST)) {
            return new Response($this->getView()->render('Admin::groups/add'));
        }
    }
}
