<?php
/**
 * holidays.php
 *
 * Displays the groups administration page
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
require_once( "models/holiday_model.php" );
require_once( "models/log_model.php" );
require_once( "models/login_model.php" );
require_once( "models/month_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );
require_once( "models/user_option_model.php" );

$A = new Absence_model;
$B = new Allowance_model;
$C = new Config_model;
$H = new Holiday_model;
$L = new Login_model;
$LOG = new Log_model;
$M = new Month_model;
$T  = new Template_model;
$U  = new User_model;
$UG = new User_group_model;
$UO = new User_option_model;

$message = false;

/**
 * Check if allowed
 */
if (!isAllowed("editHolidays")) showError("notallowed");

$monthnames = $CONF['monthnames'];
$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today = getdate();
$curryear = $today['year']; // numeric value, 4 digits
$currmonth = $today['mon']; // numeric value
$themearray = getFolders('themes');
/**
 * =========================================================================
 * ADD
 */
if ( isset($_POST['btn_hol_add']) ) 
{
   if ( strlen($_POST['hol_nameadd']) && strlen($_POST['hol_coloradd']) && strlen($_POST['hol_bgcoloradd']) ) 
   {
      /**
       * The user needs not to be bothered with the symbol that is used
       * to identify this day type internally since it is never displayed
       * to him. So we have to chose one ourselves. 0 is business day and
       * 1 is weekend. So we have 34 more (should be sufficient).
       */
      $symbols = "23456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      // Get next available symbol
      $holidays = $H->getAll();
      for ( $i=0; $i<strlen($symbols); $i++ ) 
      {
         $taken=false;
         foreach ($holidays as $row) 
         {
            if ( $row['cfgsym']==$symbols[$i] ) 
            {
               $taken=true;
               break;
            }
         }
         if (!$taken) break;
      }
      
      if ( !$taken ) 
      {
         $hol_symadd = $symbols[$i];
         $H->cfgsym=strtoupper(trim($hol_symadd));
         $H->cfgname="d".strval(rand(1000,9999));
         $H->dspsym=strtoupper(trim($hol_symadd));
         $H->dspname=trim($_POST['hol_nameadd']);
         $H->dspcolor=strtoupper(trim($_POST['hol_coloradd']));
         $H->dspbgcolor=strtoupper(trim($_POST['hol_bgcoloradd']));
         if (isset($_POST['chkBusinessDay'])) $H->setOptions($CONF['H_BUSINESSDAY']);
         $H->create();

         /**
          * Create the theme css files so it includes this absence type
          */
         foreach ($themearray as $theme) createCSS($theme["name"]);

         sendNotification("holidayadd",trim($_POST['hol_nameadd']),"");
         /**
          * Log this event
          */
         $LOG->log("logHoliday",$L->checkLogin(),"log_hol_created", $H->dspname." ".$H->dspcolor." ".$H->dspbgcolor);
      }
      else 
      {
         $message       = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['err_input_caption'];
         $msg_text    = $LANG['err_input_max_daytype'];
      }
   }
   else 
   {
      $message       = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['err_input_caption'];
      $msg_text    = $LANG['err_input_hol_add'];
   }

}
/**
 * =========================================================================
 * UPDATE
 */
else if ( isset($_POST['btn_hol_update']) ) 
{
   $H->findBySymbol($_POST['hol_symbolhidden']);
   $H->dspname=trim($_POST['hol_dspname']);
   $H->dspcolor=strtoupper(trim($_POST['hol_color']));
   $H->dspbgcolor=strtoupper(trim($_POST['hol_bgcolor']));
   $H->clearOptions($CONF['H_BUSINESSDAY']);
   if (isset($_POST['chkBusinessDay'])) $H->setOptions($CONF['H_BUSINESSDAY']);
   $H->update($_POST['hol_symbolhidden']);

   /**
    * Create the theme css files so it includes this absence type
    */
   foreach ($themearray as $theme) createCSS($theme["name"]);

   sendNotification("holidaychange",trim($_POST['hol_dspname']),"");
   
   /**
    * Log this event
    */
   $LOG->log("logHoliday",$L->checkLogin(),"log_hol_updated", $H->dspname." ".$H->dspcolor." ".$H->dspbgcolor);
}
/**
 * =========================================================================
 * DELETE
 */
