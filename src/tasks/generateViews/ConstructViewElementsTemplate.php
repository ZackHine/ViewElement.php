<?php
/**
 * Created by PhpStorm.
 * User: Zack
 * Date: 6/28/2015
 * Time: 10:58 PM
 */

function getConstructViewElementsTemplate() {
    return '        $this->{{PROPERTY_NAME}} = new ViewElement("{{PROPERTY_NAME}}");

';
}