<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Bundle\AccountBundle\Domain;

use Doctrine\Common\Collections\ArrayCollection;

class Resource
{
    protected $id;

    protected $name;

    protected $description;

    protected $accountControls;

    public function __construct()
    {
        $this->accountControls = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function addAccountControl(AccountControl $accountControl)
    {
        $this->accountControls->add($accountControl);
    }

    public function removeAccountControl(AccountControl $accountControl)
    {
        $this->accountControls->removeElement($accountControl);
    }

    public function getAccountControls()
    {
        return $this->accountControls;
    }
}
