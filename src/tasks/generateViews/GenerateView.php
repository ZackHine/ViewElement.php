<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zack
 * Date: 5/25/13
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "ViewTemplate.php";
include_once "ConstructViewElementsTemplate.php";
include_once "PropertiesTemplate.php";
include_once "ViewElementsArrayTemplate.php";

class GenerateView extends \Task {
    const VIEW_ELEMENT_KEY = "view-element";
    const DIR_SEP_KEY = "dir-sep";
    const NAMESPACES_KEY = "namespaces";
    const DEFAULT_VIEW_ELEMENT_VALUE = "ViewElement";

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
        $fileNameMatches = array();

        $this->setUpFileNameMatches($fileNameMatches);

        try {
            $viewElementBuild = file_get_contents("view-element-build.json");
            $viewElementBuild = json_decode($viewElementBuild, true);

            $viewElementValue = $this->setUpViewElement($viewElementBuild);
            $namespaceValue = $this->setUpNamespace($viewElementBuild, $fileNameMatches);
            $directorySeparator = $this->setUpDirectorySeparator($viewElementBuild);

            $viewFile = file_get_contents($this->name);
            preg_match_all('@<.* id\s*=\s*["|\']{{'.$viewElementValue.'_(.*)}}["|\'].*>@i', $viewFile, $viewMatches);// find all elements in html with id starting with $viewElementValue

            $template = getViewTemplate();// bring in basic template for a class that implements IView

            $vars = array();

            if($namespaceValue !== null) {
                $vars["namespace"] = "namespace ".$namespaceValue.";";
            } else {
                $vars["namespace"] = "";
            }
            $vars["class_name"] = ucfirst($fileNameMatches[2]);
            $vars["view_file_name"] = $this->setUpViewFileName($fileNameMatches, $directorySeparator);

            $template = $this->replaceTokens($vars, $template);

            $propertiesPiece = "";
            $constructPiece = "";
            $viewElementsArrayPiece = "";
            $cnt = 1;

            foreach($viewMatches[1] as $viewElement) {
                $dom = new DOMDocument();
                $dom->loadHTML($viewFile);
                echo "DOM HTML:";

                $veVars["property_name"] = $viewElement;
                $constructTemplate = getConstructViewElementsTemplate();

                // constructor
                $vars = array();
                $vars["property_name"] = $viewElement;

                $propertiesTemplate = getPropertiesTemplate();
                $propertiesPiece .=  $this->replaceTokens($vars, $propertiesTemplate);

                $constructPiece .=  $this->replaceTokens($veVars, $constructTemplate);

                // view elements array
                $vars = array();
                $vars["property_name"] = $viewElement;
                $vars["comma"] = count($viewMatches[1]) === $cnt ? "" : ",";

                $viewElementsArrayTemplate = getViewElementsArrayTemplate();
                $viewElementsArrayPiece .=  $this->replaceTokens($vars, $viewElementsArrayTemplate);

                ++$cnt;
            }
            $template = $this->replaceToken("view_element_declarations", $propertiesPiece, $template);
            $template = $this->replaceToken("constructor", $constructPiece, $template);
            $template = $this->replaceToken("view_elements_array", $viewElementsArrayPiece, $template);

            echo $template;

            $fh = fopen($fileNameMatches[1].ucfirst($fileNameMatches[2]).".php", "w");
            fwrite($fh, $template);
            fclose($fh);
        }
        catch (Exception $e) {
            echo "Error: view-element-build.json must exist at root";
        }
    }

    /**
     * Take an empty array and populate it with path to current file not including
     * the file, and the file name
     * @param array $fileNameMatches
     */
    protected function setUpFileNameMatches(array &$fileNameMatches) {
        echo $this->name;
        preg_match('@^(.*\\\\)*(.*)\.html@i', $this->name, $fileNameMatches);
    }

    /**
     * Given associative array representation of view-element-build.json, and fileNameMatches for current file,
     * returns namespace in view-element-build or null if not defined
     * @param $viewElementBuild
     * @param $fileNameMatches
     * @return null|string
     */
    protected function setUpNamespace($viewElementBuild, $fileNameMatches) {
        $namespace = null;
        if($viewElementBuild[GenerateView::NAMESPACES_KEY] !== null) {
            foreach($viewElementBuild[GenerateView::NAMESPACES_KEY] as $namespace) {
                if($namespace[$fileNameMatches[1]] !== null) {
                    $namespace = $namespace[$fileNameMatches[1]];
                    break;
                }
            }
        }
        return $namespace;
    }

    /**
     * Given associative array representation of view-element-build.json, returns defined view-element value or "ViewElement" by default
     * @param $viewElementBuild
     * @return string
     */
    protected function setUpViewElement($viewElementBuild) {
        return $viewElementBuild[GenerateView::VIEW_ELEMENT_KEY] !== null ? $viewElementBuild[GenerateView::VIEW_ELEMENT_KEY] : GenerateView::DEFAULT_VIEW_ELEMENT_VALUE;
    }

    /** Given associative array representation of view-element-build.json, and fileNameMatches for current file,
     * returns dir-sep in view-element-build or DIRECTORY_SEPARATOR as defined by PHP if not defined
     * @param $viewElementBuild
     * @return string
     */
    protected function setUpDirectorySeparator($viewElementBuild) {
        return $viewElementBuild[GenerateView::DIR_SEP_KEY] !== null ? $viewElementBuild[GenerateView::DIR_SEP_KEY] : DIRECTORY_SEPARATOR;
    }

    /**
     * Given $fileNameMatches for current file, and defined directorySeparator, create view file name which will
     * be inserted into View File
     * @param $fileNameMatches
     * @param $directorySeparator
     * @return mixed|string
     */
    protected function setUpViewFileName($fileNameMatches, $directorySeparator) {
        // Remove backslashes if they exist and directory separator is forward slashes, or remove forward
        // slashes if they exist and directory separator is backslashes
        $fileName = $fileNameMatches[1].ucfirst($fileNameMatches[2]).".php";
        if($directorySeparator === "\\") {
            $fileName = str_replace('/', '\\', $fileName);
        } else {
            $fileName = str_replace('\\', '/', $fileName);
        }
        return $fileName;
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