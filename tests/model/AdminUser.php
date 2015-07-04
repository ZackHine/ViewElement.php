<?php
/**
 * Created by PhpStorm.
 * User: Zack
 * Date: 7/4/2015
 * Time: 2:14 PM
 */

namespace model;


class AdminUser extends User {
    protected $adminRights = array();

    function __construct(){
        $this->setUserType(User::USER_TYPE_ADMIN);
    }

    public function addAdminRight($right) {
        array_push($this->adminRights, $right);
    }

    public function getUserBio() {
        return "";
    }
}