<?php
/**
 * log.php
 *
 * Displays the system log dialog
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

require_once("models/config_model.php" );
require_once("models/log_model.php" );
require_once("models/login_model.php" );
require_once("models/user_model.php" );

if ( !isset($_REQUEST['sort']) ) $sort="DESC";
else $sort = $_REQUEST['sort'];

$C   = new Config_model;
$LOG = new Log_model;
$L   = new Login_model;
$U   = new User_model;

/**
 * Check if allowed
 */
if (!isAllowed("viewSystemLog")) showError("notallowed");

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
$periodFrom = "2004-01-01";
$periodTo = $yeartoday."-".$monthtoday."-".$daytoday;
$error=FALSE;
$logtypes = array (
   "Absence",
   "Announcement",
   "Config",
   "Database",
   "Daynote",
   "Group",
   "Holiday",
   "Login",
   "Loglevel",
   "Month",
   "Permission",
   "Region",
   "Registration",
   "User",
);

/**
 * =========================================================================
 * APPLY
 */
if ( isset($_POST['btn_apply']) ) 
{
   if (isset($_POST['optPeriod']) AND $_POST['optPeriod']=="standard")
   {
      /*
       * Standard period was selected
      */
      $C->saveConfig("logoption","standard");
      
      switch ( $_POST['period'] ) 
      {
         case "curr_month":
            $C->saveConfig("logperiod","curr_month");
            $periodFrom = $yeartoday."-".$monthtoday."-01";
            $periodTo = $yeartoday."-".$monthtoday."-".$nofdays;
            break;
            
         case "curr_quarter":
            $C->saveConfig("logperiod","curr_quarter");
            switch ($monthtoday) {
               case 1:
               case 2:
               case 3:
                  $periodFrom = $yeartoday."-01-01";
                  $periodTo = $yeartoday."-03-31";
                  break;
               case 4:
               case 5:
               case 6:
                  $periodFrom = $yeartoday."-04-01";
                  $periodTo = $yeartoday."-06-30";
                  break;
               case 7:
               case 8:
               case 9:
                  $periodFrom = $yeartoday."-07-01";
                  $periodTo = $yeartoday."-09-30";
                  break;
               case 10:
               case 11:
               case 12:
                  $periodFrom = $yeartoday."-10-01";
                  $periodTo = $yeartoday."-12-31";
                  break;
            }
            break;
            
         case "curr_half":
            $C->saveConfig("logperiod","curr_half");
            switch ($monthtoday) {
               case 1:
               case 2:
               case 3:
               case 4:
               case 5:
               case 6:
                  $periodFrom = $yeartoday."-01-01";
                  $periodTo = $yeartoday."-06-30";
                  break;
               case 7:
               case 8:
               case 9:
               case 10:
               case 11:
               case 12:
                  $periodFrom = $yeartoday."-07-01";
                  $periodTo = $yeartoday."-12-31";
                  break;
            }
            break;
            
         case "curr_year":
            $C->saveConfig("logperiod","curr_year");
            $periodFrom = $yeartoday."-01-01";
            $periodTo = $yeartoday."-12-31";
            break;
            
         default:
            $C->saveConfig("logperiod","curr_all");
            break;
      }
      $C->saveConfig("logfrom",$periodFrom);
      $C->saveConfig("logto",$periodTo);
   }
   else
   {
      $C->saveConfig("logoption","custom");
      if ( isset($_POST['rangefrom']) AND preg_match("/(\d{4})-(\d{2})-(\d{2})/",$_POST['rangefrom']) ) 
      {
         $C->saveConfig("logfrom",$_POST['rangefrom']);
      }
      else 
      {
         $C->saveConfig("logfrom","");
      }
      
      if ( isset($_POST['rangeto']) AND preg_match("/(\d{4})-(\d{2})-(\d{2})/",$_POST['rangeto']) ) 
      {
         $C->saveConfig("logto",$_POST['rangeto']);
      }
      else 
      {
         $C->saveConfig("logto","");
      }
      $periodFrom = $C->readConfig("logfrom");
      $periodTo = $C->readConfig("logto");
   }
}
/**
 * =========================================================================
 * REFRESH
 */
