<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * user_announcement_model.php
 * 
 * Contains the class to interface with the user-announcement table
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
if (!class_exists("User_announcement_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the announcement and user-announcement table
    * @package TeamCalPro
    */
   class User_announcement_model 
   {
      var $db = '';
      var $table = '';
      var $archive_table = '';
      
      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function User_announcement_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_user_announcement'];
         $this->archive_table = $CONF['db_table_archive_user_announcement'];
      }

      // ---------------------------------------------------------------------
      /**
       * Archives a user record
       * 
       * @param string $name Username to archive
       */
      function archive($name) 
      {
         $query  = "INSERT INTO ".$this->archive_table." SELECT u.* FROM ".$this->table." u WHERE username = '".$name."';";
         $result = $this->db->db_query($query);
      }
      
      // ---------------------------------------------------------------------
      /**
       * Restore arcived user records
       * 
       * @param string $name Username to restore
       */
      function restore($name) 
      {
         $query  = "INSERT INTO ".$this->table." SELECT a.* FROM ".$this->archive_table." a WHERE username = '".$name."';";
         $result = $this->db->db_query($query);
      }
      
      // ---------------------------------------------------------------------
      /**
       * Checks whether a user record exists
       * 
       * @param string $name Username to find
       * @param boolean $archive Whether to search in archive table
       * @return integer Result of MySQL query
       */
      function exists($name='', $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $query = "SELECT username FROM `".$findTable."` WHERE username = '".$name."'";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result)) return TRUE;
         else return FALSE;
      }
       
      // ---------------------------------------------------------------------
      /**
       * Deletes all records
       * 
       * @param boolean $archive Whether to search in archive table
       */
      function deleteAll($archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $query = "SELECT * FROM `".$findTable."`;";
         $result = $this->db->db_query($query);
         if (mysql_num_rows($result))
         {
            $query = "TRUNCATE TABLE ".$findTable.";";
            $result = $this->db->db_query($query);
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Clear all records in the user-announcement table for a given user
       * 
       * @param string $username Username of the records to delete
       */
      function deleteByUser($username) 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `username`='".$username."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records for a given user into an array
       * 
       * @param string $username Username
       * @return array $uaarray Array with all records
       */
      function getAllForUser($username) 
      {
         $uaarray = array();
         $query = "SELECT * FROM `".$this->table."` WHERE username='".$username."' ORDER BY ats DESC;";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $uaarray[] = $row;
         return $uaarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records for a given timestamp into an array
       * 
       * @param string $ts Timestamp
       * @return array $uaarray Array with all records
       */
      function getAllForTimestamp($ts) 
      {
         $uaarray = array();
         $query = "SELECT * FROM `".$this->table."` WHERE ats='".$ts."' ORDER BY ats DESC;";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $uaarray[] = $row;
         return $uaarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Assign an announcement to a user (by timestamp)
       * 
       * @param string $ts Timestamp to search for
       * @param string $user Username to assign to
       */
      function assign($ts, $user) 
      {
         $query = "INSERT into `".$this->table."` (`username`,`ats`) VALUES ('".$user."','".$ts."')";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Delete an announcement by timestamp and username
       * 
       * @param string $ts Timestamp to search for
       * @param string $user Username to search for
       */
      function unassign($ts, $user) 
      {
         $query = "DELETE FROM ".$this->table." WHERE username='".$user."' AND ats='".$ts."'";
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
