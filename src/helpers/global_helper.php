<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * global_helper.php
 *
 * Collection of global functions for TeamCal Pro
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

// ---------------------------------------------------------------------------
/**
 * Archives a user and all related records
 *
 * @param string  $username     Username to archive
 */
function archiveUser($username) 
{
   global $CONF;
   
   require_once($CONF['app_root']."models/allowance_model.php" );
   require_once($CONF['app_root']."models/daynote_model.php" );
   require_once($CONF['app_root']."models/log_model.php" );
   require_once($CONF['app_root']."models/login_model.php" );
   require_once($CONF['app_root']."models/template_model.php" );
   require_once($CONF['app_root']."models/user_model.php" );
   require_once($CONF['app_root']."models/user_announcement_model.php" );
   require_once($CONF['app_root']."models/user_group_model.php" );
   require_once($CONF['app_root']."models/user_option_model.php" );
   
   $A = new Allowance_model;
   $L = new Login_model;
   $LOG = new Log_model;
   $N  = new Daynote_model;
   $T  = new Template_model;
   $U  = new User_model;
   $UA = new User_announcement_model;
   $UG = new User_group_model;
   $UO = new User_option_model;
       
   /**
    * Do not archive if username exists in any of the archive table
    */
   if ($U->exists($username, TRUE)) return FALSE;
   if ($UG->exists($username, TRUE)) return FALSE;
   if ($UO->exists($username, TRUE)) return FALSE;
   if ($T->exists($username, TRUE)) return FALSE;
   if ($N->exists($username, TRUE)) return FALSE;
   if ($A->exists($username, TRUE)) return FALSE;
   if ($UA->exists($username, TRUE)) return FALSE;
   
   /**
    * Get fullname for log
    */
   $U->findByName($username);
   $fullname = trim($U->firstname." ".$U->lastname);
         
   /**
    * Archive user
    * Archive memberships
    * Archive options
    * Archive templates
    * Archive daynotes
    * Archive allowances
    * Archive announcements
    */
   $U->archive($username);
   $UG->archive($username);
   $UO->archive($username);
   $T->archive($username);
   $N->archive($username);
   $A->archive($username);
   $UA->archive($username);

   /**
    * Delete user from active tables
    */
   deleteUser($username);
     
   /**
    * Log this event
    */
   $LOG->log("logUser",$L->checkLogin(),"log_user_archived", $fullname." (".$username.")");
    
   return true;
}

// ---------------------------------------------------------------------------
/**
 * Builds the menu based on permissions
 *
 * @return array menu
 */
function buildMenu() {

   global $CONF;

   require_once ($CONF['app_root'] . "models/config_model.php");
   require_once ($CONF['app_root'] . "models/login_model.php");
   require_once ($CONF['app_root'] . "models/user_model.php");
   require_once ($CONF['app_root'] . "models/user_group_model.php");
   require_once ($CONF['app_root'] . "models/user_option_model.php");

   $C = new Config_model;
   $L = new Login_model;
   $U = new User_model; // represents the user the operation is for
   $UL = new User_model; // represents the logged in user who wants to perform the operation
   $UG = new User_group_model;
   $UO = new User_option_model;

   /**
    * Create empty menu
    */
    $mnu = array(
      "mnu_teamcal"=>TRUE,
      "mnu_teamcal_login"=>TRUE,
      "mnu_teamcal_logout"=>FALSE,
      "mnu_teamcal_register"=>FALSE,
      "mnu_view"=>TRUE,
      "mnu_view_homepage"=>TRUE,
      "mnu_view_calendar"=>FALSE,
      "mnu_view_yearcalendar"=>FALSE,
      "mnu_view_announcement"=>FALSE,
      "mnu_view_statistics"=>FALSE,
      "mnu_view_statistics_g"=>FALSE,
      "mnu_view_statistics_r"=>FALSE,
      "mnu_tools"=>FALSE,
      "mnu_tools_profile"=>FALSE,
      "mnu_tools_message"=>FALSE,
      "mnu_tools_webmeasure"=>FALSE,
      "mnu_tools_admin"=>FALSE,
      "mnu_tools_admin_config"=>FALSE,
      "mnu_tools_admin_perm"=>FALSE,
      "mnu_tools_admin_users"=>FALSE,
      "mnu_tools_admin_groups"=>FALSE,
      "mnu_tools_admin_usergroups"=>FALSE,
      "mnu_tools_admin_absences"=>FALSE,
      "mnu_tools_admin_regions"=>FALSE,
      "mnu_tools_admin_holidays"=>FALSE,
      "mnu_tools_admin_declination"=>FALSE,
      "mnu_tools_admin_database"=>FALSE,
      "mnu_tools_admin_systemlog"=>FALSE,
      "mnu_tools_admin_env"=>FALSE,
      "mnu_tools_admin_phpinfo"=>FALSE,
      "mnu_help"=>TRUE,
      "mnu_help_legend"=>FALSE,
      "mnu_help_help"=>TRUE,
      "mnu_help_help_manualbrowser"=>TRUE,
      "mnu_help_help_manualpdf"=>TRUE,
      "mnu_help_about"=>TRUE,
   );

   /**
    * Now enable entries based on permission
    */
   if ($user=$L->checkLogin()) {
      $UL->findByName($user);
      if ($UL->checkUserType($CONF['UTADMIN'])) $mnu['mnu_teamcal_register']=TRUE;
      $mnu['mnu_teamcal_login']=FALSE;
      $mnu['mnu_teamcal_logout']=TRUE;
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_profile']=TRUE;
   }
   else {
      $mnu['mnu_teamcal_login']=TRUE;
      $mnu['mnu_teamcal_logout']=FALSE;
      if ($C->readConfig("allowRegistration")) $mnu['mnu_teamcal_register']=TRUE;
   }

   if (isAllowed("viewCalendar")) {
      $mnu['mnu_view_calendar']=TRUE;
      $mnu['mnu_help_legend']=TRUE;
   }

   if (isAllowed("viewYearCalendar")) {
      $mnu['mnu_view_yearcalendar']=TRUE;
      $mnu['mnu_help_legend']=TRUE;
   }

   if (isAllowed("viewAnnouncements")) $mnu['mnu_view_announcement']=TRUE;

   if (isAllowed("viewStatistics")) {
      $mnu['mnu_view_statistics']=TRUE;
      $mnu['mnu_view_statistics_g']=TRUE;
      $mnu['mnu_view_statistics_r']=TRUE;
   }

   if (isAllowed("useMessageCenter")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_message']=TRUE;
   }

   if (isAllowed("editConfig")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_config']=TRUE;
   }

   if (isAllowed("editPermissionScheme")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_perm']=TRUE;
   }

   if (isAllowed("manageUsers")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_users']=TRUE;
   }

   if (isAllowed("manageGroups")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_groups']=TRUE;
   }

   if (isAllowed("manageGroupMemberships")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_usergroups']=TRUE;
   }

   if (isAllowed("editAbsenceTypes")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_absences']=TRUE;
   }

   if (isAllowed("editRegions")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_regions']=TRUE;
   }

   if (isAllowed("editHolidays")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_holidays']=TRUE;
   }

   if (isAllowed("editDeclination")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_declination']=TRUE;
   }

   if (isAllowed("manageDatabase")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_database']=TRUE;
   }

   if (isAllowed("viewSystemLog")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_systemlog']=TRUE;
   }

   if (isAllowed("viewEnvironment")) {
      $mnu['mnu_tools']=TRUE;
      $mnu['mnu_tools_admin']=TRUE;
      $mnu['mnu_tools_admin_env']=TRUE;
      $mnu['mnu_tools_admin_phpinfo']=TRUE;
   }

   if ($mnu['mnu_tools'] AND $C->readConfig("webMeasure")) {
      $mnu['mnu_tools_webmeasure']=TRUE;
   }

   return $mnu;
}

// ---------------------------------------------------------------------------
/**
 * Unsets a bit combination in a given bitmask
 *
 * @param   integer $flagset     Target to change
 * @param   integer $bitmask     Bitmask to unset (0's in this bitmask will
 *                               become 0's in the target)
 * @return  integer              New target
 */
function clearFlag($flagset, $bitmask) {
   $newflagset = $flagset & (~$bitmask);
   return $newflagset;
}

// ---------------------------------------------------------------------------
/**
 * Counts all occurences of a given absence type for a given user in a given
 * time period
 *
 * @param   string $user       User to count for
 * @param   string $absid      Absence type ID to count
 * @param   string $from       Date to count from (including)
 * @param   string $to         Date to count to (including)
 * @param   boolean $useFactor Multiply count by factor
 * @param   boolean $combined  Count other absences that count as this one
 * @return  integer            Result of the count
 */
