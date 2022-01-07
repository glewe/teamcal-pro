<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * announcement_model.php
 * 
 * Contains the class to interface with the announcement and user-announcement table
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
if (!class_exists("Announcement_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the announcement and user-announcement table
    * @package TeamCalPro
    */
   class Announcement_model 
   {
      var $db = '';
      var $table = '';
      
      var $id = NULL;
      var $timestamp = '';
      var $text = '';
      var $popup = '0';
      var $silent = '0';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Announcement_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_announcements'];
      }

      // ---------------------------------------------------------------------
      /**
       * Clear all records in announcement table
       */
      function clearAnnouncements() 
      {
         $query = "TRUNCATE TABLE `".$this->table."`";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records into an array
       * 
       * @return array $aarray Array with all records
       */
      function getAll() 
      {
         $aarray = array();
         $query = "SELECT * FROM `".$this->table."`;";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $aarray[] = $row;
         return $aarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Read an announcement record for a given timestamp
       * 
       * @param string $ts Timestamp to search for
       */
      function read($ts) 
      {
         $query = "SELECT * FROM ".$this->table." WHERE timestamp='".$ts."';";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result, MYSQL_ASSOC);
            $this->text = $row['text'];
            $this->popup = $row['popup'];
            $this->silent = $row['silent'];
            return $this->text;
         }
         else 
         {
            return 0;
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Save an announcement
       * 
       * @param string $ts Timestamp to search for
       * @param string $text Text of the announcement
       * @param integer $popup 1 if this announcement should popup upon login
       * @param integer $silent 1 if this announcement should be listed on the users announcement page
       */
      function save($ts, $text, $popup, $silent) 
      {
         $query = "INSERT into `".$this->table."` (`timestamp`,`text`,`popup`,`silent`) ";
         $query .= "VALUES ('".$ts."','".$text."','".$popup."','".$silent."')";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Delete an announcement by timestamp
       * 
       * @param string $ts Timestamp to search for
       */
      function delete($ts) 
      {
         $query = "DELETE FROM ".$this->table." WHERE `timestamp`='".$ts."';";
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
