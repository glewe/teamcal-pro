<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * group_model.php
 * 
 * Contains the class dealing with the group table
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
if (!class_exists("Group_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the group table
    * @package TeamCalPro
    */
   class Group_model 
   {
      var $db = '';
      var $table = '';
      
      var $groupname = '';
      var $description = '';
      var $options = '0';
      var $min_present = '1';
      var $max_absent = '1';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Group_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_groups'];
         $this->hide = $CONF['G_HIDE'];
      }

      // ---------------------------------------------------------------------
      /**
       * Creates a new group record from local class variables
       */
      function create() 
      {
         $query = "INSERT INTO `" . $this->table . "` ";
         $query .= " (`groupname`,`description`,`options`, `min_present`, `max_absent`) ";
         $query .= "VALUES ('";
         $query .= addslashes($this->groupname) . "','";
         $query .= addslashes($this->description) . "','";
         $query .= $this->options . "','";
         $query .= $this->min_present . "','";
         $query .= $this->max_absent . "'";
         $query .= ")";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes a group record for a given group name
       * 
       * @param string $gname Group to delete
       */
      function deleteByName($gname='') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `groupname` = '".addslashes($gname)."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Finds a group record for a given group name and loads values in local class variables
       * 
       * @param string $gname Group to find
       */
      function findByName($gname='') 
      {
         $rc = 0;
         // see if the user exists
         $query = "SELECT * FROM `".$this->table."` WHERE groupname = '".$gname."'";
         $result = $this->db->db_query($query);

         // exactly one row found ( a good thing!)
         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result);
            $this->groupname = stripslashes($row['groupname']);
            $this->description = stripslashes($row['description']);
            $this->options = $row['options'];
            $this->min_present = $row['min_present'];
            $this->max_absent = $row['max_absent'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all group records into an array
       * 
       * @param boolean $excludeHidden If TRUE, exclude hidden groups
       * @return array $grouparray Array with all group records
       */
      function getAll($excludeHidden=FALSE, $order='groupname', $sort='ASC') 
      {
         $grouparray = array();
         $query = "SELECT * FROM `".$this->table."` ORDER BY `".$order."` ".$sort.";";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) 
         {
            if (!$excludeHidden) 
            {
               $grouparray[] = $row;
            }
            else 
            {
               $this->options = $row['options'];
               if (!$this->checkOptions($this->hide)) $grouparray[] = $row;
            }
         }
         return $grouparray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records from a given group into an array
       *
       * @param string $group Group to look for
       * @param boolean $excludeHidden If TRUE, exclude hidden groups
       * @return array $grouparray Array with all group records
       */
      function getAllByGroup($group="%", $excludeHidden=FALSE) 
      {
         $grouparray = array();
         $query = "SELECT * FROM `".$this->table."` WHERE groupname LIKE '".$group."' ORDER BY groupname ASC;";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) 
         {
            if (!$excludeHidden) 
            {
               $grouparray[] = $row;
            }
            else 
            {
               $this->options = $row['options'];
               if (!$this->checkOptions($this->hide)) $grouparray[] = $row;
            }
         }
         return $grouparray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all group names into an array
       * 
       * @return array $grouparray Array with all group names
       */
      function getGroups() 
      {
         $grouparray = array();
         $query = "SELECT groupname FROM `" . $this->table . "` ORDER BY groupname ASC;";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) 
         {
            $grouparray[] = stripslashes($row['groupname']);
         }
         return $grouparray;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a group record for a given group name from local class variables
       * 
       * @param string $gname Group to update
       */
      function update($gname) 
      {
         $query = "UPDATE `" . $this->table . "` ";
         $query .= "SET `groupname` = '" . addslashes($this->groupname) . "', ";
         $query .= "`description` = '" . addslashes($this->description) . "', ";
         $query .= "`options` = '" . $this->options . "', ";
         $query .= "`min_present` = '" . $this->min_present . "', ";
         $query .= "`max_absent` = '" . $this->max_absent . "' ";
         $query .= " WHERE `groupname` = '" . $gname . "'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Clears flags in the option bitmask. See config.tcpro.php for predefined bitmasks.
       * 
       * @param integer $bitmask Bitmask with flags to clear
       */ 
      function clearOptions($bitmask) 
      {
         $this->options = $this->options & (~$bitmask);
      }

      // ---------------------------------------------------------------------
      /**
       * Checks whether a bitmask ist set or not in the option field. See config.tcpro.php for predefined bitmasks.
       * 
       * @param integer $bitmask Bitmask with flags to check
       */ 
      function checkOptions($bitmask) 
      {
         if ($this->options & $bitmask)
            return 1;
         else
            return 0;
      }

      // ---------------------------------------------------------------------
      /**
       * Sets a bitmask in the option field. See config.tcpro.php for predefined bitmasks.
       * 
       * @param integer $bitmask Bitmask with flags to set
       */ 
      function setOptions($bitmask) 
      {
         $this->options = $this->options | $bitmask;
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