function countAbsence($user='%', $absid, $from, $to, $useFactor=FALSE, $combined=FALSE) 
{
   global $CONF;
   require_once ($CONF['app_root']."models/absence_model.php");
   require_once ($CONF['app_root'] . "models/template_model.php");

   $A = new Absence_model;
   $T = new Template_model;

   // 
   // Figure out starting month and ending month
   //
   $startyear = intval(substr($from, 0, 4));
   $startmonth = intval(substr($from, 4, 2));
   $startday = intval(substr($from, 6, 2));
   $endyear = intval(substr($to, 0, 4));
   $endmonth = intval(substr($to, 4, 2));
   $endday = intval(substr($to, 6, 2));

   // 
   // Get the count for this absence type
   //
   $factor = $A->getFactor($absid);
   $count = 0;
   $firstday = $startday;
   if ($firstday < 1 || $firstday > 31) $firstday = 1;

   $year = $startyear;
   $month = $startmonth;
   $ymstart = intval($year.sprintf("%02d",$month));
   $ymend= intval($endyear.sprintf("%02d",$endmonth));

   // 
   // Loop through every month of the requested period
   //
   while ($ymstart<=$ymend) 
   {
      if ($year==$startyear AND $month==$startmonth) 
      {
         $lastday = 0;
         if ($startmonth == $endmonth) 
         {
            // 
            // We only have one month. Make sure to only count until the requested end day.
            //
            $lastday = $endday;
         }
         $count+=$T->countAbsence($user,$year,$month,$absid,$startday,$lastday);
      }
      else if ($year==$endyear AND $month==$endmonth) 
      {
         $count+=$T->countAbsence($user,$year,$month,$absid,1,$endday);
      }
      else 
      {
         $count+=$T->countAbsence($user,$year,$month,$absid);
      }
      
      if ($month==12) 
      {
         $year++;
         $month = 1;
      }
      else 
      {
         $month++;
      }
      $ymstart = intval($year.sprintf("%02d",$month));
   }
   
   if ($useFactor) $count*=$factor;
    
   //
   // If requested, count all those absence types that count as this one
   //
   $otherTotal = 0;
   if ($combined)
   {
      $otherAbsences = $A->getAll();
      foreach ($otherAbsences as $otherAbs) 
      {
         if ($otherId=$otherAbs['counts_as'] AND $otherId==$absid) 
         {
            $otherCount = 0;
            $otherFactor = $otherAbs['factor'];
            $year = $startyear;
            $month = $startmonth;
            $ymstart = intval($year.sprintf("%02d",$month));
            $ymend= intval($endyear.sprintf("%02d",$endmonth));
            while ($ymstart<=$ymend) 
            {
               if ($year==$startyear AND $month==$startmonth) 
               {
                  $otherCount+=$T->countAbsence($user,$year,$month,$otherAbs['id'],$startday);
               }
               else if ($year==$endyear AND $month==$endmonth) 
               {
                  $otherCount+=$T->countAbsence($user,$year,$month,$otherAbs['id'],1,$endday);
               }
               else 
               {
                  $otherCount+=$T->countAbsence($user,$year,$month,$otherAbs['id']);
               }
               
               if ($month==12) 
               {
                  $year++;
                  $month = 1;
               }
               else 
               {
                  $month++;
               }
               $ymstart = intval($year.sprintf("%02d",$month));
            }
            
            //
            // A combined count always uses the factor. Doesn't make sense otherwise.
            //
            $otherTotal += $otherCount * $otherFactor;
         }
      }
   }
    
   $count += $otherTotal;
   return $count;
}

// ---------------------------------------------------------------------------
/**
 * Counts all business days or man days in a given time period
 *
 * @param string $cntfrom Date to count from (including)
 * @param string $cntto Date to count to (including)
 * @param string $region Region to count for
 * @param boolean $cntManDays Switch whether to multiply the business days by the amount of users and return that value instead
 * @return boolean True if reached, false if not
 */
function countBusinessDays($cntfrom, $cntto, $region = 'default', $cntManDays = 0) 
{
   global $CONF;
   require_once ($CONF['app_root'] . "models/holiday_model.php");
   require_once ($CONF['app_root'] . "models/month_model.php");
   require_once ($CONF['app_root'] . "models/user_model.php");

   $H = new Holiday_model;
   $M = new Month_model;
   $U = new User_model;

   // Figure out starting month and ending month
   $startyearmonth = intval(substr($cntfrom, 0, 6));
   $startday = intval(substr($cntfrom, 6, 2));
   $endyearmonth = intval(substr($cntto, 0, 6));
   $endday = intval(substr($cntto, 6, 2));

   // Now count
   $count = 0;
   $yearmonth = $startyearmonth;
   $firstday = $startday;
   if ($firstday < 1 OR $firstday > 31) $firstday = 1;

   while ($yearmonth <= $endyearmonth) 
   {
      $queryM = "SELECT * FROM `".$M->table."` WHERE `yearmonth`='".$yearmonth."' AND region='".$region."';";
      $resultM = $M->db->db_query($queryM);
      while ($rowM = $M->db->db_fetch_array($resultM, MYSQL_ASSOC)) 
      {
         if ($yearmonth == $endyearmonth) 
         {
            // This is the last template. Make sure we just read it up to the specified endday.
            if ($endday < strlen($rowM['template']))
               $lastday = $endday;
            else
               $lastday = strlen($rowM['template']);
         }
         else 
         {
            $lastday = strlen($rowM['template']);
         }
         
         for ($i = $firstday-1; $i < $lastday; $i++) 
         {
            $H->findBySymbol($rowM['template'][$i]);
            if ($H->checkOptions($CONF['H_BUSINESSDAY'])) 
            {
               /*
                * This daytype counts as a business day
                */
               $count++;
            }
         }
      }
      
      if (intval(substr($yearmonth, 4, 2)) == 12) 
      {
         $year = intval(substr($yearmonth, 0, 4));
         $year++;
         $yearmonth = strval($year) . "01";
      }
      else 
      {
         $year = intval(substr($yearmonth, 0, 4));
         $month = intval(substr($yearmonth, 4, 2));
         $month++;
         $yearmonth = strval($year) . sprintf("%02d",strval($month));
      }
      $firstday = 1;
   }
    
   if ($cntManDays) 
   {
      /*
       * Now we know the remaining amount of business days left in this period.
       * In order to get the remaining man days we need to multiply that amount
       * with all user in the calendar (not the admin and not those who are hidden
       * from the calendar).
       */
      $queryU = "SELECT * FROM `" . $U->table . "` WHERE `username`!='admin';";
      $resultU = $U->db->db_query($queryU);
      $usercount = 0;
      while ($rowU = $U->db->db_fetch_array($resultU, MYSQL_ASSOC)) 
      {
         if (!$U->checkStatus($CONF['USHIDDEN']))
            $usercount++;
      }
      return $count * $usercount;
   }
   else 
   {
      return $count;
   }
}

// ---------------------------------------------------------------------------
/**
 * Reads the current theme default css (default.css) file and adds/replaces
 * the holiday and absence based styles in the database.
 *
 * @param   string $theme  Name of the TeamCal Pro theme to process
 *
 */
