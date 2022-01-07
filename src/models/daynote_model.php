<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * daynote_model.php
 * 
 * Contains the class dealing with the daynote table
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
if (!class_exists("Daynote_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the daynote table
    * @package TeamCalPro
    */
   class Daynote_model 
   {
      var $db = '';
      var $table = '';
      var $archive_table = '';
      
      var $id = NULL;
      var $yyyymmdd = '';
      var $daynote = '';
      var $daynotes = array();
      var $count = NULL;
      var $username = '';
      var $region = '';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Daynote_model() 
      {
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_daynotes'];
         $this->archive_table = $CONF['db_table_archive_daynotes'];
      }

      // ---------------------------------------------------------------------
      /**
       * Archives all records for a given user
       * 
       * @param string $username Username to archive
       */
      function archive($username) 
      {
         $query  = "INSERT INTO ".$this->archive_table." SELECT t.* FROM ".$this->table." t WHERE username = '".$username."';";
         $result = $this->db->db_query($query);
      }
      
      // ---------------------------------------------------------------------
      /**
       * Restores all records for a given user
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
       * Creates a daynote record from class variables
       */
      function create() 
      {
         $query = "INSERT INTO `".$this->table."` (`yyyymmdd`,`daynote`,`username`, `region`) VALUES ('";
         $query .= $this->yyyymmdd . "','";
         $query .= mysql_real_escape_string ($this->daynote) . "','";
         $query .= $this->username . "','";
         $query .= $this->region . "'";
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
       * Deletes a daynote record by date and username
       * 
       * @param string $yyyymmdd 8 character date (YYYYMMDD) to find for deletion
       * @param string $username Userame to find for deletion
       * @param string $region Region to find for deletion
       */
      function deleteByDay($yyyymmdd='', $username='', $region='default') 
      {
         $query  = "DELETE FROM `".$this->table."` ";
         $query .= "WHERE `yyyymmdd` = '".$yyyymmdd."' ";
         $query .= "AND `username` = '".$username."' ";
         $query .= "AND `region` = '".$region."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes a daynote record by id
       * 
       * @param string $id ID to find for deletion
       */
      function deleteById($id='') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `id` = '".$id."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all daynotes for a region
       * 
       * @param string $region Region to find for deletion
       */
      function deleteByRegion($region='default') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `region` = '".$region."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all daynotes for a user
       * 
       * @param string $uname Username to find for deletion
       */
      function deleteByUser($uname='', $archive=FALSE) 
      {
         if ($archive) $findTable = $this->archive_table; else $findTable = $this->table;
         $query = "DELETE FROM `".$findTable."` WHERE `username` = '".$uname."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Finds a daynote record by date and username and loads values in local class variables
       * 
       * @param string $yyyymmdd 8 character date (YYYYMMDD) to find
       * @param string $username Userame to find
       */
      function findByDay($yyyymmdd='', $username='', $region='default') 
      {
         $rc=0;
         
         $query  = "SELECT * FROM `".$this->table."` ";
         $query .= "WHERE `yyyymmdd` = '".$yyyymmdd."' ";
         $query .= "AND `username` = '".$username."' ";
         $query .= "AND `region` = '".$region."'";
         
         $result = $this->db->db_query($query);

         if ($this->db->db_numrows($result) == 1) 
         {
            // exactly one row found ( a good thing!)
            $row = $this->db->db_fetch_array($result);
            $this->id = $row['id'];
            $this->yyyymmdd = $row['yyyymmdd'];
            $this->daynote = stripslashes($row['daynote']);
            $this->username = $row['username'];
            $this->region = $row['region'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Find all daynotes for a given user and month and load them in daynotes array
       * 
       * @param string $yyyy     Year to find
       * @param string $mm       Month to find
       * @param string $days     Number of days in month (used to set end date)
       * @param string $username Username to find
       * @param string $region   Region to find
       */
      function findAllByMonthUser($yyyy='', $mm='', $days='', $username='', $region='default') 
      {
         if ($days < 10) $days = '0' + "0".strval($days);
         $rc = 0;
         $startdate = $yyyy.$mm.'01';
         $enddate = $yyyy.$mm.$days;
         $query  = "SELECT * FROM `".$this->table."` ";
         $query .= "WHERE `yyyymmdd` BETWEEN '".$startdate."' AND '".$enddate."' ";
         $query .= "AND `username` = '".$username."' ";
         $query .= "AND `region` = '".$region."'";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) > 0) 
         {
            while($row = $this->db->db_fetch_array($result)) $this->daynotes[$row['username']][$row['yyyymmdd']] = stripslashes($row['daynote']);
            $rc = 1;
         }
         return $rc;
      }
           
      // ---------------------------------------------------------------------
      /**
       * Find all daynotes for all users in a given month and load them in daynotes array
       * 
       * @param string $yyyy      Year to find
       * @param string $mm        Month to find
       * @param string $days      Number of days in month (used to set end date)
       * @param string $usernames Array of usernames to find
       * @param string $region    Region to find
       */
      function findAllByMonth($yyyy='', $mm='', $days='', $usernames, $region='default') 
      {
         $rc = 0;
         if ($days < 10) $days = '0' + "0".strval($days);
         $startdate = $yyyy.$mm.'01';
         $enddate = $yyyy.$mm.$days;
         $query  = "SELECT * FROM `".$this->table."` ";
         $query .= "WHERE `yyyymmdd` BETWEEN '".$startdate."' AND '".$enddate."' ";
         $query .= "AND username IN('".$usernames."')";
         $query .= "AND `region` = '".$region."'";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) > 0) 
         {
            while($row = $this->db->db_fetch_array($result)) $this->daynotes[$row['username']][$row['yyyymmdd']] = stripslashes($row['daynote']);
            $rc = 1;
         }
         $this->count = $this->db->db_numrows($result);
         return $rc;
      }  

      // ---------------------------------------------------------------------
      /**
       * Finds a daynote record by id and loads values in local class variables
       * 
       * @param string $id ID to find
       */
      function findById($id='') 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `id` = '".$id."'";
         $result = $this->db->db_query($query);

         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result);
            $this->id = $row['id'];
            $this->yyyymmdd = $row['yyyymmdd'];
            $this->daynote = stripslashes($row['daynote']);
            $this->username = $row['username'];
            $this->region = $row['region'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a daynote record from local class variables
       */
      function update() 
      {
         $query = "UPDATE `".$this->table."` SET ";
         $query .= "`yyyymmdd` = '".$this->yyyymmdd."', ";
         $query .= "`daynote` = '".mysql_real_escape_string($this->daynote)."', ";
         $query .= "`username` = '".$this->username."', ";
         $query .= "`region` = '".$this->region."' ";
         $query .= "WHERE `id` = '".$this->id."'";
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
