<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * user_group_model.php
 * 
 * Contains the class dealing with the user-group table
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
if (!class_exists("User_group_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to manage the user-group table
    * @package TeamCalPro
    */
   class User_group_model 
   {
      var $db = '';
      var $table = '';
      var $archive_table = '';
      
      var $id = NULL;
      var $username = NULL;
      var $groupname = NULL;
      var $type = NULL;

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function User_group_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_user_group'];
         $this->archive_table = $CONF['db_table_archive_user_group'];
      }

      // ---------------------------------------------------------------------
      /**
       * Archives all user_group records for a given user
       * 
       * @param string $username Username to archive
       */
      function archive($username) 
      {
         $query  = "INSERT INTO ".$this->archive_table." SELECT u.* FROM ".$this->table." u WHERE username = '".$username."';";
         $result = $this->db->db_query($query);
      }
      
      // ---------------------------------------------------------------------
      /**
       * Restores all user_group records for a given user
       * 
       * @param string $name Username to restore
       */
      function restore($username) 
      {
         $query  = "INSERT INTO ".$this->table." SELECT a.* FROM ".$this->archive_table." a WHERE username = '".$username."';";
         $result = $this->db->db_query($query);
      }
      
      // ---------------------------------------------------------------------
      /**
       * Checks whether a record exists
       * 
       * @param string $username Username to find
       * @param boolean $archive Whether to search in archive table
       * @return integer Result of MySQL query
       */
      function exists($username='', $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $query = "SELECT id FROM `".$findTable."` WHERE username = '".$username."'";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result)) return TRUE;
         else return FALSE;
      } 
      
      // ---------------------------------------------------------------------
      /**
       * Creates a new user-group record
       * 
       * @param string $createuser Username
       * @param string $creategroup Groupname
       * @param string $createtype Type of membership (member, manager)
       */
      function createUserGroupEntry($createuser, $creategroup, $createtype) 
      {
         $query = "INSERT INTO `" . $this->table . "` ";
         $query .= "(`username`,`groupname`,`type`) ";
         $query .= "VALUES ('";
         $query .= $createuser . "','";
         $query .= $creategroup . "','";
         $query .= $createtype . "'";
         $query .= ")";
         $result = $this->db->db_query($query);
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
       * Reads all usernames of a given group
       * 
       * @param string $groupname Group to search by
       * @return array $uarray Array with all group records
       */
      function getAllforGroup($groupname) 
      {
         $uarray = array();
         $query = "SELECT username FROM `".$this->table."` WHERE groupname='".$groupname."' ORDER BY username ASC;";
         $result = $this->db->db_query($query);
         while ($row=$this->db->db_fetch_array($result)) {
            $uarray[] = $row['username'];
         }
         return $uarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records for a given user into an array
       * 
       * @param string $username Username to find
       * @return array $ugarray Array with all records
       */
      function getAllforUser($username) 
      {
         $ugarray = array();
         $query = "SELECT * FROM `".$this->table."` WHERE `username` = '" . $username . "';";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $ugarray[] = $row;
         return $ugarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records for a given user into an array
       * 
       * @param string $username Username to find
       * @return array $ugarray Array with all records
       */
      function getAllforUser2($username) 
      {
         $ugarray = array();
         $query = "SELECT * FROM `".$this->table."` WHERE `username` = '" . $username . "';";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $ugarray[$row['groupname']] = $row['type'];
         return $ugarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Gets the first group a user has an entry for
       * 
       * @param string $user Username to find
       * @return string Groupname of first group found or 'unknown'
       */
      function getGroupName($user) 
      {
         $query = "SELECT groupname FROM `".$this->table."` WHERE `username` = '".$user."';";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result)) 
         {
            $row = $this->db->db_fetch_array($result, MYSQL_NUM);
            return $row[0];
         }
         else 
         {
            return "unknown";
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Gets all group managers of a given group
       * 
       * @param string $checkgroup Groupname to check
       * @return array $gmarray Array with usernames of group managers
       */
      function getGroupManagers($checkgroup) 
      {
         $gmarray = array();
         $query = "SELECT `username` FROM `".$this->table."` WHERE groupname` = '".$checkgroup."' AND `type` = 'manager';";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $gmarray[$row['groupname']] = $row['username'];
         return $gmarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes a user-group record by ID from local class variable
       */
      function deleteById() 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `id` = '".$this->id."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all records for a given user
       * 
       * @param string $user Username to delete
       * @param boolean $archive Whether to search in archive table
       */
      function deleteByUser($deluser = '', $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $query = "DELETE FROM `".$findTable."` WHERE `username` = '".$deluser."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all records for a given group
       * 
       * @param string $user Groupname to delete
       */
      function deleteByGroup($delgroup = '') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `groupname` = '".$delgroup."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all records for a given user and group (Deletes membership)
       * 
       * @param string $deluser Username
       * @param string $delgroup Groupname
       */
      function deleteMembership($deluser='',$delgroup = '') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `username` ='".$deluser."' AND `groupname` = '".$delgroup."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Checks whether a given user is member od a given group
       * 
       * @param string $finduser Username
       * @param string $findgroup Groupname
       * @return boolean True if member, false if not
       */
      function isMemberOfGroup($finduser, $findgroup) 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `username` = '".$finduser."' AND `groupname` = '".$findgroup."';";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result, MYSQL_ASSOC);
            $this->username = $row['username'];
            $this->groupname = $row['groupname'];
            $this->type = $row['type'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Checks whether two given users share membership of at least one group
       * 
       * @param string $user1 First username
       * @param string $user2 Second username
       * @return boolean True if they do, false if not
       */
      function shareGroups($user1, $user2) 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `username` = '".$user1."';";
         $result = $this->db->db_query($query);
         while ($row = $this->db->db_fetch_array($result)) 
         {
            $query2 = "SELECT * FROM `".$this->table."` WHERE `username` = '".$user2."' AND `groupname` = '".$row['groupname']."';";
            $result2 = $this->db->db_query($query2);
            if ($this->db->db_numrows($result2)) return 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Checks whether a given user is manager of any group
       * 
       * @param string $checkuser Username to check
       * @return boolean True if he is, false if not
       */
      function isGroupManager($checkuser) 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `username` = '".$checkuser."' AND `type` = 'manager';";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) >= 1) $rc = 1;
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Checks whether a given user is manager of a given group
       * 
       * @param string $checkuser Username to check
       * @param string $checkgroup Groupname to check
       * @return boolean True if he is, false if not
       */
      function isGroupManagerOfGroup($checkuser, $checkgroup) 
      {
         $rc = 0;
         $query = "SELECT `username` FROM `".$this->table."` WHERE `username` = '".$checkuser."' AND `groupname` = '".$checkgroup."' AND `type` = 'manager';";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) $rc = 1;
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Checks whether a given user is manager of another given user
       * 
       * @param string $user1 Username to check whether he is manager of user 2
       * @param string $user1 Username to check whether he is managed by user 1
       * @return boolean True if he is, false if not
       */
      function isGroupManagerOfUser($user1, $user2) 
      {
         $rc = 0;
         $query = "SELECT `groupname` FROM `".$this->table."` WHERE `username` = '".$user2."';";
         $result = $this->db->db_query($query);
         while ($row = $this->db->db_fetch_array($result, MYSQL_ASSOC)) 
         {
            $query2 = "SELECT `username` FROM `".$this->table."` WHERE `username` = '".$user1."' AND `groupname` = '".$row['groupname']."' AND `type` = 'manager';";
            $result2 = $this->db->db_query($query2);
            if ($this->db->db_numrows($result2) == 1) return 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Finds a user-group record by ID
       * 
       * @param string $findid Record ID to find
       * @return integer Result of MySQL query
       */
      function findById($findid) 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `id` = '".$findid."';";
         $result = $this->db->db_query($query);
         // exactly one row found ( a good thing!)
         if ($this->db->db_numrows($result) == 1)
         {
            $row = $this->db->db_fetch_array($result);
            $this->username = $row['username'];
            $this->groupname = $row['groupname'];
            $this->type = $row['type'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a user-group record from local class variables
       */
      function update() 
      {
         $query = "UPDATE `".$this->table."` ";
         $query .= "SET `username`   = '".$this->username."', ";
         $query .= "`groupname`  = '".$this->groupname."', ";
         $query .= "`type`       = '".$this->type."' ";
         $query .= "WHERE `id`       = '".$this->id."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a groupname in all records
       * 
       * @param string $groupold Old groupname
       * @param string $groupnew New groupname
       */
      function updateGroupname($groupold, $groupnew) 
      {
         $query = "UPDATE `".$this->table."` ";
         $query .= "SET `groupname`   = '".$groupnew."' ";
         $query .= "WHERE `groupname` = '".$groupold."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Updates the type of membership
       * 
       * @param string $upduser Username to update
       * @param string $updgroup Groupname to update
       * @param string $updtype New membership type
       */
      function updateUserGroupType($upduser, $updgroup, $updtype) 
      {
         $query = "UPDATE `".$this->table."` ";
         $query .= "SET `type`   = '".$updtype."' ";
         $query .= "WHERE `groupname` = '".$updgroup."' AND `username`='".$upduser."'";
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
