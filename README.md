# ViewElement.php
> PHP Template Engine that blurs the line between Markup and PHP

# Purpose
ViewElement.php is built with Separation of Concerns in mind. It's too easy to write bad PHP code that mixes PHP with HTML. This can become incredibly hard if not impossible to maintain.

Even in other popular templating engines, there is still a lot of "pseudo-code" written in HTML files. ViewElement.php makes it easier than ever to not include code in your HTML files. It does this by converting HTML files into PHP Classes and giving the Classes access to what we call "ViewElements". ViewElements are any HTML elements the programmer wants to be able to modify via PHP.

After the ViewElements are programmatically modified to the programmer's liking, the generated PHP Class is then converted back into HTML and can be returned to the browser.
  
ViewElement.php is very flexible and can be used to generate any HTML file imaginable.

# How to Use
ViewElement.php achieves what it sets out to do by creating a 2-step process. The first step is converting HTML files into PHP Classes through a Phing Build Task. The second step is giving the programmer the ability to modify those PHP Classes in any way they want before converting the result back into HTML.

## Install
It is recommended that you use Composer to install this library. To do that, your composer.json file should have the following:

    "require": {
        "php": ">=5.3.0",
        "zackhine/view-element.php": "*"
      }
      
If you don't want to use Composer you could download the source here and include the library in your application. Just be weary that the documentation here is written assuming Composer is being used. Thus, if you're not using Composer some things like paths could end up being different.

## Phing Build
One of the nicest features of ViewElement.php is all of the would-be repetitive work is handled by a Phing Build Task. The GenerateView Task looks through all of your HTML files that you pass to it and converts them into PHP Classes. The Classes can then be used in your application code to set up your view. In order to get the GenerateView Task working please read the information below:

### view-element-build.json
The view-element.build.json file is the configuration file for the GenerateView Phing Task. This file must be at the root of your project and currently must exist.

There are 4 properties that can all be optionally added which will be discussed below. For reference, an example view-element-build.json file looks like:

    {
      "view-element": "ALViewElement",
      "view-file-root": "tests",
      "dir-sep": "/",
      "namespaces": [
        {
          "commands\\User\\view\\": "commands\\User\\view"
        },
        {
          "commands\\Admin\\view\\": "commands\\Admin\\view"
        },
        {
          "commands\\common\\view\\": "commands\\Common\\view"
        }
      ]
    }


#### view-element *(Optional)*:
This is what the GenerateView Task will look for on your HTML elements to know which ViewElements to create in the generated View files. The task will look for:
  
    id="{{[view-element-property]_yourElementId}}"

If this is not included in your view-element-build.json file, the default value is "ViewElement". An example of this would look like:

    <div id="{{ViewElement_myDivId}}"></div>
    
An example that would work with the reference view-element-build.json included above would look like:

    <div id="{{ALViewElement_myDivId}}"></div>

#### view-file-root *(Optional)*:
This is only needed if your view-element-build.json and build.xml files are not at the root of your project. For example, in this project view-element-build.json and build.xml were located at this location:

    - project root
        - tests
            - build.xml
            - view-element-build.json
            
The value for this property **must** be relative your project root.

#### dir-sep *(Optional)*:
The generated PHP View Classes all implement the IView Interface. One method of the IView interface is getViewFile which must return the path to the HTML file the PHP Class is generated from relative the project root. The dir-sep property can be used to tell the GenerateView Task what to use as a directory separator in this path.
 
For example if you have the following:

    "dir-sep": "/"
    
Then the generated getViewFile method would look something like:

     public function getViewFile() {
            return "path/to/yourView.html";
     }
     
If this property is not included, then GenerateView will use the value obtained by calling "DIRECTORY_SEPARATOR" in PHP.

#### namespaces *(Optional)*:
The namespaces property expects an array mapping source paths to namespaces. The GenerateView Task will use the path of the HTML file passed in to look into this mapping to find what value should be used as the namespace in the generated PHP View Class file. If GenerateView cannot find the path, there will be no namepsace in the generated Class.

## build.xml
With your view-element-build.json file configured correctly, you can run the GenerateView Phing task to generate PHP Classes based off HTML Templates.

To get this working, you must do three things in your build.xml file:

1.  include composer autoload.php in your build.xml
    
        <php expression="include('../vendor/autoload.php')"/>
2.  Define the GenerateView Task

        <taskdef name="generateview" classname="generateViews.GenerateView" />
3.  Pass the file name for each HTML file you want to generate a PHP Class for into the generateview task

        <generateview name="${absname}"/>  
        

For reference, a complete build.xml file is included below:

    <?xml version="1.0" encoding="UTF-8"?>
    
    <project name="FooBar" default="dist" basedir=".">
        <php expression="include('../vendor/autoload.php')"/>
    
        <taskdef name="generateview" classname="generateViews.GenerateView" />
    
        <!-- ============================================  -->
        <!-- Target: build                                 -->
        <!-- ============================================  -->
        <target name="build">
    
            <foreach param="file" absparam="absname" target="viewgenerator">
                <fileset dir="commands">
                    <include name="*/view/*.html"/>
                </fileset>
            </foreach>
    
        </target>
    
        <target name="viewgenerator" >
            <echo msg="Generating View: ${absname}" />
            <generateview name="${absname}"/>
        </target>
    
        <!-- ============================================  -->
        <!-- (DEFAULT)  Target: dist                       -->
        <!-- ============================================  -->
        <target name="dist" depends="build">
            <echo msg="Doing build..." />
        </target>
    </project>

## Running build
I recommend using [PhpStorm](https://www.jetbrains.com/phpstorm/) for PHP Development. PhpStorm makes it very easy to run a Phing Build. Please see this page for instructions:

[Phing Build using PhpStorm](https://www.jetbrains.com/phpstorm/help/enabling-phing-support.html)


## require in source
Once all of your files have been generated, it's easy to use ViewElement.php in your project. 

All you need to do is include the composer autoload.php file:

    require_once __DIR__ . '/vendor/autoload.php';
    
## Usage in your code
TODO...


