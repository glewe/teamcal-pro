<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * styles_model.php
 * 
 * Contains the class to interface with the styles table
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
if (!class_exists("Styles_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the announcement and user-announcement table
    * @package TeamCalPro
    */
   class Styles_model 
   {
      var $db = '';
      var $table = '';
      
      var $id = NULL;
      var $name = '';
      var $value = '';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Styles_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_styles'];
      }

      // ---------------------------------------------------------------------
      /**
       * Clear all records in styles table
       */
      function deleteAll() 
      {
         $query = "TRUNCATE TABLE `".$this->table."`;";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Clear one record
       * 
       * @param string $name Name of style to delete
       */
      function deleteStyle($name) 
      {
         $query = "DELETE FROM ".$this->table." WHERE name='".$name."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records into an array
       * 
       * @return array $sarray Array with all records
       */
      function getAll() 
      {
         $myarray = array();
         $query = "SELECT * FROM ".$this->table.";";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $myarray[] = $row;
         return $myarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Read a stylesheet
       * 
       * @param string $name Name of stylesheet
       */
      function getStyle($name) 
      {
         $query = "SELECT value FROM ".$this->table." WHERE name='".$name."';";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result, MYSQL_ASSOC);
            return $row['value'];
         }
         return 0;
      }

      // ---------------------------------------------------------------------
      /**
       * Save a style
       * 
       * @param string $name Name of style to save
       * @param string $value Style to save
       */
      function saveStyle($name, $value) 
      {
         $query = "SELECT * FROM ".$this->table." WHERE name='".$name."';";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result, MYSQL_ASSOC);
            $query = "UPDATE ".$this->table." SET name='".$name."', value='".$value."' WHERE id='".$row['id']."'";
         }
         else 
         {
            $query = "INSERT into ".$this->table." (name,value) VALUES ('".$name."','".$value."')";
         }
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Optimize table
       * 
       * @return boolean Optimize result
       */ 
      function optimize() 
      {
         $result = $this->db->db_query('OPTIMIZE TABLE '.$this->table);
         return $result;
      }
   }
}
?>