function createCSS($theme) {
   global $CONF;
   require_once ($CONF['app_root']."models/css_model.php");
   require_once ($CONF['app_root']."models/absence_model.php");
   require_once ($CONF['app_root']."models/config_model.php");
   require_once ($CONF['app_root']."models/holiday_model.php");
   require_once ($CONF['app_root']."models/styles_model.php");

   $A   = new Absence_model;
   $H   = new Holiday_model;
   $CSS = new Css_model;
   $C   = new Config_model;
   $S   = new Styles_model;

   /**
    * Read the theme css file into the CSS array
    */
   $CSS->parseFile("themes/".$theme."/css/default.css");
   $CSS->setKey(".noscreen","display: none;");

   $toBorderWidth = $C->readConfig("todayBorderSize");
   $toBorderColor = $C->readConfig("todayBorderColor");
   $daytypes = array('','-note','-bday','-bdaynote');
   $otherdaytypes = array('-sum-present', '-sum-absent', '-sum-delta-negative', '-sum-delta-positive', '-day-absent');
    
   /**
    * Create the today based styles in the array
    */
   $readkey=$CSS->getKeyProperties("td.daynum");
   $CSS->setKey("td.todaynum"," ".$readkey);
   $CSS->setProperty("td.todaynum","border-right",$toBorderWidth."px solid #".$toBorderColor);
   $CSS->setProperty("td.todaynum","border-left",$toBorderWidth."px solid #".$toBorderColor);

   foreach($daytypes as $dtype){
      $readkey=$CSS->getKeyProperties("td.weekday".$dtype);
      $CSS->setKey("td.toweekday".$dtype," ".$readkey);
      $CSS->setProperty("td.toweekday".$dtype,"border-right",$toBorderWidth."px solid #".$toBorderColor);
      $CSS->setProperty("td.toweekday".$dtype,"border-left",$toBorderWidth."px solid #".$toBorderColor);
      
      $readkey=$CSS->getKeyProperties("td.day".$dtype);
      $CSS->setKey("td.today".$dtype," ".$readkey);
      $CSS->setProperty("td.today".$dtype,"border-right",$toBorderWidth."px solid #".$toBorderColor);
      $CSS->setProperty("td.today".$dtype,"border-left",$toBorderWidth."px solid #".$toBorderColor);
   }

   foreach($otherdaytypes as $odtype) {
      $readkey=$CSS->getKeyProperties("td.day".$odtype);
      $CSS->setKey("td.today".$odtype," ".$readkey);
      $CSS->setProperty("td.today".$odtype,"border-right",$toBorderWidth."px solid #".$toBorderColor);
      $CSS->setProperty("td.today".$odtype,"border-left",$toBorderWidth."px solid #".$toBorderColor);
   }

   $readkey=$CSS->getKeyProperties("td.legend");
   $CSS->setKey("td.legend-today"," ".$readkey);
   $CSS->setProperty("td.legend-today","border-right",$toBorderWidth."px solid #".$toBorderColor);
   $CSS->setProperty("td.legend-today","border-left",$toBorderWidth."px solid #".$toBorderColor);

   /**
    * Add/replace/change the holiday based styles in the array
    */
   $holidays = $H->getAll();
   foreach ($holidays as $hol) {

      $readkey=$CSS->getKeyProperties("td.daynum");
      $CSS->setKey("td.daynum-".$hol['cfgname']," ".$readkey);
      $CSS->setProperty("td.daynum-".$hol['cfgname'],"background-color","#".$hol['dspbgcolor']);
      $CSS->setProperty("td.daynum-".$hol['cfgname'],"color","#".$hol['dspcolor']);

      $CSS->setKey("td.todaynum-".$hol['cfgname']," ".$readkey);
      $CSS->setProperty("td.todaynum-".$hol['cfgname'],"color","#".$hol['dspcolor']);
      $CSS->setProperty("td.todaynum-".$hol['cfgname'],"background-color","#".$hol['dspbgcolor']);
      $CSS->setProperty("td.todaynum-".$hol['cfgname'],"border-right",$toBorderWidth."px solid #".$toBorderColor);
      $CSS->setProperty("td.todaynum-".$hol['cfgname'],"border-left",$toBorderWidth."px solid #".$toBorderColor);

      foreach($daytypes as $dtype){
         $readkey=$CSS->getKeyProperties("td.weekday".$dtype);
         $CSS->setKey("td.weekday-".$hol['cfgname'].$dtype," ".$readkey);
         $CSS->setProperty("td.weekday-".$hol['cfgname'].$dtype,"background-color","#".$hol['dspbgcolor']);
         $CSS->setProperty("td.weekday-".$hol['cfgname'].$dtype,"color","#".$hol['dspcolor']);

         $CSS->setKey("td.toweekday-".$hol['cfgname'].$dtype," ".$readkey);
         $CSS->setProperty("td.toweekday-".$hol['cfgname'].$dtype,"background-color","#".$hol['dspbgcolor']);
         $CSS->setProperty("td.toweekday-".$hol['cfgname'].$dtype,"color","#".$hol['dspcolor']);
         $CSS->setProperty("td.toweekday-".$hol['cfgname'].$dtype,"border-right",$toBorderWidth."px solid #".$toBorderColor);
         $CSS->setProperty("td.toweekday-".$hol['cfgname'].$dtype,"border-left",$toBorderWidth."px solid #".$toBorderColor);

         $readkey=$CSS->getKeyProperties("td.day".$dtype);
         $CSS->setKey("td.day-".$hol['cfgname'].$dtype," ".$readkey);
         $CSS->setProperty("td.day-".$hol['cfgname'].$dtype,"background-color","#".$hol['dspbgcolor']);
         $CSS->setProperty("td.day-".$hol['cfgname'].$dtype,"color","#".$hol['dspcolor']);
      
         $CSS->setKey("td.today-".$hol['cfgname'].$dtype," ".$readkey);
         $CSS->setProperty("td.today-".$hol['cfgname'].$dtype,"background-color","#".$hol['dspbgcolor']);
         $CSS->setProperty("td.today-".$hol['cfgname'].$dtype,"color","#".$hol['dspcolor']);
         $CSS->setProperty("td.today-".$hol['cfgname'].$dtype,"border-right",$toBorderWidth."px solid #".$toBorderColor);
         $CSS->setProperty("td.today-".$hol['cfgname'].$dtype,"border-left",$toBorderWidth."px solid #".$toBorderColor);
      }

      foreach($otherdaytypes as $odtype){
         $readkey=$CSS->getKeyProperties("td.day".$odtype);
         $CSS->setKey("td.day-".$hol['cfgname'].$odtype," ".$readkey);
         $CSS->setProperty("td.day-".$hol['cfgname'].$odtype,"background-color","#".$hol['dspbgcolor']);
         $CSS->setProperty("td.day-".$hol['cfgname'].$odtype,"color","#".$hol['dspcolor']);
         
         $CSS->setKey("td.today-".$hol['cfgname'].$odtype," ".$readkey);
         $CSS->setProperty("td.today-".$hol['cfgname'].$odtype,"background-color","#".$hol['dspbgcolor']);
         $CSS->setProperty("td.today-".$hol['cfgname'].$odtype,"color","#".$hol['dspcolor']);
         $CSS->setProperty("td.today-".$hol['cfgname'].$odtype,"border-right",$toBorderWidth."px solid #".$toBorderColor);
         $CSS->setProperty("td.today-".$hol['cfgname'].$odtype,"border-left",$toBorderWidth."px solid #".$toBorderColor);
      }
   }

   /**
    * Add/replace/change absence based styles in the array
    */
   $absences = $A->getAll();
   foreach ($absences as $abs) {
      foreach($daytypes as $daytype){
         $readkey=$CSS->getKeyProperties("td.day".$daytype);
         $CSS->setKey("td.day".$daytype."-a".$abs['id']," ".$readkey);
         $CSS->setProperty("td.day".$daytype."-a".$abs['id'],"background-color","#".$abs['bgcolor']);
         $CSS->setProperty("td.day".$daytype."-a".$abs['id'],"color","#".$abs['color']);
         $CSS->setKey("td.today".$daytype."-a".$abs['id']," ".$readkey);
         $CSS->setProperty("td.today".$daytype."-a".$abs['id'],"background-color","#".$abs['bgcolor']);
         $CSS->setProperty("td.today".$daytype."-a".$abs['id'],"color","#".$abs['color']);
         $CSS->setProperty("td.today".$daytype."-a".$abs['id'],"border-left",$toBorderWidth."px solid #".$toBorderColor);
         $CSS->setProperty("td.today".$daytype."-a".$abs['id'],"border-right",$toBorderWidth."px solid #".$toBorderColor);
      }
   }

   /**
    * Put the whole thing together
    */
   $buffer  = "/**\r\n";
   $buffer .= " * Stylesheet created by TeamCalPro at: ".date('Y-m-d H:i:s')."\r\n";
   $buffer .= " */\r\n";
   $buffer .= $CSS->printCSS();

   /**
    * Save sylesheet to database
    */
   $buffer=str_replace("url(../img/", "url(themes/".$theme."/img/", $buffer);
   $S->saveStyle($theme,$buffer);

   /**
    * Adjust and write the new CSS file for print output
    */
   $CSS->setKey(".noprint","display: none;");
   $CSS->setKey(".noscreen","display: block;");
   $CSS->setProperty("body","size","landscape");
   $CSS->setProperty("body","background-color","#FFFFFF");
   $CSS->setProperty("body","color","#000000");
   $CSS->setProperty("table.header","display","none");
   $CSS->setProperty("table.menubar","display","none");
   $CSS->setProperty("table.menu","display","none");
   $CSS->setProperty("table.statusbar","display","none");
   $buffer  = "/**\r\n";
   $buffer .= " * Stylesheet created by TeamCalPro at: ".date('Y-m-d H:i:s')."\r\n";
   $buffer .= " */\r\n";
   $buffer .= $CSS->printCSS();

   /**
    * Save sylesheet to database
    */
   $buffer=str_replace("url(../img/", "url(themes/".$theme."/img/", $buffer);
   $S->saveStyle($theme."_print",$buffer);

}

// ---------------------------------------------------------------------------
/**
 * Creates an empty month template marking Saturdays and Sundays as weekend
 *
 * @param   string $yr  Four character string representing the year
 * @param   string $mt  Two character string representing the month
 *
 * @return  string      The template string of the month
 */
