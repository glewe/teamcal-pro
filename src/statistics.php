<?php
/**
 * statistics.php
 *
 * Displays and runs the statistics page
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
require_once( "models/allowance_model.php" );
require_once( "models/config_model.php" );
require_once( "models/group_model.php" );
require_once( "models/login_model.php" );
require_once( "models/month_model.php" );
require_once( "models/region_model.php" );
require_once( "models/statistic_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );

$A = new Absence_model;
$B = new Allowance_model;
$C = new Config_model;
$G = new Group_model;
$L = new Login_model;
$M = new Month_model;
$M2 = new Month_model;
$R = new Region_model;
$ST = new Statistic_model;
$T = new Template_model;
$U = new User_model;
$U1 = new User_model;
$UL = new User_model;
$UG = new User_group_model;
$error=FALSE;

/**
 * Check if allowed
 */
if (!isAllowed("viewStatistics")) showError("notallowed");

/**
 * Set diagram size
 */
$diagramwidth=550;
$barareawidth=450;

/**
 * Get current user
 */
$user=$L->checkLogin();
$UL->findByName($user);

/**
 * Get Today
 */
$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today      = getdate();
$daytoday   = sprintf("%02d",$today['mday']);   // Numeric representation of todays' day of the month
$monthtoday = sprintf("%02d",$today['mon']);    // Numeric representation of todays' month
$yeartoday  = $today['year'];                   // A full numeric representation of todays' year, 4 digits
$nofdays    = sprintf("%02d",date("t",time()));

/**
 * Defaults
 */
$periodType = "standard";
$periodFrom = $yeartoday.$monthtoday."01";
$periodTo = $yeartoday.$monthtoday.$nofdays;
$statregion = "default";
$statgroup = "%";
$periodAbsence = "All";
$periodAbsenceName = "All";

/**
 * =========================================================================
 * APPLY
 */
if (isset($_POST['btn_apply'])) 
{
   if (isset($_POST['optPeriod']) AND $_POST['optPeriod']=="standard") 
   {
      /*
       * Standard period was selected
       */
      $periodType = "standard";
      switch ( $_POST['period'] ) {
         case "curr_month":
            $periodFrom = $yeartoday.$monthtoday."01";
            $periodTo = $yeartoday.$monthtoday.$nofdays;
            break;
         case "curr_quarter":
            switch ($monthtoday) {
               case 1:
               case 2:
               case 3:
                  $periodFrom = $yeartoday."0101";
                  $periodTo = $yeartoday."0331";
                  break;
               case 4:
               case 5:
               case 6:
                  $periodFrom = $yeartoday."0401";
                  $periodTo = $yeartoday."0630";
                  break;
               case 7:
               case 8:
               case 9:
                  $periodFrom = $yeartoday."0701";
                  $periodTo = $yeartoday."0930";
                  break;
               case 10:
               case 11:
               case 12:
                  $periodFrom = $yeartoday."1001";
                  $periodTo = $yeartoday."1231";
                  break;
            }
            break;
         case "curr_half":
            switch ($monthtoday) {
               case 1:
               case 2:
               case 3:
               case 4:
               case 5:
               case 6:
                  $periodFrom = $yeartoday."0101";
                  $periodTo = $yeartoday."0630";
                  break;
               case 7:
               case 8:
               case 9:
               case 10:
               case 11:
               case 12:
                  $periodFrom = $yeartoday."0701";
                  $periodTo = $yeartoday."1231";
                  break;
            }
            break;
         case "curr_year":
            $periodFrom = $yeartoday."0101";
            $periodTo = $yeartoday."1231";
            break;
         case "curr_period":
            $periodFrom = str_replace("-","",$C->readConfig("defperiodfrom"));
            $periodTo = str_replace("-","",$C->readConfig("defperiodto"));
            break;
      }
   }
   else {
      /**
       * Custom period was selected
       */
      $periodType = "custom";
      $periodFrom = str_replace("-","",$_POST['rangefrom']);
      $periodTo = str_replace("-","",$_POST['rangeto']);
   }

   /**
    * Get region, group and absence
    */
   if ( isset($_POST['statregion']) AND !empty($_POST['statregion'])) $statregion = $_POST['statregion']; else $statregion = 'default';
   if ( isset($_POST['periodgroup']) AND !empty($_POST['periodgroup'])) $statgroup = $_POST['periodgroup']; else $statgroup="%";
   if ( $statgroup =="All") $statgroup="%";
   $periodAbsence = $_POST['periodabsence'];
   $A->get($periodAbsence);
   $periodAbsenceName = $A->name;
}

