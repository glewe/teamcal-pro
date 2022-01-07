<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * month_model.php
 * 
 * Contains the class dealing with the month table
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
if (!class_exists("Month_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to manage the month table
    * @package TeamCalPro
    */
   class Month_model 
   {
      var $db = '';
      var $table = '';
      var $firstweekday = '';
      var $monthno = '';
      var $monthname = '';
      var $nofdays = '';
      var $weekday1 = '';
      var $weekdays = '';
      
      var $yearmonth = '';
      var $template = '';
      var $region = '';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Month_model() {
         unset($CONF);
         require ("config.tcpro.php");
         global $LANG;
         $this->db = new Db_model;
         $this->table = $CONF['db_table_months'];
         $this->weekdays = $CONF['weekdays'];
      }

      // ---------------------------------------------------------------------
      /**
       * Create a month template record from local variables
       */
      function create() 
      {
         $query = "INSERT INTO `".$this->table."` (`yearmonth`,`template`,`region`) ";
         $query .= "VALUES ('";
         $query .= $this->yearmonth . "','";
         $query .= $this->template . "','";
         $query .= $this->region . "'";
         $query .= ")";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes a month template record by name
       * 
       * @param string $mname Name of month to delete (YYYYMM)
       */
      function deleteByName($region='default', $mname='') 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `region` = '".addslashes($region)."' AND `yearmonth` = '".addslashes($mname)."'";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes all records for a given region
       * 
       * @param string $region Region to delete
       */
      function deleteRegion($region) 
      {
         $query = "DELETE FROM `".$this->table."` WHERE `region` = '".$region."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Finds a month template record by name
       * 
       * @param string $mname Name of month to find (YYYYMM)
       * @return integer Result of the MySQL query
       */
      function findByName($region='default', $mname='') 
      {
         $rc = 0;
         // see if the user exists
         $query = "SELECT * FROM `".$this->table."` WHERE `region` = '".$region."' AND `yearmonth` = '".$mname."';";
         $result = $this->db->db_query($query);

         // exactly one row found ( a good thing!)
         if ($this->db->db_numrows($result) == 1)
         {
            $row = $this->db->db_fetch_array($result);
            $this->yearmonth = stripslashes($row['yearmonth']);
            $this->template = stripslashes($row['template']);
            $this->region = stripslashes($row['region']);
            $rc = 1;
         }
         return $rc;
      }

      // ---------------------------------------------------------------------
      /**
       * Updates a month template record by name from local variables
       * 
       * @param string $mname Name of month to update (YYYYMM)
       */
      function update($region='default', $mname) 
      {
         $query  = "UPDATE `".$this->table."` ";
         $query .= "SET `template` = '".$this->template."' ";
         $query .= "WHERE `region` = '".$region."' ";
         $query .= "AND `yearmonth` = '".$mname."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Updates the all records of a given region
       * 
       * @param string $region Region to update
       */
      function updateRegion($region_old, $region_new) 
      {
         $query  = "UPDATE `".$this->table."` ";
         $query .= "SET `region` = '".$region_new."' ";
         $query .= "WHERE `region` = '".$region_old."';";
         $result = $this->db->db_query($query);
      }

      // ---------------------------------------------------------------------
      /**
       * Fills local variables with information about a given year/month:
       * number of days, first weekday, month name, month number
       * 
       * @param string $year Year to check (YYYY)
       * @param string $month Month to check (friendly name e.g. January)
       */
      function getMonthInfo($year, $month) 
      {
         global $LANG;
         /**
          * Create a timestamp for the given year and month (using day 1 of the 
          * month) and use it to get some relevant information using date() and
          * getdate()
          */
         $mytime = $month." 1,".$year;
         $myts = strtotime($mytime);
         /**
          * Get number of days in month
          */
         $this->nofdays = date("t", $myts);
         /**
          * Get first weekday of the month
          */
         $mydate = getdate($myts);
         $this->weekday1 = $mydate['wday'];
         if ($this->weekday1 == "0") $this->weekday1 = "7";

         $this->firstweekday = intval($this->weekday1);
         $this->monthno = sprintf("%02d", intval($mydate['mon']));
         /**
          * Set the friendly name of the month
          */
         $this->monthname = $LANG['monthnames'][intval($this->monthno)] . " " . $year;
      }

      // ---------------------------------------------------------------------
      /**
       * Removes a holiday from all month templates
       *  
       * @param string $region Region to do this for
       * @param string $symbol Holiday symbol to delete
       */
      function removeHoliday($region='default', $symbol) 
      {
         global $CONF;
         
         $query = "SELECT * FROM `".$this->table."` WHERE `region` = '".$region."'";
         $result = $this->db->db_query($query);
         while ($row = $this->db->db_fetch_array($result)) 
         {
            $mymonth = $CONF['monthnames'][intval(substr($row['yearmonth'],4,2))];
            $mytime = $mymonth . " 1," . substr($row['yearmonth'],0,4);
            $myts = strtotime($mytime);
            $mydate = getdate($myts);
            $weekday1 = $mydate['wday'];
            $nofdays = date("t", $myts);
            $dayofweek = intval($weekday1);
            
            // Replace symbol in this record
            for ($i = 1; $i <= $nofdays; $i++) 
            {
               if ( $row['template'][$i-1] == $symbol ) 
               {
                  if ( $dayofweek==6 || $dayofweek==7 ) $row['template'][$i-1] = 1;
                  else $row['template'][$i-1] = 0;   
               }
               $dayofweek += 1;
               if ($dayofweek == 8) $dayofweek = 1;
            }
            // Now update the record
            $qry  = "UPDATE `" . $this->table . "` ";
            $qry .= "SET `template` = '" . $row['template'] . "' ";
            $qry .= "WHERE `region` = '" . $row['region'] . "' ";
            $qry .= "AND `yearmonth` = '" . $row['yearmonth'] . "'";
            $res = $this->db->db_query($qry);
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Updates the weekends in all month templates based on the config setting
       * whether Sat and/or Sun counts as a business day. If business day, the
       * symbol will be set to '0' so that business day coloring is applied in
       * the calendar displays.
       */
      function updateWeekends() 
      {
         global $C, $CONF;
         
         $query = "SELECT * FROM `".$this->table."`";
         $result = $this->db->db_query($query);
         
         while ($row = $this->db->db_fetch_array($result)) 
         {
            $mymonth = $CONF['monthnames'][intval(substr($row['yearmonth'],4,2))];
            $mytime = $mymonth . " 1," . substr($row['yearmonth'],0,4);
            $myts = strtotime($mytime);
            $mydate = getdate($myts);
            $nofdays = date("t", $myts);
            $weekday1 = $mydate['wday'];
            if ($weekday1 == "0") $weekday1 = "7";
            $dayofweek = intval($weekday1);
            
            /**
             * Loop through all days of this month template
             */
            for ($i = 1; $i <= $nofdays; $i++) 
            {
               switch ($dayofweek) 
               {
                  case 6 : 
                  /**
                   * Saturday
                   * Only change if not set as a custom holiday
                   */
                  if ( $row['template'][$i-1] == 0 OR $row['template'][$i-1] == 1 )
                  {
                     if ($C->readConfig("satBusi")) $template .= $row['template'][$i-1] = 0; else $template .= $row['template'][$i-1] = 1;
                  }
                  break;
                  
                  case 7 :
                  /**
                   * Sunday
                   * Only change if not set as a custom holiday
                   */
                  if ( $row['template'][$i-1] == 0 OR $row['template'][$i-1] == 1 )
                  {
                     if ($C->readConfig("sunBusi")) $template .= $row['template'][$i-1] = 0; else $template .= $row['template'][$i-1] = 1;
                  }
                  break;
               }
               $dayofweek += 1;
               if ($dayofweek == 8) $dayofweek = 1;
            }
            // Now update the record
            $qry  = "UPDATE `" . $this->table . "` ";
            $qry .= "SET `template` = '" . $row['template'] . "' ";
            $qry .= "WHERE `region` = '" . $row['region'] . "' ";
            $qry .= "AND `yearmonth` = '" . $row['yearmonth'] . "'";
            $res = $this->db->db_query($qry);
         }
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