function createMonthTemplate($yr, $mt) {
   global $CONF;
   global $LANG;

   require_once ($CONF['app_root'] . "models/holiday_model.php");

   $C = new Config_model;
   $H = new Holiday_model;
   $H->findByName('busi');
   $busisym = $H->cfgsym;
   $H->findByName('wend');
   $wendsym = $H->cfgsym;

   /*
    * Create a timestamp for the given year and month (using day 1 of the
    * month) and use it to get some relevant information using date() and
    * getdate()
    */
   $mytime = $mt." 1,".$yr;
   $myts = strtotime($mytime);
   // Get number of days in month
   $nofdays = date("t", $myts);
   // Get first weekday of the month
   $mydate = getdate($myts);
   $monthno = sprintf("%02d", intval($mydate['mon']));
   $weekday1 = $mydate['wday'];
   if ($weekday1 == "0") $weekday1 = "7";
   $dayofweek = intval($weekday1);

   $template = "";
   for ($i = 1; $i <= $nofdays; $i++) {
      switch ($dayofweek) {
         case 1 : // Monday
            $template .= $busisym;
            break;
         case 2 : // Tuesday
            $template .= $busisym;
            break;
         case 3 : // Wednesday
            $template .= $busisym;
            break;
         case 4 : // Thursday
            $template .= $busisym;
            break;
         case 5 : // Friday
            $template .= $busisym;
            break;
         case 6 : // Saturday
            if ($C->readConfig("satBusi")) $template .= $busisym; else $template .= $wendsym;
            break;
         case 7 : // Sunday
            if ($C->readConfig("sunBusi")) $template .= $busisym; else $template .= $wendsym;
            break;
         default :
            $template .= $busisym;
            break;
      }
      $dayofweek += 1;
      if ($dayofweek == 8) {
         $dayofweek = 1;
      }
   }
   // Return the template
   return $template;
}

// ---------------------------------------------------------------------------
/**
 * Creates <div> tag with the user popup message for jQuery.tipsy
 *
 * @param array $Usr Instance of user_model
 *
 * @return string Div with popup HTML
 */
function createPopup($id, $body, $caption='', $capicon='') {
   global $LANG;
   
   $div  = '<div id="popup-'.$id.'" style="display: none;">';
   
   if (strlen($caption)) {
      $div .= '<div class="tt-caption">';
      if (strlen($capicon)) {
         $div .= '<img src="'.$capicon.'" style="padding-right: 6px; vertical-align: middle;" alt="icon">';
      }
      $div .= $caption.'</div>';
   }
   $div .= '<div class="tt-body">'.$body.'</div>';
   
   $div .= "
      <script type='text/javascript'>
      $(function() {
         $('#".$id."').tipsy({
            delayIn: 0,                                            // delay before showing tooltip (ms)
            delayOut: 0,                                           // delay before hiding tooltip (ms)
            fade: true,                                            // fade tooltips in/out?
            fallback: '',                                          // fallback text to use when no tooltip text
            gravity: 'nw',                                         // gravity
            html: true,                                            // is tooltip content HTML?
            live: false,                                           // use live event support?
            offset: 10,                                            // pixel offset of tooltip from element
            opacity: 1.0,                                          // opacity of tooltip
            title:function(){return $('#popup-".$id."').html();},  // attribute/callback containing tooltip text
            trigger: 'hover'                                       // how tooltip is triggered - hover|focus|manual
         });
      });
      </script>
   ";
   
   $div .= '</div>';
   return $div;
}

// ---------------------------------------------------------------------------
/**
 * Checks wether the maximum absences threshold is reached
 *
 * @param   string $year   Year of the day to count for
 * @param   string $month  Month of the day to count for
 * @param   string $day    Day to count for
 * @param   string $base   Threshold base: user or group
 * @param   string $group  Group to refer to in case of base=group
 * @return  boolean        True if reached, false if not
 */
function declineThresholdReached($year, $month, $day, $base, $group = '') 
{
   global $CONF;
   require_once ($CONF['app_root'] . "models/config_model.php");
   require_once ($CONF['app_root'] . "models/group_model.php");
   require_once ($CONF['app_root'] . "models/template_model.php");
   require_once ($CONF['app_root'] . "models/user_model.php");
   require_once ($CONF['app_root'] . "models/user_group_model.php");

   $C = new Config_model;
   $G = new Group_model;
   $T = new Template_model;
   $U = new User_model;
   $UG = new User_group_model;

   if ($base=="group") 
   {
      /*
       * Count group members
       */
      $query = "SELECT * FROM " . $UG->table . " WHERE " . $UG->table . ".groupname='" . $group . "'";
      $result = $UG->db->db_query($query);
      $users = $UG->db->db_numrows($result);

      /*
       *  Count all group absences for this day
       */
      $query = "SELECT ".$T->table.".* FROM ".$T->table.",".$UG->table." " .
      "WHERE (".$T->table.".year='".$year."' AND ".$T->table.".month='".sprintf("%02d",$month)."') " .
      "AND (".$T->table.".username=".$UG->table.".username AND ".$UG->table.".groupname='".$group."');";
      $result = $T->db->db_query($query);
      $absences = 0;
      while ($row = $T->db->db_fetch_array($result, MYSQL_ASSOC)) 
      {
         $prop='abs'.$day;
         if ($row[$prop] != 0) $absences++;
      }
   }
   else if ($base=="min_present") 
   {
      /*
       * Count group members
       */
      $query = "SELECT * FROM " . $UG->table . " WHERE " . $UG->table . ".groupname='" . $group . "'";
      $result = $UG->db->db_query($query);
      $users = $UG->db->db_numrows($result);

      /*
       *  Count all group absences for this day
       */
      $query = "SELECT ".$T->table.".* FROM ".$T->table.",".$UG->table." " .
      "WHERE (".$T->table.".year='".$year."' AND ".$T->table.".month='".sprintf("%02d", $month)."') " .
      "AND   (".$T->table.".username=".$UG->table.".username AND ".$UG->table.".groupname='".$group."');";
      $result = $T->db->db_query($query);
      $absences = 0;
      while ($row = $T->db->db_fetch_array($result, MYSQL_ASSOC)) 
      {
         $prop='abs'.$day;
         if ($row[$prop] != 0) $absences++;
      }

      $G->findByName($group);
      if ($users-$absences < $G->min_present) return true; else return false;
   }
   else if ($base=="max_absent") 
   {
      /*
       *  Count all group absences for this day
       */
      $query = "SELECT ".$T->table.".* FROM ".$T->table.",".$UG->table." " .
      "WHERE (".$T->table.".year='".$year."' AND ".$T->table.".month='".sprintf("%02d", $month)."') " .
      "AND (".$T->table.".username=".$UG->table.".username AND ".$UG->table.".groupname='".$group."');";
      $result = $T->db->db_query($query);
      $absences = 0;
      while ($row = $T->db->db_fetch_array($result, MYSQL_ASSOC)) 
      {
         $prop='abs'.$day;
         if ($row[$prop] != 0) $absences++;
      }

      $G->findByName($group);
      if ($absences+1 > $G->max_absent) return true; else return false;
   }
   else 
   {
      /*
       * Count all members
       */
      $query = "SELECT * FROM ".$U->table.";";
      $result = $U->db->db_query($query);
      $users = $U->db->db_numrows($result) - 1; // Subtract Admin

      /*
       *  Count all absences for this day
       */
      $query = "SELECT * FROM `".$T->table."` WHERE `year`='".$year."' AND `month`='".sprintf("%02d", $month)."';";
      $result = $T->db->db_query($query);
      $absences = 0;
      while ($row = $T->db->db_fetch_array($result, MYSQL_ASSOC)) 
      {
         $prop='abs'.$day;
         if ($row[$prop] != 0) $absences++;
      }
   }

   /*
    *  Ccheck absences against threshold
    */
   $absencerate = ((100 * $absences) / $users);
   $threshold = intval($C->readConfig("declThreshold"));
   //echo "<script type=\"text/javascript\">alert(\"Threshold ".$absencerate." : ".$threshold."\");</script>";
   if ($absencerate >= $threshold) 
   {
      return true;
   }
   else 
   {
      return false;
   }

}

// ---------------------------------------------------------------------------
/**
 * Deletes a user and all related records
 *
 * @param string  $deluser      User to delete
 * @param boolean $fromArchive  Flag whether to delete from archive tables
 */
function deleteUser($username, $fromArchive=FALSE) 
{
   global $CONF;
   
   require_once($CONF['app_root']."models/allowance_model.php" );
   require_once($CONF['app_root']."models/avatar_model.php" );
   require_once($CONF['app_root']."models/daynote_model.php" );
   require_once($CONF['app_root']."models/log_model.php" );
   require_once($CONF['app_root']."models/login_model.php" );
   require_once($CONF['app_root']."models/template_model.php" );
   require_once($CONF['app_root']."models/user_model.php" );
   require_once($CONF['app_root']."models/user_announcement_model.php" );
   require_once($CONF['app_root']."models/user_group_model.php" );
   require_once($CONF['app_root']."models/user_option_model.php" );
   
   $AV = new Avatar_model;
   $A = new Allowance_model;
   $L = new Login_model;
   $LOG = new Log_model;
   $N  = new Daynote_model;
   $T  = new Template_model;
   $U  = new User_model;
   $UA = new User_announcement_model;
   $UG = new User_group_model;
   $UO = new User_option_model;
       
   /**
    * Get fullname for log
    */
   $U->findByName($username);
   $fullname = trim($U->firstname." ".$U->lastname);
   
   /**
    * Delete user
    * Delete memberships
    * Delete options
    * Delete templates
    * Delete daynotes
    * Delete allowances
    * Delete announcements
    * Delete avatars
    */
   $U->deleteByName($username, $fromArchive);
   $UG->deleteByUser($username, $fromArchive);
   $UO->deleteByUser($username, $fromArchive);
   $T->deleteByUser($username, $fromArchive);
   $N->deleteByUser($username, $fromArchive);
   $A->deleteByUser($username, $fromArchive);
   $UA->deleteByUser($username, $fromArchive);
   $AV->delete($username);

   /**
    * Send notification e-mails
    */
   sendNotification("userdelete",$fullname,"");
   
   /**
    * Log this event
   */
   if ($fromArchive) 
      $LOG->log("logUser",$L->checkLogin(),"log_user_archived_deleted", $fullname." (".$username.")");
   else
      $LOG->log("logUser",$L->checkLogin(),"log_user_deleted", $fullname." (".$username.")");
}

