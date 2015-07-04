<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zack
 * Date: 6/28/15
 * Time: 6:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ViewElement\view;

class ViewElement {
    private $_elementId;

    private $_attributes = array();
    private $_appendedAttributes = array();
    private $_content;

    private $_removeIdAfterCreation = false;
    private $_remove = false;
    private $_contentAppendMode = false;

    function __construct($elementId) {
        $this->_elementId = $elementId;
    }

    public function setContent($content) {
        $this->_content = $content;
    }

    public function getContent() {
        return $this->_content;
    }

    public function appendContent($content) {
        $this->_contentAppendMode = true;
        $this->_content .= $content;
    }

    public function setAttribute($attr, $val) {
        $this->_attributes[$attr] = $val;
    }

    public function getAttributes() {
        return $this->_attributes;
    }

    public function getAppendedAttributes() {
        return $this->_appendedAttributes;
    }

    /**
     * Append to attributes already passed in by setAttribute. The ViewElement will attempt to append to currently existing
     * attributes in the HTML for every entry passed in to appendAttribute. Because of this, appendAttribute is much more expensive than
     * just using setAttribute, so it should only be used if you want to append to currently existing HTML.
     * @see setAttribute()
     * @param $attr
     * @param $val
     */
    public function appendAttribute($attr, $val) {
        if($this->_attributes[$attr] && $this->_appendedAttributes[$attr]) {
            $this->_appendedAttributes[$attr] .= " ".$val;
            unset($this->_attributes[$attr]);
        } else if($this->_attributes[$attr] && !$this->_appendedAttributes[$attr]) {
            $this->_appendedAttributes[$attr] = $this->_attributes[$attr]." ".$val;
            unset($this->_attributes[$attr]);
        } else {
            if($this->_appendedAttributes[$attr]) {
                $this->_appendedAttributes[$attr] .= " ".$val;
            } else {
                $this->_appendedAttributes[$attr] = $val;
            }
        }
    }

    public function getAttribute($attr) {
        return $this->_attributes[$attr];
    }

    public function remove() {
        $this->_remove = true;
    }

    /**
     * After the ViewElement is generated, the id will be stripped from the html.
     * Use this when you want to define an html element as a ViewElement but don't want it to have an id attribute.
     * @access public
     */
    public function removeIdAfterCreation() {
        $this->_removeIdAfterCreation = true;
    }

    /**
     * Modifies the DOMElement in the passed in DOMDocument to reflect the ViewElement
     * @see View::create()
     * @access public
     * @param \DOMDocument $viewDocument
     * @param $viewElementValue
     */
    public function replaceHtml(\DOMDocument $viewDocument, $viewElementValue) {
        $viewElement = $viewDocument->getElementById("{{".$viewElementValue."_".$this->_elementId."}}");
        if($viewElement !== null) {// could be null if removed a parent of the ViewElement earlier
            if($this->_remove === true) {
                $viewElement->parentNode->removeChild($viewElement);
            } else {
                $newContent = '<div id="html-to-dom-input-wrapper">' . $this->getContent() . '</div>';
                $hdoc = new \DOMDocument();
                $hdoc->loadHTML($newContent);
                try {
                    $children = $hdoc->getElementById('html-to-dom-input-wrapper')->childNodes;
                    foreach($children as $child) {
                        $child = $viewDocument->importNode($child, true);
                        if($this->_contentAppendMode === true) {
                            $viewElement->appendChild($child);// place content before closing tag
                        } else {
                            $viewElement->insertBefore($child, $viewElement->firstChild);// place content after opening tag
                        }
                    }
                } catch (\Exception $ex) {
                    error_log($ex->getMessage(), 0);
                }

                // put on all the normal non-appended attributes
                $this->createAttributes($viewElement);

                // now need to handle all the appended attributes
                $this->createAppendedAttributes($viewElement);

                if($this->_removeIdAfterCreation === true) {
                    $viewElement->removeAttribute("id");
                } else {
                    if($viewElement->getAttribute("id") === "{{".$viewElementValue."_".$this->_elementId."}}") {// if they set the id to something else, keep it
                        $viewElement->setAttribute("id",  $this->_elementId);
                    }
                }
            }
        }
    }

    /**
     * Appends all attributes
     * @access protected
     * @param \DOMElement $viewElement
     */
    protected function createAppendedAttributes(\DOMElement $viewElement) {
        foreach($this->getAppendedAttributes() as $key => $val) {
            if($viewElement->getAttribute($key) !== null) {
                $viewElement->setAttribute($key, $viewElement->getAttribute($key)."".$val);
            } else {
                $viewElement->setAttribute($key, $val);
            }
        }
    }

    /**
     * Generates attributes from the loaded attributes array
     * @see $attributes
     * @access protected
     * @param \DOMElement $viewElement
     */
    protected function createAttributes(\DOMElement $viewElement) {
        foreach($this->getAttributes() as $key => $val) {
            $viewElement->setAttribute($key, $val);
        }
    }
}
?>