<?php
/**
 * announcement.php
 *
 * Displays the announcement page
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

require_once("models/announcement_model.php");
require_once("models/config_model.php");
require_once("models/log_model.php");
require_once("models/login_model.php");
require_once("models/user_model.php");
require_once("models/user_announcement_model.php");

$AN  = new Announcement_model;
$C   = new Config_model;
$L   = new Login_model;
$LOG = new Log_model;
$U   = new User_model;
$UA  = new User_announcement_model;
$UL  = new User_model;

/**
 * Check if allowed
 */
if (!isAllowed("viewAnnouncements")) showError("notallowed");

$user=$L->checkLogin();
$UL->findByName($user);

/**
 * =========================================================================
 * CONFIRM
 */
if ( isset($_POST['btn_confirm']) && strlen($_POST['ats'])) {

   $UA->unassign($_POST['ats'], $UL->username);

   /**
    * Log this event
    */
   $chars = array("-", " ", ":");
   $ats = str_replace($chars, "", $_POST['ats']);
   $LOG->log("logAnnouncement",$user,"log_ann_confirmed", $ats." -> ".$UL->username);
}

/**
 * =========================================================================
 * CONFIRM ALL
 */
else if ( isset($_POST['btn_confirm_all'])) {
   
   $UA->deleteByUser($UL->username);
   
   /**
    * Log this event
    */
   $LOG->log("logAnnouncement",$user,"log_ann_all_confirmed_by", $UL->username);
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_announcement'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Announcements';
}

require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="2">
               <?php printDialogTop($LANG['ann_title']." ".$UL->firstname." ".$UL->lastname, $help, "ico_bell.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-caption" style="text-align: left;"><?=$LANG['ann_col_ann']?></td>
            <td class="dlg-caption" style="text-align: center; padding-right: 8px;"><?=$LANG['ann_col_action']?></td>
         </tr>
            <tr>
               <?php $uas = $UA->getAllForUser($UL->username);
               if (count($uas)) { ?>
                  <td class="config-row1">&nbsp;</td>
                  <td class="config-row1" style="text-align: center; vertical-align: middle;">
                     <form class="form" name="form-ann" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                        <input name="btn_confirm_all" type="submit" class="button" value="<?=$LANG['btn_confirm_all']?>" onclick="return confirmSubmit('<?=$LANG['ann_confirm_all_confirm']?>')">
                     </form>
                  </td>
               <?php }
               else { ?>
                  <td colspan="2" class="config-row1"><?=$LANG['ann_no_ann']?></td>
               <?php } ?>
            </tr>
         <?php $style="1";
         foreach ($uas as $ua) {
            if ($style=="1") $style="2"; else $style="1";
            ?>
            <tr>
               <td class="config-row<?=$style?>">
                  <fieldset><legend><img src="themes/<?=$theme?>/img/ico_bell.png" alt="" style="vertical-align: middle;">&nbsp;<?=$LANG['ann_id'].": ".$ua['ats']?></legend>
                     <br><?=$AN->read($ua['ats'])?>
                  </fieldset>
               </td>
               <td class="config-row<?=$style?>" style="text-align: center; vertical-align: middle;">
                  <form class="form" name="form-ann-<?=$ua['ats']?>" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                     <input name="ats" type="hidden" class="text" value="<?=$ua['ats']?>">&nbsp;
                     <input name="btn_confirm" type="submit" class="button" value="<?=$LANG['btn_confirm']?>" onclick="return confirmSubmit('<?=$LANG['ann_delete_confirm_1'].$ua['ats'].$LANG['ann_delete_confirm_2']?>');">
                  </form>
               </td>
            </tr>
         <?php } ?>
      </table>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>