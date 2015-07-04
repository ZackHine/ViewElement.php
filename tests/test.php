<?php
/**
 * Created by PhpStorm.
 * User: Zack
 * Date: 6/28/2015
 * Time: 7:10 PM
 */
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload'
require_once 'model/User.php';
require_once 'model/AdminUser.php';
require_once 'commands/common/view/CommonView.php';
require_once 'commands/Admin/view/AdminHomeView.php';
require_once 'commands/User/view/UserHomeView.php';


// SET UP MODELS FOR OUR TEST

$ran = rand(0, 1);

$user = null;
if($ran === 0) {// Admin User
    echo "THIS IS AN ADMIN USER: \n\n";

    $user = new \model\AdminUser();
    $user->setUserName("Admin User");
    $user->setUserId(1);
} else {// normal User
    echo "THIS IS A NORMAL USER: \n\n";

    $user = new \model\User();
    $user->setUserName("Normal User");
    $user->setUserBio("This is my bio.");
    $user->setUserId(2);
}

// CREATE COMMON VIEW AND SET UP COMMON CONTENT

$commonView = new \commands\Common\view\CommonView();

$commonView->homeItem->setAttribute("class", "active");
$commonView->viewSelectedContent->setAttribute("class", "home-content");
$commonView->viewSelectedContent->removeIdAfterCreation();// the id "viewSelectedContent" will not be present in generated DOM
$commonView->messagesItem->appendContent(" 2");
$commonView->userImg->appendAttribute("src", $user->getUserId());
$commonView->userName->setContent($user->getUserName());

if($user->getUserType() === \model\User::USER_TYPE_ADMIN) {
    // CREATE ADMIN-SPECIFIC VIEW CONTENT AND EVENTUALLY ADD IT TO COMMON VIEW

    $commonView->homeHeaderNavTitle->setContent("Admin Options");

    $adminHomeView = new \commands\Admin\view\AdminHomeView();
    $adminHomeView->adminAction1->removeIdAfterCreation();
    $adminHomeView->adminAction2->removeIdAfterCreation();
    $adminHomeView->adminAction3->removeIdAfterCreation();
    $adminHomeView->adminAction1->appendAttribute("class", " active");
    $adminHomeView->adminTabContent->setContent("Admin Action 1 Content");

    $commonView->viewSelectedContent->appendContent($adminHomeView->create());// generate adminHomeView DOM to add it as content to "viewSelectedContent"
} else {
    // CREATE NORMAL USER-SPECIFIC VIEW CONTENT AND EVENTUALLY ADD IT TO COMMON VIEW

    $commonView->homeHeaderNavTitle->setContent("Admin Options");
    $commonView->adminAccessItem->remove();

    $userHomeView = new \commands\User\view\UserHomeView();
    $userHomeView->userBio->setContent($user->getUserBio());

    $commonView->viewSelectedContent->appendContent($userHomeView->create());// generate userHomeView DOM to add it as content to "viewSelectedContent"
}

// ECHO THE GENERATED DOM

echo $commonView->create();