// ---------------------------------------------------------------------------
/**
 * Generates a password
 *
 * @param   integer $length    Desired password length
 * @return  string             Password
 */
function generatePassword($length=9)
{
   $characters = 'abcdefghjklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789@#$%';

   $password = '';
   for ($i = 0; $i < $length; $i++) {
         $password .= $characters[(rand() % strlen($characters))];
   }
   return $password;
}


// ---------------------------------------------------------------------------
/**
 * Scans a given directory for files. Optionally you can specify an array of
 * extension to look for.
 *
 * @param string $myDir Directory name to scan
 * @param string $myExt Array of extensions to scan for
 * @return array Array containing the names of the files (optionally matching one of the extension in myExt)
 */
function getFiles($myDir, $myExt = NULL) 
{
   $myDir = rtrim($myDir, "/");
   $dir = opendir($myDir);
   
   while (false !== ($filename = readdir($dir)))
   {
      $files[] = $filename;
   }

   foreach ($files as $pos => $file) 
   {
      if (is_dir($file)) 
      {
         $dirs[] = $file;
         unset ($files[$pos]);
      }
   }

   if (count($myExt)) 
   {
      if (count($files)) 
      {
         foreach ($files as $pos => $file) 
         {
            $thisExt = explode(".", $file);
            if (in_array($thisExt[1], $myExt)) 
            {
               $filearray[] = $file;
            }
         }
      }
      return $filearray;
   }
   else 
   {
      return $files;
   }
}


// ---------------------------------------------------------------------------
/**
 * Extracts the file extension from a given file name
 *
 * @param string str String containing the path or filename
 * @return string File extension of the string passed
 */
function getFileExtension($str) 
{
   $i = strrpos($str,".");
   if (!$i) return "";
   $l = strlen($str) - $i;
   $ext = substr($str,$i+1,$l);
   return $ext;
}


// ---------------------------------------------------------------------------
/**
 * Gets all folders in a given directory
 *
 * @return array Array containing the folder names
 */
function getFolders($myDir) 
{
	$myDir = rtrim($myDir, '/').'/'; // Ensure trailing slash
   $handle = opendir($myDir);
   $diridx = 0;
   
   while (false !== ($dir = readdir($handle))) 
   {
      if (is_dir($myDir . "/$dir") && $dir != "." && $dir != "..") 
      {
         $dirarray[$diridx]["name"] = $dir;
         $diridx++;
      }
   }
   closedir($handle);
   return $dirarray;
}


// ---------------------------------------------------------------------------
/**
 * Gets the number of days in a given month
 *
 * @param   string $yr  Four character string representing the year
 * @param   string $mt  Two character string representing the month
 *
 * @return  array       [monthno, days, weekday1]
 */
function getMonthInfo($yr, $mt) {
   $mytime = $mt . " 1," . $yr;
   $myts = strtotime($mytime);
   $mydate = getdate($myts);
   $mi['monthno']  = sprintf("%02d",intval($mydate['mon']));
   $mi['nofdays']  = date("t", $myts);
   $mi['weekday1'] = $mydate['wday'];
   return $mi;
}


// ---------------------------------------------------------------------------
/**
 * Gets all language directory names from the TeamCal Pro language directory
 *
 * @return array Array containing the names
 */
function getLanguages() {
   $mydir = "languages/";
   $handle = opendir($mydir); // open directory
   $fileidx = 0;
   while (false !== ($file = readdir($handle))) {
      if (!is_dir($mydir . "/$file") && $file != "." && $file != "..") {
         $filearray[$fileidx]["name"] = $file;
         $fileidx++;
      }
   }
   closedir($handle);

   // If there are language files
   if ($fileidx > 0) {
      // Extract the language name
      // Filename mus follow the format "english.tcpro.php"
      for ($i = 0; $i < $fileidx; $i++) {
         $langName = explode(".", $filearray[$i]["name"]);
         if ($langName[1] == "tcpro" && $langName[2] == "php") {
            $langarray[$i] = $langName[0];
         }
      }
   }
   return $langarray;
}


// ---------------------------------------------------------------------------
/**
 * Gets all $_REQUEST and $_POST parameters and fills the $CONF['options'][] array
 *
 */
