<?php
class User
{
    public $userID;
    public $password;
    public $role;

    function __construct($userID, $password, $role)
    {
        $this->userID = $userID;
        $this->password = $password;
        $this->role = $role;
    }

    function setUserID($userID)
    {
        $this->userID = $userID;
    }

    function getUserID()
    {
        return $this->userID;
    }

    function setPassword($password)
    {
        $this->password = $password;
    }

    function getPassword()
    {
        return $this->password;
    }

    function setRole($role)
    {
        $this->role = $role;
    }

    function getRole()
    {
        return $this->role;
    }
}
