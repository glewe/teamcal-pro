<?php
/**
 * editmonth.php
 *
 * Displays the edit month dialog
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

require_once ("models/config_model.php");
require_once ("models/daynote_model.php");
require_once ("models/holiday_model.php");
require_once ("models/login_model.php");
require_once ("models/log_model.php");
require_once ("models/month_model.php");
require_once ("models/user_model.php");

$C = new Config_model;
$H = new Holiday_model;
$H2 = new Holiday_model;
$L = new Login_model;
$LOG = new Log_model;
$M = new Month_model;
$N = new Daynote_model;
$UL = new User_model;
$error=FALSE;

/**
 * Check if allowed
 */
if (!isAllowed("editGlobalCalendar")) showError("notallowed");

if (isset ($_REQUEST['region'])) $region = $_REQUEST['region']; else $region = $CONF['options']['region'];
if (isset ($_REQUEST['Year']))   $Year = $_REQUEST['Year']; else $Year = date('Y');
if (isset ($_REQUEST['Month']))  $Month = $_REQUEST['Month']; else $Month = date('M');

/**
 * =========================================================================
 * BACKWARD
 */
if ( isset($_POST['btn_bwd']) ) 
{
   $Year=$_POST['hid_bwdYear'];
   $Month=$_POST['hid_bwdMonth'];
   header("Location: ".$_SERVER['PHP_SELF'] . "?region=" . $region . "&Year=" . $Year . "&Month=" . $Month . "&lang=" . $CONF['options']['lang']);
}
/**
 * =========================================================================
 * FORWARD
 */
if ( isset($_POST['btn_fwd']) ) 
{
   $Year=$_POST['hid_fwdYear'];
   $Month=$_POST['hid_fwdMonth'];
   header("Location: ".$_SERVER['PHP_SELF'] . "?region=" . $region . "&Year=" . $Year . "&Month=" . $Month . "&lang=" . $CONF['options']['lang']);
}

$weekdays = $LANG['weekdays'];
/**
 * First create a timestamp
 */
$mytime = $Month . " 1," . $Year;
$myts = strtotime($mytime);
/**
 * Get number of days in month
 */
$nofdays = date("t", $myts);
/**
 * Get first weekday of the month
 */
$mydate = getdate($myts);
$monthno = sprintf("%02d", intval($mydate['mon']));
$weekday1 = $mydate['wday'];
if ($weekday1 == "0") $weekday1 = "7";
$dayofweek = intval($weekday1);
/**
 * Prepare the Fwd/Bwd buttons
 */
if (intval($monthno)==12) {
   $fwdMonth=$CONF['monthnames'][1];
   $fwdYear=$Year+1;
}
else {
   $fwdMonth=$CONF['monthnames'][intval($monthno)+1];
   $fwdYear=$Year;
}

if (intval($monthno)==1) {
   $bwdMonth=$CONF['monthnames'][12];
   $bwdYear=$Year-1;
}
else {
   $bwdMonth=$CONF['monthnames'][intval($monthno)-1];
   $bwdYear=$Year;
}

/**
 * See if a template exists. If not, create one.
 */
$found = $M->findByName($region, $Year.$monthno);
if (!$found || !$M->template) {
   $M->region = $region;
   $M->yearmonth = $Year . $monthno;
   $M->template = createMonthTemplate($Year, $Month);
   $M->create();
   /**
    * Log this event
    */
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_created", $M->region . " " . $M->yearmonth . " " . $M->template);
}
/**
 * =========================================================================
 * APPLY
 */
