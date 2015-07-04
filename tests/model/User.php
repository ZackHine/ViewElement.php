<?php
/**
 * Created by PhpStorm.
 * User: Zack
 * Date: 7/4/2015
 * Time: 2:13 PM
 */

namespace model;


class User {
    const USER_TYPE_USER = "User";
    const USER_TYPE_ADMIN = "Admin";

    protected $userId;
    protected $userName;
    protected $userType;
    private $_userBio;

    function __construct(){
        $this->setUserType(User::USER_TYPE_USER);
    }

    /**
     * @return int
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserName() {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName) {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserType() {
        return $this->userType;
    }

    /**
     * @param string $userType
     */
    public function setUserType($userType) {
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getUserBio() {
        return $this->_userBio;
    }

    /**
     * @param mixed $userBio
     */
    public function setUserBio($userBio) {
        $this->_userBio = $userBio;
    }


}