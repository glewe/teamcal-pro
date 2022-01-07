<?php
/**
 * declination.php
 *
 * Displays a declination management page
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

require_once( "models/config_model.php" );
require_once( "models/log_model.php" );
require_once( "models/login_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );
require_once( "models/user_option_model.php" );

$C = new Config_model;
$L = new Login_model;
$LOG = new Log_model;
$U  = new User_model;
$UG = new User_group_model;
$UO = new User_option_model;

$message = false;

/**
 * Check if allowed
 */
if (!isAllowed("editDeclination")) showError("notallowed");

/**
 * =========================================================================
 * APPLY
 */
if ( isset($_POST['btn_apply']) ) 
{
   $monthnames = $CONF['monthnames'];
   $tz = $C->readConfig("timeZone");
   if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
   else date_default_timezone_set ($tz);
   $today = getdate();
   $curryear = $today['year']; // numeric value, 4 digits
   $currmonth = $today['mon']; // numeric value
   $declineupdate=false;

   /**
    * Absence threshold declination
    */
   if ( isset($_POST['chk_declAbsence']) ) {

      $C->saveConfig("declAbsence","1");

      if ( strlen($_POST['txt_declThreshold']) ) $C->saveConfig("declThreshold",$_POST['txt_declThreshold']);
      else                                       $C->saveConfig("declThreshold","0");

      switch ($_POST['opt_declBase']) 
      {
         case "all":   $C->saveConfig("declBase","all"); break;
         case "group": $C->saveConfig("declBase","group"); break;
         default:      $C->saveConfig("declBase","all"); break;
      }

      $declineupdate=true;
   }
   else 
   {
      $C->saveConfig("declAbsence","0");
   }

   /**
    * Before date declination
    */
   if ( isset($_POST['chk_declBefore']) ) 
   {
      if ( isset($_POST['opt_declBefore']) AND $_POST['opt_declBefore']=="Today" ) 
      {
         $C->saveConfig("declBefore","Today");
         $declineupdate=true;
      }
      else if ( isset($_POST['opt_declBefore']) AND $_POST['opt_declBefore']=="Date" ) 
      {
         if ( strlen($_POST['txt_declBeforeDate']) ) 
         {
            $C->saveConfig("declBefore","Date");
            $declinebefore = str_replace("-","",$_POST['txt_declBeforeDate']);
            $C->saveConfig("declBeforeDate",$declinebefore);
            $declineupdate=true;
         }
         else 
         {
            $message     = true;
            $msg_type    = 'error';
            $msg_title   = $LANG['error'];
            $msg_caption = $LANG['err_input_caption'];
            $msg_text    = $LANG['err_input_declbefore'];
         }
      }
      else 
      {
         $message     = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['err_input_caption'];
         $msg_text    = $LANG['err_input_declbefore'];
      }
   }
   else 
   {
      $C->saveConfig("declBefore","0");
   }

   /**
    * Declination Period 1
    */
   if ( isset($_POST['chk_declPeriod']) ) 
   {
      if ( strlen($_POST['txt_declPeriodStart']) && strlen($_POST['txt_declPeriodEnd']) ) 
      {
         $periodstart = str_replace("-","",$_POST['txt_declPeriodStart']);
         $periodend   = str_replace("-","",$_POST['txt_declPeriodEnd']);
         if ($periodend>$periodstart) 
         {
            $C->saveConfig("declPeriod","1");
            $C->saveConfig("declPeriodStart",$periodstart);
            $C->saveConfig("declPeriodEnd",$periodend);
            $declineupdate=true;
         }
         else 
         {
            $message     = true;
            $msg_type    = 'error';
            $msg_title   = $LANG['error'];
            $msg_caption = $LANG['err_input_caption'];
            $msg_text    = $LANG['err_input_period'];
         }
      }
      else 
      {
         $C->saveConfig("declPeriod","0");
         $message     = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['err_input_caption'];
         $msg_text    = $LANG['err_input_period'];
      }
   }
   else 
   {
      $C->saveConfig("declPeriod","0");
   }

   /**
    * Declination Period 2
    */
   if ( isset($_POST['chk_declPeriod2']) ) 
   {
      if ( strlen($_POST['txt_declPeriod2Start']) && strlen($_POST['txt_declPeriod2End']) ) 
      {
         $periodstart = str_replace("-","",$_POST['txt_declPeriod2Start']);
         $periodend   = str_replace("-","",$_POST['txt_declPeriod2End']);
         if ($periodend>$periodstart) 
         {
            $C->saveConfig("declPeriod2","1");
            $C->saveConfig("declPeriod2Start",$periodstart);
            $C->saveConfig("declPeriod2End",$periodend);
            $declineupdate=true;
         }
         else 
         {
            $message     = true;
            $msg_type    = 'error';
            $msg_title   = $LANG['error'];
            $msg_caption = $LANG['err_input_caption'];
            $msg_text    = $LANG['err_input_period'];
         }
      }
      else 
      {
         $C->saveConfig("declPeriod2","0");
         $message     = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['err_input_caption'];
         $msg_text    = $LANG['err_input_period'];
      }
   }
   else 
   {
      $C->saveConfig("declPeriod2","0");
   }

   /**
    * Declination Period 3
    */
   if ( isset($_POST['chk_declPeriod3']) ) 
   {
      if ( strlen($_POST['txt_declPeriod3Start']) && strlen($_POST['txt_declPeriod3End']) ) 
      {
         $periodstart = str_replace("-","",$_POST['txt_declPeriod3Start']);
         $periodend   = str_replace("-","",$_POST['txt_declPeriod3End']);
         if ($periodend>$periodstart) 
         {
            $C->saveConfig("declPeriod3","1");
            $C->saveConfig("declPeriod3Start",$periodstart);
            $C->saveConfig("declPeriod3End",$periodend);
            $declineupdate=true;
         }
         else 
         {
            $message     = true;
            $msg_type    = 'error';
            $msg_title   = $LANG['error'];
            $msg_caption = $LANG['err_input_caption'];
            $msg_text    = $LANG['err_input_period'];
         }
      }
      else 
      {
         $C->saveConfig("declPeriod3","0");
         $message     = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['err_input_caption'];
         $msg_text    = $LANG['err_input_period'];
      }
   }
   else 
   {
      $C->saveConfig("declPeriod3","0");
   }

   /**
    * Notfication options
    */
   if (isset($_POST['chk_declNotifyUser'])) $C->saveConfig("declNotifyUser","1"); else $C->saveConfig("declNotifyUser","0");
   if (isset($_POST['chk_declNotifyManager'])) $C->saveConfig("declNotifyManager","1"); else $C->saveConfig("declNotifyManager","0");
   if (isset($_POST['chk_declNotifyDirector'])) $C->saveConfig("declNotifyDirector","1"); else $C->saveConfig("declNotifyDirector","0");
   if (isset($_POST['chk_declNotifyAdmin'])) $C->saveConfig("declNotifyAdmin","1"); else $C->saveConfig("declNotifyAdmin","0");

   /**
    * Apply Declination
    */
   if (isset($_POST['radio_declApplyToAll'])) $C->saveConfig("declApplyToAll",$_POST['radio_declApplyToAll']); else $C->saveConfig("declApplyToAll","0");
    
   /**
    * Log this event
    */
   if ($declineupdate) 
   {
      $LOG->log("logConfig",$L->checkLogin(),"log_decl_updated");
      header("Location: ".$_SERVER['PHP_SELF']);
      die();
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_declination'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Declination';
}
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      
      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <table style="width: 100%;">
         <tr>
            <td valign="top">
               <form class="form" name="form-decl" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
      
                  <!--  DECLINATION MANAGEMENT ============================================== -->
                  <table class="dlg">
                     <tr>
                        <td class="dlg-header" colspan="2">
                           <?php printDialogTop($LANG['decl_title'], $help, "ico_declination.png"); ?>
                        </td>
                     </tr>
      
                     <?php $style="2"; ?>
                     <tr>
                        <td class="dlg-caption" colspan="2"><?=$LANG['decl_options']?></td>
                     </tr>
      
                     <!--  Absence threshold declination -->
                     <?php if ($style=="1") $style="2"; else $style="1"; ?>
                     <tr>
                        <td class="config-row<?=$style?>" style="text-align: left; width: 50%;">
                           <span class="config-key"><?=$LANG['decl_threshold']?></span><br>
                           <span class="config-comment"><?=$LANG['decl_threshold_comment']?></span>
                        </td>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <input style="vertical-align: middle;" name="chk_declAbsence" id="chk_declAbsence" value="chk_declAbsence" type="checkbox" <?=($C->readConfig("declAbsence"))?'checked':''?>><?=$LANG['decl_activate']?><br>
                           <div style="padding-left: 20px;">
                              <?=$LANG['decl_threshold_value']?>:&nbsp;<input style="margin-bottom: 4px;" name="txt_declThreshold" type="text" class="text" size="4" maxlength="2" value="<?=$C->readConfig("declThreshold")?>"><br>
                              <?=$LANG['decl_based_on']?>
                              <input name="opt_declBase" type="radio" value="all" <?=($C->readConfig("declBase")=="all")?'CHECKED':''?>><?=$LANG['decl_base_all']?>
                              <input name="opt_declBase" type="radio" value="group" <?=($C->readConfig("declBase")=="group")?'CHECKED':''?>><?=$LANG['decl_base_group']?>
                           </div>
                        </td>
                     </tr>
      
                     <!--  Decline before -->
                     <?php if ($style=="1") $style="2"; else $style="1"; ?>
                     <tr>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <span class="config-key"><?=$LANG['decl_before']?></span><br>
                           <span class="config-comment"><?=$LANG['decl_before_comment']?></span>
                        </td>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <script type="text/javascript">$(function() { $( "#txt_declBeforeDate" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                           <input style="vertical-align: middle;" name="chk_declBefore" id="chk_declBefore" value="chk_declBefore" type="checkbox" <?=($C->readConfig("declBefore"))?'checked':''?>><?=$LANG['decl_activate']?><br>
                           <div style="padding-left: 20px;">
                              <input name="opt_declBefore" value="Today" type="radio" <?=($C->readConfig("declBefore")=="Today")?'checked':''?>><?=$LANG['decl_before_today']?><br>
                              <input name="opt_declBefore" value="Date" type="radio" <?=($C->readConfig("declBefore")=="Date")?'checked':''?>><?=$LANG['decl_before_date']?><br>
                              <?php
                                 if ($C->readConfig("declBeforeDate")) $declbeforedate = substr($C->readConfig("declBeforeDate"),0,4)."-".substr($C->readConfig("declBeforeDate"),4,2)."-".substr($C->readConfig("declBeforeDate"),6,2);
                                 else $declbeforedate="";
                              ?>
                              <input style="margin: 4px 0px 0px 20px;" name="txt_declBeforeDate" id="txt_declBeforeDate" size="10" maxlength="10" type="text" class="text" value="<?=$declbeforedate?>">
                           </div>
                        </td>
                     </tr>
      
                     <!--  Declination period 1 -->
                     <?php if ($style=="1") $style="2"; else $style="1"; ?>
                     <tr>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <span class="config-key"><?=$LANG['decl_period']?> 1</span><br>
                           <span class="config-comment"><?=$LANG['decl_period_comment']?></span>
                        </td>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <script type="text/javascript">
                              $(function() { $( "#txt_declPeriodStart" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                              $(function() { $( "#txt_declPeriodEnd" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                           </script>
                           <input style="vertical-align: middle;" name="chk_declPeriod" value="chk_declPeriod" type="checkbox" <?=($C->readConfig("declPeriod"))?'checked':''?>><?=$LANG['decl_activate']?><br>
                           <div style="padding-left: 20px;">
                              <?php
                                 if ($C->readConfig("declPeriodStart")) $declPeriodStart = substr($C->readConfig("declPeriodStart"),0,4)."-".substr($C->readConfig("declPeriodStart"),4,2)."-".substr($C->readConfig("declPeriodStart"),6,2);
                                 else $declPeriodStart="";
                              ?>
                              <input style="margin: 4px 0px 4px 0px;" name="txt_declPeriodStart" id="txt_declPeriodStart" size="10" maxlength="10" type="text" class="text" value="<?=$declPeriodStart?>">&nbsp;<?=$LANG['decl_period_start']?><br>
                              <?php
                                 if ($C->readConfig("declPeriodEnd")) $declPeriodEnd = substr($C->readConfig("declPeriodEnd"),0,4)."-".substr($C->readConfig("declPeriodEnd"),4,2)."-".substr($C->readConfig("declPeriodEnd"),6,2);
                                 else $declPeriodEnd="";
                              ?>
                              <input name="txt_declPeriodEnd" id="txt_declPeriodEnd" size="10" maxlength="10" type="text" class="text" value="<?=$declPeriodEnd?>">&nbsp;<?=$LANG['decl_period_end']?>
                           </div>
                        </td>
                     </tr>
      
                     <!--  Declination period 2 -->
                     <?php if ($style=="1") $style="2"; else $style="1"; ?>
                     <tr>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <span class="config-key"><?=$LANG['decl_period']?> 2</span><br>
                           <span class="config-comment"><?=$LANG['decl_period_comment']?></span>
                        </td>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <script type="text/javascript">
                              $(function() { $( "#txt_declPeriod2Start" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                              $(function() { $( "#txt_declPeriod2End" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                           </script>
                           <input style="vertical-align: middle;" name="chk_declPeriod2" value="chk_declPeriod2" type="checkbox" <?=($C->readConfig("declPeriod2"))?'checked':''?>><?=$LANG['decl_activate']?><br>
                           <div style="padding-left: 20px;">
                              <?php
                                 if ($C->readConfig("declPeriod2Start")) $declPeriod2Start = substr($C->readConfig("declPeriod2Start"),0,4)."-".substr($C->readConfig("declPeriod2Start"),4,2)."-".substr($C->readConfig("declPeriod2Start"),6,2);
                                 else $declPeriod2Start="";
                              ?>
                              <input style="margin: 4px 0px 4px 0px;" name="txt_declPeriod2Start" id="txt_declPeriod2Start" size="10" maxlength="10" type="text" class="text" value="<?=$declPeriod2Start?>">&nbsp;<?=$LANG['decl_period_start']?><br>
                              <?php
                                 if ($C->readConfig("declPeriod2End")) $declPeriod2End = substr($C->readConfig("declPeriod2End"),0,4)."-".substr($C->readConfig("declPeriod2End"),4,2)."-".substr($C->readConfig("declPeriod2End"),6,2);
                                 else $declPeriod2End="";
                              ?>
                              <input name="txt_declPeriod2End" id="txt_declPeriod2End" size="10" maxlength="10" type="text" class="text" value="<?=$declPeriod2End?>">&nbsp;<?=$LANG['decl_period_end']?>
                           </div>
                        </td>
                     </tr>
      
                     <!--  Declination period 3 -->
                     <?php if ($style=="1") $style="2"; else $style="1"; ?>
                     <tr>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <span class="config-key"><?=$LANG['decl_period']?> 3</span><br>
                           <span class="config-comment"><?=$LANG['decl_period_comment']?></span>
                        </td>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <script type="text/javascript">
                              $(function() { $( "#txt_declPeriod3Start" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                              $(function() { $( "#txt_declPeriod3End" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
                           </script>
                           <input style="vertical-align: middle;" name="chk_declPeriod3" value="chk_declPeriod3" type="checkbox" <?=($C->readConfig("declPeriod3"))?'checked':''?>><?=$LANG['decl_activate']?><br>
                           <div style="padding-left: 20px;">
                              <?php
                                 if ($C->readConfig("declPeriod3Start")) $declPeriod3Start = substr($C->readConfig("declPeriod3Start"),0,4)."-".substr($C->readConfig("declPeriod3Start"),4,2)."-".substr($C->readConfig("declPeriod3Start"),6,2);
                                 else $declPeriod3Start="";
                              ?>
                              <input style="margin: 4px 0px 4px 0px;" name="txt_declPeriod3Start" id="txt_declPeriod3Start" size="10" maxlength="10" type="text" class="text" value="<?=$declPeriod3Start?>">&nbsp;<?=$LANG['decl_period_start']?><br>
                              <?php
                                 if ($C->readConfig("declPeriod3End")) $declPeriod3End = substr($C->readConfig("declPeriod3End"),0,4)."-".substr($C->readConfig("declPeriod3End"),4,2)."-".substr($C->readConfig("declPeriod3End"),6,2);
                                 else $declPeriod3End="";
                              ?>
                              <input name="txt_declPeriod3End" id="txt_declPeriod3End" size="10" maxlength="10" type="text" class="text" value="<?=$declPeriod3End?>">&nbsp;<?=$LANG['decl_period_end']?>
                           </div>
                        </td>
                     </tr>
      
                     <!--  Notifications -->
                     <?php if ($style=="1") $style="2"; else $style="1"; ?>
                     <tr>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <span class="config-key"><?=$LANG['decl_notify']?></span><br>
                           <span class="config-comment"><?=$LANG['decl_notify_comment']?></span>
                        </td>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <input style="vertical-align: middle;" name="chk_declNotifyUser" id="chk_declNotifyUser" type="checkbox" value="chkDeclNotifyUser" <?=($C->readConfig("declNotifyUser"))?'checked':''?>><?=$LANG['decl_notify_user']?><br>
                           <input style="vertical-align: middle;" name="chk_declNotifyManager" id="chk_declNotifyManager" type="checkbox" value="chk_declNotifyManager" <?=($C->readConfig("declNotifyManager"))?'checked':''?>><?=$LANG['decl_notify_manager']?><br>
                           <input style="vertical-align: middle;" name="chk_declNotifyDirector" id="chk_declNotifyDirector" type="checkbox" value="chk_declNotifyDirector" <?=($C->readConfig("declNotifyDirector"))?'checked':''?>><?=$LANG['decl_notify_director']?><br>
                           <input style="vertical-align: middle;" name="chk_declNotifyAdmin" id="chk_declNotifyAdmin" type="checkbox" value="chk_declNotifyAdmin" <?=($C->readConfig("declNotifyAdmin"))?'checked':''?>><?=$LANG['decl_notify_admin']?><br>
                        </td>
                     </tr>
                     
                     <!--  Apply Declination -->
                     <?php if ($style=="1") $style="2"; else $style="1"; ?>
                     <tr>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <span class="config-key"><?=$LANG['decl_applyto']?></span><br>
                           <span class="config-comment"><?=$LANG['decl_applyto_comment']?></span>
                        </td>
                        <td class="config-row<?=$style?>" style="text-align: left;">
                           <input style="vertical-align: bottom;" name="radio_declApplyToAll" type="radio" value="0" <?=(!$C->readConfig("declApplyToAll"))?'checked':''?>>&nbsp;<?=$LANG['decl_applyto_regular']?><br>
                           <input style="vertical-align: bottom;" name="radio_declApplyToAll" type="radio" value="1" <?=($C->readConfig("declApplyToAll"))?'checked':''?>>&nbsp;<?=$LANG['decl_applyto_all']?><br>
                        </td>
                     </tr>
                     
                     <tr>
                        <td class="dlg-menu" style="text-align: left;" colspan="2">
                           <input name="btn_apply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">
                        </td>
                     </tr>
                  </table>
               </form>
            <br>
         </td>
      </tr>
      </table>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
