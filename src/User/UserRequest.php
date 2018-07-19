<?php
/**
 * Created by PhpStorm.
 * User: Etudiant0
 * Date: 29/06/2018
 * Time: 15:04
 */

namespace App\User;

use Doctrine\Common\Collections\ArrayCollection;

class UserRequest
{
    private $id;

    private $firstname;

    private $lastname;

    private $email;

    private $password;

    private $registrationDate;

    private $lastConnectionDate;

    private $roles;

    private $articles;

    /**
     * UserRequest constructor.
     * @param string $role
     * @internal param $roles
     */
    public function __construct(string $role = 'ROLE_USER')
    {
        $this->registrationDate = new \DateTime();
        $this->roles[] = $role;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return UserRequest
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     *
     * @return UserRequest
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     *
     * @return UserRequest
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return UserRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return UserRequest
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationDate(): \DateTime
    {
        return $this->registrationDate;
    }

    /**
     * @param \DateTime $registrationDate
     *
     * @return UserRequest
     */
    public function setRegistrationDate(\DateTime $registrationDate): UserRequest
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastConnectionDate()
    {
        return $this->lastConnectionDate;
    }

    /**
     * @param mixed $lastConnectionDate
     *
     * @return UserRequest
     */
    public function setLastConnectionDate($lastConnectionDate)
    {
        $this->lastConnectionDate = $lastConnectionDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     *
     * @return UserRequest
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getArticles(): ArrayCollection
    {
        return $this->articles;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $articles
     *
     * @return UserRequest
     */
    public function setArticles(ArrayCollection $articles): UserRequest
    {
        $this->articles = $articles;
        return $this;
    }
}