function getOptions() {
   global $CONF;
   global $_REQUEST;
   global $_POST;

   require_once ($CONF['app_root']."models/config_model.php");
   require_once ($CONF['app_root']."models/absence_model.php");
   require_once ($CONF['app_root']."models/group_model.php");
   require_once ($CONF['app_root']."models/login_model.php");
   require_once ($CONF['app_root']."models/region_model.php");
   require_once ($CONF['app_root']."models/user_model.php");
   require_once ($CONF['app_root']."models/user_option_model.php");

   $A = new Absence_model;
   $C = new Config_model;
   $G = new Group_model;
   $L = new Login_model;
   $R = new Region_model;
   $UL = new User_model;
   $UO = new User_option_model;
   $user = $L->checkLogin();

   /**
    * Set time zone
    */
   $tz = $C->readConfig("timeZone");
   if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
   else date_default_timezone_set ($tz);
   
   /**
    * Set defaults
    */
   $today = getdate();
   $CONF['options'] = array(
      "lang"=>'english',
      "groupfilter"=>'All',
      "region"=>'default',
      "absencefilter"=>'All',
      "month_id"=>$today['mon'],
      "year_id"=>$today['year'],
      "show_id"=>1,
      "summary"=>'',
      "remainder"=>'',
   );

   if (!$C->readConfig("defaultLanguage")) $C->saveConfig("defaultLanguage","english");
   else $CONF['options']['lang'] = $C->readConfig("defaultLanguage");
   
   if (!$C->readConfig("defgroupfilter")) $C->saveConfig("defgroupfilter","All");
   else $CONF['options']['groupfilter'] = $C->readConfig("defgroupfilter");

   if (!$C->readConfig("defregion")) $C->saveConfig("defregion","default");
   else $CONF['options']['region'] = $C->readConfig("defregion");

   $CONF['options']['show_id'] = $C->readConfig("showMonths");

   if ($C->readConfig("showRemainder")) $CONF['options']['remainder'] = "show";
   else                                 $CONF['options']['remainder'] = "hide";

   if ($C->readConfig("showSummary"))   $CONF['options']['summary'] = "show";
   else                                 $CONF['options']['summary'] = "hide";

   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE) {
      $debug ="After Defaults\\r\\n";
      $debug.="tc_config['options']['lang'] = ".$CONF['options']['lang']."\\r\\n";
      $debug.="tc_config['options']['groupfilter'] = ".$CONF['options']['groupfilter']."\\r\\n";
      $debug.="tc_config['options']['region'] = ".$CONF['options']['region']."\\r\\n";
      $debug.="tc_config['options']['month_id'] = ".$CONF['options']['month_id']."\\r\\n";
      $debug.="tc_config['options']['year_id'] = ".$CONF['options']['year_id']."\\r\\n";
      $debug.="tc_config['options']['show_id'] = ".$CONF['options']['show_id']."\\r\\n";
      $debug.="tc_config['options']['summary'] = ".$CONF['options']['summary']."\\r\\n";
      $debug.="tc_config['options']['remainder'] = ".$CONF['options']['remainder']."\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"".$debug."\");</script>";
   }

   /**
    * Get user preferences if someone is logged in
    */
   if ($user) {
      if ($userlang=$UO->find($user,"language") AND $userlang!="default") {
         $CONF['options']['lang'] = $userlang;
      } 
   
      if ($userprefgroup=$UO->find($user,"defgroup") AND $userprefgroup!="default") {
         $CONF['options']['groupfilter'] = $userprefgroup;
      }
   
      if ($userregion=$UO->find($user,"defregion") AND $userregion!="default") {
         $CONF['options']['region'] = $userregion;
      }
   }

   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE) {
      $debug ="After Preferences\\r\\n";
      $debug.="tc_config['options']['lang'] = ".$CONF['options']['lang']."\\r\\n";
      $debug.="tc_config['options']['groupfilter'] = ".$CONF['options']['groupfilter']."\\r\\n";
      $debug.="tc_config['options']['region'] = ".$CONF['options']['region']."\\r\\n";
      $debug.="tc_config['options']['month_id'] = ".$CONF['options']['month_id']."\\r\\n";
      $debug.="tc_config['options']['year_id'] = ".$CONF['options']['year_id']."\\r\\n";
      $debug.="tc_config['options']['show_id'] = ".$CONF['options']['show_id']."\\r\\n";
      $debug.="tc_config['options']['summary'] = ".$CONF['options']['summary']."\\r\\n";
      $debug.="tc_config['options']['remainder'] = ".$CONF['options']['remainder']."\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"".$debug."\");</script>";
   }

   /**
    * Get $_REQUEST (overwriting user preferences)
    */
   if (isset ($_REQUEST['groupfilter']) AND strlen($_REQUEST['groupfilter'])
       AND (in_array($_REQUEST['groupfilter'],$G->getGroups())
            OR $_REQUEST['groupfilter']=="All"
            OR $_REQUEST['groupfilter']=="Allbygroup"
           )
      )
      $CONF['options']['groupfilter'] = trim($_REQUEST['groupfilter']);

   if (isset ($_REQUEST['region']) && strlen($_REQUEST['region'])
       AND in_array($_REQUEST['region'],$R->getRegions())
      )
      $CONF['options']['region'] = trim($_REQUEST['region']);

   if (isset ($_REQUEST['absencefilter']) && strlen($_REQUEST['absencefilter'])
       AND in_array($_REQUEST['absencefilter'],$A->getAll())
      )
      $CONF['options']['absencefilter'] = trim($_REQUEST['absencefilter']);

   $mo = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
   if (isset ($_REQUEST['month_id']) && strlen($_REQUEST['month_id'])
       AND in_array($_REQUEST['month_id'],$mo)
      )
      $CONF['options']['month_id'] = intval($_REQUEST['month_id']);

   if (isset ($_REQUEST['year_id']) && strlen($_REQUEST['year_id'])
       AND is_numeric($_REQUEST['year_id'])
      )
      $CONF['options']['year_id'] = intval($_REQUEST['year_id']);

   $sho = array("1", "2", "3", "6", "12");
   if (isset ($_REQUEST['show_id']) && strlen($_REQUEST['show_id'])
       AND in_array($_REQUEST['show_id'],$sho)
      )
      $CONF['options']['show_id'] = intval($_REQUEST['show_id']);

   $showhide = array("show", "hide");
   if (isset ($_REQUEST['summary']) && strlen($_REQUEST['summary'])
       AND in_array($_REQUEST['summary'],$showhide)
      )
      $CONF['options']['summary'] = trim($_REQUEST['summary']);

   if (isset ($_REQUEST['remainder']) && strlen($_REQUEST['remainder'])
       AND in_array($_REQUEST['remainder'],$showhide)
      )
      $CONF['options']['remainder'] = trim($_REQUEST['remainder']);

   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE) {
      $debug ="After _REQUEST\\r\\n";
      $debug.="tc_config['options']['lang'] = ".$CONF['options']['lang']."\\r\\n";
      $debug.="tc_config['options']['groupfilter'] = ".$CONF['options']['groupfilter']."\\r\\n";
      $debug.="tc_config['options']['region'] = ".$CONF['options']['region']."\\r\\n";
      $debug.="tc_config['options']['month_id'] = ".$CONF['options']['month_id']."\\r\\n";
      $debug.="tc_config['options']['year_id'] = ".$CONF['options']['year_id']."\\r\\n";
      $debug.="tc_config['options']['show_id'] = ".$CONF['options']['show_id']."\\r\\n";
      $debug.="tc_config['options']['summary'] = ".$CONF['options']['summary']."\\r\\n";
      $debug.="tc_config['options']['remainder'] = ".$CONF['options']['remainder']."\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"".$debug."\");</script>";
   }

   /**
    * Now get $_POST (overwrites $_REQUEST and user preferences)
    */
   if (isset ($_POST['user_lang']) && strlen($_POST['user_lang']) AND in_array($_POST['user_lang'],getLanguages()))
      $CONF['options']['lang'] = trim($_POST['user_lang']);

   if (isset ($_POST['groupfilter']) && strlen($_POST['groupfilter']) AND (in_array($_POST['groupfilter'],$G->getGroups()) OR $_POST['groupfilter']=="Allbygroup") )
      $CONF['options']['groupfilter'] = trim($_POST['groupfilter']);

   if (isset ($_POST['regionfilter']) && strlen($_POST['regionfilter']) AND in_array($_POST['regionfilter'],$R->getRegions()))
      $CONF['options']['region'] = trim($_POST['regionfilter']);

   if (isset ($_POST['absencefilter']) && strlen($_POST['absencefilter']) AND $A->get($_POST['absencefilter']))
      $CONF['options']['absencefilter'] = trim($_POST['absencefilter']);

   if (isset ($_POST['month_id']) && strlen($_POST['month_id']) AND in_array($_POST['month_id'],$mo))
      $CONF['options']['month_id'] = intval($_POST['month_id']);

   if (isset ($_POST['year_id']) && strlen($_POST['year_id']) AND is_numeric($_POST['year_id']))
      $CONF['options']['year_id'] = intval($_POST['year_id']);

   if (isset ($_POST['show_id']) && strlen($_POST['show_id']) AND in_array($_POST['show_id'],$sho))
      $CONF['options']['show_id'] = intval($_POST['show_id']);

   /**
    * DEBUG: Set to TRUE for debug info
    */
   if (FALSE) {
      $debug ="After _POST\\r\\n";
      $debug.="tc_config['options']['lang'] = ".$CONF['options']['lang']."\\r\\n";
      $debug.="tc_config['options']['groupfilter'] = ".$CONF['options']['groupfilter']."\\r\\n";
      $debug.="tc_config['options']['region'] = ".$CONF['options']['region']."\\r\\n";
      $debug.="tc_config['options']['month_id'] = ".$CONF['options']['month_id']."\\r\\n";
      $debug.="tc_config['options']['year_id'] = ".$CONF['options']['year_id']."\\r\\n";
      $debug.="tc_config['options']['show_id'] = ".$CONF['options']['show_id']."\\r\\n";
      $debug.="tc_config['options']['summary'] = ".$CONF['options']['summary']."\\r\\n";
      $debug.="tc_config['options']['remainder'] = ".$CONF['options']['remainder']."\\r\\n";
      echo "<script type=\"text/javascript\">alert(\"".$debug."\");</script>";
   }
}


// ---------------------------------------------------------------------------
/**
 * Checks whether a user is authorized in the active permission scheme
 *
 * @param string $scheme Permission scheme to check
 * @param string $permission Permission to check
 * @param string $targetuser Some features reference data of other users. This is the target
 * @return boolean True if allowed, false if not.
 */
function isAllowed($permission='') {

   global $CONF;

   require_once ($CONF['app_root'] . "models/config_model.php");
   require_once ($CONF['app_root'] . "models/login_model.php");
   require_once ($CONF['app_root'] . "models/permission_model.php");
   require_once ($CONF['app_root'] . "models/user_model.php");

   $C = new Config_model;
   $L = new Login_model;
   $P = new Permission_model;
   $UL = new User_model;

   $pscheme = $C->readConfig("permissionScheme");

   if ($currentuser = $L->checkLogin()) {
      /**
       * Someone is logged in. Check permission by role.
       */
      $UL->findByName($currentuser);
      if ($UL->checkUserType($CONF['UTADMIN'])) 
      {
         return $P->isAllowed($pscheme, $permission, "admin");
      }
      else if ($UL->checkUserType($CONF['UTDIRECTOR'])) 
      {
         return $P->isAllowed($pscheme, $permission, "director");
      }
      else if ($UL->checkUserType($CONF['UTMANAGER'])) 
      {
         return $P->isAllowed($pscheme, $permission, "manager");
      }
      else if ($UL->checkUserType($CONF['UTASSISTANT'])) 
      {
         return $P->isAllowed($pscheme, $permission, "assistant");
      }
      else 
      {
         return $P->isAllowed($pscheme, $permission, "user");
      }
   }
   else 
   {
      /**
       * It's a public viewer
       */
      return $P->isAllowed($pscheme, $permission, "public");
   }
}


// ---------------------------------------------------------------------------
/**
 * Checks wether a bit combination is set in a given bitmask
 *
 * @param   integer $flagset  Target to check
 * @param   integer $bitmask  Bit combination to check
 * @return  boolean           True if set, false if not
 */
function isFlag($flagset, $bitmask) {
   if ($flagset & $bitmask)
      return true;
   else
      return false;
}


// ---------------------------------------------------------------------------
/**
 * Creates a message div and displays it via jQuery dialog()
 *
 * @param string $type     What type of message: info, success, warning, error
 * @param string $title    Dialog title
 * @param string $caption  Inside the dialog above the message, bold and backcolored
 * @param string $message  The message itself
 * @return string $html    The HTML code
 */
