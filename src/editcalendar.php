<?php
/**
 * editcalendar.php
 *
 * Displays the edit calendar dialog
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * Set parent flag to control access to child scripts
 */
define( '_VALID_TCPRO', 1 );

/**
 * Includes
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");

require_once( "models/absence_model.php" );
require_once( "models/absence_group_model.php" );
require_once( "models/config_model.php" );
require_once( "models/daynote_model.php" );
require_once( "models/group_model.php" );
require_once( "models/holiday_model.php" );
require_once( "models/login_model.php" );
require_once( "models/log_model.php" );
require_once( "models/month_model.php" );
require_once( "models/template_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );

$A   = new Absence_model;
$AG  = new Absence_group_model;
$C   = new Config_model;
$G   = new Group_model;
$H   = new Holiday_model;
$L   = new Login_model;
$LOG = new Log_model;
$M   = new Month_model;
$N   = new Daynote_model;
$T   = new Template_model;
$TT  = new Template_model; // used for template user loop
$U   = new User_model;
$UC  = new User_model; // User of this calendar
$UL  = new User_model; // User logged in
$UT  = new User_model; // used for template user loop
$UG  = new User_group_model;

$error=FALSE;
$error_decl=FALSE;
$warning=FALSE;

/**
 * Get the user that is logged in. 
 * $UL represents the logged in user, $luser his username.
 */
$luser=$L->checkLogin();
$UL->findByName($luser);

/**
 * Get the target user, the one that we edit the calender off here
 * $UC represents the user of the calendar, $caluser his username.
 */
if (isset($_REQUEST['Member'])) 
{
   $caluser=$_REQUEST['Member'];
   $UC->findByName($caluser);
}
else 
{
   showError("notarget");
}

/**
 * Check authorization
 */
$allowed=FALSE;

if ( $luser == $caluser ) 
{
   if (isAllowed("editOwnUserCalendars")) $allowed=TRUE;
}
else if ( $UG->shareGroups($luser, $caluser) ) 
{
   if (isAllowed("editGroupUserCalendars")) 
   {
      if ($UG->isGroupManagerOfUser($luser, $caluser) OR !$UG->isGroupManagerOfUser($caluser, $luser) OR $UL->checkUserType($CONF['UTADMIN']))
      {
         $allowed=TRUE;
      }
   }
}
else 
{
   if (isAllowed("editAllUserCalendars")) $allowed=TRUE;
}

if (!$allowed) showError("notallowed");

/**
 * Get the rest of the URI parameters
 */
if (isset($_REQUEST['Year']))   $Year=$_REQUEST['Year'];
if (isset($_REQUEST['Month']))  $Month=$_REQUEST['Month'];
if (isset($_REQUEST['region'])) $region=$_REQUEST['region']; else $region = $CONF['options']['region'];

/**
 * =========================================================================
 * BACKWARD
 */
if ( isset($_POST['btn_bwd']) ) 
{
   $Year=$_POST['hid_bwdYear'];
   $Month=$_POST['hid_bwdMonth'];
   $caluser=$_POST['hid_Member'];
   header("Location: ".$_SERVER['PHP_SELF'] . "?region=" . $region . "&Year=" . $Year . "&Month=" . $Month . "&lang=" . $CONF['options']['lang'] . "&Member=" . $caluser);
}

/**
 * =========================================================================
 * FORWARD
 */
if ( isset($_POST['btn_fwd']) ) 
{
   $Year=$_POST['hid_fwdYear'];
   $Month=$_POST['hid_fwdMonth'];
   $caluser=$_POST['hid_Member'];
   header("Location: ".$_SERVER['PHP_SELF'] . "?region=" . $region . "&Year=" . $Year . "&Month=" . $Month . "&lang=" . $CONF['options']['lang'] . "&Member=" . $caluser);
}

/**
 * Get month info
 */
$weekdays = $LANG['weekdays'];
$mi = getMonthInfo($Year, $Month);
$nofdays = $mi['nofdays'];
$monthno = $mi['monthno'];
$weekday1 = $mi['weekday1'];
if ($weekday1=="0") $weekday1="7";
$dayofweek = intval($weekday1);

/**
 * Prepare the Fwd/Bwd buttons
 */
if (intval($monthno)==12) 
{
   $fwdMonth=$CONF['monthnames'][1];
   $fwdYear=$Year+1;
}
else 
{
   $fwdMonth=$CONF['monthnames'][intval($monthno)+1];
   $fwdYear=$Year;
}

if (intval($monthno)==1) 
{
   $bwdMonth=$CONF['monthnames'][12];
   $bwdYear=$Year-1;
}
else 
{
   $bwdMonth=$CONF['monthnames'][intval($monthno)-1];
   $bwdYear=$Year;
}

if ( $UL->checkUserType($CONF['UTADMIN']) )    $isAdmin    = TRUE; else $isAdmin    = FALSE;
if ( $UL->checkUserType($CONF['UTDIRECTOR']) ) $isDirector = TRUE; else $isDirector = FALSE;
if ( $UL->checkUserType($CONF['UTMANAGER']) )  $isManager  = TRUE; else $isManager  = FALSE;

/**
 * Read Month Template
 */
$found = $M->findByName($region, $Year.$monthno);
if ( !$found ) 
{
   /**
    * Seems there is no default template for this month yet.
    * Let's create a default one.
    */
   $M->region = $region;
   $M->yearmonth = $Year.$monthno;
   $M->template = createMonthTemplate($Year,$Month);
   $M->create();
}
else if ( empty($M->template) ) 
{
   /**
    * Seems there is an empty default template. That can't be.
    * Let's create a default one.
    */
   $M->template = createMonthTemplate($Year,$Month);
   $M->update($region, $Year.$monthno);
}

/**
 * Get the user for this calendar
 */
$UC->findByName($caluser);
$notify = $UC->notify;
$notifygroup = $UC->notify_group;

/**
 * Try to find this user's current template for this month
 */
$found = $T->getTemplate($caluser,$Year,$monthno);
if (!$found) 
{
   /**
    * No template found for this user and month yet.
    * Create a default one.
    */
   $T->username = $caluser;
   $T->year = $Year;
   $T->month = $monthno;
   for ($i=1; $i<=intval($nofdays); $i++ ) 
   {
      $prop='abs'.$i;      
      $T->$prop = 0;
   }
   $T->create();
   /**
    * Log this event
    */
   $LOG->log("logUser",$luser, "log_cal_usr_def_tpl", $T->year.$T->month);
}

/**
 * =========================================================================
 * APPLY
 */
