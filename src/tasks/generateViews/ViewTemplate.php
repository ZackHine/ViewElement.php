<?php
/**
 * Created by PhpStorm.
 * User: Zack
 * Date: 6/28/2015
 * Time: 10:47 PM
 */

function getViewTemplate() {
return '<?php

namespace commands\\{{COMMAND_NAME}}\\view;

use \\view\\View as View,
    \\view\\IView as IView,
    \\view\\ViewElement as ViewElement,
    \\view\\ViewEmptyElement as ViewEmptyElement;

/**
  * This is a generated class. Do not edit.
  */
class {{CLASS_NAME}} extends View implements IView {
    private $_commandName = "{{COMMAND_NAME}}";

{{VIEW_ELEMENT_DECLARATIONS}}
    function __construct(){
{{CONSTRUCTOR}}
    }

    public function getViewFile() {
        return "{{VIEW_FILE_NAME}}.html";
    }

    public function getViewElements() {
        $arr = array({{VIEW_ELEMENTS_ARRAY}});
        return $arr;
    }

    public function getCommandName() {
        return $this->_commandName;
    }

}
?>';
}