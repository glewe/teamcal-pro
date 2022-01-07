<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * absence_model.php
 * 
 * Contains the class to interface with the absence type table
 *
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */
if (!class_exists("Absence_model")) 
{
   require_once ("models/db_model.php");
   
   /**
    * Provides objects and methods to interface with the absence type table
    * @package TeamCalPro
    */
   class Absence_model 
   {
      var $db = NULL;
      var $table = '';
      
      var $id = 0;
      var $name = '';
      var $symbol = '';
      var $icon = '';
      var $color = '';
      var $bgcolor = '';
      var $bgtransparent = 0;
      var $factor = 1;
      var $allowance = '0';
      var $counts_as = 0;
      var $show_in_remainder = 1;
      var $show_totals = 1;
      var $approval_required = 0;
      var $counts_as_present = 0;
      var $manager_only = 0;
      var $hide_in_profile = 0;
      var $confidential = 0;
      var $admin_allowance = 0;
      
      //----------------------------------------------------------------------
      /**
       * Constructor
       */ 
      function Absence_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         $this->db = new Db_model;
         $this->table = $CONF['db_table_absence'];
      }

      //----------------------------------------------------------------------
      /**
       * Creates an absence type record
       */ 
      function create() 
      {
         $query = "INSERT INTO `".$this->table."` ";
         $query .= "(
                     `name`,
                     `symbol`,
                     `icon`,
                     `color`,
                     `bgcolor`,
                     `bgtransparent`,
                     `factor`,
                     `allowance`,
                     `counts_as`,
         				`show_in_remainder`,
                     `show_totals`,
                     `approval_required`,
                     `counts_as_present`,
                     `manager_only`,
                     `hide_in_profile`,
                     `confidential`,
                     `admin_allowance`
                     ) ";
         
         $query .= "VALUES (
                   '".$this->name."',
                   '".$this->symbol."',
                   '".$this->icon."',
                   '".$this->color."',
                   '".$this->bgcolor."',
                   '".$this->bgtransparent."',
                   '".$this->factor."',
                   '".$this->allowance."',
                   '".$this->counts_as."',
                   '".$this->show_in_remainder."',
                   '".$this->show_totals."',
                   '".$this->approval_required."',
                   '".$this->counts_as_present."',
                   '".$this->manager_only."',
                   '".$this->hide_in_profile."',
                   '".$this->confidential."',
                   '".$this->admin_allowance."'
                   )";
         
         $result = $this->db->db_query($query);
         return $this->db->db_query("SELECT LAST_INSERT_ID()");
      }

      //----------------------------------------------------------------------
      /**
       * Deletes an absence type record
       * 
       * @param string $absid Record ID
       */ 
      function delete($absid = '') 
      {
         $result=0;
         if (isset($absid)) 
         {
            $query = "DELETE FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
         }
         return $result;
      }

      //----------------------------------------------------------------------
      /**
       * Deletes all absence type records
       */ 
      function deleteAll() 
      {
         $query = "TRUNCATE `".$this->table."`;";
         $result = $this->db->db_query($query);
         return $result;
      }

      //----------------------------------------------------------------------
      /**
       * Gets an absence type record
       * 
       * @param string $absid Record ID
       */ 
      function get($absid='') 
      {
         $rc = 0;
         if (isset($absid)) 
         {
            $query = "SELECT * FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $this->id = $row['id'];
               $this->name = $row['name'];
               $this->symbol = $row['symbol'];
               $this->icon = $row['icon'];
               $this->color = $row['color'];
               $this->bgcolor = $row['bgcolor'];
               $this->bgtransparent = $row['bgtransparent'];
               $this->factor = $row['factor'];
               $this->allowance = $row['allowance'];
               $this->counts_as = $row['counts_as'];
               $this->show_in_remainder = $row['show_in_remainder'];
               $this->show_totals = $row['show_totals'];
               $this->approval_required = $row['approval_required'];
               $this->counts_as_present = $row['counts_as_present'];
               $this->manager_only = $row['manager_only'];
               $this->hide_in_profile = $row['hide_in_profile'];
               $this->confidential = $row['confidential'];
               $this->admin_allowance = $row['admin_allowance'];
               $rc = 1;
            }
         }
         return $rc;
      }

      //----------------------------------------------------------------------
      /**
       * Reads all records into an array
       * 
       * @param string $order Column to sort by
       * @param string $sort Sort direction
       * @return array $absarray Array with all records
       */
      function getAll($order='name', $sort='ASC') 
      {
         $absarray = array();
         $query = "SELECT * FROM `".$this->table."` ORDER BY `".$order."` ".$sort.";";
         $result = $this->db->db_query($query);
         while ($row=$this->db->db_fetch_array($result)) $absarray[] = $row;
         return $absarray;
      }

      //----------------------------------------------------------------------
      /**
       * Reads all absence types counting as the given ID
       * 
       * @param string $order Column to sort by
       * @param string $sort Sort direction
       * @return array $absarray Array with all records
       */
      function getAllSub($absid) 
      {
         $absarray = array();
         $query = "SELECT * FROM `".$this->table."` WHERE counts_as='".$absid."' ORDER BY `name` ASC;";
         $result = $this->db->db_query($query);
         while ($row=$this->db->db_fetch_array($result)) $absarray[] = $row;
         return $absarray;
      }

      //----------------------------------------------------------------------
      /**
       * Gets the factor value of an absence type
       * 
       * @param string $absid Record ID
       * @return string Absence type factor
       */ 
      function getFactor($absid = '') 
      {
         $rc = 1; // default factor is 1
         if (isset($absid)) 
         {
            $query = "SELECT factor FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['factor'];
            }
         }
         return $rc;
      }

      //----------------------------------------------------------------------
      /**
       * Gets the absence ID of the allowance linked absence, or own ID
       * 
       * @param string $absid Record ID
       * @return string Absence ID
       */ 
      function getCountsAs($absid = '') 
      {
         if (isset($absid)) 
         {
            $query = "SELECT counts_as FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['counts_as'];
            }
         }
         if ($rc)
         {
         	//
         	// Means there is a value greater 0 in here => ID of another absence type
         	//
         	return $rc;
         }
         else
         {
         	return FALSE;
         }
      }

      //----------------------------------------------------------------------
      /**
       * Gets the approval required value of an absence type
       *
       * @param string $absid Record ID
       * @return boolean Approval required
       */
      function getApprovalRequired($absid = '') 
      {
         $rc=0; // Default approval required is 0
         if (isset($absid)) 
         {
            $query = "SELECT approval_required FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['approval_required'];
            }
         }
         return $rc;
      }

      //----------------------------------------------------------------------
      /**
       * Gets the manager only flag of an absence type
       *
       * @param string $absid Record ID
       * @return boolean Manager only flag
       */
      function getManagerOnly($absid = '') 
      {
         $rc=0; // Default return 0
         if (isset($absid)) 
         {
            $query = "SELECT manager_only FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['manager_only'];
            }
         }
         return $rc;
      }
      
      //----------------------------------------------------------------------
      /**
       * Gets the admin allowance flag of an absence type
       *
       * @param string $absid Record ID
       * @return boolean Admin allowance flag
       */
      function getAdminAllowance($absid = '') 
      {
         $rc=0; // Default return 0
         if (isset($absid)) 
         {
            $query = "SELECT admin_allowance FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['admin_allowance'];
            }
         }
         return $rc;
      }
      
      //----------------------------------------------------------------------
      /**
       * Gets the background transparent flag of an absence type
       *
       * @param string $absid Record ID
       * @return boolean Background transparent flag
       */
      function getBackgroundTransparent($absid = '') 
      {
         $rc=0; // Default return 0
         if (isset($absid)) 
         {
            $query = "SELECT bgtransparent FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['bgtransparent'];
            }
         }
         return $rc;
      }
      
      //----------------------------------------------------------------------
      /**
       * Gets the name of an absence type
       *
       * @param string $absid Record ID
       * @return string Absence type name
       */
      function getName($absid = '') 
      {
         $rc='unknown';
         if (isset($absid)) 
         {
            $query = "SELECT name FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['name'];
            }
         }
         return $rc;
      }
      
      //----------------------------------------------------------------------
      /**
       * Gets the symbol of an absence type
       *
       * @param string $absid Record ID
       * @return string Absence type symbol
       */
      function getSymbol($absid = '') 
      {
         $rc='.';
         if (isset($absid)) 
         {
            $query = "SELECT symbol FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['symbol'];
            }
         }
         return $rc;
      }
      
      //----------------------------------------------------------------------
      /**
       * Gets the icon of an absence type
       *
       * @param string $absid Record ID
       * @return string Absence type icon
       */
      function getIcon($absid = '') 
      {
         $rc='.';
         if (isset($absid)) 
         {
            $query = "SELECT icon FROM `".$this->table."` WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
            if ($this->db->db_numrows($result) == 1) 
            {
               $row = $this->db->db_fetch_array($result);
               $rc = $row['icon'];
            }
         }
         return $rc;
      }
      
      //----------------------------------------------------------------------
      /**
       * Gets the last auto-increment ID
       * 
       * @return string Last auto-increment ID
       */ 
      function getLastId() 
      {
         $result = mysql_query('SHOW TABLE STATUS LIKE "'.$this->table.'";');
         $row = mysql_fetch_assoc($result);
         return intval($row['Auto_increment'])-1;
      }
            
      //----------------------------------------------------------------------
      /**
       * Gets the next auto-increment ID
       * 
       * @return string Next auto-increment ID
       */ 
      function getNextId() 
      {
         $result = mysql_query('SHOW TABLE STATUS LIKE "'.$this->table.'"');
         $row = mysql_fetch_assoc($result);
         return $row['Auto_increment'];
      }
            
      //----------------------------------------------------------------------
      /**
       * Updates an absence type by it's symbol from the current array data
       * 
       * @param string $absid Record ID
       */ 
      function update($absid='') 
      {
         $result=0;
         if (isset($absid)) 
         {
            $query = "UPDATE `".$this->table."` SET 
                     `name`              = '".$this->name."', 
                     `symbol`            = '".$this->symbol."', 
                     `icon`              = '".$this->icon."', 
                     `color`             = '".$this->color."', 
                     `bgcolor`           = '".$this->bgcolor."', 
                     `bgtransparent`     = '".$this->bgtransparent."', 
                     `factor`            = '".$this->factor."', 
                     `allowance`         = '".$this->allowance."', 
                     `counts_as`         = '".$this->counts_as."', 
                     `show_in_remainder` = '".$this->show_in_remainder."', 
                     `show_totals`       = '".$this->show_totals."', 
                     `approval_required` = '".$this->approval_required."', 
                     `counts_as_present` = '".$this->counts_as_present."', 
                     `manager_only`      = '".$this->manager_only."', 
                     `hide_in_profile`   = '".$this->hide_in_profile."', 
                     `confidential`      = '".$this->confidential."', 
                     `admin_allowance`   = '".$this->admin_allowance."' 
                     WHERE id='".$absid."';";
            $result = $this->db->db_query($query);
         }
         return $result;
      }

      //----------------------------------------------------------------------
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