if (isset ($_POST['btn_apply'])) {
   /**
    * First clear out the template
    */
   $template = "";
   $dayofweek = intval($weekday1);
   for ($i = 1; $i <= $nofdays; $i++) {
      switch ($dayofweek) {
         case 1 : $template .= '0'; break;
         case 2 : $template .= '0'; break;
         case 3 : $template .= '0'; break;
         case 4 : $template .= '0'; break;
         case 5 : $template .= '0'; break;
         case 6 : if ($C->readConfig("satBusi")) $template .= '0'; else $template .= '1'; break;
         case 7 : if ($C->readConfig("sunBusi")) $template .= '0'; else $template .= '1'; break;
      }
      $dayofweek += 1;
      if ($dayofweek == 8) $dayofweek = 1;
   }

   /**
    * Check the radio boxes
    */
   for ($i=1; $i<=$nofdays; $i++) {
      $key = 'opt_hol_'.$i;
      if (isset($_POST[$key])) $template[$i-1] = $_POST[$key];
   }
    
   /**
    * Write the new template
    */
   $M->template = $template;
   $M->update($M->region, $M->yearmonth);
   /**
    * Log this event
    */
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_updated", $M->region . " " . $M->yearmonth . " " . $M->template);
   /**
    * Send notification e-Mails
    */
   $subject = $LANG['monthnames'][intval($monthno)] . " " . trim($Year);
   sendNotification("monthchange", $subject, "");
}
/**
 * =========================================================================
 * CLEAR
 */