/**
 * Make sure we have month templates for all years in the desired period.
 */
for ($y=intval(substr($periodFrom,0,4)); $y<=intval(substr($periodTo,0,4)); $y++) {
   for ($m=1; $m<=12; $m++) {
      $find=strval($y).sprintf("%02d",$m);
      if (!$M->findByName($statregion,$find)) {
         $M2->yearmonth = $find;
         $M2->region = $statregion;
         $mname = $LANG['monthnames'][$m];
         $M2->template = createMonthTemplate(substr($find,0,4), $mname);
         $M2->create();
      }
   }
}

if ($C->readConfig("presenceBase")=="calendardays") 
{
   /**
    * Count the calendar days in period
    */
   $daysInPeriod = $periodTo-$periodFrom+1;
}
else 
{
   /**
    * Count the business days in period
    */
   $daysInPeriod = countBusinessDays($periodFrom, $periodTo, $statregion);
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_statistics'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Global+Statistics';
}
require( "includes/header_html_inc.php" );
require( "includes/header_app_inc.php" );
require( "includes/menu_inc.php" );
?>
<div id="content">
   <div id="content-content">
      <table class="dlg">
         <tr>
            <td class="dlg-header">
               <?php printDialogTop($LANG['stat_title'], $help, "ico_statistics.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body">
               <div align="center">
   
                  <!-- TOTAL ABSENCE USER-->
                  <?php
                  $legend=array();
                  $value=array();
                  
                  if ($statgroup=="%") $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;All)";
                  else $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;".$statgroup.")";
                  
                  if ($periodAbsence=="All") $forabsence = "&nbsp;(".$LANG['stat_absence'].":&nbsp;All)";
                  else $forabsence = "&nbsp;(".$LANG['stat_absence'].":&nbsp;".$periodAbsenceName.")";
                  
                  echo "<fieldset style=\"text-align: left; width: 96%;\"><legend>".$LANG['stat_results_total_absence_user'].$periodFrom."-".$periodTo.$forgroup.$forabsence."</legend>";
                  echo "<table><tr><td style=\"vertical-align: top;\">\n\r";
                  echo "<table>\n\r";
                  
                  /**
                   * Get totals per user
                   */
                  $totaluser=0;
                  $useFactor=TRUE;
                  $countCombined=FALSE;
                  $groups = $G->getAllByGroup($statgroup);
                  foreach ($groups as $group) 
                  {
                     $G->findByName($group['groupname']);
                     if (!$G->checkOptions($CONF['G_HIDE']) ) 
                     {
                        $total=0;
                        $gusers = $UG->getAllforGroup($group['groupname']);
                        foreach ($gusers as $guser) 
                        {
                           $U1->findByName($guser);
                           if ( !$U1->checkUserType($CONF['UTTEMPLATE']) AND !$U1->checkStatus($CONF['USHIDDEN']) )
                           {
                              $total=0;
                              if ($periodAbsence=="All") 
                              {
                                 $absences=$A->getAll();
                                 foreach ($absences as $abs) 
                                 {
                                    if ($A->get($abs['id']) AND !$A->counts_as_present) 
                                    {
                                       $count=countAbsence($guser,$A->id,$periodFrom,$periodTo,$useFactor,$countCombined);
                                       $total+=$count;
                                       $totaluser+=$count;
                                    }
                                 }
                              }
                              else 
                              {
                                 if ($A->get($periodAbsence) AND !$A->counts_as_present) 
                                 {
                                    $count=countAbsence($guser,$A->id,$periodFrom,$periodTo,$useFactor,$countCombined);
                                    $total+=$count;
                                    $totaluser+=$count;
                                 }
                              }
                              if ( strlen($U1->firstname)) $displayname = $U1->lastname.", ".$U1->firstname;
                              else                         $displayname = $U1->lastname;
                              $legend[] = $displayname;
                              $value[] = $total;
                              echo "<tr><td class=\"stat-caption\">".$displayname."</td><td class=\"stat-value\">".sprintf("%1.1f",$total)." ".$LANG['stat_days']."</td></tr>\n\r";
                           }
                        }
                     }
                  }
                  echo "<tr><td class=\"stat-sum-caption\">".$LANG['stat_results_all_members']."</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$totaluser)." ".$LANG['stat_days']."</b></td></tr>\n\r";
                  echo "</table>\n\r";
                  echo "</td><td style=\"vertical-align: top; padding-left: 20px;\">";
                  $header = $LANG['stat_results_total_absence_user'].$periodFrom."-".$periodTo;
                  $footer = "";
                  echo $ST->barGraphH($legend,$value,$diagramwidth,$barareawidth,"red",$header,$footer);
                  echo "</td></tr></table>\n\r";
                  echo "</fieldset><br>\n\r";
                  ?>
   
                  <!-- TOTAL PRESENCE USER-->
                  <?php
                  $legend=array();
                  $value=array();
                  if ($statgroup=="%") $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;All)";
                  else $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;".$statgroup.")";
                  echo "<fieldset style=\"text-align: left; width: 96%;\"><legend>".$LANG['stat_results_total_presence_user'].$periodFrom."-".$periodTo.$forgroup."</legend>";
                  echo "<table><tr><td style=\"vertical-align: top;\">\n\r";
                  echo "<table>\n\r";
                  
                  /**
                   * Get totals per user
                   */
                  $total=0;
                  $useFactor=TRUE;
                  $countCombined=FALSE;
                  $groups = $G->getAllByGroup($statgroup);
                  foreach ($groups as $group) 
                  {
                     $G->findByName($group['groupname']);
                     if (!$G->checkOptions($CONF['G_HIDE']) ) 
                     {
                        $gusers = $UG->getAllforGroup($group['groupname']);
                        foreach ($gusers as $guser) 
                        {
                           $U1->findByName($guser);
                           if ( !$U1->checkUserType($CONF['UTTEMPLATE']) AND !$U1->checkStatus($CONF['USHIDDEN']) ) 
                           {
                              /*
                               * Count all absences that count as absent
                               */
                              $totalAbsences=0;
                              $absences=$A->getAll();
                              foreach($absences as $abs) 
                              {
                                 if ($A->get($abs['id']) AND !$A->counts_as_present) 
                                 {
                                    $totalAbsences+=countAbsence($guser,$A->id,$periodFrom,$periodTo,$useFactor,$countCombined);
                                 }
                              }
                              $total = $daysInPeriod-$totalAbsences;
                              $totaluser += $total;
                              //echo "<script type=\"text/javascript\">alert(\"Debug: $daysInPeriod,$businessDaysInMonth\");</script>";
                              
                              if ( strlen($U1->firstname)) $displayname = $U1->lastname.", ".$U1->firstname;
                              else                         $displayname = $U1->lastname;
                              $legend[] = $displayname;
                              $value[] = $total;
                              echo "<tr><td class=\"stat-caption\">".$displayname."</td><td class=\"stat-value\">".sprintf("%1.1f",$total)." ".$LANG['stat_days']."</td></tr>\n\r";
                           }
                        }
                     }
                  }
                  echo "<tr><td class=\"stat-sum-caption\">".$LANG['stat_results_all_members']."</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$totaluser)." ".$LANG['stat_days']."</b></td></tr>\n\r";
                  echo "</table>\n\r";
                  echo "</td><td style=\"vertical-align: top; padding-left: 20px;\">";
                  $header = $LANG['stat_results_total_presence_user'].$periodFrom."-".$periodTo;
                  $footer = "";
                  echo $ST->barGraphH($legend,$value,$diagramwidth,$barareawidth,"green",$header,$footer);
                  echo "</td></tr></table>\n\r";
                  echo "</fieldset><br>\n\r";
                  ?>
   
                  <!-- TOTAL ABSENCE GROUP -->
                  <?php
                  $legend=array();
                  $value=array();
                  if ($statgroup=="%") $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;All)";
                  else $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;".$statgroup.")";
                  if ($periodAbsence=="All") $forabsence = "&nbsp;(".$LANG['stat_absence'].":&nbsp;All)";
                  else $forabsence = "&nbsp;(".$LANG['stat_absence'].":&nbsp;".$periodAbsenceName.")";
                  echo "<fieldset style=\"text-align: left; width: 96%;\"><legend>".$LANG['stat_results_total_absence_group'].$periodFrom."-".$periodTo.$forgroup.$forabsence."</legend>";
                  echo "<table><tr><td style=\"vertical-align: top;\">\n\r";
                  echo "<table>\n\r";
                  /**
                   * Get totals per group
                   */
                  $totalgroup=0;
                  $useFactor=TRUE;
                  $countCombined=FALSE;
                  $groups = $G->getAllByGroup($statgroup);
                  foreach ($groups as $group) 
                  {
                     $G->findByName($group['groupname']);
                     if (!$G->checkOptions($CONF['G_HIDE']) ) 
                     {
                        $total=0;
                        $gusers = $UG->getAllforGroup($group['groupname']);
                        foreach ($gusers as $guser) 
                        {
                           $U1->findByName($guser);
                           if ( !$U1->checkUserType($CONF['UTTEMPLATE']) AND !$U1->checkStatus($CONF['USHIDDEN']) ) 
                           {
                              if ( $periodAbsence=="All" ) 
                              {
                                 $absences=$A->getAll();
                                 foreach ($absences as $abs) 
                                 {
                                    if ($A->get($abs['id']) AND !$A->counts_as_present) 
                                    {
                                       $total+=countAbsence($guser,$A->id,$periodFrom,$periodTo,$useFactor,$countCombined);
                                    }
                                 }
                              }
                              else {
                                 if ($A->get($periodAbsence) AND !$A->counts_as_present) 
                                 {
                                    $total+=countAbsence($guser,$A->id,$periodFrom,$periodTo,$useFactor,$countCombined);
                                 }
                              }
                           }
                        }
                        $legend[] = $group['groupname'];
                        $value[] = $total;
                        $totalgroup += $total;
                        echo "<tr><td class=\"stat-caption\">".$LANG['stat_results_group'].$group['groupname']."</td><td class=\"stat-value\">".sprintf("%1.1f",$total)." ".$LANG['stat_days']."</td></tr>\n\r";
                     }
                  }
                  echo "<tr><td class=\"stat-sum-caption\">".$LANG['stat_results_all_groups']."</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$totalgroup)." ".$LANG['stat_days']."</b></td></tr>\n\r";
   
                  echo "</table>\n\r";
                  echo "</td><td style=\"vertical-align: top; padding-left: 20px;\">";
                  $header = $LANG['stat_results_total_absence_group'].$periodFrom."-".$periodTo;
                  $footer = "";
                  echo $ST->barGraphH($legend,$value,$diagramwidth,$barareawidth,"red",$header,$footer);
                  echo "</td></tr></table>\n\r";
                  echo "</fieldset><br>\n\r";
                  ?>
   
                  <!-- TOTAL PRESENCE GROUP -->
                  <?php
                  if ($statgroup=="%") $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;All)";
                  else $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;".$statgroup.")";
                  echo "<fieldset style=\"text-align: left; width: 96%;\"><legend>".$LANG['stat_results_total_presence_group'].$periodFrom."-".$periodTo.$forgroup."</legend>";
                  echo "<table><tr><td style=\"vertical-align: top;\">\n\r";
                  echo "<table>\n\r";
                  /**
                   * Get totals per group
                   */
                  $totalAllGroups=0;
                  $useFactor=TRUE;
                  $countCombined=FALSE;
                  $legend=array();
                  $value=array();
                  $groups = $G->getAllByGroup($statgroup);
                  foreach ($groups as $group) 
                  {
                     $G->findByName($group['groupname']);
                     if (!$G->checkOptions($CONF['G_HIDE']) ) 
                     {
                        $totalThisGroup=0;
                        $gusers = $UG->getAllforGroup($group['groupname']);
                        foreach ($gusers as $guser) 
                        {
                           $U1->findByName($guser);
                           if ( !$U1->checkUserType($CONF['UTTEMPLATE']) AND !$U1->checkStatus($CONF['USHIDDEN']) ) 
                           {
                              /*
                               * Count all absences that count as absent
                               */
                              $totalAbsences=0;
                              $absences=$A->getAll();
                              foreach($absences as $abs) 
                              {
                                 if ($A->get($abs['id']) AND !$A->counts_as_present) 
                                 {
                                    $totalAbsences+=countAbsence($guser,$A->id,$periodFrom,$periodTo,$useFactor,$countCombined);
                                 }
                              }
                           }
                           $totalThisUser = $daysInPeriod-$totalAbsences;
                           $totalThisGroup += $totalThisUser;
                        }
                        $legend[] = $group['groupname'];
                        $value[] = $totalThisGroup;
                        echo "<tr><td class=\"stat-caption\">".$LANG['stat_results_group'].$group['groupname']."</td><td class=\"stat-value\">".sprintf("%1.1f",$totalThisGroup)." ".$LANG['stat_days']."</td></tr>\n\r";
                     }
                     $totalAllGroups += $totalThisGroup;
                  }
                  echo "<tr><td class=\"stat-sum-caption\">".$LANG['stat_results_all_groups']."</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$totalAllGroups)." ".$LANG['stat_days']."</b></td></tr>\n\r";
   
                  echo "</table>\n\r";
                  echo "</td><td style=\"vertical-align: top; padding-left: 20px;\">";
                  $header = $LANG['stat_results_total_presence_group'].$periodFrom."-".$periodTo;
                  $footer = "";
                  echo $ST->barGraphH($legend,$value,$diagramwidth,$barareawidth,"green",$header,$footer);
                  echo "</td></tr></table>\n\r";
                  echo "</fieldset><br>\n\r";
                  ?>
   
                  <!-- TOTAL ABSENCE BY TYPE -->
                  <?php
                  if ($statgroup=="%") $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;All)";
                  else $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;".$statgroup.")";
                  echo "<fieldset style=\"text-align: left; width: 96%;\"><legend>".$LANG['stat_results_total_per_type'].$periodFrom."-".$periodTo.$forgroup."</legend>";
                  echo "<table><tr><td style=\"vertical-align: top;\">\n\r";
                  echo "<table>\n\r";
                  /**
                   * Get totals per absence type
                   */
                  $sum=0;
                  $useFactor=FALSE;
                  $countCombined=FALSE;
                  $legend=array();
                  $value=array();
                  $absences = $A->getAll();
                  foreach ($absences as $abs) 
                  {
                     if (!$abs['counts_as_present']) 
                     {
                        $total=0;
                        $groups = $G->getAllByGroup($statgroup);
                        foreach ($groups as $group) 
                        {
                           $G->findByName($group['groupname']);
                           if (!$G->checkOptions($CONF['G_HIDE']) ) 
                           {
                              $gusers = $UG->getAllforGroup($group['groupname']);
                              foreach ($gusers as $guser) 
                              {
                                 $U1->findByName($guser);
                                 if ( !$U1->checkUserType($CONF['UTTEMPLATE']) AND !$U1->checkStatus($CONF['USHIDDEN']) ) 
                                 {
                                    $total+=countAbsence($guser,$abs['id'],$periodFrom,$periodTo,$useFactor,$countCombined);
                                 }
                              }
                           }
                        }
                        $sum+=$total;
                        $legend[] = $abs['name'];
                        $value[] = $total;
                        echo "<tr><td class=\"stat-caption\">".$abs['name']."</td><td class=\"stat-value\">".sprintf("%1.1f",$total)." ".$LANG['stat_days']."</td></tr>\n\r";
                     }
                  }
                  echo "<tr><td class=\"stat-sum-caption\">".$LANG['stat_results_all_members']."</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$sum)." ".$LANG['stat_days']."</b></td></tr>\n\r";
                  echo "</table>\n\r";
                  echo "</td><td style=\"vertical-align: top; padding-left: 20px;\">";
                  $header = $LANG['stat_results_total_per_type'].$periodFrom."-".$periodTo;
                  $footer = "";
                  echo $ST->barGraphH($legend,$value,$diagramwidth,$barareawidth,"orange",$header,$footer);
                  echo "</td></tr></table>\n\r";
                  echo "</fieldset><br>\n\r";
                  ?>
   
                  <!-- TOTAL REMAINDER BY TYPE -->
                  <?php
                  if ($statgroup=="%") $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;All)";
                  else $forgroup = "&nbsp;(".$LANG['stat_group'].":&nbsp;".$statgroup.")";
                  echo "<fieldset style=\"text-align: left; width: 96%;\"><legend>".$LANG['stat_results_remainders'].$yeartoday.$forgroup."</legend>";
                  echo "<table><tr><td style=\"vertical-align: top;\">\n\r";
                  echo "<table>\n\r";
                  /**
                   * Get total remainders per absence type for current year
                   */
                  $sum=0;
                  $useFactor=TRUE;
                  $countCombined=TRUE;
                  $legend=array();
                  $value=array();
                  $absences = $A->getAll();
                  foreach ($absences as $abs) 
                  {
                     if ($A->get($abs['id']) AND !$A->counts_as_present AND $A->allowance AND !$A->counts_as AND $A->factor) 
                     {
                        $total=0;
   
                        $groups = $G->getAllByGroup($statgroup);
                        foreach ($groups as $group) 
                        {
                           $G->findByName($group['groupname']);
                           if (!$G->checkOptions($CONF['G_HIDE']) ) 
                           {
                              $gusers = $UG->getAllforGroup($group['groupname']);
                              foreach ($gusers as $guser) 
                              {
                                 $U1->findByName($guser);
                                 if ( !$U1->checkUserType($CONF['UTTEMPLATE']) AND !$U1->checkStatus($CONF['USHIDDEN']) ) 
                                 {
                                    if ( $B->find($guser,$A->id) ) 
                                    {
                                       $lstyr = $B->lastyear;
                                       $allow = $B->curryear;
                                    }
                                    else
                                    {
                                       $lstyr = 0;
                                       $allow = $A->allowance;
                                    }
                                    $periodFrom = $yeartoday."0101";
                                    $periodTo = $yeartoday."1231";
                                    $taken=countAbsence($guser,$A->id,$periodFrom,$periodTo,$useFactor,$countCombined);
                                    $total += ($lstyr+$allow)-($taken);
                                    $sum += $total;
                                 }
                              }
                           }
                        }
                        $legend[] = $abs['name'];
                        $value[] = $total;
                        echo "<tr><td class=\"stat-caption\">".$abs['name']."</td><td class=\"stat-value\">".sprintf("%1.1f",$total)." ".$LANG['stat_days']."</td></tr>\n\r";
                     }
                  }
                  echo "<tr><td class=\"stat-sum-caption\">".$LANG['stat_results_all_members']."</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$sum)." ".$LANG['stat_days']."</b></td></tr>\n\r";
                  $fromtoday=$yeartoday.$monthtoday.$daytoday;
                  $remainBusi=countBusinessDays($fromtoday,$periodTo,$statregion);
                  echo "<tr><td class=\"stat-sum-caption\">Remaining Business Days</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$remainBusi)." ".$LANG['stat_days']."</b></td></tr>\n\r";
                  $remainBusi=countBusinessDays($fromtoday,$periodTo,$statregion,1);
                  echo "<tr><td class=\"stat-sum-caption\">Remaining Man Days</td><td class=\"stat-sum-value\"><b>".sprintf("%1.1f",$remainBusi)." ".$LANG['stat_days']."</b></td></tr>\n\r";
                  echo "</table>\n\r";
                  echo "</td><td style=\"vertical-align: top; padding-left: 20px;\">";
                  $header = $LANG['stat_results_remainders'].$yeartoday;
                  $footer = "";
                  echo $ST->barGraphH($legend,$value,$diagramwidth,$barareawidth,"cyan",$header,$footer);
                  echo "</td></tr></table>\n\r";
                  echo "</fieldset><br>\n\r";
                  ?>
   
               </div>
            </td>
         </tr>
      </table>
   </div>
</div>
<?php require( "includes/footer_inc.php" ); ?>