else if ( isset($_POST['btn_hol_delete']) ) 
{
   $delsym = strtoupper(trim($_POST['hol_symbolhidden']));
   $H->findBySymbol($delsym);
   $delname = $H->dspname;
   $H->deleteBySymbol($delsym);
   /**
    * Remove holiday from all month templates
    */
   $M->removeHoliday($CONF['options']['region'],$delsym);

   /**
    * Create the theme css files so it includes this absence type
    */
   foreach ($themearray as $theme) createCSS($theme["name"]);

   sendNotification("holidaydelete",$delname,"");
   
   /**
    * Log this event
    */
   $LOG->log("logHoliday",$L->checkLogin(),"log_hol_deleted", $delname);
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_holidays'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) 
{
   $help .= 'Holiday+Types';
}
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      
      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <!--  HOLIDAY TYPES ==================================================== -->
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="4">
               <?php printDialogTop($LANG['admin_holiday_title'], $help, "ico_holidays.png"); ?>
            </td>
         </tr>
         <tr>
            <td>
               <form class="form" name="form-hol-add" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
               <table style="border-collapse: collapse; border: 0px; width: 100%;">
                  <tr>
                     <td class="dlg-caption" width="5%">&nbsp;</td>
                     <td class="dlg-caption" width="30%"><?=$LANG['ed_column_name']?></td>
                     <td class="dlg-caption" width="15%"><?=$LANG['ed_column_color']?></td>
                     <td class="dlg-caption" width="15%"><?=$LANG['ed_column_bgcolor']?></td>
                     <td class="dlg-caption" width="10%"><?=$LANG['ed_column_businessday']?></td>
                     <td class="dlg-caption" width="25%"><?=$LANG['ed_column_action']?>   </td>
                  </tr>
                  <tr>
                     <td class="dlg-row1" style="vertical-align: middle;"><img src="themes/<?=$theme?>/img/ico_add.png" alt=""></td>
                     <td class="dlg-row1"><input name="hol_nameadd" size="28" type="text" class="text" value=""></td>
                     <td class="dlg-row1">
                        <input name="hol_coloradd" id="color-new" size="6" maxlength="6" type="text" class="text" style="text-align: center;" value="">
                     </td>
                     <td class="dlg-row1">
                        <input name="hol_bgcoloradd" id="bgcolor-new" size="6" maxlength="6" type="text" class="text" style="text-align: center;" value="">
                     </td>
                     <td class="dlg-row1"><input name="chkBusinessDay" type="checkbox" value="chkBusinessDay"></td>
                     <td class="dlg-row1"><input name="btn_hol_add" type="submit" class="button" value="<?=$LANG['btn_add']?>"></td>
                  </tr>
               </table>
               </form>
            </td>
         </tr>
         <?php
         $i=1;
         $printrow=1;
         $holids = "#color-new, #bgcolor-new, ";
         $holidays = array();
         $holidays = $H->getAll();
         foreach ($holidays as $row) 
         {
            $H->findBySymbol($row['cfgsym']);
            if ($printrow==1) $printrow=2; else $printrow=1;
            ?>
            <!-- <?=$H->dspname?> -->
            <tr>
               <td>
                  <form class="form" name="form-hol-<?=$i?>" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                  <table style="border-collapse: collapse; border: 0px; width: 100%;">
                     <tr>
                        <td class="dlg-row<?=$printrow?>" width="5%">
                           <table style="border-collapse: collapse;">
                              <tr><td class="daynum-<?=$H->cfgname?>" style="border: 1px solid #000000; width: 20px; height: 20px;">15</td></tr>
                           </table>
                        </td>
                        <td class="dlg-row<?=$printrow?>" width="30%">
                           <input name="hol_symbolhidden" type="hidden" class="text" value="<?=$H->cfgsym?>">
                           <input name="hol_dspname" size="28" type="text" class="text" value="<?=$H->dspname?>">
                        </td>
                        <td class="dlg-row<?=$printrow?>" width="15%">
                           <input name="hol_color" id="color-<?=$i?>" size="6" maxlength="6" type="text" class="text" style="text-align: center;" value="<?=$H->dspcolor?>">
                        </td>
                        <td class="dlg-row<?=$printrow?>" width="15%">
                           <input name="hol_bgcolor" id="bgcolor-<?=$i?>" size="6" maxlength="6" type="text" class="text" style="text-align: center;" value="<?=$H->dspbgcolor?>">
                        </td>
                        <td class="dlg-row<?=$printrow?>" width="10%">
                           <?php
                           if ( $H->cfgsym!='0' && $H->cfgsym!='1' ) 
                           { 
                              // Business Day and Weekend Day cannot be set/unset as business days here.
                              // For Sat and Sun this can be done in Config -> Calendar Display ?>
                              <input name="chkBusinessDay" type="checkbox" value="chkBusinessDay" <?=($H->checkOptions($CONF['H_BUSINESSDAY'])?'CHECKED':'')?>>
                           <?php } ?>
                        </td>
                        <td class="dlg-row<?=$printrow?>" width="25%">
                           <input name="btn_hol_update" type="submit" class="button" value="<?=$LANG['btn_update']?>">&nbsp;
                           <?php
                           if ( $H->cfgsym!='0' && $H->cfgsym!='1' ) 
                           { 
                              // Business Day and Weekend Day cannot be deleted ?>
                              <input name="btn_hol_delete" type="submit" class="button" value="<?=$LANG['btn_delete']?>" onclick="return confirmSubmit('<?=$LANG['ed_delete_confirm']?>')">
                           <?php } ?>
                        </td>
                     </tr>
                  </table>
                  </form>
               </td>
            </tr>
            <?php 
            $holids.="#color-".$i.", #bgcolor-".$i.", ";
            $i+=1;
         }
         ?>
      </table>
   </div>
</div>
<?php $holids=substr($holids,0,-2); ?>
<script type="text/javascript">$(function() { $( "<?=$holids?>" ).ColorPicker({ onSubmit: function(hsb, hex, rgb, el) { $(el).val(hex.toUpperCase()); $(el).ColorPickerHide(); }, onBeforeShow: function () { $(this).ColorPickerSetColor(this.value); } }) .bind('keyup', function(){ $(this).ColorPickerSetColor(this.value); }); });</script>
<?php require("includes/footer_inc.php"); ?>
