<?php
/**
 * Created by PhpStorm.
 * User: Zack
 * Date: 6/28/2015
 * Time: 7:10 PM
 */
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload
require_once 'commands/Admin/view/AdminView.php';

$adminView = new \commands\Admin\view\AdminView();

$adminView->wrapper->setAttribute("class", "myClassAttribute");
$adminView->somethingElse->remove();

echo $adminView->create();