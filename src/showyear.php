<?php
/**
 * showyear.php
 *
 * Displays the yearly calendar view page
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

require_once( "helpers/global_helper.php" );
require_once( "models/absence_model.php" );
require_once( "models/config_model.php" );
require_once( "models/daynote_model.php" );
require_once( "models/holiday_model.php" );
require_once( "models/login_model.php" );
require_once( "models/month_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );
require_once( "models/user_option_model.php" );

$A  = new Absence_model;
$C  = new Config_model;
$D  = new Daynote_model;
$H  = new Holiday_model;
$L  = new Login_model;
$M  = new Month_model;
$T  = new Template_model;
$U  = new User_model;
$UG = new User_group_model;
$UL = new User_model;
$UO = new User_option_model;

/**
 * Check if allowed
 */
if (!isAllowed("viewYearCalendar")) showError("notallowed");

/**
 * A new user was selected
 */
if ( isset($_POST['obar_user']) OR isset($_POST['obar_year']) ) {
   header("Location: ".$_SERVER['PHP_SELF']."?showuser=".$_POST['obar_user']."&showyear=".$_POST['obar_year']);
   die();
}

/**
 * Get the user to display
 */
$luser=$L->checkLogin();
$showuser='';
if (isset($_REQUEST['showuser'])) $showuser = trim($_REQUEST['showuser']);

$allowed=FALSE;
if (!$luser) {
   /**
    * Public user. Can he see user calendars?
    */
   if (isAllowed("viewAllUserCalendars")) $allowed=TRUE;
}
else {
   /**
    * Logged in user
    */
   if (strlen($showuser)) {
      $U->findByName($showuser);
      /**
       * May he see the requested calendar of showuser?
       */
      if ($luser==$showuser) {
         $allowed=TRUE;
      }
      else if ( !$U->checkStatus($CONF['USHIDDEN']) AND $UG->shareGroups($luser, $showuser) ) {
         if (isAllowed("viewGroupUserCalendars")) $allowed=TRUE;
      }
      else if (!$U->checkStatus($CONF['USHIDDEN'])) {
         if (isAllowed("viewAllUserCalendars")) $allowed=TRUE;
      }
   }

   if (!$allowed) {
      /**
       * At this point the logged in user is not allowed to see showuser or
       * no showuser was given. Check whether he can see any other user.
       */
      $yusers = $U->getAllButAdmin();
      foreach ($yusers as $yu) {
         if ($yu['username']==$luser) {
            $allowed=TRUE;
            $showuser=$yu['username'];
            break;
         }
         else if ( !($yu['status']&$CONF['USHIDDEN']) AND $UG->shareGroups($yu['username'], $luser) ) {
            if (isAllowed("viewGroupUserCalendars")) {
               $allowed=TRUE;
               $showuser=$yu['username'];
               break;
            }
         }
         else if ( !($yu['status']&$CONF['USHIDDEN']) ) {
            if (isAllowed("viewAllUserCalendars")) {
               $allowed=TRUE;
               $showuser=$yu['username'];
               break;
            }
         }
      }
   }
}

if (!$allowed) {
   /**
    * At this point we have determined the year calendar view is allowed
    * but no user's calendar may be viewed.
    */
   $err_short=$LANG['err_not_authorized_short'];
   $err_long=$LANG['err_not_authorized_long'];
   $err_module=$_SERVER['SCRIPT_NAME'];
   $err_btn_close=FALSE;
   require( "includes/header_html_inc.php" );
   require( "includes/header_app_inc.php" );
   require( "includes/menu_inc.php" );
   include ("error.php");
   die();
}

/**
 * Compute date stuff
 */
$monthnames = $CONF['monthnames'];
$weekdays = $CONF['weekdays'];
$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today     = getdate();
$curryear  = $today['year'];  // A full numeric representation of todays' year, 4 digits
$currmonth = $today['mon'];   // Numeric representation of todays' month
$currday   = $today['mday'];  // Numeric representation of todays' day of the month

/**
 * Get the year to display
 */
$showyear = $curryear;
if ( isset($_REQUEST['showyear']) 
     AND strlen($_REQUEST['showyear'])==4
     AND is_numeric($_REQUEST['showyear']) ) 
{
   $showyear = $_REQUEST['showyear'];
}

