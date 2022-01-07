<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * user_model.php
 * 
 * Contains the class dealing with the user table
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
if (!class_exists("User_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to manage the user table
    * @package TeamCalPro
    */
   class User_model 
   {
      var $db = '';
      var $table = '';
      var $archive_table = '';
      
      var $username = '';
      var $password = NULL;
      var $firstname = NULL;
      var $lastname = NULL;
      var $title = NULL;
      var $position = NULL;
      var $group = NULL;
      var $phone = NULL;
      var $mobile = NULL;
      var $email = NULL;
      var $notify = 0;
      var $notify_group = NULL;
      var $status = 0;
      var $usertype = 0;
      var $ut_group = NULL;
      var $privileges = 1;
      var $bad_logins = 0;
      var $bad_logins_start = NULL;
      var $last_pw_change = NULL;
      var $birthday = NULL;
      var $idnumber = NULL;
      var $last_login = NULL;
      var $custom1 = '';
      var $custom2 = '';
      var $custom3 = '';
      var $custom4 = '';
      var $custom5 = '';
      var $customFree = '';
      var $customPopup = '';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function User_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_users'];
         $this->archive_table = $CONF['db_table_archive_users'];
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
       * Creates a new user record from local variables
       */
      function create() 
      {
         $query  = "INSERT INTO `".$this->table."` ";
         $query .= " (`username`,`password`,`firstname`,`lastname`,`title`,`position`,`group`,`phone`,`mobile`,`email`,`notify`,`notify_group`,`status`,`usertype`,`ut_group`,`privileges`,`bad_logins`,`bad_logins_start`,`last_pw_change`,`birthday`,`idnumber`,`last_login`,`custom1`,`custom2`,`custom3`,`custom4`,`custom5`,`customFree`,`customPopup`) ";
         $query .= "VALUES (";
         $query .= "'" . addslashes($this->username) . "',";
         $query .= "'" . $this->password . "',";
         $query .= "'" . addslashes($this->firstname) . "',";
         $query .= "'" . addslashes($this->lastname) . "',";
         $query .= "'" . addslashes($this->title) . "',";
         $query .= "'" . addslashes($this->position) . "',";
         $query .= "'" . addslashes($this->group) . "',";
         $query .= "'" . addslashes($this->phone) . "',";
         $query .= "'" . addslashes($this->mobile) . "',";
         $query .= "'" . addslashes($this->email) . "',";
         $query .= "'" . $this->notify . "',";
         $query .= "'" . addslashes($this->notify_group) . "',";
         $query .= "'" . $this->status . "',";
         $query .= "'" . $this->usertype . "',";
         $query .= "'" . addslashes($this->ut_group) . "',";
         $query .= "'" . $this->privileges . "',";
         $query .= "'" . $this->bad_logins . "',";
         $query .= "'" . $this->bad_logins_start . "',";
         if ($this->last_pw_change == NULL) { $query .= "NULL,"; } else { $query .= "'" . $this->last_pw_change . "',"; }
         if ($this->birthday == NULL) { $query .= "NULL,"; } else { $query .= "'" . $this->birthday . "',"; }
         $query .= "'" . addslashes($this->idnumber) . "',";
         if ($this->last_login == NULL) { $query .= "NULL,"; } else { $query .= "'" . $this->last_login . "',"; }
         $query .= "'" . addslashes($this->custom1) . "',";
         $query .= "'" . addslashes($this->custom2) . "',";
         $query .= "'" . addslashes($this->custom3) . "',";
         $query .= "'" . addslashes($this->custom4) . "',";
         $query .= "'" . addslashes($this->custom5) . "',";
         $query .= "'" . addslashes($this->customFree) . "',";
         $query .= "'" . addslashes($this->customPopup) . "'";
         $query .= ")";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes a user record by name
       * 
       * @param string $name Username to find
       * @param boolean $archive Whether to search in archive table
       */
      function deleteByName($name='', $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $query = "DELETE FROM `".$findTable."` WHERE `username` = '".$name."'";
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
       * Finds a user record by name and fills values into local variables
       * 
       * @param string $name Username to find
       * @param boolean $archive Whether to search in archive table
       * @return integer Result of MySQL query
       */
      function findByName($name='', $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $rc = 0;
         $query = "SELECT * FROM `".$findTable."` WHERE username = '".$name."'";
         $result = $this->db->db_query($query);

         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result, MYSQL_ASSOC);
            $this->username = stripslashes($row['username']);
            $this->password = $row['password'];
            $this->firstname = stripslashes($row['firstname']);
            $this->lastname = stripslashes($row['lastname']);
            $this->title = stripslashes($row['title']);
            $this->position = stripslashes($row['position']);
            $this->group = stripslashes($row['group']);
            $this->phone = stripslashes($row['phone']);
            $this->mobile = stripslashes($row['mobile']);
            $this->email = stripslashes($row['email']);
            $this->notify = $row['notify'];
            $this->notify_group = stripslashes($row['notify_group']);
            $this->status = $row['status'];
            $this->usertype = $row['usertype'];
            $this->ut_group = stripslashes($row['ut_group']);
            $this->privileges = $row['privileges'];
            $this->bad_logins = $row['bad_logins'];
            $this->bad_logins_start = $row['bad_logins_start'];
            $this->last_pw_change = $row['last_pw_change'];
            $this->birthday = str_replace("-", "", $row['birthday']);
            $this->idnumber = stripslashes($row['idnumber']);
            
            if (strlen($row['last_login'])==14)
               /*
                * MySQL did not provide the colons in the timestamps so we have to put them in.
                */
               $this->last_login=substr($row['last_login'],0,4)."-".substr($row['last_login'],4,2)."-".substr($row['last_login'],6,2)." ".substr($row['last_login'],8,2).":".substr($row['last_login'],10,2).":".substr($row['last_login'],12,2);
            else
               /*
                * Seems the colons are already in the timestamps
                */
               $this->last_login=$row['last_login'];
               
            //$this->last_login .= " ".date("T"); // Append the web servers timezone
            if (substr($this->last_login,0,10)=="0000-00-00") $this->last_login="";
            
            $this->custom1 = stripslashes($row['custom1']);
            $this->custom2 = stripslashes($row['custom2']);
            $this->custom3 = stripslashes($row['custom3']);
            $this->custom4 = stripslashes($row['custom4']);
            $this->custom5 = stripslashes($row['custom5']);
            $this->customFree = $row['customFree'];
            $this->customPopup = $row['customPopup'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records into an array
       * 
       * @return array $uarray Array with all records
       */
      function getAll($order1='lastname', $order2='firstname', $sort='ASC', $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $uarray = array();
         $query = "SELECT * FROM `".$findTable."` ORDER BY `".$order1."` ".$sort.",`".$order2."` ".$sort.";";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $uarray[] = $row;
         return $uarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records into an array where username, lastname or firstname
       * is like specified
       * 
       * @param string $like Likeness to search for
       * @return array $uarray Array with all records
       */
      function getAllLike($like, $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $uarray = array();
         $query = "SELECT * FROM `".$findTable."` ".
                  "WHERE `firstname` LIKE '%".$like."%' ".
                  "OR `lastname` LIKE '%".$like."%' ".
                  "OR `username` LIKE '%".$like."%' ".
                  "ORDER BY `lastname` ASC, `firstname` ASC;";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $uarray[] = $row;
         return $uarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records into an array, except admin
       * 
       * @return array $uarray Array with all records
       */
      function getAllButAdmin($order1='lastname', $order2='firstname', $sort='ASC') 
      {
         $uarray = array();
         $query = "SELECT * FROM `".$this->table."` WHERE username!='admin' ORDER BY `".$order1."` ".$sort.",`".$order2."` ".$sort.";";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $uarray[] = $row;
         return $uarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all usernames into an array
       * 
       * @return array $unamearray Array with all usernames
       */
      function getUsernames() 
      {
         $unamearray = array();
         $query = "SELECT username FROM `".$this->table."`";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $unamearray[] = $row['username'];
         return $unamearray;
      }

      // ---------------------------------------------------------------------
      /**
       * Clears one or more privilege flags
       * 
       * @param integer $bitmask Contains 0's for flags to clear (see config.tcpro.php for predefined bitmasks)
       */
      function clearPrivilege($bitmask) 
      {
         $this->privileges = $this->privileges & (~$bitmask);
      }

      // ---------------------------------------------------------------------
      /**
       * Checks and returns a privilege bitmask
       * 
       * @param integer $bitmask Bitmask to check (see config.tcpro.php for predefined bitmasks)
       * @return boolean True if matches the bitmask, false if not
       */
      function checkPrivilege($bitmask) 
      {
         if ($this->privileges & $bitmask)
            return 1;
         else
            return 0;
      }

      // ---------------------------------------------------------------------
      /**
       * Sets one or more privilege flags
       * 
       * @param integer $bitmask Contains 1's for flags to set (see config.tcpro.php for predefined bitmasks)
       */
      function setPrivilege($bitmask) 
      {
         $this->privileges = $this->privileges | $bitmask;
      }

      // ---------------------------------------------------------------------
      /**
       * Checks a notify bitmask
       * 
       * @param integer $bitmask Bitmask to check (see config.tcpro.php for predefined bitmasks)
       * @return boolean True if matches the bitmask, false if not
       */
      function checkNotify($bitmask) 
      {
         if ($this->notify & $bitmask)
            return 1;
         else
            return 0;
      }

      // ---------------------------------------------------------------------
      /**
       * Sets one or more notify flags
       * 
       * @param integer $bitmask Contains 1's for flags to set (see config.tcpro.php for predefined bitmasks)
       */
      function setNotify($bitmask) 
      {
         $this->notify = $this->notify | $bitmask;
      }

      // ---------------------------------------------------------------------
      /**
       * Clears one or more notify flags
       * 
       * @param integer $bitmask Contains 0's for flags to clear (see config.tcpro.php for predefined bitmasks)
       */
      function clearNotify($bitmask) 
      {
         $this->notify = $this->notify & (~$bitmask);
      }

      // ---------------------------------------------------------------------
      /**
       * Clears one or more status flags
       * 
       * @param integer $bitmask Contains 0's for flags to clear (see config.tcpro.php for predefined bitmasks)
       */
      function clearStatus($bitmask) 
      {
         $this->status = $this->status & (~$bitmask);
      }

      // ---------------------------------------------------------------------
      /**
       * Checks a status bitmask
       * 
       * @param integer $bitmask Bitmask to check (see config.tcpro.php for predefined bitmasks)
       * @return boolean True if matches the bitmask, false if not
       */
      function checkStatus($bitmask) 
      {
         if ($this->status & $bitmask)
            return 1;
         else
            return 0;
      }

      // ---------------------------------------------------------------------
      /**
       * Sets one or more status flags
       * 
       * @param integer $bitmask Contains 1's for flags to set (see config.tcpro.php for predefined bitmasks)
       */
      function setStatus($bitmask) 
      {
         $this->status = $this->status | $bitmask;
      }

      // ---------------------------------------------------------------------
      /**
       * Clears one or more user type flags
       * 
       * @param integer $bitmask Contains 0's for flags to clear (see config.tcpro.php for predefined bitmasks)
       */
      function clearUserType($bitmask) 
      {
         $this->usertype = $this->usertype & (~intval($bitmask));
      }

      // ---------------------------------------------------------------------
      /**
       * Checks a user type bitmask
       * 
       * @param integer $bitmask Bitmask to check (see config.tcpro.php for predefined bitmasks)
       * @return boolean True if matches the bitmask, false if not
       */
      function checkUserType($bitmask) 
      {
         if ($this->usertype & $bitmask)
            return 1;
         else
            return 0;
      }

      // ---------------------------------------------------------------------
      /**
       * Sets one or more status flags
       * 
       * @param integer $bitmask Contains 1's for flags to set (see config.tcpro.php for predefined bitmasks)
       */
      function setUserType($bitmask) 
      {
         $this->usertype = $this->usertype | $bitmask;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates an existing user record from local class variables
       * 
       * @param string $name Username of record to update
       */
      function update($name) 
      {
         $query  = "UPDATE `".$this->table."` ";
         $query .= "SET `username`     = '" . addslashes($this->username) . "', ";
         $query .= "`password`         = '" . $this->password . "', ";
         $query .= "`firstname`        = '" . addslashes($this->firstname) . "', ";
         $query .= "`lastname`         = '" . addslashes($this->lastname) . "', ";
         $query .= "`title`            = '" . addslashes($this->title) . "', ";
         $query .= "`position`         = '" . addslashes($this->position) . "', ";
         $query .= "`group`            = '" . addslashes($this->group) . "', ";
         $query .= "`phone`            = '" . addslashes($this->phone) . "', ";
         $query .= "`mobile`           = '" . addslashes($this->mobile) . "', ";
         $query .= "`email`            = '" . addslashes($this->email) . "', ";
         $query .= "`notify`           = '" . $this->notify . "', ";
         $query .= "`notify_group`     = '" . addslashes($this->notify_group) . "', ";
         $query .= "`status`           = '" . $this->status . "', ";
         $query .= "`usertype`         = '" . $this->usertype . "', ";
         $query .= "`ut_group`         = '" . addslashes($this->ut_group) . "', ";
         $query .= "`privileges`       = '" . $this->privileges . "', ";
         $query .= "`bad_logins`       = '" . $this->bad_logins . "', ";
         $query .= "`bad_logins_start` = '" . $this->bad_logins_start . "', ";
         
         if ($this->last_pw_change == NULL) { 
            $query .= "`last_pw_change`= NULL, "; 
         } else { 
            $query .= "`last_pw_change`= '" . $this->last_pw_change . "', "; 
         }
         
         if ($this->birthday == NULL) { 
            $query .= "`birthday`      = NULL, "; 
         } else { 
            $query .= "`birthday`      = '" . $this->birthday . "', "; 
         }
         
         $query .= "`idnumber`         = '" . $this->idnumber . "', ";
         
         if ($this->last_login == NULL) { 
            $query .= "`last_login`    = NULL, "; 
         } else { 
            $query .= "`last_login`    = '" . substr($this->last_login, 0, 19) . "', "; 
         }
         
         $query .= "`custom1`          = '" . addslashes($this->custom1) . "', ";
         $query .= "`custom2`          = '" . addslashes($this->custom2) . "', ";
         $query .= "`custom3`          = '" . addslashes($this->custom3) . "', ";
         $query .= "`custom4`          = '" . addslashes($this->custom4) . "', ";
         $query .= "`custom5`          = '" . addslashes($this->custom5) . "', ";
         $query .= "`customFree`       = '" . addslashes($this->customFree) . "', ";
         $query .= "`customPopup`      = '" . addslashes($this->customPopup) . "'";
         $query .= " WHERE `username`  = '" . $name . "'";
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
