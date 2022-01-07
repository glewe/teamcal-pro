<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * absence_group_model.php
 * 
 * Contains the class to interface with the abs group table
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
if (!class_exists("Absence_group_model")) 
{
   require_once ("models/db_model.php");
   
   /**
    * Provides objects and methods to interface with the absence group table
    * @package TeamCalPro
    */
   class Absence_group_model 
   {
      var $db = '';
      var $table = '';
      var $log = '';
      var $logtype = '';
      var $id = NULL;
      var $absence = NULL;
      var $group = NULL;

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Absence_group_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_absence_group'];
         $this->log = $CONF['db_table_log'];
      }

      // ---------------------------------------------------------------------
      /**
       * Creates a record assigning an absence type to a group
       * 
       * @param string $absid Absence ID
       * @param string $group Group short name
       */
      function assign($absid, $group) 
      {
         $query = "INSERT INTO `".$this->table."` (`absid`,`group`) VALUES ('".$absid."','".$group."')";
         $result = $this->db->db_query($query);
      }

      //----------------------------------------------------------------------
      /**
       * Reads all absence IDs for a given groupname
       * 
       * @param string $groupname
       * @return array $absarray Array with the IDs
       */
      function getAllForGroup($groupname="%") 
      {
         $absarray = array();
         $query = "SELECT absid FROM `".$this->table."` WHERE `group` = '".$groupname."';";
         $result = $this->db->db_query($query);
         while ($row=$this->db->db_fetch_array($result)) $absarray[] = $row['absid'];
         return $absarray;
      }
      
      // ---------------------------------------------------------------------
      /**
       * Deletes a record matching absence and group
       * 
       * @param string $absid Absence ID
       * @param string $group Group short name
       */
      function unassign($absid='', $group='') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `absid`='".$absid." AND `group`=".$group."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all records for an absence type
       * 
       * @param string $absid Absence ID
       */
      function unassignAbs($absid='') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `absid` = '".$absid."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all records for a group
       * 
       * @param string $group Group short name
       */
      function unassignGroup($group = '') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `group`='".$group."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Checks whether an absence is assigned to a group
       * 
       * @param string $absid Absence ID
       * @param string $group Group short name
       */
      function isAssigned($absid, $group) 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `absid`='".$absid."' AND `group`='".$group."'";
         $result = $this->db->db_query($query);
         if ($this->db->db_numrows($result) == 1) $rc = 1;
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a record with the values in the class variables
       * 
       */
      function update() 
      {
         $query = "UPDATE `".$this->table."` SET `absid`='".$this->absid."', `group`='".$this->group."' WHERE `id`='".$this->id."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Updates the absence type of an existing record
       * 
       * @param string $absold Absence ID to change
       * @param string $absnew New absence ID
       */
      function updateAbsence($absold, $absnew) 
      {
         $query = "UPDATE `".$this->table."` SET `absence`='".$absnew."' WHERE `absid`='".$absold."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Updates the group name of an existing record
       * 
       * @param string $groupold Old group name
       * @param string $groupnew New group name
       */
      function updateGroupname($groupold, $groupnew) 
      {
         $query = "UPDATE `".$this->table."` SET `group`='".$groupnew."' WHERE `group`='".$groupold."'";
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
