<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * css_model.php
 * 
 * Provides methods to deal with styles and style sheets. It will read and 
 * parse a style sheet (string or file) into a 2-dimensional array of format
 * array[key][property].
 * <p>
 * The array can be accessed for reading and writing (overwrite and append).
 * See details in method descriptions.
 * <p>
 * Terminology used:
 * key       Represents a tag/id/class/subclass of a style sheet
 * property  Represents a property af a key (e.g. font-family)
 * value     Represents the value of a property
 *
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

if (!class_exists("Css_model")) 
{
   /** 
    * Provides objects and methods to read, change and write css style sheets
    * @package TeamCalPro
    */
   class Css_model 
   {
      var $css;
   
      // ---------------------------------------------------------------------
      /** 
       * Class constructor
       */
      function Css_model() 
      {
         /*
          * Register "destructor"
          */
         register_shutdown_function(array(& $this,"finalize"));
         $this->clear();
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Callback function to be executed when script processing is complete.
       * Clears (unsets) the css array object.
       */
      function finalize() 
      {
         unset ($this->css);
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Unsets and re-initializes the style sheet array.
       */
      function clear() 
      {
         unset ($this->css);
         $this->css = array();
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Gets all properties for a key in the css array.
       * 
       * @param string $key Name of the key, representing the tag/id/class/subclass
       * @return string A string with all properties in css format (not inluding
       *                a leading and trailing {})
       */
      function getKeyProperties($key) 
      {
         $key = strtolower($key);
         $properties = "";
         if (!isset ($this->css[$key])) 
         {
            return "";
         }
         else 
         {
            foreach ($this->css[$key] as $property => $value) 
            {
               $properties .= $property.": ".$value."; ";   
            }
         }
         return $properties;
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Gets the value of a single property for a key in the css array.
       * 
       * @param string $key Name of the key, representing the tag/id/class/subclass
       * @param string $property Name of the property
       * @return string A string containing the value of the property
       */
      function getPropertyValue($key, $property) 
      {
         $key = strtolower($key);
         $property = strtolower($property);
   
         @list ($tag, $subtag) = explode(":", $key);
         @list ($tag, $class) = explode(".", $tag);
         @list ($tag, $id) = explode("#", $tag);
         $result = "";
         foreach ($this->css as $_tag => $value) 
         {
            @list ($_tag, $_subtag) = explode(":", $_tag);
            @list ($_tag, $_class) = explode(".", $_tag);
            @list ($_tag, $_id) = explode("#", $_tag);
   
            $tagmatch = (strcmp($tag, $_tag) == 0) | (strlen($_tag) == 0);
            $subtagmatch = (strcmp($subtag, $_subtag) == 0) | (strlen($_subtag) == 0);
            $classmatch = (strcmp($class, $_class) == 0) | (strlen($_class) == 0);
            $idmatch = (strcmp($id, $_id) == 0);
   
            if ($tagmatch & $subtagmatch & $classmatch & $idmatch) 
            {
               $temp = $_tag;
               
               if ((strlen($temp) > 0) & (strlen($_class) > 0)) 
               {
                  $temp .= "." . $_class;
               }
               elseif (strlen($temp) == 0) 
               {
                  $temp = "." . $_class;
               }
               
               if ((strlen($temp) > 0) & (strlen($_subtag) > 0)) 
               {
                  $temp .= ":" . $_subtag;
               }
               elseif (strlen($temp) == 0) 
               {
                  $temp = ":" . $_subtag;
               }
               
               if (isset ($this->css[$temp][$property])) 
               {
                  $result = $this->css[$temp][$property];
               }
            }
         }
         return $result;
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Read a css formatted file and parses it into the css array.
       * 
       * @param string $filename String containing the path/filename
       * @return boolean False if file not found, else true
       */
      function parseFile($filename) 
      {
         $this->clear();
         if (file_exists($filename)) 
         {
            return $this->parseStr(file_get_contents($filename));
         }
         else 
         {
            return false;
         }
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Read a css formatted string and parses it into the css array.
       * 
       * @param string $str String containing a full style sheet
       * @return integer 0 if no parts (trailing "}") were found, or number of elements in css array
       */
      function parseStr($str) 
      {
         $this->clear();
         
         /**
          * Remove comments
          */
         $str = preg_replace("/\/\*(.*)?\*\//Usi", "", $str);
         
         /**
          * Parse the CSS code
          */
         $parts = explode("}", $str);
         if (count($parts) > 0) 
         {
            foreach ($parts as $part) 
            {
               @list ($keystr, $codestr) = explode("{", $part);
               $keys = explode(",", trim($keystr));
               if (count($keys) > 0) 
               {
                  foreach ($keys as $key) 
                  {
                     if (strlen($key) > 0) 
                     {
                        $key = str_replace("\n", "", $key);
                        $key = str_replace("\\", "", $key);
                        $this->setKey($key, trim($codestr));
                     }
                  }
               }
            }
         }
         return (count($this->css) > 0);
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Prints the css array in form of a style sheet text.
       * 
       * @return string The style sheet text
       */
      function printCSS() 
      {
         $result = "";
         foreach ($this->css as $key => $values) 
         {
            $result .= $key . " { ";
            foreach ($values as $key => $value) 
            {
               $result .= "$key: $value; ";
            }
            $result .= "}\n";
         }
         return $result;
      }
      
      // ---------------------------------------------------------------------
      /** 
       * Sets all properties for a key in the css array. If the key exists
       * it will be overwritten, if not it will be added.
       * 
       * @param string $key Name of the key, representing the tag/id/class/subclass
       * @param string $properties String containing all properties in css format (colon 
       * seperates property name from value, semicolon seperates properties)
       */
      function setKey($key, $properties) 
      {
         $key = strtolower($key);
         $properties = strtolower($properties);
         
         if (!isset ($this->css[$key])) 
         {
            $this->css[$key] = array ();
         }
         $props = explode(";", $properties);
         
         if (count($props) > 0) 
         {
            foreach ($props as $prop) 
            {
               $prop = trim($prop);
               @list ($propname, $propvalue) = explode(":", $prop);
               if (strlen($propname) > 0) 
               {
                  $this->css[$key][trim($propname)] = trim($propvalue);
               }
            }
         }
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Sets the value of a single property for a key in the css array.
       * 
       * @param string $key Name of the key, representing the tag/id/class/subclass
       * @param string $property Name of the property
       * @param string $newvalue New value to be set for the property
       * @return string A string containing the value of the property
       */
      function setProperty($key, $property, $newvalue) 
      {
         $key = strtolower($key);
         $property = strtolower($property);
   
         @list ($tag, $subtag) = explode(":", $key);
         @list ($tag, $class) = explode(".", $tag);
         @list ($tag, $id) = explode("#", $tag);
         $result = "";
         foreach ($this->css as $_tag => $value) 
         {
            @list ($_tag, $_subtag) = explode(":", $_tag);
            @list ($_tag, $_class) = explode(".", $_tag);
            @list ($_tag, $_id) = explode("#", $_tag);
   
            $tagmatch = (strcmp($tag, $_tag) == 0) | (strlen($_tag) == 0);
            $subtagmatch = (strcmp($subtag, $_subtag) == 0) | (strlen($_subtag) == 0);
            $classmatch = (strcmp($class, $_class) == 0) | (strlen($_class) == 0);
            $idmatch = (strcmp($id, $_id) == 0);
   
            if ($tagmatch & $subtagmatch & $classmatch & $idmatch) 
            {
               $temp = $_tag;
               
               if ((strlen($temp) > 0) & (strlen($_class) > 0)) 
               {
                  $temp .= "." . $_class;
               }
               elseif (strlen($temp) == 0) 
               {
                  $temp = "." . $_class;
               }
               
               if ((strlen($temp) > 0) & (strlen($_subtag) > 0)) 
               {
                  $temp .= ":" . $_subtag;
               }
               elseif (strlen($temp) == 0) 
               {
                  $temp = ":" . $_subtag;
               }
               $this->css[$temp][$property]=$newvalue;
            }
         }
      }
   }
}
?>