if (isset($_POST['btn_apply'])) 
{
   /**
    * We have to create an array for this month with all unchanged old absences
    * and all the new ones. This is then the $requested() array.
    * So we either pick up the old absence from $T or the new one if posted.
    */
   $requested = array();
   for ($i=1; $i<=$nofdays; $i++) 
   {
      $key = 'opt_abs_'.$i;
      $prop = 'abs'.$i;
      if (isset($_POST[$key])) 
         $requested[$i] = $_POST[$key];
      else 
         $requested[$i] = $T->$prop;
   }
    
   foreach($_POST as $key=>$value) 
   {
      /**
       * Get the range input
       */
      if ( $key=="rangeabs" && strlen($_POST['rangefrom']) && strlen($_POST['rangeto']) ) 
      {
	   	$yearfrom = substr($_POST['rangefrom'],0,4);
	   	$monthfrom = substr($_POST['rangefrom'],5,2);
	   	$dayfrom = substr($_POST['rangefrom'],8,2);
	   	$yearto = substr($_POST['rangeto'],0,4);
	   	$monthto = substr($_POST['rangeto'],5,2);
	   	$dayto = substr($_POST['rangeto'],8,2);
         if ( $yearfrom!=$Year || $monthfrom!=$monthno || $yearto!=$Year || $monthto!=$monthno ) 
         {
            echo "<script type=\"text/javascript\">alert(\"".$LANG['cal_range_within']."\");</script>";
         }
         else if ( $_POST['rangefrom']>$_POST['rangeto']) 
         {
            echo "<script type=\"text/javascript\">alert(\"".$LANG['cal_range_start']."\");</script>";
         }
         else 
         {
            for ($i=intval($dayfrom);$i<=intval($dayto);$i++) 
            {
               if (isset($_POST['range_only_business'])) 
               {
                  if ( $H->findBySymbol($M->template[$i-1]) ) 
                  {
                     if ( $H->cfgname=='busi' OR $H->checkOptions($CONF['H_BUSINESSDAY']) ) $requested[$i]=$_POST['rangeabs'];
                  }
               }
               else 
               {
		            $requested[$i]=$_POST['rangeabs'];
               }
            }
         }
      }

      /**
       * Then get the recurring input
       */
      $wdays = array('monday'=>1, 'tuesday'=>2, 'wednesday'=>3, 'thursday'=>4, 'friday'=>5, 'saturday'=>6, 'sunday'=>7);
      foreach ($wdays as $wday => $wdaynr) 
      {
         if ( $key==$wday ) 
         {
            $x = intval($weekday1);
            for ($i=1; $i<=$nofdays; $i++) 
            {
               if ($x==$wdaynr) 
               {
                  if (isset($_POST['recurring_only_business'])) 
                  {
                     if ( $H->findBySymbol($M->template[$i-1]) ) 
                     {
                        if ( $H->cfgname=='busi' OR $H->checkOptions($CONF['H_BUSINESSDAY']) ) $requested[$i]=$_POST['recurrabs'];
                     }
                  }
                  else 
                  {
                     $requested[$i]=$_POST['recurrabs'];
                  }
               }
              if($x<=6) $x++; else $x=1;
            }
         }
      }
      
      if ( $key=="workdays" ) 
      {
         $x = intval($weekday1);
         for ($i=1; $i<=$nofdays; $i++) 
         {
            if ($x>=1 AND $x<=5) 
            {
               if (isset($_POST['recurring_only_business'])) 
               {
                  if ( $H->findBySymbol($M->template[$i-1]) ) 
                  {
                     if ( $H->cfgname=='busi' OR $H->checkOptions($CONF['H_BUSINESSDAY']) ) $requested[$i]=$_POST['recurrabs'];
                  }
               }
               else 
               {
                  $requested[$i]=$_POST['recurrabs'];
               }
            }
            if($x<=6) $x++; else $x=1;
         }
      }

      if ( $key=="weekend" ) 
      {
         $x = intval($weekday1);
         for ($i=1; $i<=$nofdays; $i=$i+1) 
         {
            if ($x>=6 AND $x<=7) 
            {
               if (isset($_POST['recurring_only_business'])) 
               {
                  if ( $H->findBySymbol($M->template[$i-1]) ) 
                  {
                     if ( $H->cfgname=='busi' OR $H->checkOptions($CONF['H_BUSINESSDAY']) ) $requested[$i]=$_POST['recurrabs'];
                  }
               }
               else 
               {
                  $requested[$i]=$_POST['recurrabs'];
               }
            }
            if($x<=6) $x++; else $x=1;
         }
      }

   }

   /**
    * We have the current absences in $T
    * We have the new absences in $requested
    * We will create an array for all accepted absences: $accepted
    * We will create an array for all unapproved absences: $unapproved
    * Now we have to check each requested absence (removed or added) by comparison.
    * If rejected, we will write the absence from $T into $accepted
    * If accepted, we will write the absence from $requested into $accepted
    *
    * Let's assume all is good. Set the corresponding flag
    */
   $declined=FALSE;
   $errorarray = array();
   $usergroups = $UG->getAllforUser($caluser);

   /**
    * Check whether $T and $requested differ in any way.
    * Otherwise we can save us the trouble of the one by one comparison.
    * Once we are at it, fill the default accepted array with what is in $T
    * Also, use this loop to initiate the unapproved array
    */
   $accepted = array();
   $difference=FALSE;
   $allChangesInPast=TRUE;
   $todayDate=date("Ymd", time());
   for ($i=1; $i<=$nofdays; $i++) 
   {
      $prop='abs'.$i;
      if ($T->$prop!=$requested[$i]) 
      {
         /**
          * We have a difference
          */
         $difference=TRUE;
         /**
          * Check whether at least one change is not in the past. We need that
          * info later for not sending notification mails if all is in the past.
          */
         $iDate = intval($Year.$monthno.sprintf("%02d",$i));
         if ($iDate>=$todayDate) $allChangesInPast=FALSE;
      }
      $accepted[$i]=$requested[$i];
      $unapproved[$i]='0';
      //print $i.': '.$T->$prop.'-'.$requested[$i].'<br>';
   } 

   
   /**
    * Only go through this if the new template is different from the old
    * and if the user requesting this is not the Admin
    */
   if ($difference AND !$isAdmin) 
   {
      /**
       * Create an array that will hold only groups affected by a declination.
       * This array is used later to send emails to only the affected managers.
       */
      $affectedgroups=array();
      /**
       * Loop through each day for the comparison
       */
      for ($i=1; $i<=$nofdays; $i++) 
      {
         $prop='abs'.$i;
         /**
          * See if there was a change requested for this day
          */
         if ($T->$prop!=$requested[$i]) 
         {
            /**
             * ABSENCE THRESHOLD
             */
            if ( $C->readConfig("declAbsence") AND !isAllowed("editAllUserCalendars") ) 
            {
               if ($C->readConfig("declBase")=="group") 
               {
                  /**
                   * There is a declination threshold for groups.
                   * We have to go through each group of this user and see
                   * wether the threshold would be violated by this request.
                   */
                  $groups = "";
                  foreach ($usergroups as $row) 
                  {
                     if (declineThresholdReached($Year,$monthno,$i+1,"group",$row['groupname'])) 
                     {
                        /**
                         * Only decline and add the affected group if user 
                         * - is not allowed to edit group calendars
                         * - OR is allowed but is neither member nor manager
                         */
                        if ( !isAllowed("editGroupUserCalendars") 
                             OR
                             $C->readConfig("declApplyToAll")
                             OR
                             (!$UG->isGroupManagerOfGroup($luser,$row['groupname']) AND !$UG->isMemberOfGroup($luser,$row['groupname']))
                           )
                        {
                           $affectedgroups[] = $row['groupname'];
                           $groups .= $row['groupname'].", ";
                        }
                     }
                  }
                  
                  if (strlen($groups)) 
                  {
                     /**
                      * Absence threshold for on or more groups is reached. Absence cannot be set.
                      */
                     $declined=TRUE;
                     $groups = substr($groups,0,strlen($groups)-2);
                     $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_group_threshold'].$groups;
                     $unapproved[$i]=$requested[$i];
                     $accepted[$i]=$T->$prop;
                  }
               }
               else 
               {
                  if (declineThresholdReached($Year,$monthno,$ixx,"all")) {
                     /**
                      * Absence threshold for all is reached. Absence cannot be set.
                      */
                     $declined=TRUE;
                     $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_total_threshold'];
                     $unapproved[$i]=$requested[$i];
                     $accepted[$i]=$T->$prop;
                  }
               }
            }

            /**
             * MIN_PRESENT or MAX_ABSENT
             */
            if ($T->$prop=='0') 
            {
               $groups_min = "";
               $groups_max = "";
               foreach ($usergroups as $row) 
               {
                  $G->findByName($row['groupname']);
                  if ($G->checkOptions($CONF['G_MIN_PRESENT'])) 
                  {
                     if (declineThresholdReached($Year,$monthno,$i,"min_present",$row['groupname']) AND !isAllowed("editAllUserCalendars")) 
                     {
                        /**
                         * Only decline and add the affected group if user 
                         * - is not allowed to edit group calendars
                         * - OR is allowed but is neither member nor manager
                         */
                        if ( !isAllowed("editGroupUserCalendars") 
                             OR
                             $C->readConfig("declApplyToAll")
                             OR 
                             (!$UG->isGroupManagerOfGroup($luser,$row['groupname']) AND !$UG->isMemberOfGroup($luser,$row['groupname']))
                           )
                        {
                           $affectedgroups[] = $row['groupname'];
                           $groups_min .= $row['groupname'].", ";
                        }
                     }
                  }
                  if ($G->checkOptions($CONF['G_MAX_ABSENT'])) 
                  {
                     if (declineThresholdReached($Year,$monthno,$i,"max_absent",$row['groupname']) AND !isAllowed("editAllUserCalendars")) 
                     {
                        /**
                         * Only decline and add the affected group if user 
                         * - is not allowed to edit group calendars
                         * - OR is allowed but is neither member nor manager
                         */
                        if ( !isAllowed("editGroupUserCalendars") 
                             OR
                             $C->readConfig("declApplyToAll")
                             OR 
                             (!$UG->isGroupManagerOfGroup($luser,$row['groupname']) AND !$UG->isMemberOfGroup($luser,$row['groupname']))
                           )
                        {
                           $affectedgroups[] = $row['groupname'];
                           $groups_max .= $row['groupname'].", ";
                        }
                     }
                  }
               }
               
               if (strlen($groups_min)) 
               {
                  /**
                   * Minimum presence of one or more groups is not given anymore. Absence cannot be set.
                   */
                  $declined=TRUE;
                  $groups_min = substr($groups_min,0,strlen($groups_min)-2);
                  $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_min_present'].$groups_min;
                  $unapproved[$i]=$requested[$i];
                  $accepted[$i]=$T->$prop;
               }
               
               if (strlen($groups_max)) 
               {
                  /**
                   * Maximum absence of one or more groups is reached. Absence cannot be set.
                   */
                  $declined=TRUE;
                  $groups_max = substr($groups_max,0,strlen($groups_max)-2);
                  $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_max_absent'].$groups_max;
                  $unapproved[$i]=$requested[$i];
                  $accepted[$i]=$T->$prop;
               }
            }

            /**
             * DECLINE BEFORE
             */
            if ( $C->readConfig("declBefore")!="0" AND !isAllowed("editAllUserCalendars") ) 
            {
               $declineBefore = FALSE;
               $iDate = intval($Year.$monthno.sprintf("%02d",$i));
               $todayDate=date("Ymd", time());
               $yesterdayDate=date("Ymd", time()-86400);

               if ( $C->readConfig("declBefore")=="Today" ) $blockBeforeDate = intval($todayDate);
               else $blockBeforeDate = intval($C->readConfig("declBeforeDate"));
               
               if ( $iDate<$blockBeforeDate ) 
               {
                  foreach ($usergroups as $row) 
                  {
                     /**
                      * Only decline and add the affected group if user 
                      * - is not allowed to edit group calendars
                      * - OR is allowed but is neither member nor manager
                      */
                     if ( !isAllowed("editGroupUserCalendars") 
                          OR
                          $C->readConfig("declApplyToAll")
                          OR 
                          (!$UG->isGroupManagerOfGroup($luser,$row['groupname']) AND !$UG->isMemberOfGroup($luser,$row['groupname']))
                        )
                     {
                        $affectedgroups[] = $row['groupname'];
                        $declineBefore = TRUE;
                     }
                  }
                  
                  if ($declineBefore) 
                  {
                     /**
                      * Absences before this date are not allowed. Absence cannot be set.
                      */
                     $declined=TRUE;
                     $dspDate = substr($blockBeforeDate,0,4)."-".substr($blockBeforeDate,4,2)."-".substr($blockBeforeDate,6,2);
                     $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_before'].$dspDate.".";
                     $unapproved[$i]=$requested[$i];
                     $accepted[$i]=$T->$prop;
                  }
               }
            }

            /**
             * DECLINATION PERIOD
             */
            if ( ($C->readConfig("declPeriod") OR $C->readConfig("declPeriod2") OR $C->readConfig("declPeriod3")) AND !isAllowed("editAllUserCalendars") ) 
            {
               $declinationPeriod = FALSE;
               $iDate = intval($Year.$monthno.sprintf("%02d",$i));
               $startDate = intval($C->readConfig("declPeriodStart"));
               $endDate = intval($C->readConfig("declPeriodEnd"));
               $startDate2 = intval($C->readConfig("declPeriod2Start"));
               $endDate2 = intval($C->readConfig("declPeriod2End"));
               $startDate3 = intval($C->readConfig("declPeriod3Start"));
               $endDate3 = intval($C->readConfig("declPeriod3End"));
                
               if ( ($iDate >= $startDate AND $iDate <= $endDate) OR ($iDate >= $startDate2 AND $iDate <= $endDate2) OR ($iDate >= $startDate3 AND $iDate <= $endDate3) ) 
               {
                  /**
                   * We are in a declination period
                   */
                  foreach ($usergroups as $row) 
                  {
                     /**
                      * Only decline and add the affected group if user 
                      * - is not allowed to edit group calendars
                      * - OR is allowed but is neither member nor manager
                      */
                     if ( !isAllowed("editGroupUserCalendars") 
                          OR
                          $C->readConfig("declApplyToAll")
                          OR 
                          (!$UG->isGroupManagerOfGroup($luser,$row['groupname']) AND !$UG->isMemberOfGroup($luser,$row['groupname']))
                        )
                     {
                        $affectedgroups[] = $row['groupname'];
                        $declinationPeriod = TRUE;
                     }
                  }
                  
                  if ($declinationPeriod) {
                     /**
                      * Absences is in declination period. Absence cannot be set.
                      */
                     $declined=TRUE;
                     if ( $iDate >= $startDate AND $iDate <= $endDate ) 
                     { 
                        $dspStartDate = substr($startDate,0,4)."-".substr($startDate,4,2)."-".substr($startDate,6,2);
                        $dspEndDate = substr($endDate,0,4)."-".substr($endDate,4,2)."-".substr($endDate,6,2);
                     }
                     else if ( $iDate >= $startDate2 AND $iDate <= $endDate2 ) 
                     { 
                        $dspStartDate = substr($startDate2,0,4)."-".substr($startDate2,4,2)."-".substr($startDate2,6,2);
                        $dspEndDate = substr($endDate2,0,4)."-".substr($endDate2,4,2)."-".substr($endDate2,6,2);
                     }
                     else if ( $iDate >= $startDate3 AND $iDate <= $endDate3 ) 
                     { 
                        $dspStartDate = substr($startDate3,0,4)."-".substr($startDate3,4,2)."-".substr($startDate3,6,2);
                        $dspEndDate = substr($endDate3,0,4)."-".substr($endDate3,4,2)."-".substr($endDate3,6,2);
                     }
                     $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_period'].$dspStartDate.$LANG['err_decl_and'].$dspEndDate.".";
                     $unapproved[$i]=$requested[$i];
                     $accepted[$i]=$T->$prop;
                  }
               }
            }

            /**
             * APPROVAL REQUIRED
             */
            $approvalRequired=FALSE;
            if ($T->$prop!='0' AND $A->getApprovalRequired($T->$prop) AND !isAllowed("editAllUserCalendars") ) 
            {
               /**
                * Only decline if user 
                * - is not allowed to edit group calendars
                * - OR is allowed but is neither member nor manager
                */
               if ( !isAllowed("editGroupUserCalendars") 
                    OR
                    $C->readConfig("declApplyToAll")
                    OR 
                    (!$UG->isGroupManagerOfGroup($luser,$row['groupname']) AND !$UG->isMemberOfGroup($luser,$row['groupname']))
                  )
               {
                  $approvalRequired = TRUE;
               }
               
               if ($approvalRequired) 
               {
                  /**
                   * The old absence type requires approval and cannot be changed
                   */
                  $declined=TRUE;
                  foreach ($usergroups as $row) 
                  {
                     $affectedgroups[] = $row['groupname'];
                  }
                  $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_old_abs'].$A->getName($T->$prop).$LANG['err_decl_approval'];
                  $unapproved[$i]=$requested[$i];
                  $accepted[$i]=$T->$prop;
               }
            }
            
            $approvalRequired=FALSE;
            if ($requested[$i]!='0' AND $A->getApprovalRequired($requested[$i]) AND !isAllowed("editAllUserCalendars") ) 
            {
               /**
                * Only decline if user 
                * - is not allowed to edit group calendars
                * - OR is allowed but is neither member nor manager
                */
               if ( !isAllowed("editGroupUserCalendars") 
                    OR
                    $C->readConfig("declApplyToAll")
                    OR 
                    (!$UG->isGroupManagerOfGroup($luser,$row['groupname']) AND !$UG->isMemberOfGroup($luser,$row['groupname']))
                  )
               {
                  $approvalRequired = TRUE;
               }

               if ($approvalRequired) 
               {
                  /**
                   * The new absence type requires approval and cannot be set
                   */
                  $declined=TRUE;
                  foreach ($usergroups as $row) 
                  {
                     $affectedgroups[] = $row['groupname'];
                  }
                  $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_new_abs'].$A->getName($requested[$i]).$LANG['err_decl_approval'];
                  $unapproved[$i]=$requested[$i];
                  $accepted[$i]=$T->$prop;
               }
            }
            
            /**
             * MANAGEMENT ONLY
             */
            $managerOnly = FALSE;
            if ($T->$prop!='0' AND $A->getManagerOnly($T->$prop) AND !isAllowed("editAllUserCalendars") ) 
            {
               /**
                * There is a manager-only absence set on this day already and can only be unset by a manager. 
                * Decline if user is not Admin, Director or Manager
                */
               if ( !$isAdmin AND ! $isDirector AND !$isManager )
               {
                  $managerOnly = TRUE;
               }
                
               if ($managerOnly) 
               {
                  /**
                   * The old absence type is manager only and cannot be changed
                   */
                  $declined=TRUE;
                  foreach ($usergroups as $row) 
                  {
                     $affectedgroups[] = $row['groupname'];
                  }
                  $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_old_abs'].$A->getName($T->$prop).$LANG['err_decl_manager_only'];
                  $unapproved[$i]=$requested[$i];
                  $accepted[$i]=$T->$prop;
               }
            }
            
            $managerOnly = FALSE;
            if ($requested[$i]!='0' AND $A->getManagerOnly($requested[$i]) AND !isAllowed("editAllUserCalendars") ) 
            {
               /**
                * The new absence type is manager-only and can only be set by a manager. 
                * Decline if user is not Admin, Director or Manager
                */
               if ( !$isAdmin AND ! $isDirector AND !$isManager )
               {
                  $managerOnly = TRUE;
               }
            
               if ($managerOnly) 
               {
                  /**
                   * The new absence type is manager only and cannot be set
                   */
                  $declined=TRUE;
                  foreach ($usergroups as $row) {
                     $affectedgroups[] = $row['groupname'];
                  }
                  $errorarray[] = $T->year."-".$T->month."-".sprintf("%02d",($i)).$LANG['err_decl_new_abs'].$A->getName($requested[$i]).$LANG['err_decl_manager_only'];
                  $unapproved[$i]=$requested[$i];
                  $accepted[$i]=$T->$prop;
               }
            }

         } // if ($T->$prop!=$newtemplate[$i]) {

      } // Loop thru each day

   } // if ($difference AND !$isAdmin) {

   
   /*
    * TODO: Do something with the $unapproved array
    */
     
   
   /*
    * After this check we now have $T and the $accepted array
    * Let's see if there are differences
    */
   $difference=FALSE;
   for ($i=1; $i<=$nofdays; $i++) 
   {
      $prop='abs'.$i;
      if ($T->$prop!=$accepted[$i]) 
      {
         $difference=TRUE;
         break;
      }
   }

   if ($difference) 
   {
      /**
       * One or more absence changes are accpeted. Update $T.
       */
      if ( $UC->checkUserType($CONF['UTTEMPLATE']) ) 
      {
         /**
          * This is a template user. We must not overwrite his old template yet.
          * We have to go through the templates of all other users in the same
          * group first and adjust them accordingly.
          *
          */
         $query  = "SELECT groupname FROM `".$UG->table."` WHERE username='".$caluser."'";
         $result = $UG->db->db_query($query);
         
         while ($row=$UG->db->db_fetch_array($result,MYSQL_ASSOC) ) 
         {
            /**
             * Go through all users of the same group
             */
            $groupfilter = $row['groupname'];
            $query2 = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
                     " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].
                     " WHERE (".$CONF['db_table_users'].".username!='admin'" .
                     " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username".
                     " AND ".$CONF['db_table_users'].".username!='".$caluser."'".
                     " AND ".$CONF['db_table_user_group'].".groupname = '".$groupfilter."'" .
                     " AND ".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname".
                     " AND (".$CONF['db_table_groups'].".options&1)=0 )";
            $result2 = $UT->db->db_query($query2);
            
            while ( $row2 = $UT->db->db_fetch_array($result2,MYSQL_ASSOC) ) 
            {
               $UT->findByName($row2['username']);
               /**
                * Find the template for the current loop user, otherwise create a fresh one
                */
               if (!$rc=$TT->getTemplate($UT->username,$Year,$monthno)) 
               {
                  /**
                   * No template found for this template user. Create a default one.
                   */
                  $TT->username = $UT->username;
                  $TT->year = $Year;
                  $TT->month = $monthno;
                  for ($i=1; $i<=intval($nofdays); $i++ ) 
                  {
                     $prop='abs'.$i;      
                     $TT->$prop = 0;
                  }
                  $TT->create();
                  /**
                   * Log this event
                   */
                  $LOG->log("logUser",$luser,"log_cal_tplusr_def_tpl", $TT->username."|".$TT->year.$TT->month);
               }
               /**
                * Loop through each day and compare the templates. Set new value from template
                * based on rules.
                * $T = old absences of the template user
                * $accepted = new absences of the template user
                * $TT = absences of the current user in the loop
                *
                * Template User Old | Template User New | Current User | Action to Regular User
                * -----------------------------------------------------------------------------
                * absence x         | present           | absence x    | set present
                * present           | absence x         | present      | set absence x
                * absence x         | absence x         | present      | set absence x
                * absence x         | absence y         | present      | set absence y
                * absence x         | absence y         | absence x    | set absence y
                *
                */
               for ($it=1; $it<=intval($nofdays); $it++ ) 
               {
                  $prop='abs'.$it;      
                  if ( ($accepted[$it]=='0' AND $T->$prop==$TT->$prop) OR 
                       ($accepted[$it]!='0' AND $TT->$prop=='0') OR
                       ($accepted[$it]!='0' AND $T->$prop!='0' AND $accepted[$it]!=$T->$prop AND $TT->$prop==$T->$prop)
                     ) 
                  {
                     /* 
                      * Current user and template user match and new absence is present. OR
                      * Current user is present but new absence is absent. OR
                      * Current user and template user match. Old absence and new absence is not present and not the same. 
                      * => Set current user to new absence.
                      */
                     $TT->$prop = $accepted[$it];
                  }
               }
               /**
                * Now update the current user's template
                */
               $TT->update($UT->username,$Year,$monthno);
            }
         }
      }

      /**
       * Now we can finally updated the current users absences with the new accepted ones.
       * Send notification e-Mails.
       * Then log the event.
       */
      $mailtemplate="";
      $logtemplate="|";
      
      for ($i=1; $i<=$nofdays; $i++) 
      {
         $prop='abs'.$i;
         $T->$prop=$accepted[$i];
         $symbol=$A->getSymbol($T->$prop);
         $mailtemplate.=$symbol;
         $logtemplate.=$symbol."|";
      } 
      $T->update($caluser,$Year,$monthno);

      /**
       * Create an HTML table for the template
       */
      $ninfo = '<p>'.$LANG['notification_new_template'].$T->year.'-'.$T->month.'</p>';
      $ninfo .= '<table style="border-collapse: collapse;"><tbody><tr style="background-color: #DDDDDD;">';
      $j=1;
      for ($i=0; $i<strlen($mailtemplate); $i++)
      {
         $ninfo .= '<th style="border: 1px solid #999999; text-align: center;">'. sprintf("%02d",$j++).'</th>';
      }
      $ninfo .= '</tr><tr>';
       
      for ($i=0; $i<strlen($mailtemplate); $i++)
      {
         $ninfo .= '<td style="border: 1px solid #999999; text-align: center;">'. $mailtemplate[$i].'</td>';
      }
      $ninfo .= '</tr></tbody></table>';
       
      $ninfo .= '<p>';
      $ats = $A->getAll();
      foreach ($ats as $at)
      {
         $ninfo .= $at['symbol']." = ".$at['name']."<br />";
      }
      $ninfo .= '</p>';
      
      /**
       * Send out the mails
       */
      if ( !$allChangesInPast OR !$C->readConfig("emailNoPastNotifications") )
      { 
         $nobject = $UC->firstname." ".$UC->lastname;
         $ugroups = $UG->getAllforUser($caluser);
         foreach ($ugroups as $ugroup) 
         {
            $ntype = "usercalchange";
            $naffectedgroup = $ugroup['groupname'];
            sendNotification($ntype, $nobject, $naffectedgroup, $ninfo);
         }
      }

      /**
       * Log this event
       */
      $LOG->log("logUser",$luser,"log_cal_usr_tpl_chg", $caluser." ".$T->year.$T->month." ".$logtemplate);

   }

   if ($declined) 
   {
      /**
       * One or more absence requests have been declined.
       * Build javascript error message. Will be shown at bottom of page.
       */
      $error_decl = TRUE;
      $notificationerror = "";
      $errormessage = $LANG['err_decl_title'];
      $errormessage .= $LANG['err_decl_subtitle'];
      foreach($errorarray as $err) $errormessage .= $err."\\n";

      /**
       * Build notification message and send it to the appropriate receivers
       */
      if ($C->readConfig("emailNotifications")) 
      {
         $subject = $LANG['notification_subject'];
         $notification =$LANG['notification_greeting'];
         $notification.=$LANG['notification_decl_msg'];
         $notification.=$LANG['notification_decl_user'].$UC->firstname." ".$UC->lastname."\n";
               
         if (isset($_POST['txtReason']) AND $_POST['txtReason']!=$LANG['cal_reason_dummy']) 
         {
            $notification.=$LANG['notification_decl_reason'].strip_tags(trim($_POST['txtReason']))."\n\n";
         }
         
         $notification.=$LANG['notification_decl_msg_2'];

         foreach($errorarray as $err) $notificationerror .= $err."\n";
         
         $notification.=$notificationerror;
         $notification.=$LANG['notification_decl_sign'];

         /*
          * Send email to requesting user if configured so in Declination Management
          */
         if ( $C->readConfig("declNotifyUser") ) 
         {
            $to = $UL->email;
            sendEmail($to, $subject, $notification);
            /*
             * Set to TRUE for debug
             */
            if (FALSE) 
            {
               echo "<textarea cols=\"100\" rows=\"12\">To: ".$to."\n\n".
                    "Subject: ".stripslashes($subject)."\n\n".
                    stripslashes($notification)."</textarea>";
            }
         }

         /*
          * Send email to group manager of requesting user if configured so in Declination Management
          */
         if ( $C->readConfig("declNotifyManager") ) 
         {
            $affgroups = array_unique($affectedgroups); 
            foreach($affgroups as $grp) 
            {
               $query  = "SELECT DISTINCT ".$U->table.".email FROM ".$U->table.",".$UG->table." " .
                         "WHERE ".$U->table.".username=".$UG->table.".username " .
                         "AND ".$UG->table.".groupname='".trim($grp)."' " .
                         "AND ".$UG->table.".type='manager'";
               $result = $UG->db->db_query($query);
               
               while ($row=$UG->db->db_fetch_array($result,MYSQL_NUM) ) 
               {
                  $to = $row[0];
                  sendEmail($to, $subject, $notification);
                  /*
                   * Set to TRUE for debug
                   */
                  if (FALSE) 
                  {
                     echo "<textarea cols=\"100\" rows=\"12\">To: ".$to."\n\n".
                          "Subject: ".stripslashes($subject)."\n\n".
                          stripslashes($notification)."</textarea>";
                  }
               }
            }
         }

         /*
          * Send email to director if configured so in Declination Management
          */
         if ( $C->readConfig("declNotifyDirector") ) 
         {
            $query  = "SELECT username FROM `".$U->table."`";
            $result = $U->db->db_query($query);
            
            while ($row=$U->db->db_fetch_array($result,MYSQL_NUM) ) 
            {
               $U->findByName($row[0]);
               if ($U->checkUserType($CONF['UTDIRECTOR'])) 
               {
                  $to = $U->email;
                  sendEmail($to, $subject, $notification);
                  /*
                   * Set to TRUE for debug
                   */
                  if (FALSE) 
                  {
                     echo "<textarea cols=\"100\" rows=\"12\">To: ".$to."\n\n".
                          "Subject: ".$subject."\n\n".
                          $notification."</textarea>";
                  }
               }
            }
         }

         /*
          * Send email to admin if configured so in Declination Management
          */
         if ( $C->readConfig("declNotifyAdmin") ) 
         {
            $users = $U->getAll();
            foreach ($users as $u) 
            {
               $U->findByName($u['username']);
               
               if ($U->checkUserType($CONF['UTADMIN'])) 
               {
                  $to = $U->email;
                  sendEmail($to, $subject, $notification);
                  /*
                   * Set to TRUE for debug
                   */
                  if (FALSE) 
                  {
                     echo "<textarea cols=\"100\" rows=\"12\">To: ".$to."\n\n".
                          "Subject: ".$subject."\n\n".
                          $notification."</textarea>";
                  }
               }
            }
         }
      }

      /*
       * Log this event
       */
      $LOG->log("logUser",$luser,"log_cal_declined", $caluser."\n".$notificationerror);
   }
}

