<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * config_model.php
 *
 * Contains the class dealing with the config table
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
if (!class_exists("Config_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the config table
    * @package TeamCalPro
    */
   class Config_model 
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
      function Config_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_config'];
      }

      // ---------------------------------------------------------------------
      /**
       * Read the value of an option
       *
       * @param string $name Name of the option
       * @return string Value of the option or empty if not found
       */
      function readConfig($name) 
      {
         $query = "SELECT value FROM `".$this->table."` WHERE `name` = '".$name."'";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result, MYSQL_ASSOC);
            return trim($row['value']);
         }
         else 
         {
            return "";
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Update/create the value of an option
       *
       * @param string $name Name of the option
       * @param string @value Value to save
       * @return integer Query result, or 0 if query not successful
       */
      function saveConfig($name, $value) 
      {
         $query = "SELECT value FROM `".$this->table."` WHERE `name`='".$name."'";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) 
         {
            $query = "UPDATE `".$this->table."` SET `value`='".$value."' WHERE `name`='".$name."'";
            $result = $this->db->db_query($query);
            return $result;
         }
         elseif ($this->db->db_numrows($result) == 0) 
         {
            $query = "INSERT INTO `".$this->table."` (`name`,`value`) VALUES ('".$name."','".$value."')";
            $result = $this->db->db_query($query);
            return $result;
         }
         else 
         {
            return 0;
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Update default region
       *
       * @param string $region_old Old region name
       * @param string @region_new New region name
       * @return integer Query result, or 0 if query not successful
       */
      function updateRegion($region_old, $region_new='default') 
      {
         $query = "UPDATE `".$this->table."` SET `value`='".$region_new."' WHERE `name`='defregion' AND `value`='".$region_old."'";
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
         $result = $this->db->db_query('OPTIMIZE TABLES '.$this->table);
         return $result;
      }
   }
}
?>
