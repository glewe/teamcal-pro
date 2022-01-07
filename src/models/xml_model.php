<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * xml_model.php
 * 
 * Provides classes to deal with XML parsing
 *
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */
/**
 * Make sure the class hasn't been loaded yet
 */
if (!class_exists("Xml_model")) 
{
   /** 
    * Provides objects and methods to parse MySQL into XML
    * @package TeamCalPro
    */
   class Xml_model 
   {
      var $header;
      var $startTag;
      var $endTag;
      var $body;
   
      // ---------------------------------------------------------------------
      /** 
       * Constructor. Creates the start and end tag.
       * 
       * @param string $tablename Name of MySQL table to parse
       */
      function Xml_model($tablename) 
      {
         /* $this->header="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; */
         $this->startTag = "<Table Name=\"".$tablename."\">";
         $this->endTag = "</Table>\n";
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Adds an element to the XML ouput based on a given MySQL query result handle
       * 
       * @param array $row Single MySQL query result row
       * @param integer MySQL query result handle
       */
      function addElement($row, $rows) 
      {
         $out = "\t<DataRow>\n";
         for ($i = 0; $i < mysql_num_fields($rows); $i++) 
         {
            $meta = mysql_fetch_field($rows, $i);
            if ($meta->name == "password") 
            {
               $out = $out."\t\t<DataField Name=\"".$meta->name."\" Type=\"".$meta->type."\">********</DataField>\n";
            }
            else 
            {
               $out = $out."\t\t<DataField Name=\"".$meta->name."\" Type=\"".$meta->type."\">".htmlspecialchars($row[$i])."</DataField>\n";
            }
         }
         $out = $out."\t</DataRow>\n";
         $this->body = $this->body.$out;
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Returns the XML text
       * 
       * @return string XML text
       */
      function getXMLDocument() 
      {
         return $this->header.$this->startTag."\n".$this->body.$this->endTag;
      }
   }
}

/**
 * Make sure the class hasn't been loaded yet
 */
if (!class_exists("sql2xml")) 
{
   /** 
    * Provides object and methods to access a MySQL database and wraps up the XML export
    * @package TeamCalPro
    */
   class sql2xml 
   {
      var $connection;
   
      // ---------------------------------------------------------------------
      /** 
       * Constructor
       * 
       * @param string $dbhost Database server name ot IP
       * @param string $dbport Database server port
       * @param string $dbname Database name
       * @param string $dbuser Database user name
       * @param string $dbpwd Database user password
       */
      function sql2xml($dbhost, $dbport, $dbname, $dbuser, $dbpwd) 
      {
         $this->connection = mysql_connect($dbhost . ":" . $dbport, $dbuser, $dbpwd);
         mysql_select_db($dbname, $this->connection);
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Launches the export of a table
       * 
       * @param string $dbtable Database table name to export
       * @param string $filename File name to export to
       */
      function exportTable($dbtable, $filename) 
      {
         $result = mysql_query("SELECT * FROM ".$dbtable);
         if (!$result) die('Query failed: "SELECT * FROM '.$dbtable.'"'.mysql_error());
         $this->export($result, $filename);
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Launches the export of a custom query
       * 
       * @param string $query Custom query to export
       * @param string $filename File name to export to
       */
      function exportCustomTable($query, $filename) 
      {
         $rows = mysql_query($query);
         $this->export($rows, $filename);
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Exports the query result into XML
       * 
       * @param integer $result MySQL query result handle
       * @param string $filename File name to export to
       */
      function export($result, $filename) 
      {
         $meta = mysql_fetch_field($result);
         $xmldoc = new xmlHandler($meta->table);
   
         while ($row = mysql_fetch_array($result, MYSQL_NUM)) 
         {
            $xmldoc->addElement($row, $result);
         }
   
         header("Content-type: application/force-download");
         header("Content-Disposition: attachment; filename=tcpro_" . $meta->table . "_" . date('Ymd_His') . ".xml");
         /** 
          * I am not creating a file from here. Just returning the text
          */
         //$fileHandle=fopen($filename,"w");
         //fwrite($fileHandle,$xmldoc->getXMLDocument());
         //fclose($fileHandle);
         echo $xmldoc->getXMLDocument();
      }
   
      // ---------------------------------------------------------------------
      /** 
       * Returns an XML document from a database table
       * 
       * @param string $dbtable Table name
       */
      function getTableAsXml($dbtable) 
      {
         $rows = mysql_query("SELECT * FROM ".$dbtable);
         $meta = mysql_fetch_field($rows);
         $xmldoc = new xmlHandler($meta->table);
   
         while ($row = mysql_fetch_array($rows, MYSQL_NUM)) 
         {
            $xmldoc->addElement($row, $rows);
         }
         return $xmldoc->getXMLDocument();
      }
   }
}
?>
