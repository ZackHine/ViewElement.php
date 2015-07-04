<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zack
 * Date: 6/28/15
 * Time: 6:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ViewElement\view;

class View implements IView  {
    private $_html = "";

    public function create($replaceHtml = false) {
        $this->_html = file_get_contents($this->getViewFile());// this is relative root directory
        $dom = new \DOMDocument();
//        libxml_use_internal_errors(true);
        $dom->loadHTML($this->_html);
//        libxml_clear_errors();
        if($replaceHtml) {// use this if we ever need to get rid of doc types or html tags that we for some reason get wrapped in when calling loadHTML
            // remove doc type
            $dom->removeChild($dom->firstChild);
            //  remove <html><body></body></html>
            $dom->replaceChild($dom->firstChild->firstChild->firstChild, $dom->firstChild);
        }
        foreach($this->getViewElements() as $viewElement) {
            $viewElement->replaceHtml($dom, $this->getViewElementValue());
        }
        $html = $dom->saveHTML();
        return $this->_html = trim($html);
    }

    public function getHtml() {
        return $this->_html;
    }

    function getViewElements() {
        return array();
    }

    function getViewFile()  {
        throw new \Exception ("Error: getViewFile must be implemented in Child Class");
    }

    function getViewElementValue() {
        return "ViewElement";// default
    }
}