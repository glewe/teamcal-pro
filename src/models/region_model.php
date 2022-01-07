<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * region_model.php
 * 
 * Contains the class dealing with the regions table
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
if (!class_exists("Region_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the region table
    * @package TeamCalPro
    */
   class Region_model 
   {
      var $db = '';
      var $table = '';

      // Database fields
      var $regionname = '';
      var $description = '';
      var $options = '0';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Region_model() 
      {
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_regions'];
      }

      // ---------------------------------------------------------------------
      /**
       * Creates a new region record from local class variables
       */
      function create() 
      {
         $query = "INSERT INTO `".$this->table."` ";
         $query .= " (`regionname`,`description`,`options`) ";
         $query .= "VALUES ('";
         $query .= addslashes($this->regionname) . "','";
         $query .= addslashes($this->description) . "','";
         $query .= $this->options . "'";
         $query .= ")";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes a region record for a given region name
       * 
       * @param string $gname Region to delete
       */
      function deleteByName($rname = '') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `regionname` = '".addslashes($rname)."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Finds a region record and load values in local class variables
       * 
       * @param string $gname Region to find
       */
      function findByName($rname = 'default') 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE regionname = '".$rname."';";
         $result = $this->db->db_query($query);

         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result);
            $this->regionname = stripslashes($row['regionname']);
            $this->description = stripslashes($row['description']);
            $this->options = $row['options'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records into an array
       * 
       * @return array $absarray Array with all records
       */
      function getAll($order='regionname', $sort='ASC') 
      {
         $rarray = array();
         $query = "SELECT * FROM `".$this->table."` ORDER BY `".$order."` ".$sort.";";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $rarray[] = $row;
         return $rarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all region names into an array
       * 
       * @return array $regionarray Array with all region names
       */
      function getRegions() 
      {
         $regionarray = array();
         $query = "SELECT regionname FROM `".$this->table."`;";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $regionarray[] = stripslashes($row['regionname']);
         return $regionarray;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a region record for a given region name from local class variables
       * 
       * @param string $gname Region to update
       */
      function update($rname) 
      {
         $query = "UPDATE `".$this->table."` ";
         $query .= "SET `regionname` = '" . addslashes($this->regionname) . "', ";
         $query .= "`description` = '" . addslashes($this->description) . "', ";
         $query .= "`options` = '" . $this->options . "' ";
         $query .= " WHERE `regionname` = '" . $rname . "'";
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
