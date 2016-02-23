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

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class User
{
    const STATE_NOT_ACTIVATED = 0;

    const STATE_ACTIVE = 1;

    const STATE_BANNED = 2;

    protected $id;

    protected $email;

    protected $username;

    protected $password;

    protected $token;

    protected $state;

    protected $activationCode;

    protected $firstName;

    protected $middleName;

    protected $lastName;

    protected $created;

    protected $groups;

    protected $accountControls;

    public function __construct()
    {
        $this->state           = self::STATE_NOT_ACTIVATED;
        $this->groups          = new ArrayCollection();
        $this->accountControls = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = hash('sha512', $password);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function isPasswordMatch($plainPassword)
    {
        return $this->password === hash('sha512', $plainPassword);
    }

    public function setToken($token)
    {
        $this->token = hash('sha512', $token);
    }

    public function getToken()
    {
        return $this->token;
    }

    public function generateToken()
    {
        $plainToken  = (new DateTime())->getTimestamp();
        $plainToken .= $this->id . $this->username . $this->email . $this->password;

        $encryptedToken = hash('sha512', $plainToken);

        $this->token = $encryptedToken;
    }

    public function clearToken()
    {
        $this->token = null;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getState($toString = false)
    {
        if ($toString) {
            switch ($this->state) {
                case self::STATE_NOT_ACTIVATED:
                    return 'not activated';
                case self::STATE_ACTIVE:
                    return 'active';
                case self::STATE_BANNED:
                    return 'banned';
            }
        }

        return $this->state;
    }

    public static function getStates()
    {
        return [
            self::STATE_NOT_ACTIVATED => 'not activated',
            self::STATE_ACTIVE        => 'active',
            self::STATE_BANNED        => 'banned',
        ];
    }

    public function isActive()
    {
        return $this->state === self::STATE_ACTIVE;
    }

    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }

    public function getActivationCode()
    {
        return $this->activationCode;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFullName()
    {
        $fullName = $this->getFirstName();

        if ($this->getMiddleName()) {
            $fullName .= ' ' . $this->getMiddleName();
        }

        $fullName .= ' ' . $this->getLastName();

        return $fullName;
    }

    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function addGroup(Group $group)
    {
        $this->groups->add($group);
    }

    public function removeGroup(Group $group)
    {
        $this->groups->removeElement($group);
    }

    public function hasGroup(Group $group)
    {
        foreach ($this->groups as $userGroup) {
            if ($userGroup == $group) {
                return true;
            }
        }

        return false;
    }

    public function getGroups()
    {
        return $this->groups;
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

    public function prePersist()
    {
        if ($this->created === null) {
            $this->created = new \DateTime();
        }

        if ($this->activationCode === null) {
            $this->activationCode = hash('sha512', $this->email . $this->created->format('YmdHis'));
        }
    }
}