function jQueryPopup($type='info', $title='Information', $caption, $message) 
{
   $color = array(
      'info' => array( 'border'=>'#bce8f1', 'headerbg'=>'#d9edf7', 'headerfg'=>'#31708f'), 
      'success' => array( 'border'=>'#d6e9c6', 'headerbg'=>'#dff0d8', 'headerfg'=>'#3c763d'),
      'warning' => array( 'border'=>'#faebcc', 'headerbg'=>'#fcf8e3', 'headerfg'=>'#8a6d3b'),
      'error' => array( 'border'=>'#ebccd1', 'headerbg'=>'#f2dede', 'headerfg'=>'#a94442'),
   );

   $html = '
   <div id="jqpopup" title="'.$title.'" style="border: 1px solid '.$color[$type]['border'].'; margin: 8px; padding: 0px; border-radius: 4px;">
      <p style="color: '.$color[$type]['headerfg'].'; font-weight: bold; padding: 8px; margin: 0px; background-color: '.$color[$type]['headerbg'].';">'.$caption.'</p>
      <p style="background-color: #ffffff; padding: 0 8px 8px 8px;">'.$message.'</p>
   </div>
   <script type="text/javascript">
      $(function() { 
         $( "#jqpopup" ).dialog({
            modal: true,
            buttons: { Ok: function() { $( this ).dialog( "close" ); } }
         });
      });
   </script>';
                           
   return $html;
}


// ---------------------------------------------------------------------------
/**
 * Uses Javascript to close the current window and reload the calling page
 * without the previous POST parameters
 *
 * @param string $page URL to redirect to
 */
function jsCloseAndReload($page = 'index.php') {
   global $CONF;
   echo "<html>" .
   "<head></head>" .
   "<body>" .
   "   <script type=\"text/javascript\" type=\"javascript\">" .
   "      opener.location.href=\"" . $page . "\";" .
   "      self.close();" .
   "   </script>" .
   "</body>" .
   "</html>";
}


// ---------------------------------------------------------------------------
/**
 * Sends a HTTP redirect instruction to the browser via http-equiv
 *
 * @param string $url URL to redirect to
 */
function jsReloadPage($url = '') {
   echo "<html>" .
   "   <head>" .
   "      <meta http-equiv=\"refresh\" content=\"0;URL=".$url."\">" .
   "   </head>" .
   "   <body></body>" .
   "</html>";
}


// ---------------------------------------------------------------------------
/**
 * Prints the top HTML code of a dialog
 *
 * @param  string $title     The title of the dialog
 * @param  string $helpfile  The name of the help file to be linked to from the help icon
 * @param  string $icon      The icon to appear left of the dialog title
 */
function printDialogTop($title = '', $help = '', $icon = '') {
   global $CONF, $LANG, $theme;
   
   echo '<table style="border-collapse: collapse; border: 0px; width: 100%;"><tr><td class="dlg-header">';

   if (strlen($icon)) {
      echo '<img src="themes/'.$theme.'/img/' . $icon . '" alt="" width="16" height="16" align="top">&nbsp;';
   }
   
   echo $title . "</td>";
   
   if (strlen($help)) {
      echo '<td align="right" style="font-size: 9pt; background-color: inherit;">
               <div align="right">
                  <a href="'.$help.'" target="_blank">
                     <img class="noprint" title="'.$LANG['mnu_help'].'..." align="middle" alt="" src="themes/'.$theme.'/img/ico_help.png" width="16" height="16" border="0">
                  </a>
               </div>
            </td>';
   }
   
   echo '</tr></table>';
}


// ---------------------------------------------------------------------------
/**
 * Restores a user and all related records from archive
 *
 * @param string  $username     Username to restore
 */
function restoreUser($username) 
{
   global $CONF;
   
   require_once($CONF['app_root']."models/allowance_model.php" );
   require_once($CONF['app_root']."models/daynote_model.php" );
   require_once($CONF['app_root']."models/log_model.php" );
   require_once($CONF['app_root']."models/login_model.php" );
   require_once($CONF['app_root']."models/template_model.php" );
   require_once($CONF['app_root']."models/user_model.php" );
   require_once($CONF['app_root']."models/user_announcement_model.php" );
   require_once($CONF['app_root']."models/user_group_model.php" );
   require_once($CONF['app_root']."models/user_option_model.php" );
   
   $A = new Allowance_model;
   $L = new Login_model;
   $LOG = new Log_model;
   $N  = new Daynote_model;
   $T  = new Template_model;
   $U  = new User_model;
   $UA = new User_announcement_model;
   $UG = new User_group_model;
   $UO = new User_option_model;
    
   /**
    * Do not restore if username exists in any of the active tables
    */
   if ($U->exists($username)) return FALSE;
   if ($UG->exists($username)) return FALSE;
   if ($UO->exists($username)) return FALSE;
   if ($T->exists($username)) return FALSE;
   if ($N->exists($username)) return FALSE;
   if ($A->exists($username)) return FALSE;
   if ($UA->exists($username)) return FALSE;
   
   /**
    * Get fullname for log
    */
   $U->findByName($username);
   $fullname = trim($U->firstname." ".$U->lastname);
         
   /**
    * Restore user
    * Restore memberships
    * Restore options
    * Restore templates
    * Restore daynotes
    * Restore allowances
    * Restore announcements
    */
   $U->restore($username);
   $UG->restore($username);
   $UO->restore($username);
   $T->restore($username);
   $N->restore($username);
   $A->restore($username);
   $UA->restore($username);

   /**
    * Delete user from archive tables
    */
   deleteUser($username, TRUE);
     
   /**
    * Log this event
    */
   $LOG->log("logUser",$L->checkLogin(),"log_user_restored", $fullname." (".$username.")");
    
   return true;
}

// ---------------------------------------------------------------------------
/**
 * If a user was added or updated we send him an info to let him know.
 * Esepcially when he was added he needs to know what URL to navigate to and
 * how to login.
 *
 * @param  string $uname  The username created
 * @param  string $pwd    The password created
 */
function sendAccountCreatedMail($uname, $pwd) {
   global $CONF;
   global $LANG;

   require_once ($CONF['app_root'] . "models/user_model.php");
   require_once ($CONF['app_root'] . "models/config_model.php");

   $C = new Config_model;
   $U = new User_model;

   if ($U->findByName($uname)) {
      $to = $U->email;
      $subject = $LANG['user_add_subject'];
      $message = $LANG['user_add_greeting'];
      $message .= $LANG['user_add_info_1'];
      $message .= $U->username;
      $message .= $LANG['user_add_info_2'] . $pwd;
      $message .= $LANG['user_add_info_3'];
      sendEmail($to, $subject, $message);
   }
}


// ---------------------------------------------------------------------------
/**
 * Sends a notification eMail to one ore more users based on the type given
 *
 * @param  string $type          Type of notification
 * @param  string $object        Object of the activity. Listed at the bottom of the message
 * @param  string $grouptouched  Affected group for group notification
 * @param  string $addlinfo      Additional info in case needed
 */
