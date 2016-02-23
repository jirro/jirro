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

class Group
{
    protected $id;

    protected $code;

    protected $name;

    protected $description;

    protected $users;

    protected $accountControls;

    public function __construct()
    {
        $this->users           = new ArrayCollection();
        $this->accountControls = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
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

    public function addUser(User $user)
    {
        $user->addGroup($this);
        $this->users->add($user);
    }

    public function removeUser(User $user)
    {
        $user->removeGroup($this);
        $this->users->removeElement($user);
    }

    public function hasUser(User $user)
    {
        foreach ($this->users as $groupUser) {
            if ($groupUser == $user) {
                return true;
            }
        }

        return false;
    }

    public function getUsers()
    {
        return $this->users;
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
