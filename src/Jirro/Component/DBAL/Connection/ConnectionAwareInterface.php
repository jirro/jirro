<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\DBAL\Connection;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;

interface ConnectionAwareInterface
{
    public function setConnection(ConnectionInterface $connection);

    public function getConnection();
}
