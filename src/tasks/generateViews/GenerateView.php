<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zack
 * Date: 5/25/13
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */

class GenerateView extends Task {

    /**
     * The name passed in the buildfile.
     */
    private $name = null;

    /**
     * The setter for the attribute "name"
     */
    public function setName($val) {
        $this->name = $val;
    }

    /**
     * The init method: Do init steps.
     */
    public function init() {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main() {
        $matches = array();
        preg_match('@^\.\.\\\commands\\\(.*)\\\view\\\(.*)\.html@i', $this->name, $matches);

        echo "$this->name<---------";
        $viewFile = file_get_contents($this->name);
        preg_match_all('@<.* id\s*=\s*["|\']{{ALViewElement_(.*)}}["|\'].*>@i', $viewFile, $viewMatches);

        $template = file_get_contents("generateViews/ViewTemplate");

        $vars = array();
        $vars["command_name"] = ucfirst($matches[1]);
        if($vars["command_name"] == "Common") {
            $vars["command_name"] = "common";
        }
        $vars["class_name"] = ucfirst($matches[2]);
        $vars["view_file_name"] = $matches[2];
        $template = $this->replaceTokens($vars, $template);

        $propertiesPiece = "";
        $constructPiece = "";
        $viewElementsArrayPiece = "";
        $cnt = 1;

        echo "-->";print_r($viewMatches);
        foreach($viewMatches[1] as $viewElement) {
            $dom = new DOMDocument();
            $dom->loadHTML($viewFile);
            echo "DOM HTML:";

            $veVars["property_name"] = $viewElement;
            $constructTemplate = file_get_contents("generateViews/ConstructViewElementsTemplate");

            // constructor
            $vars = array();
            $vars["property_name"] = $viewElement;

            $propertiesTemplate = file_get_contents("generateViews/PropertiesTemplate");
            $propertiesPiece .=  $this->replaceTokens($vars, $propertiesTemplate);

            $constructPiece .=  $this->replaceTokens($veVars, $constructTemplate);

            // view elements array
            $vars = array();
            $vars["property_name"] = $viewElement;
            $vars["comma"] = count($viewMatches[1]) === $cnt ? "" : ",";

            $viewElementsArrayTemplate = file_get_contents("generateViews/ViewElementsArrayTemplate");
            $viewElementsArrayPiece .=  $this->replaceTokens($vars, $viewElementsArrayTemplate);

            ++$cnt;
        }
        $template = $this->replaceToken("view_element_declarations", $propertiesPiece, $template);
        $template = $this->replaceToken("constructor", $constructPiece, $template);
        $template = $this->replaceToken("view_elements_array", $viewElementsArrayPiece, $template);

        $fh = fopen("../commands/".$matches[1]."/view/".ucfirst($matches[2]).".php", "w");
        fwrite($fh, $template);
        fclose($fh);
    }

    private function replaceTokens($tokens, $template) {
        foreach($tokens as $n => $value) {
            $template = $this->replaceToken($n, $value, $template);
        }
        return $template;
    }

    private function replaceToken($token, $replace, $template) {
        $template = str_replace('{{' . strtoupper($token) . '}}', $replace, $template);
        return $template;
    }
}
?>