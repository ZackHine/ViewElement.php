<?php

namespace commands\User\view;

use ViewElement\view\View as View,
    ViewElement\view\IView as IView,
    ViewElement\view\ViewElement as ViewElement;

/**
  * This is a generated class. Do not edit.
  */
class UserView extends View implements IView {
    private $_viewElementValue;
    public $wrapper;

    function __construct(){
        $this->_viewElementValue = "ALViewElement";

        $this->wrapper = new ViewElement("wrapper");


    }

    public function getViewFile() {
        return "tests/commands/User/view/UserView.html";
    }

    public function getViewElements() {
        $arr = array($this->wrapper);
        return $arr;
    }

    public function getViewElementValue() {
        return $this->_viewElementValue;
    }

}
?>