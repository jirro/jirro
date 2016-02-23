<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Http\Controller;

use Jirro\Component\Account\Auth\AuthAwareInterface;
use Jirro\Component\Account\Auth\AuthAwareTrait;
use Jirro\Component\ORM\ObjectManager\ObjectManagerAwareInterface;
use Jirro\Component\ORM\ObjectManager\ObjectManagerAwareTrait;
use Jirro\Component\ORM\Validator\ValidatorAwareInterface;
use Jirro\Component\ORM\Validator\ValidatorAwareTrait;

abstract class AbstractController implements
    AuthAwareInterface,
    ObjectManagerAwareInterface,
    ValidatorAwareInterface
{
    use AuthAwareTrait;

    use ObjectManagerAwareTrait;

    use ValidatorAwareTrait;
}
