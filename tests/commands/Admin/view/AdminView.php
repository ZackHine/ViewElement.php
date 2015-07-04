<?php

namespace commands\Admin\view;

use ViewElement\view\View as View,
    ViewElement\view\IView as IView,
    ViewElement\view\ViewElement as ViewElement;

/**
  * This is a generated class. Do not edit.
  */
class AdminView extends View implements IView {
    public $wrapper;
    public $somethingElse;

    function __construct(){
        $this->wrapper = new ViewElement("wrapper");

        $this->somethingElse = new ViewElement("somethingElse");


    }

    public function getViewFile() {
        return "commands/Admin/view/AdminView.php";
    }

    public function getViewElements() {
        $arr = array($this->wrapper,$this->somethingElse);
        return $arr;
    }

}
?>