$U->findByName($showuser);
$showuserfullname = $U->firstname." ".$U->lastname;
$showuserbday=$U->birthday;

/**
 * Build the year array
 * First, initialze
 */
$yarray = array (
    1 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    2 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    3 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    4 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    5 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    6 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    7 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    8 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
    9 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
   10 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
   11 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>""),
   12 => array ( "nofdays"=> 31, "fwday"=>1, "tpl"=>"")
);

/**
 * Now get the real values into the array
 */
for ($i=1; $i<=12; $i++) {
   $mytime = $monthnames[$i] . " 1," . $showyear;
   $myts = strtotime($mytime);
   $yarray[$i]['nofdays']=date("t",$myts);
   $mydate = getdate($myts);
   $monthno = sprintf("%02d",intval($mydate['mon']));
   $weekday1 = $mydate['wday'];
   if ($weekday1=="0") $weekday1="7";
   $yarray[$i]['fwday'] = $weekday1;
   $found = $M->findByName($CONF['options']['region'],$showyear.$monthno);
   if ( !$found ) {
      /**
       * Seems there is no default template for this month yet.
       * Let's create a default one.
       */
      $M->region=$CONF['options']['region'];
      $M->yearmonth = $showyear.$monthno;
      $M->template = createMonthTemplate(strval($showyear),$monthnames[$i]);
      $M->create();
   }
   else if ( empty($M->template) ) {
      /**
       * Seems there is an empty default template. That can't be.
       * Let's create a default one.
       */
      $M->template = createMonthTemplate(strval($showyear),$monthnames[$i]);
      $M->update($CONF['options']['region'],$showyear.$monthno);
   }
   $yarray[$i]['tpl'] = $M->template;
}
/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_showyear'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Year+Calendar';
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
               <?php printDialogTop($LANG['showyear_title_1']."&nbsp;".$showyear."&nbsp;".$LANG['showyear_title_2']."&nbsp;&nbsp;".$showuserfullname, $help, "ico_calendar.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body">

               <table class="year">

                  <!-- HEADER ROW -->
                  <tr>
                     <td class="year" colspan="2"><?=$showyear?>&nbsp;(<?=$LANG['month_region']?>:&nbsp;<?=$CONF['options']['region']?>)</td>
                     <?php
                     for ($i=1;$i<=37;$i++)
                     {
                        if (($j=$i%7)==0) $j=7;
                        $class="yweekday";
                        if ( ($j==6 AND !$C->readConfig('satBusi')) OR ($j==7 AND !$C->readConfig('sunBusi')) ) $class="yweekday-wend";
                        echo "<td class=\"".$class."\">".$LANG['weekdays'][$j]."</td>\n";
                     }
                     ?>
                  </tr>

                  <!-- MONTHS -->
                  <?php
                  /**
                   * Loop through each month
                   */
                  for ($m=1; $m<=12; $m++)
                  {
                     /**
                      * Get month template
                      */
                     $monthno = sprintf("%02d",$m);
                     $M->findByName($CONF['options']['region'],$showyear.$monthno);
                     /**
                      * Try to find this users template for this month
                      */
                     $found = $T->getTemplate($showuser,$showyear,$monthno);
                     if (!$found) {
                        /**
                         * No template found for this user and month.
                         * Create a default one.
                         */
                        $T->username = $showuser;
                        $T->year = $showyear;
                        $T->month = $monthno;
                        for ($t=1; $t<=$yarray[$m]['nofdays']; $t++ ) {
                           $prop='abs'.$t;
                           $T->$prop = 0;
                        }
                        $T->create();
                     }

                     /**
                      * Top row: Month name and Day numbers
                      */
                     echo "<!-- ".strtoupper($monthnames[$m])." -->\n";
                     echo "<!-- Top row: Month name and Day numbers -->\n";
                     echo "<tr>\n";
                     echo "<td class=\"ymonth\" rowspan=\"3\">".$LANG['monthnames'][$m]."</td>\n";
                     $buttoncell="<td class=\"ymonthb\" rowspan=\"3\">";
                     if (isAllowed("editGlobalCalendar") ) {
                        $buttoncell.="<a href=\"javascript:openPopup('editmonth.php?Year=".$showyear."&amp;Month=".$monthnames[$m]."&amp;region=".$CONF['options']['region']."','shop','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=no,dependent=1,width=1024,height=400');\"><img class=\"noprint\" src=\"themes/".$theme."/img/date.png\" width=\"16\" height=\"16\" border=\"0\" title=\"".$LANG['cal_img_alt_edit_month']."\" alt=\"".$LANG['cal_img_alt_edit_month']."\"></a>&nbsp;\n\r";
                     }
                     if (isAllowed("editAllUserCalendars") OR
                         ($UG->shareGroups($luser, $showuser) AND isAllowed("editGroupUserCalendars")) OR
                         ($luser==$showuser AND isAllowed("editOwnUserCalendars"))
                        ) {
                        $buttoncell.="<a href=\"javascript:openPopup('editcalendar.php?Year=".$showyear."&amp;Month=".$monthnames[$m]."&amp;Member=".$showuser."','shop','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=no,dependent=1,width=980,height=640');\"><img class=\"noprint\" src=\"themes/".$theme."/img/btn_edit.gif\" width=\"16\" height=\"16\" border=\"0\" title=\"".$LANG['cal_img_alt_edit_cal']."\" alt=\"".$LANG['cal_img_alt_edit_cal']."\"></a>\n\r";
                     }
                     $buttoncell.="</td>\n\r";
                     echo $buttoncell;
                     for ($i=1;$i<=37;$i++) {
                        if ($i<$yarray[$m]['fwday']) {
                           /**
                            * Not a day. Gray out this cell.
                            */
                           $class="yndaytop";
                           echo "<td class=\"".$class."\">&nbsp;</td>\n";
                        }
                        else if ($i==$yarray[$m]['fwday']) {
                           for ($n=1;$n<=$yarray[$m]['nofdays'];$n++) {
                              $class=" class=\"ydaytop\"";
                              $title="";
                              $ttbody="";
                              $addstyle="";
                              $onmouseover="";
                              $onmouseout="";
                              if ( $H->findBySymbol($M->template[$n-1]) ) {
                                 if ( $H->cfgname=='busi' ) {
                                    /**
                                     * Regular business day
                                     */
                                    $title=" title=\"".$H->dspname."\"";
                                 } else {
                                    /**
                                     * Holiday or any other non-busi day
                                     */
                                    $title=" title=\"".$H->dspname."\"";
                                    $addstyle.="background-color: #".$H->dspbgcolor.";";
                                 }
                              }
                              /**
                               * Get general Daynote into $title if one exists
                               */
                              $dd = sprintf("%02d",$n);
                              if ( $D->findByDay($showyear.$monthno.$dd,"all") ) {
                                 /*
                                  * Prepare tooltip
                                 */
                                 $ttid = 'span-'.$n;
                                 $ttbody=$D->daynote;
                                 $ttcaption = $LANG['tt_title_dayinfo'];
                                 $ttcapicon = 'themes/'.$theme.'/img/ico_daynote.png';
                                  
                                 $addstyle.=" background-image: url(themes/".$theme."/img/ovl_daynote.gif); background-repeat: no-repeat; background-position: top right;";
                              }
                              if ( $showyear==$curryear && $m==$currmonth && $n==$currday ) {
                                 $addstyle.=" border-left: 2px solid #".$C->readConfig("todayBorderColor")."; border-right: 2px solid #".$C->readConfig("todayBorderColor")."; border-top: 2px solid #".$C->readConfig("todayBorderColor").";";
                              }
                              if (strlen($addstyle)) $addstyle = " style=\"".$addstyle."\"";
                              if (strlen($ttbody)) {
                                 echo "<td".$class.$addstyle.$title."><span id=\"".$ttid."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$n."</span></td>\n";
                              }
                              else {
                                 echo "<td".$class.$addstyle.$title.">".$n."</td>\n";
                              }
                              $i++;
                           }
                           $i--;
                        }
                        else {
                           /**
                            * Not a day. Gray out this cell.
                            */
                           $class="yndaytop";
                           echo "<td class=\"".$class."\">&nbsp;</td>\n";
                        }
                     }
                     echo "</tr>\n";
                     /**
                      * Middle row: Absences
                      */
                     echo "<!-- Middle row: Absences -->\n";
                     echo "<tr>\n";
                     for ($i=1;$i<=37;$i++) {
                        if ($i<$yarray[$m]['fwday']) {
                           /**
                            * Not a day. Gray out this cell.
                            */
                           $class="yndaymid";
                           echo "<td class=\"".$class."\">&nbsp;</td>\n";
                        }
                        else if ($i==$yarray[$m]['fwday']) {
                           for ($n=1;$n<=$yarray[$m]['nofdays'];$n++) {
                              /**
                               * Prepare tootlip
                               */
                              $ttid = 'td-'.$U->username.$i;
                              $ttbody = '';
                              $ttcaption = $LANG['tt_title_userdayinfo'];
                              $ttcapicon = 'themes/'.$theme.'/img/ico_daynote.png';
                              /**
                               * Set style and color background bases on holiday
                               */
                              $class=" class=\"ydaymid\"";
                              $title="";
                              $addstyle="";
                              //$onmouseover="";
                              //$onmouseout="";
                              $content="";
                              if ( $H->findBySymbol($M->template[$n-1]) ) {
                                 if ( $H->cfgname!='busi' ) {
                                    // Holiday or any other non-busi day
                                    if ( substr_count($addstyle,"background-color: #") ) {
                                       $pos=strpos($addstyle,"background-color: #");
                                       $replace="background-color: #".$H->dspbgcolor.";";
                                       $addstyle=substr_replace($addstyle,$replace,$pos,26);
                                    }
                                    else
                                       $addstyle.="background-color: #".$H->dspbgcolor.";";
                                 }
                              }

                              /**
                               * Set birthday note if applicable
                               */
                              //$popup="";
                              $birthday=false;
                              $daynote=false;
                              if ( substr($showuserbday,4)==(sprintf("%02d",$m).sprintf("%02d",$n)) && ($UO->true($showuser,"showbirthday")) ) {
                                 /**
                                  * Birthday
                                  */
                                 $ttbody='<img src="img/icons/cake.png" alt="cake" style="vertical-align: bottom; padding-right: 4px;">';
                                 if($UO->true($showuser,"ignoreage")) {
                                    $birthdate=date("d M",strtotime($showuserbday));
                                    $ttbody .= "* ".$LANG['cal_birthday'].": ".$birthdate.". * <br><br>";
                                 } else {
                                    $birthdate=date("d M Y",strtotime($showuserbday));
                                    $dayofbirth=date("d M",strtotime($showuserbday));
                                    $age=intval($showyear)-intval(substr($showuserbday,0,4));
                                    $ttbody .= "* ".$LANG['cal_birthday'].": ".$birthdate.". (".$LANG['cal_age'].": ".$age.") * <br><br>";
                                 }
                                 $birthday=true;
                              }

                              /**
                               * Set personal daynote if applicable
                               */
                              if ( $D->findByDay($showyear.$monthno.sprintf("%02d",$n),$showuser) ) {
                                 $ttbody.=$D->daynote;
                                 $daynote=true;
                              }

                              /**
                               * Build the popup message from above findings
                               */
                              if (strlen($ttbody)) {
                                 if ($birthday && !$daynote) $marker="ovl_birthday.gif";
                                 if (!$birthday && $daynote) $marker="ovl_daynote.gif";
                                 if ($birthday && $daynote) $marker="ovl_bdaynote.gif";
                                 $addstyle.=" background-image: url(themes/".$theme."/img/".$marker."); background-repeat: no-repeat; background-position: top right;";
                              }

                              /**
                               * Set style and color background based on absence.
                               * This overwrites the holiday settings.
                               */
                              $prop='abs'.$n;
                              if ($A->get($T->$prop)) 
                              {
                                 if ( !$A->confidential OR $luser=='admin' OR ($A->confidential AND $luser==$showuser) )
                                 {
                                    if ($pos=strpos($addstyle,"background-color: #")) 
                                    {
                                       if (!$A->bgtransparent) 
                                       {
                                          $replace="background-color: #".$A->bgcolor.";";
                                          $addstyle=substr_replace($addstyle,$replace,$pos,26);
                                       }
                                    }
                                    else
                                    {
                                       if (!$A->bgtransparent) $addstyle.=" background-color: #".$A->bgcolor.";";
                                    }
   
                                    $addstyle.=" color: #".$A->color.";";
                                    if ($A->icon!='No') 
                                    {
                                       $content="<img title=\"".$A->name."\" align=\"top\" alt=\"\" src=\"".$CONF['app_icon_dir'].$A->icon."\" width=\"16\" height=\"16\">";
                                    }
                                    else 
                                    {
                                       $content="<span title=\"".$A->name."\">".$A->symbol."</span>";
                                    }
                                 }
                                 else 
                                 {
                                    $content="&nbsp;";
                                 }
                              }
                              else
                              {
                                 $content="&nbsp;";
                              }
                              
                              /**
                               * Draw the cell
                               */
                              if ( $showyear==$curryear && $m==$currmonth && $n==$currday ) {
                                 $addstyle.=" border-left: 2px solid #".$C->readConfig("todayBorderColor")."; border-right: 2px solid #".$C->readConfig("todayBorderColor").";";
                              }
                              if (strlen($addstyle)) $addstyle = " style=\"".$addstyle."\"";
                              if (strlen($ttbody)) {
                                 echo "<td".$class.$addstyle." id=\"td-".$U->username.$i."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$content."</td>\n";
                              }
                              else {
                                 echo "<td".$class.$addstyle."\">".$content."</td>\n";
                              }
                              $i++;
                           }
                           $i--;
                        }
                        else {
                           /**
                            * Not a day. Gray out this cell.
                            */
                           $class="yndaymid";
                           echo "<td class=\"".$class."\">&nbsp;</td>\n";
                        }
                     }
                     echo "</tr>\n";
                     /**
                      * Bottom row: Week numbers
                      */
                     echo "<!-- Bottom row: Week numbers -->\n";
                     echo "<tr>\n";
                     for ($i=1;$i<=37;$i++) {
                        if ($i<$yarray[$m]['fwday']) {
                           /**
                            * Not a day. Gray out this cell.
                            */
                           $class="yndaybot";
                           echo "<td class=\"".$class."\">&nbsp;</td>\n";
                        }
                        else if ($i==$yarray[$m]['fwday']) {
                           for ($n=1;$n<=$yarray[$m]['nofdays'];$n++) {
                              $class=" class=\"ydaybot\"";
                              $title="";
                              $addstyle="";
                              $onmouseover="";
                              $onmouseout="";
                              if ( $H->findBySymbol($M->template[$n-1]) ) {
                                 if ( $H->cfgname=='busi' ) {
                                    /**
                                     * Regular business day
                                     */
                                    $title=" title=\"".$H->dspname."\"";
                                 } else {
                                    /**
                                     * Holiday or any other non-busi day
                                     */
                                    $addstyle.="background-color: #".$H->dspbgcolor.";";
                                    $title=" title=\"".$H->dspname."\"";
                                 }
                              }
                              if ( $showyear==$curryear && $m==$currmonth && $n==$currday ) {
                                 $addstyle.=" border-left: 2px solid #".$C->readConfig("todayBorderColor")."; border-right: 2px solid #".$C->readConfig("todayBorderColor")."; border-bottom: 2px solid #".$C->readConfig("todayBorderColor").";";
                              }
                              if (($i%7)==1) {
                                 $w=sprintf("%d",date("W",mktime(0,0,0,$m,$n,$showyear)));
                                 $title=" title=\"".$LANG['showyear_weeknumber']." ".$w."\"";
                              }
                              else {
                                 $w="&nbsp;";
                              }
                              if (strlen($addstyle)) $addstyle = " style=\"".$addstyle."\"";
                              echo "<td".$class.$addstyle.$title.$onmouseover.$onmouseout.">".$w."</td>\n";
                              $i++;
                           }
                           $i--;
                        }
                        else {
                           /**
                            * Not a day. Gray out this cell.
                            */
                           $class="yndaybot";
                           echo "<td class=\"".$class."\">&nbsp;</td>\n";
                        }
                     }
                     echo "</tr>\n";
                  }
                  ?>

               </table>
            </td>
         </tr>
      </table>
   </div>
</div>
<?php require( "includes/footer_inc.php" ); ?>
