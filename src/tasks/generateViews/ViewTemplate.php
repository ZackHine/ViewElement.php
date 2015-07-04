<?php
/**
 * Created by PhpStorm.
 * User: Zack
 * Date: 6/28/2015
 * Time: 10:47 PM
 */

function getViewTemplate() {
return '<?php

{{NAMESPACE}}

use ViewElement\\view\\View as View,
    ViewElement\\view\\IView as IView,
    ViewElement\\view\\ViewElement as ViewElement;

/**
  * This is a generated class. Do not edit.
  */
class {{CLASS_NAME}} extends View implements IView {
    private $_viewElementValue;
{{VIEW_ELEMENT_DECLARATIONS}}
    function __construct(){
        $this->_viewElementValue = "{{VIEW_ELEMENT_VALUE}}";

{{CONSTRUCTOR}}
    }

    public function getViewFile() {
        return "{{VIEW_FILE_NAME}}";
    }

    public function getViewElements() {
        $arr = array({{VIEW_ELEMENTS_ARRAY}});
        return $arr;
    }

    public function getViewElementValue() {
        return $this->_viewElementValue;
    }

}
?>';
}