function sendNotification($type, $object, $grouptouched = '', $addlinfo = '') {
   global $CONF;
   global $LANG;

   require_once ($CONF['app_root'] . "models/config_model.php");
   require_once ($CONF['app_root'] . "models/user_model.php");

   $C = new Config_model;
   $U = new User_model;

   /*
    * Now we're gonna send a mail to every user who wants to be notified
    * about this change. Each user can set that option in his profile
    */
   $query = "SELECT * FROM `" . $CONF['db_table_users'] . "` ORDER BY `username`;";
   $result = $U->db->db_query($query);
   $i = 1;
   while ($row = $U->db->db_fetch_array($result, MYSQL_ASSOC)) {
      $notify = $row['notify'];
      $notifygroup = $row['notify_group'];
      $sendmail = false;
      switch (strtolower($type)) {
         case "useradd" :
            if (($notify & $CONF['userchg']) == $CONF['userchg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_usr_msg'];
               $message .= $LANG['notification_usr_add_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "userchange" :
            if (($notify & $CONF['userchg']) == $CONF['userchg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_usr_msg'];
               $message .= $LANG['notification_usr_chg_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "userdelete" :
            if (($notify & $CONF['userchg']) == $CONF['userchg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_usr_msg'];
               $message .= $LANG['notification_usr_del_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "groupadd" :
            if (($notify & $CONF['groupchg']) == $CONF['groupchg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_grp_msg'];
               $message .= $LANG['notification_grp_add_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "groupchange" :
            if (($notify & $CONF['groupchg']) == $CONF['groupchg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_grp_msg'];
               $message .= $LANG['notification_grp_chg_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "groupdelete" :
            if (($notify & $CONF['groupchg']) == $CONF['groupchg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_grp_msg'];
               $message .= $LANG['notification_grp_del_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "monthchange" :
            if (($notify & $CONF['monthchg']) == $CONF['monthchg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_month_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "usercalchange" :
            if (($notify & $CONF['usercalchg']) == $CONF['usercalchg'] && ($notifygroup == $grouptouched || $notifygroup == "All")) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_usr_cal'];
               $message .= $LANG['notification_usr_cal_msg'];
               $message .= $object;
               $message .= "\r\n";
               $message .= $addlinfo;
               $message .= "\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "absenceadd" :
            if (($notify & $CONF['absencechg']) == $CONF['absencechg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_abs_msg'];
               $message .= $LANG['notification_abs_add_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "absencechange" :
            if (($notify & $CONF['absencechg']) == $CONF['absencechg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_abs_msg'];
               $message .= $LANG['notification_abs_chg_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "absencedelete" :
            if (($notify & $CONF['absencechg']) == $CONF['absencechg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_abs_msg'];
               $message .= $LANG['notification_abs_del_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "holidayadd" :
            if (($notify & $CONF['holidaychg']) == $CONF['holidaychg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_hol_msg'];
               $message .= $LANG['notification_hol_add_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "holidaychange" :
            if (($notify & $CONF['holidaychg']) == $CONF['holidaychg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_hol_msg'];
               $message .= $LANG['notification_hol_chg_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "holidaydelete" :
            if (($notify & $CONF['holidaychg']) == $CONF['holidaychg']) {
               $message = $LANG['notification_greeting'];
               $message .= $LANG['notification_hol_msg'];
               $message .= $LANG['notification_hol_del_msg'];
               $message .= $object . ".\r\n\r\n";
               $sendmail = true;
            }
            break;
         case "decline" :
            $message = $LANG['notification_greeting'];
            $message .= $LANG['notification_decl_msg'];
            $message .= $LANG['notification_hol_del_msg'];
            $message .= $object . ".\r\n\r\n";
            $sendmail = true;
            break;
         default :
            break;
      }

      if ($sendmail AND $C->readConfig("emailNotifications")) {
         $to = $row['email'];
         $subject = stripslashes($LANG['notification_subject']);
         $message .= stripslashes($LANG['notification_signature']);
         /*
          * Set to TRUE for Debug
          */
         if (FALSE) {
            echo "<textarea cols=\"100\" rows=\"12\">email:   " . $to . "\n\n".
                 "subject: " . $subject . "\n\n".
                 $type." - message: " . $message . "\n\n".
                 "headers: " . $headers . "</textarea>";
         }
         sendEmail($to, $subject, $message);
      }
   }
   return;
}


// ---------------------------------------------------------------------------
/**
 * Sends an HTML eMail, either via SMTP or regular PHP mail
 * Requires the PEAR Mail package installed on the server that Tcpro is running on
 *
 * @param  string $to          eMail to address
 * @param  string $subject     eMail subject
 * @param  string $body        eMail body
 * @return bool                Email success
 */
function sendEmail($to, $subject, $body, $from='') 
{
   global $CONF;
   require_once ($CONF['app_root']."models/config_model.php");
   $C = new Config_model;
   error_reporting(E_ALL ^ E_STRICT);

   $from_regexp = preg_match('/<(.*?)>/', $from, $fetch);

   if ((!strlen($from)) OR ($from_regexp AND ($fetch[1] == $C->readConfig("mailReply")))) {
      $from = $replyto = mb_encode_mimeheader($C->readConfig("mailFrom"))." <".$C->readConfig("mailReply").">";
      $from_mailonly = $C->readConfig("mailReply");
   }
   else if ($from_regexp) {
      $from_mailonly = $fetch[1];
      $replyto = mb_encode_mimeheader($from);
   }
   /*
    * "To" has to be a valid email or comma separated list of valid emails.
    * It might be empty if a user to be notified has not setup his email address
    */
   $toArray = explode(",",$to);
   $toValid = "";
   foreach ($toArray as $toPiece)
   {
      if (!validEmail($toPiece)) $toValid.=$C->readConfig("mailReply").",";
      else $toValid .= $toPiece.",";
   }
   $toValid = substr($toValid,0,strlen($toValid)-1); // remove the last ","
      
   if ($C->readConfig("mailSMTP")) 
   {
      include_once('Mail.php');
      
      $host     = $C->readConfig("mailSMTPHost");
      $port     = $C->readConfig("mailSMTPPort");
      $username = $C->readConfig("mailSMTPusername");
      $password = $C->readConfig("mailSMTPPassword");
      if ($C->readConfig("mailSMTPSSL")) $ssl="ssl://"; else $ssl="";
      
      /*
       * SMTP requires a valid email address in the From field
       */
      if (!validEmail($from_mailonly)) {
         $from = $replyto = mb_encode_mimeheader($C->readConfig("mailFrom"))." <".$C->readConfig("mailReply").">";
      }
   
      $headers = array (
         'From' => $from,
         'Reply-To' => $replyto,
         'To' => $toValid,
         'Subject' => $subject,
         'Content-type' => "text/html;  charset=iso-8859-1"
      );

      /*
       * Put an HTML envelope around the body
       */
      $body = '<html><body>'.$body.'</body></html>';
      
      $smtp = @Mail::factory(
         'smtp',
         array (
            'host' => $ssl.$host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password
         )
      );
   
      $mail = @$smtp->send($toValid, $headers, $body);
   
      if ($error = @PEAR::isError($mail)) 
      {
         /*
          * Display SMTP error in a Javascript popup
          */
         $err=$mail->getMessage();
         $err.="<table style=\"border-collapse: collapse;\">
                  <tr><td style=\"border: 1px solid #BBBBBB;\">From:</td><td style=\"border: 1px solid #BBBBBB;\">".$headers['From']."</td></tr>
                  <tr><td style=\"border: 1px solid #BBBBBB;\">To:</td><td style=\"border: 1px solid #BBBBBB;\">".$headers['To']."</td></tr>
                  <tr><td style=\"border: 1px solid #BBBBBB;\">Subject:</td><td style=\"border: 1px solid #BBBBBB;\">".$headers['Subject']."</td></tr>
                  <tr><td style=\"border: 1px solid #BBBBBB;\">Body:</td><td style=\"border: 1px solid #BBBBBB;\"><pre>".$body."</pre></td></tr>
               </table>";
         showError("smtp",$err);
         return FALSE;
      }
      else 
      {
         return TRUE;
      }
   }
   else 
   {
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= "From: " . $from . "\r\n";
      $headers .= "Reply-To: " . $replyto;
      $body = '<html><body>'.$body.'</body></html>';
      $result = mail($toValid, $subject, $body, $headers);
      
      return $result;
   }
}


// ---------------------------------------------------------------------------
/**
 * Sets a bit combination in a given bitmask
 *
 * @param   integer $flagset  Target to change
 * @param   integer $bitmask  Bitmask to set (1's in this bitmask will become 1's in the target)
 * @return  integer           New target
 */
function setFlag($flagset, $bitmask) {
   $newflagset = $flagset | $bitmask;
   return $newflagset;
}


// ---------------------------------------------------------------------------
/**
 * Builds the URL request parameters based on whats in the tc_config['options'][] array
 *
 * @return  string  URL request string
 */
function setRequests() {
   global $CONF;
   $requ = "";
   foreach ($CONF['options'] as $key => $value) {
      if (strlen($value)) $requ .= $key . "=" . $value . "&amp;";
   }
   return $requ;
}


// ---------------------------------------------------------------------------
/**
 * Shows the error page
 */
function showError($error="notallowed",$message="",$closeButton=FALSE) 
{
   global $CONF, $LANG, $U;
   switch($error) 
   {
      case "smtp":
         $err_short="SMTP Error";
         $err_long=$message;
         $err_module=$_SERVER['SCRIPT_NAME'];
         $err_btn_close=$closeButton;
         break;
      case "notarget":
         $err_short=$LANG['err_notarget_short'];
         $err_long=$LANG['err_notarget_long'];
         $err_module=$_SERVER['SCRIPT_NAME'];
         $err_btn_close=$closeButton;
         break;
      case "notallowed":
         $err_short=$LANG['err_not_authorized_short'];
         $err_long=$LANG['err_not_authorized_long'];
         $err_module=$_SERVER['SCRIPT_NAME'];
         $err_btn_close=$closeButton;
         break;
      case "input":
         $err_short=$LANG['err_input_caption'];
         $err_long=$message;
         $err_module=$_SERVER['SCRIPT_NAME'];
         $err_btn_close=$closeButton;
         break;
      default:
         $err_short=$LANG['err_unspecified_short'];
         $err_long=$LANG['err_unspecified_long'];
         $err_module=$_SERVER['SCRIPT_NAME'];
         $err_btn_close=$closeButton;
         break;
   }
   /**
    * HTML title. Will be shown in browser tab.
    */
   $CONF['html_title'] = $LANG['html_title_error'];

   require("includes/header_html_inc.php");
   if (!$closeButton) 
   {
      require("includes/header_app_inc.php");
      require("includes/menu_inc.php");
   }
   require("error.php");
   require("includes/footer_inc.php");
   die();
}


// ---------------------------------------------------------------------------
/**
 * Validate an email address.
 *
 * @param email The email address to validate
 * @return $isValid Boolean result
*/
function validEmail($email) {
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex) {
      $isValid = false;
   }
   else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64) {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255) {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.') {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local)) {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain)) {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local))) {
            $isValid = false;
         }
      }
      
      // Under Windows, this only works in PHP 5.3.0 or higher. Causes problems at some users. 
      //if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
         // domain not found in DNS
         //$isValid = false;
      //}
   }
   return $isValid;
}
?>
