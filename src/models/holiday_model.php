<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * holiday_model.php
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
if (!class_exists("Holiday_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to interface with the holiday table
    * @package TeamCalPro
    */
   class Holiday_model 
   {
      var $db = '';
      var $table = '';

      var $cfgsym = '';
      var $cfgname = '';
      var $dspsym = '';
      var $dspname = '';
      var $dspcolor = '';
      var $dspbgcolor = '';
      var $options = '0';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Holiday_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_holidays'];
      }

      // ---------------------------------------------------------------------
      /**
       * Creates a new holiday record from local class variables
       */
      function create() 
      {
         $query = "INSERT INTO `".$this->table."` ";
         $query .= "(`cfgsym`,`cfgname`,`dspsym`,`dspname`,`dspcolor`,`dspbgcolor`,`options`) ";
         $query .= "VALUES ('";
         $query .= $this->cfgsym . "','";
         $query .= $this->cfgname . "','";
         $query .= $this->dspsym . "','";
         $query .= $this->dspname . "','";
         $query .= $this->dspcolor . "','";
         $query .= $this->dspbgcolor . "','";
         $query .= $this->options . "'";
         $query .= ")";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes a holiday record for a given symbol
       * 
       * @param string $symbol Symbol of record to delete
       */
      function deleteBySymbol($symbol='') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `cfgsym` = '".$symbol."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Finds a holiday record for a given symbol
       * 
       * @param string $symbol Symbol to find
       * @return integer 1 if found, 0 if not found
       */
      function findBySymbol($symbol='') 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `cfgsym` = '".$symbol."';";
         $result = $this->db->db_query($query);

         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result);
            $this->cfgsym = $row['cfgsym'];
            $this->cfgname = $row['cfgname'];
            $this->dspsym = $row['dspsym'];
            $this->dspname = $row['dspname'];
            $this->dspcolor = $row['dspcolor'];
            $this->dspbgcolor = $row['dspbgcolor'];
            $this->options = $row['options'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Finds a holiday record for a given name
       * 
       * @param string $cfgname Name to find
       * @return integer 1 if found, 0 if not found
       */
      function findByName($cfgname='') 
      {
         $rc = 0;
         $query = "SELECT * FROM `".$this->table."` WHERE `cfgname` = '".$cfgname."';";
         $result = $this->db->db_query($query);

         if ($this->db->db_numrows($result) == 1) 
         {
            $row = $this->db->db_fetch_array($result);
            $this->cfgsym = $row['cfgsym'];
            $this->cfgname = $row['cfgname'];
            $this->dspsym = $row['dspsym'];
            $this->dspname = $row['dspname'];
            $this->dspcolor = $row['dspcolor'];
            $this->dspbgcolor = $row['dspbgcolor'];
            $this->options = $row['options'];
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Reads all records into an array
       * 
       * @return array $harray Array with all records
       */
      function getAll($order='cfgsym', $sort='ASC') 
      {
         $harray = array();
         $query = "SELECT * FROM `".$this->table."` ORDER BY `".$order."` ".$sort.";";
         $result = $this->db->db_query($query);
         while ( $row=$this->db->db_fetch_array($result) ) $harray[] = $row;
         return $harray;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a holiday record from local class variables
       */
      function update($symbol) 
      {
         $query = "UPDATE `".$this->table."` ";
         $query .= "SET `cfgsym` = '".$this->cfgsym."', ";
         $query .= "`cfgname`    = '".$this->cfgname."', ";
         $query .= "`dspsym`     = '".$this->dspsym."', ";
         $query .= "`dspname`    = '".$this->dspname."', ";
         $query .= "`dspcolor`   = '".$this->dspcolor."', ";
         $query .= "`dspbgcolor` = '".$this->dspbgcolor."', ";
         $query .= "`options`    = '".$this->options."' ";
         $query .= "WHERE `cfgsym`   = '".$symbol."';";
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
