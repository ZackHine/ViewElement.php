<?php

namespace commands\Admin\view;

use ViewElement\view\View as View,
    ViewElement\view\IView as IView,
    ViewElement\view\ViewElement as ViewElement;

/**
  * This is a generated class. Do not edit.
  */
class AdminView extends View implements IView {
    private $_viewElementValue;
    public $wrapper;
    public $somethingElse;

    function __construct(){
        $this->_viewElementValue = "ALViewElement";

        $this->wrapper = new ViewElement("wrapper");

        $this->somethingElse = new ViewElement("somethingElse");


    }

    public function getViewFile() {
        return "tests/commands/Admin/view/AdminView.html";
    }

    public function getViewElements() {
        $arr = array($this->wrapper,$this->somethingElse);
        return $arr;
    }

    public function getViewElementValue() {
        return $this->_viewElementValue;
    }

}
?>