else if (isset ($_POST['btn_clear'])) {
   /**
    * First clear out the template
    */
   $template = "";
   $dayofweek = intval($weekday1);
   for ($i = 1; $i <= $nofdays; $i++) {
      switch ($dayofweek) {
         case 1 : $template .= '0'; break;
         case 2 : $template .= '0'; break;
         case 3 : $template .= '0'; break;
         case 4 : $template .= '0'; break;
         case 5 : $template .= '0'; break;
         case 6 : if ($C->readConfig("satBusi")) $template .= '0'; else $template .= '1'; break;
         case 7 : if ($C->readConfig("sunBusi")) $template .= '0'; else $template .= '1'; break;
      }
      $dayofweek += 1;
      if ($dayofweek == 8) $dayofweek = 1;
   }
   /**
    * Write the new template
    */
   $M->template = $template;
   $M->update($M->region, $M->yearmonth);
   /**
    * Log this event
    */
   $LOG->log("logMonth", $L->checkLogin(), "log_month_tpl_updated", $M->region . " " . $M->yearmonth . " " . $M->template);
   /**
    * Send notification e-Mails
    */
   $subject = $LANG['monthnames'][intval($monthno)] . " " . trim($Year);
   sendNotification("monthchange", $subject, "");
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_editmonth'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Month+Template';
}
require("includes/header_html_inc.php");
?>
<div id="content">
   <div id="content-content">
      <form  name="monthform" method="POST" action="<?=($_SERVER['PHP_SELF']."?region=".$region."&amp;Year=".$Year."&amp;Month=".$Month)?>">
         <table class="dlg">
            <tr>
               <td class="dlg-header">
                  <?php printDialogTop($LANG['month_edit']." ".$LANG['monthnames'][intval($monthno)]." ".$Year." (".$LANG['month_region'].": ".$region.")", $help, "ico_holidays.png"); ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-body">
                  <?php
                  /**
                   * Month frame: Day of month
                   */
                  ?>
                  <br>
                  <table class="month">
                     <tr>
                        <td class="month"><?=$LANG['monthnames'][intval($monthno)] . "&nbsp;" . trim($Year)?></td>
                        <td class="month-button">&nbsp;</td>
                        <?php
                        /**
                         * Daynumber row
                         */
                        for ($i = 1; $i <= $nofdays; $i = $i +1) {
                           if ($H->findBySymbol($M->template[$i -1])) {
                              if ($H->cfgname == 'busi') {
                                 // A business day has a special bgcolor in this dayofmonth row
                                 echo "<td class=\"daynum\">" . $i . "</td>";
                              } else {
                                 echo "<td class=\"daynum-" . $H->cfgname . "\">" . $i . "</td>";
                              }
                           } else {
                              echo "<td class=\"daynum\">" . $i . "</td>";
                           }
                        }
                        ?>
                     </tr>
                     <tr>
                        <td class="title" style="font-size: 8pt;">
                           <input title="<?=$LANG['tt_page_bwd']?>" name="btn_bwd" type="submit" class="button" value="&lt;&lt;">
                           <input title="<?=$LANG['tt_page_fwd']?>" name="btn_fwd" type="submit" class="button" value="&gt;&gt;">
                           <input type="hidden" name="hid_fwdMonth" value="<?=$fwdMonth?>">
                           <input type="hidden" name="hid_fwdYear" value="<?=$fwdYear?>">
                           <input type="hidden" name="hid_bwdMonth" value="<?=$bwdMonth?>">
                           <input type="hidden" name="hid_bwdYear" value="<?=$bwdYear?>">
                        </td>
                        <td class="title-button">&nbsp;</td>
                        <?php
                        /**
                         * Weekday columns
                         */
                        $x = intval($weekday1);
                        for ($i = 1; $i <= $nofdays; $i = $i +1) {
                           if ($H->findBySymbol($M->template[$i -1])) {
                              if ($H->cfgname == 'busi') {
                                 // A business day has a special bgcolor in this dayofweek row
                                 echo "<td class=\"weekday\">" . $weekdays[$x] . "</td>";
                              } else {
                                 echo "<td class=\"weekday-" . $H->cfgname . "\">" . $weekdays[$x] . "</td>";
                              }
                           } else {
                              echo "<td class=\"weekday\">" . $weekdays[$x] . "</td>";
                           }
                           if ($x <= 6)
                              $x += 1;
                           else
                              $x = 1;
                        }
                        ?>
                     </tr>
                     <tr>
                        <td class="title"><?=$LANG['month_global_daynote']?></td>
                        <td class="title-button">&nbsp;</td>
                        <?php
                        /**
                         * Global daynote row
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
                     <?php
                     /**
                      * Holiday rows
                      */
                     $i = 1;
                     $holidays = $H->getAll();
                     foreach ($holidays as $row) {
                        // Show a row for each type of day except business and weekend.
                        if ($row['cfgsym'] != '0' && $row['cfgsym'] != '1') {
                           echo "
                              <tr>
                                 <td class=\"name\">" . $row['dspname'] . "</td>
                                 <td class=\"name-button\">&nbsp;</td>";
                           for ($count=0; $count<strlen($M->template); $count++) {
                              if ($M->template[$count] == $row['cfgsym']) {
                                 echo "
                                    <td class=\"day-".$row['cfgname']."\">
                                       <input name=\"opt_hol_".($count+1)."\" type=\"radio\" value=\"".$row['cfgsym']."\" CHECKED>";
                              } else {
                                 if ($H2->findBySymbol($M->template[$count])) {
                                    echo "<td class=\"day-".$H2->cfgname."\">";
                                 } else {
                                    echo "<td class=\"day\">";
                                 }
                                 echo "<input name=\"opt_hol_".($count+1)."\" type=\"radio\" value=\"".$row['cfgsym']."\">";
                              }
                              echo "</td>";
                           }
                           echo "</tr>";
                        }
                     }
                  
                     /**
                      * Clear Holiday row
                      */
                     ?>
                     <tr>
                        <td class="title"><?=$LANG['month_clear_holiday']?></td>
                        <td class="title-button">&nbsp;</td>
                     <?php
                     /**
                      * Show a line for this absence type covering each day of the month
                      */
                     for ($count=0; $count<strlen($M->template); $count++) { ?>
                        <td class="weekday"><input name="opt_hol_<?=$count+1?>" type="radio" value="0"></td>
                     <?php } ?>
                     </tr>
               
                     
                  </table>
                  <br>
               </td>
            </tr>
            <tr>
               <td class="dlg-menu">
                  <input name="btn_clear" type="submit" class="button" value="<?=$LANG['btn_clear']?>">
                  <input name="btn_apply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">
                  <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
                  <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
                  <input name="btn_done" type="button" class="button" onclick="javascript:closeme();" value="<?=$LANG['btn_done']?>">
               </td>
            </tr>
         </table>
      </form>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
