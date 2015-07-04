<?php

namespace commands\User\view;

use ViewElement\view\View as View,
    ViewElement\view\IView as IView,
    ViewElement\view\ViewElement as ViewElement;

/**
  * This is a generated class. Do not edit.
  */
class UserView extends View implements IView {
    public $wrapper;

    function __construct(){
        $this->wrapper = new ViewElement("wrapper");


    }

    public function getViewFile() {
        return "commands/User/view/UserView.php";
    }

    public function getViewElements() {
        $arr = array($this->wrapper);
        return $arr;
    }

}
?>