else if ( isset($_POST['btn_refresh']) )
{
   foreach ($logtypes as $lt) 
   {
      /**
       * Set log levels
       */
      if ( isset($_POST['chk_log'.$lt]) AND $_POST['chk_log'.$lt]) $C->saveConfig("log".$lt,"1");
      else $C->saveConfig("log".$lt,"0");
      /**
       * Set log filters
       */
      if ( isset($_POST['chk_logfilter'.$lt]) AND $_POST['chk_logfilter'.$lt]) $C->saveConfig("logfilter".$lt,"1");
      else $C->saveConfig("logfilter".$lt,"0");
   }
   /**
    * Log this event
    */
   $LOG->log("logLoglevel",$L->checkLogin(),"log_log_updated");
   header("Location: ".$_SERVER['PHP_SELF']);
}
else if ( isset($_POST['btn_clear']) ) 
{
   $query  = "TRUNCATE TABLE `".$CONF['db_table_log']."`";
   $LOG->db->db_query($query);
   /**
    * Log this event
   */
   $LOG->log("logLogLevel",$L->checkLogin(),"log_log_cleared");
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_log'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) 
{
   $help .= 'System+Log';
}
require("includes/header_html_inc.php" );
require("includes/header_app_inc.php" );
require("includes/menu_inc.php" );
?>
<script type="text/javascript">$(function() { $( "#tabs" ).tabs(); });</script>
<div id="content">
   <div id="content-content">

      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="4">
               <?php printDialogTop($LANG['log_title'], $help, "ico_log.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body">
               <div id="tabs">
                  <ul>
                     <li><a href="#tabs-1"><?=$LANG['log_title']?></a></li>
                     <li><a href="#tabs-2"><?=$LANG['log_settings']?></a></li>
                  </ul>

                  <!-- LOG -->
                  <div id="tabs-1">
                     <table class="dlg">
                        <tr class="logheader">
                           <td class="logheader">
                              <?php if ( $sort=="DESC" ) { ?>
                                 <a href="<?=$_SERVER['PHP_SELF']."?sort=ASC"?>"><img src="themes/<?=$theme?>/img/asc.png" border="0" align="middle" alt="" title="<?=$LANG['log_sort_asc']?>"></a>
                              <?php }else { ?>
                                 <a href="<?=$_SERVER['PHP_SELF']."?sort=DESC"?>"><img src="themes/<?=$theme?>/img/desc.png" border="0" align="middle" alt="" title="<?=$LANG['log_sort_desc']?>"></a>
                              <?php } ?>
                              &nbsp;<?=$LANG['log_header_timestamp']?>
                           </td>
                           <td class="logheader"><?=$LANG['log_header_type']?></td>
                           <td class="logheader"><?=$LANG['log_header_user']?></td>
                           <td class="logheader"><?=$LANG['log_header_event']?></td>
                        </tr>
                        <?php
                        $result=$LOG->read($sort, $C->readConfig("logfrom"), $C->readConfig("logto"));
                        $rowstyle=0;
                        while ( $row=$LOG->db->db_fetch_array($result,MYSQL_ASSOC) ) {
                           if ( $C->readConfig("logfilter".substr($row['type'],3)) ) {
                              $timestamp=$row['timestamp'];
                              $eventtype = substr($row['type'],3);
                              ?>
                              <tr class="logrow<?=$rowstyle?>">
                                 <td class="logrow<?=$rowstyle?>" style="white-space: nowrap;"><?=$timestamp?></td>
                                 <td class="logrow<?=$rowstyle?>"><?=$eventtype?></td>
                                 <td class="logrow<?=$rowstyle?>"><?=$row['user']?></td>
                                 <td class="logrow<?=$rowstyle?>"><?=str_replace("\n", "<br>", $row['event'])?></td>
                              </tr>
                              <?php
                           }
                           if ($rowstyle) $rowstyle=0; else $rowstyle=1;
                        }
                        ?>
                     </table>
                  </div>

                  <!-- LOG SETTINGS -->
                  <div id="tabs-2">
                     <form class="form" name="log-settings" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                        <table class="dlg">
                           <tr>
                              <td class="dlg-caption" style="text-align: left; border-left: 1px solid #777777;"><?=$LANG['log_settings_event']?></td>
                              <td class="dlg-caption" style="text-align: center; border-left: 1px solid #AAAAAA; border-right: 1px solid #AAAAAA;"><?=$LANG['log_settings_log']?></td>
                              <td class="dlg-caption" style="text-align: center; border-right: 1px solid #777777;"><?=$LANG['log_settings_show']?></td>
                           </tr>
                           <?php
                           $i=0; $style=0;
                           foreach ($logtypes as $lt) {
                              if ($style=="1") $style="2"; else $style="1";
                              $i++; ?>
                              <tr>
                                 <td class="config-row<?=$style?>" style="border-left: 1px solid #777777;"><?=$lt?></td>
                                 <td class="config-row<?=$style?>" style="text-align: center; border-left: 1px solid #AAAAAA; border-right: 1px solid #AAAAAA;">
                                    <?php if (isAllowed("editSystemLog")) { ?>
                                       <input type="checkbox" name="chk_log<?=$lt?>" value="chk_log<?=$lt?>" <?=($C->readConfig("log".$lt))?'CHECKED':''?>>
                                    <?php } else { ?>
                                       <img src="img/icons/checkmark.png" alt="" title="<?=$LANG['log_tt_notallowed']?>">
                                    <?php } ?>
                                 </td>
                                 <td class="config-row<?=$style?>" style="text-align: center; border-right: 1px solid #777777;">
                                    <input type="checkbox" name="chk_logfilter<?=$lt?>" value="chk_logfilter<?=$lt?>" <?=($C->readConfig("logfilter".$lt))?'CHECKED':''?>>
                                 </td>
                              </tr>
                           <?php } ?>
                           <tr>
                              <td class="dlg-menu" colspan="3" style="text-align: left;">
                                 <input name="btn_refresh" type="submit" class="button" style="font-size: 8pt;" value="<?=$LANG['btn_refresh']?>">
                                 <?php if (isAllowed("editSystemLog")) { ?>
                                    <input name="btn_clear" type="submit" class="button" style="font-size: 8pt;" value="<?=$LANG['log_btn_clearlog']?>" onclick="return confirmSubmit('<?=$LANG['log_clear_confirm']?>')">
                                 <?php } ?>
                              </td>
                           </tr>
                        </table>
                     </form>
                  </div>
               </div>
           </td>
        </tr>
      </table>
   </div>
</div>
<script type="text/javascript">
$(function() { 
   $("#logfrom").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); 
   $("#logto").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); 
});
</script>

<?php require("includes/footer_inc.php" ); ?>