/**
 * =========================================================================
 * CLEAR
 */
else if (isset($_POST['btn_clear'])) 
{
   /**
    * Reset absences to present for this month
    */
   $mailtemplate='';
   for ($i=1; $i<=intval($nofdays); $i++ ) 
   {
      $prop='abs'.$i;
      $T->$prop = 0;
      $mailtemplate.='0';
   }
   
   $T->update($caluser,$Year,$monthno);
   $query  = "SELECT groupname FROM `".$UG->table."` WHERE username='".$caluser."'";
   $result = $UG->db->db_query($query);
   while ($row=$UG->db->db_fetch_array($result,MYSQL_NUM) ) 
   {
      sendNotification("usercalchange",$U->firstname." ".$U->lastname, $row[0],$T->year.$T->month." ".$mailtemplate);
   }
   /**
    * Log this event
    */
   $LOG->log("logUser",$luser,"log_cal_usr_tpl_clr", $caluser." ".$T->year.$T->month." ".$mailtemplate);
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_editcalendar'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'User+Calendar';
}
$currlang = $CONF['options']['lang'];
require("includes/header_html_inc.php");
$CONF['options']['lang']=$currlang;
?>
<div id="content">
   <div id="content-content">
      <form  name="monthform" method="POST" action="<?=$_SERVER['PHP_SELF']."?Year=".$Year."&amp;Month=".$Month."&amp;Member=".$caluser."&amp;region=".$region?>">
      <table class="dlg">
         <tr>
            <td class="dlg-header">
               <?php printDialogTop($LANG['member_edit']." ".$UC->firstname." ".$UC->lastname." (".$LANG['month_region'].": ".$region.")", $help, "ico_calendar.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body"><br>
               
               <!-- CALENDAR -->
               <table class="month">
               
                  <!-- Day of month row -->
                  <tr>
                     <td class="month"><?=$LANG['monthnames'][intval($monthno)]."&nbsp;".trim($Year)?></td>
                     <td class="month-button">&nbsp;</td>
                     <?php for ($i=1; $i<=$nofdays; $i=$i+1) {
                        if ( $H->findBySymbol($M->template[$i-1]) ) {
                           if ( $H->cfgname=='busi' ) {
                              /**
                               * A holiday but business day => business day color
                               */
                              echo "<td class=\"daynum\">".$i."</td>";
                           }
                           else {
                              /**
                               * A holiday and not business day => holiday color
                               */
                              echo "<td class=\"daynum-".$H->cfgname."\">".$i."</td>";
                           }
                        }
                        else {
                           echo "<td class=\"daynum\">".$i."</td>";
                        }
                     }

                     $x = intval($weekday1);
                     ?>
                  </tr>
                  
                  <!-- Weekday row -->
                  <tr>
                     <td class="title" style="font-size: 8pt;">
                        <input title="<?=$LANG['tt_page_bwd']?>" name="btn_bwd" type="submit" class="button" value="&lt;&lt;">
                        <input title="<?=$LANG['tt_page_fwd']?>" name="btn_fwd" type="submit" class="button" value="&gt;&gt;">
                        <input type="hidden" name="hid_fwdMonth" value="<?=$fwdMonth?>">
                        <input type="hidden" name="hid_fwdYear" value="<?=$fwdYear?>">
                        <input type="hidden" name="hid_bwdMonth" value="<?=$bwdMonth?>">
                        <input type="hidden" name="hid_bwdYear" value="<?=$bwdYear?>">
                        <input type="hidden" name="hid_Member" value="<?=$caluser?>">
                     </td>
                     <td class="title-button">&nbsp;</td>
                     <?php
                     for ($i=1; $i<=$nofdays; $i=$i+1) {
                        if ( $H->findBySymbol($M->template[$i-1]) ) {
                           if ( $H->cfgname=='busi' ) {
                              /**
                               * A holiday but business day => business day color
                               */
                              echo "<td class=\"weekday\">".$weekdays[$x]."</td>";
                           }
                           else {
                              /**
                               * A holiday and not business day => holiday color
                               */
                              echo "<td class=\"weekday-".$H->cfgname."\">".$weekdays[$x]."</td>";
                           }
                        }
                        else {
                           echo "<td class=\"weekday\">".$weekdays[$x]."</td>";
                        }
                        if($x<=6) $x+=1; else $x = 1;
                     }
                     ?>
                  </tr>
                  
                  <!-- Week number row -->
                  <?php if (intval($C->readConfig("showWeekNumbers"))) 
                  { ?>
                  <tr>
                     <td class="title"><?=$LANG['cal_caption_weeknumber']?></td>
                     <td class="title-button">&nbsp;</td>
                     <?php
                     $mytime = $Month." 1,".$Year;
                     $myts = strtotime($mytime);
                     $mydate = getdate($myts);   // Get first weekday of the current month
                     $wd = intval($weekday1);
                     $colspan=0;
                     $firstDayOfWeeknumber = intval($C->readConfig("firstDayOfWeek"));
                     if ($firstDayOfWeeknumber<1 || $firstDayOfWeeknumber>7) $firstDayOfWeeknumber = 1;
                     $lastDayOfWeeknumber = $firstDayOfWeeknumber-1;
                     if ($lastDayOfWeeknumber==0) $lastDayOfWeeknumber = 7;
                     
                     for ($i=1; $i<=$nofdays; $i=$i+1) 
                     {
                        if ($wd != $lastDayOfWeeknumber) 
                        {
                           $colspan++;
                           $wd++;
                           if ($wd==8) $wd = 1;
                        }
                        else 
                        {
                           $colspan++;
                           $w=date("W",mktime(0,0,0,intval($mydate['mon']),$i,$Year));
                           echo "<td class=\"weeknumber\" colspan=\"".$colspan."\">".sprintf("%d",$w)."</td>\n\r";
                           $colspan=0;
                           $wd++;
                           if ($wd==8) $wd = 1;
                        }
                     }
                     $w=date("W",mktime(0,0,0,intval($mydate['mon']),$i,$Year));
                     if ($colspan>0) echo "<td class=\"weeknumber\" colspan=\"".$colspan."\">".sprintf("%d",$w)."</td>\n\r";
                     ?>
                  </tr>
                  <?php } ?>
                  
                  <!-- Global daynote row -->
                  <tr>
                     <td class="title"><?=$LANG['month_global_daynote']?></td>
                     <td class="title-button">&nbsp;</td>
                     <?php
                     /**
                      * Daynote columns
                      */
                     $x = intval($weekday1);
                     for ($i = 1; $i <= $nofdays; $i = $i +1) 
                     {
                        if ($i < 10)
                           $dd = "0" . strval($i);
                        else
                           $dd = strval($i);

                        $ttid = 'td-'.$i;
                        
                        if ($N->findByDay($Year . $monthno . $dd, "all", $region))
                        {
                           $ttbody=$N->daynote;
                           $ttcaption = $LANG['tt_title_dayinfo'];
                           $ttcapicon = 'themes/'.$theme.'/img/ico_daynote.png';
                           
                           if ($H->findBySymbol($M->template[$i -1]))
                           {
                              if ($H->cfgname == 'busi')
                                 $style = "weekday-note";
                              else
                                 $style = "weekday-" . $H->cfgname . "-note";
                           }
                           else
                           {
                              $style = "weekday-note";
                           }
                           
                           echo '<td class="'.$style.'" id="'.$ttid.'">';
                           if (isAllowed("editGlobalDaynotes"))
                           {
                              echo "<a href=\"javascript:this.blur(); openPopup('daynote.php?date=$Year$monthno$dd&amp;daynotefor=all&amp;region=$region&amp;datestring=$dd.%20".$LANG['monthnames'][intval($monthno)]."%20$Year','daynote','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=600,height=340');\"><img src=\"themes/".$theme."/img/ico_daynote.png\" alt=\"\" title=\"".$LANG['month_daynote_tooltip']."\" border=\"0\"></a>";
                           }
                           echo createPopup($ttid, $ttbody, $ttcaption, $ttcapicon);
                           echo "</td>\n";
                        }
                        else
                        {
                           if ($H->findBySymbol($M->template[$i -1]))
                           {
                              if ($H->cfgname == 'busi')
                                 $style = "weekday";
                              else
                                 $style = "weekday-" . $H->cfgname;
                           }
                           else
                           {
                              $style = "weekday";
                           }
                           
                           echo '<td class="'.$style.'" id="'.$ttid.'">';
                           if (isAllowed("editGlobalDaynotes"))
                           {
                              echo "<a href=\"javascript:this.blur(); openPopup('daynote.php?date=$Year$monthno$dd&amp;daynotefor=all&amp;region=$region&amp;datestring=$dd.%20".$LANG['monthnames'][intval($monthno)]."%20$Year','daynote','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=600,height=340');\"><img src=\"themes/".$theme."/img/ico_daynote.png\" alt=\"\" title=\"".$LANG['month_daynote_tooltip']."\" border=\"0\"></a>";
                           }
                           echo "</td>\n";
                        }
                        
                        if ($x <= 6) $x += 1;
                        else         $x  = 1;
                     }
                     ?>
                  </tr>
                  
                  <!-- Personal daynote row -->
                  <?php 
                  if ( !intval($C->readConfig("hideDaynotes")) 
                        OR ( $UL->checkUserType($CONF['UTADMIN']) 
                             OR $UL->checkUserType($CONF['UTDIRECTOR'])
                             OR $UL->checkUserType($CONF['UTMANAGER'])
                           )
                     ) 
                  {
                     $x = intval($weekday1); ?>
                     <tr>
                        <td class="title"><?=$LANG['month_personal_daynote']?></td>
                        <td class="title-button">&nbsp;</td>
                     <?php for ($i=1; $i<=$nofdays; $i=$i+1) {
                        if ($i<10) $dd="0".strval($i); else $dd=strval($i);
                        if ( $H->findBySymbol($M->template[$i-1]) ) {
                           if ( $H->cfgname=='busi' ) {
                              if ( $N->findByDay($Year.$monthno.$dd,$caluser) ) $style="weekday-note"; else $style="weekday";
                           } else {
                              if ( $N->findByDay($Year.$monthno.$dd,$caluser) ) $style="weekday-".$H->cfgname."-note"; else $style="weekday-".$H->cfgname;
                           }
                        } else {
                           if ( $N->findByDay($Year.$monthno.$dd,$caluser) ) $style="weekday-note"; else $style="weekday";
                        } ?>
                        <td class="<?=$style?>">
                          <a href="javascript:this.blur();openPopup('daynote.php?date=<?=$Year.$monthno.$dd?>&amp;daynotefor=<?=$caluser?>&amp;region=default&amp;datestring=<?=$dd?>%20<?=$LANG['monthnames'][intval($monthno)]?>%20<?=$Year?>','daynote','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=600,height=340');">
                             <img src="themes/<?=$theme?>/img/ico_daynote.png" alt="" title="<?=$LANG['month_daynote_tooltip']?>" border="0">
                          </a>
                        </td>
                        <?php if($x<=6) $x+=1; else $x = 1;
                     } ?>
                     </tr>
                  <?php } ?>

                  <!-- Current absence row -->
                  <tr>
                     <td class="title"><?=$LANG['month_current_absence']?></td>
                     <td class="title-button">&nbsp;</td>
                     <?php for($idx=1; $idx<=strlen($M->template); $idx++) {
                        $prop='abs'.$idx;
                        $inner="&nbsp;";
                        if ( $A->get($T->$prop) ) 
                        {
                           if ($A->bgtransparent)
                           {
                              if ( $H->findBySymbol($M->template[$idx-1]) ) $class="day-".$H->cfgname;
                              else $class="day";
                           }
                           else 
                           {
                              $class="day-a".$A->id;
                           }
                           if ($A->icon!='No')
                           {
                              $inner = "<img align=\"top\" alt=\"\" src=\"".$CONF['app_icon_dir'].$A->icon."\" width=\"16\" height=\"16\">";
                           }
                           else
                           {   
                              $inner = $A->symbol;
                           }
                        }
                        else 
                        {
                           if ( $H->findBySymbol($M->template[$idx-1]) ) 
                           {
                              $class="day-".$H->cfgname;
                           }
                           else 
                           {
                              $class="day";
                           }
                        } ?>
                        <td class="<?=$class?>"><?=$inner?></td>
                     <?php } ?>
                  </tr>
                  
                  <?php 
                  /**
                   * Absence type loop
                   */
                  $absences = $A->getAll();
                  foreach ($absences as $abs) 
                  {
                     /**
                      * Make sure this absence type is allowed for this group
                      */
                     $showthisabsence=false;
                     $groups = $G->getAll();
                     foreach ($groups as $Grow) 
                     {
                        if ( $UG->isMemberOfGroup($caluser,$Grow['groupname']) AND $AG->isAssigned($abs['id'],$Grow['groupname']) ) 
                        {
                           if ($abs['manager_only'] AND intval($C->readConfig("hideManagerOnlyAbsences")))
                           {
                              if ( $UL->checkUserType($CONF['UTMANAGER']) OR $UL->checkUserType($CONF['UTADMIN']))
                              {
                                 $showthisabsence=true;
                              }
                           }
                           else
                           {    
                              $showthisabsence=true;
                           }
                        }
                     }

                     /**
                      * Create an icon to show certain absence type properties
                      */
                     $absicon = '&nbsp;';
                     if ($abs['approval_required'] OR $abs['manager_only'] )
                     {
                        $info = '';
                        if ($abs['approval_required'] OR $abs['manager_only'] )
                        {
                           $info .= $LANG['abs_info_approval_required'];
                        }
                        if ($abs['manager_only'])
                        {
                           $info .= $LANG['abs_info_manager_only'];
                        }
                        $absicon = '<img align="top" alt="" src="'.$CONF['app_icon_dir'].'info.png" title="'.$info.'" width="16" height="16">';
                     }
                      
                     /**
                      * Show the absence row
                      */
                     if ($showthisabsence) { ?>
                        <tr>
                           <td class="name"><?=str_replace(" ","&nbsp;",$abs['name'])?></td>
                           <td class="name-button"><?=$absicon?></td>
                        <?php
                        /**
                         * Show a line for this absence type covering each day of the month
                         */
                        for($idx=1; $idx<=strlen($M->template); $idx++) {
                           $prop='abs'.$idx;
                           if ( $A->get($T->$prop) AND !$A->bgtransparent) { 
                              $class="day-a".$A->id;
                           }
                           else {
                              if ( $H->findBySymbol($M->template[$idx-1]) ) $class="day-".$H->cfgname;
                              else $class="day";
                           }
                           if ($T->$prop==$abs['id']) $checked="checked"; else $checked=''; 
                           ?>
                           <td class="<?=$class?>"><input name="opt_abs_<?=$idx?>" type="radio" value="<?=$abs['id']?>" <?=$checked?>></td>
                        <?php } ?>
                        </tr>
                     <?php }
                  }
                  ?>
                  
                  <?php 
                  /**
                   * Clear Absence row
                   */
                  ?>
                  <tr>
                     <td class="title"><?=$LANG['cal_clear_absence']?></td>
                     <td class="title-button">&nbsp;</td>
                  <?php
                  /**
                   * Show a line for this absence type covering each day of the month
                   */
                  for($idx=1; $idx<=strlen($M->template); $idx++) { ?>
                     <td class="weekday"><input name="opt_abs_<?=$idx?>" type="radio" value="0"></td>
                  <?php } ?>
                  </tr>
                  
               </table>
               
               <!-- RANGE INPUTS -->
               <div style="float: left; width: 48%; padding: 8px 8px 8px 0;<?=($C->readConfig('showRangeInput'))?"":" display: none;";?>">
                  <fieldset><legend><?=$LANG['cal_range_title']?></legend>
   			         <table>
   			            <tr>
   			               <td><?=$LANG['cal_range_type']?></td>
   			               <td><?=$LANG['cal_range_from']?></td>
   				           <td><?=$LANG['cal_range_to']?></td>
   				           <td>&nbsp;</td>
   				        </tr>
   				        <tr>
                           <td style="padding-right: 4px;">
                              <select name="rangeabs" id="rangeabs" class="select">
                              <?php
                              foreach ($absences as $abs) {
                                 /**
                                  * Make sure this users calendar only contains those absence types
                                  * that his group(s) is(are) entitled for
                                  */
                                 $showthisabsence=false;
                                 $groups = $G->getAll();
                                 foreach ($groups as $Grow) {
                                    if ($UG->isMemberOfGroup($caluser,$Grow['groupname']) &&
                                        $AG->isAssigned($abs['id'],$Grow['groupname'])
                                       ) {
                                       $showthisabsence=true;
                                       if ($abs['manager_only']) {
                                          if ( ($UL->checkUserType($CONF['UTADMIN']) || $UL->checkUserType($CONF['UTDIRECTOR'])) || ($UL->checkUserType($CONF['UTMANAGER']) && $UG->isMemberOfGroup($UL->username,$Grow['groupname'])) ) {
                                             $showthisabsence=true;
                                          }
                                          else {
                                             $showthisabsence=false;
                                          }
                                       }
                                    }
                                 }
                                 if ($showthisabsence) { ?>
                                    <option class="option" value="<?=$abs['id']?>"><?=$abs['name']?></option>
                                 <?php }
                              }
                              ?>
                              </select>
                           </td>
                           <td style="padding-right: 4px;">
                              <script type="text/javascript">
                                 $(function() { $( "#rangefrom" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                                 $(function() { $( "#rangeto" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                              </script>
                              <input name="rangefrom" id="rangefrom" size="10" maxlength="10" type="text" class="text" value="">
                           </td>
                           <td style="padding-right: 4px;">
                              <input name="rangeto" id="rangeto" size="10" maxlength="10" type="text" class="text" value="">
                           </td>
                           <td>&nbsp;</td>
                        </tr>
                        <tr>
                           <td colspan="4" style="padding-bottom: 7px;"><input name="range_only_business" type="checkbox" id="range_only_business" value="range_only_business" checked="checked"><?=$LANG['cal_only_business']?></td>
                        </tr>
                     </table>
                  </fieldset>
               </div>
               
               <div style="float: left; width: 48%; padding: 8px 0 8px 0;<?=($C->readConfig('showRecurringInput'))?"":" display: none;";?>">
                  <fieldset><legend><?=$LANG['cal_recurring_title']?></legend>
                  <table>
                     <tr>
                        <td><input name="monday" type="checkbox" id="monday" value="monday"></td>
                        <td><?=$LANG['weekdays_long'][1]?></td>
                        <td><input name="thursday" type="checkbox" id="thursday" value="thursday"></td>
                        <td><?=$LANG['weekdays_long'][4]?></td>
                        <td><input name="sunday" type="checkbox" id="sunday" value="sunday"></td>
                        <td><?=$LANG['weekdays_long'][7]?></td>
                        <td style="padding-left: 10px; vertical-align: top;"><?=$LANG['cal_range_type']?></td>
                     </tr>
                     <tr>
                        <td><input name="tuesday" type="checkbox" id="tuesday" value="tuesday"></td>
                        <td><?=$LANG['weekdays_long'][2]?></td>
                        <td><input name="friday" type="checkbox" id="friday" value="friday"></td>
                        <td><?=$LANG['weekdays_long'][5]?></td>
                        <td><input name="workdays" type="checkbox" id="workdays" value="workdays"></td>
                        <td><?=$LANG['cal_recurring_workdays']?></td>
                        <td rowspan="2" style="padding-left: 10px; vertical-align: top;">
                           <select name="recurrabs" id="recurrabs" class="select">
                           <?php
                           foreach ($absences as $abs) {
                              /*
                               * Make sure this users calendar only contains those absence types
                               * that his group(s) is(are) entitled for
                               */
                              $showthisabsence=false;
                              $groups = $G->getAll();
                              foreach ($groups as $Grow) {
                                 if ($UG->isMemberOfGroup($caluser,$Grow['groupname']) &&
                                     $AG->isAssigned($abs['id'],$Grow['groupname'])
                                    ) {
                                    $showthisabsence=true;
                                    if ($abs['manager_only']) {
                                       if ( ($UL->checkUserType($CONF['UTADMIN']) || $UL->checkUserType($CONF['UTDIRECTOR'])) || ($UL->checkUserType($CONF['UTMANAGER']) && $UG->isMemberOfGroup($UL->username,$Grow['groupname'])) ) {
                                          $showthisabsence=true;
                                       }
                                       else {
                                          $showthisabsence=false;
                                       }
                                    }
                                    if ($abs['approval_required']) $approvalNeeded=true;
                                 }
                              }
                              if ($showthisabsence) { ?>
                                 <option class="option" value="<?=$abs['id']?>"><?=$abs['name']?></option>
                              <?php }
                           }
                           ?>
                           </select><br>
                           <input name="recurring_only_business" type="checkbox" id="recurring_only_business" value="rcurring_only_business" checked="checked"><?=$LANG['cal_only_business']?>
                        </td>
                     </tr>
                     <tr>
                        <td><input name="wednesday" type="checkbox" id="wednesday" value="wednesday"></td>
                        <td><?=$LANG['weekdays_long'][3]?></td>
                        <td><input name="saturday" type="checkbox" id="saturday" value="saturday"></td>
                        <td><?=$LANG['weekdays_long'][6]?></td>
                        <td><input name="weekend" type="checkbox" id="weekend" value="weekend"></td>
                        <td><?=$LANG['cal_recurring_weekend']?></td>
                     </tr>
                  </table>
                  </fieldset>
               </div>
               
               <div style="clear: both; width: 97%; padding: 4px 0 8px 0;<?=($C->readConfig('showCommentReason'))?"":" display: none;";?>">
                  <fieldset><legend><?=$LANG['cal_reason_title']?></legend>
                     <input class="text" name="txtReason" id="txtReason" type="text" size="130" maxlength="130" value="<?=$LANG['cal_reason_dummy']?>">
                  </fieldset>
               </div>
               
               
            </td>
         </tr>
         <tr>
            <td class="dlg-menu">
               <input name="btn_clear" type="submit" class="button" value="<?=$LANG['btn_clear']?>" onclick="return confirmSubmit('<?=$LANG['cal_clear_confirm']?>')">
               <input name="btn_apply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">
               <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
               <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
               <input name="btn_done"  type="button" class="button" onclick="javascript:closeme();" value="<?=$LANG['btn_done']?>">
            </td>
         </tr>
      </table>
      </form>
   </div>
</div>
<?php
//
// Show javascript error message to user if there is one
//
if ($error_decl) echo "<script type=\"text/javascript\">alert(\"".$errormessage."\");</script>";
require( "includes/footer_inc.php" );
?>
