<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * log_model.php
 * 
 * Contains the class dealing with the holiday table
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
if (!class_exists("Log_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the logging table
    * @package TeamCalPro
    */
   class Log_model 
   {
      var $db = '';
      var $table = '';

      // Database fields
      var $id = NULL;
      var $type = NULL;
      var $timestamp = '';
      var $user = '';
      var $event = '';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Log_model() 
      {
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_log'];
      }

      // ---------------------------------------------------------------------
      /**
       * Delete current record
       */
      function clear() 
      {
         $query = "SELECT * FROM `".$this->table."`;";
         $result = $this->db->db_query($query);
         if (mysql_num_rows($result))
         {
            $query = "TRUNCATE TABLE ".$this->table.";";
            $result = $this->db->db_query($query);
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Find
       * 
       * @param integer $order Sort order (1=DESC, 0=ASC)
       * @return integer 1 success, 0 failure
       */
      function read($order='DESC', $from='', $to='') 
      {
         $query = "SELECT * FROM ".$this->table." ".
                  "WHERE (timestamp>='".$from."' AND timestamp<='".$to." 23:59:59') ".
                  "ORDER BY timestamp ".$order.";";
         $result = $this->db->db_query($query);
         return $result;
      }

      // ---------------------------------------------------------------------
      /**
       * Event Logging
       * 
       * @param string $type Type of log entry
       * @param string $user Corresponding user name of log entry
       * @param string $event Type of event to log
       */
      function log($type, $user, $event, $object='') 
      {
         global $CONF;
         require ($CONF['app_root'] . "models/config_model.php");
         $C = new Config_model;
         if (!strlen($C->readConfig("logLanguage"))) $loglang='english'; 
         else $loglang = $C->readConfig("logLanguage");
         require ($CONF['app_root'] . "languages/".$loglang.".log.tcpro.php");
         
         $myEvent = $LANG[$event].$object;
         
         if ($C->readConfig($type)) 
         {
            $ts = date("YmdHis");
            $query = "INSERT into `" . $this->table . "` (`type`,`timestamp`,`user`,`event`) ";
            $query .= "VALUES ('" . $type . "','" . $ts . "','" . $user . "','" . addslashes($myEvent) . "')";
            $result = $this->db->db_query($query);
         }
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
