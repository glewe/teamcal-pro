<?php
/**
 * permissions.php
 *
 * Displays the permissions configuration page
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

require_once("models/permission_model.php");
require_once("models/log_model.php");
require_once("models/login_model.php");
require_once("models/user_model.php");

$C = new Config_model;
$L = new Login_model;
$LOG = new Log_model;
$P = new Permission_model;
$U = new User_model;

/**
 * Check if allowed
 */
if (!isAllowed("editPermissionScheme")) showError("notallowed");

/**
 * Default permission array
 */
$perms = array (
            array ("p"=>"editConfig",              "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editPermissionScheme",    "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"manageUsers",             "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"manageGroups",            "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"manageGroupMemberships",  "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editAbsenceTypes",        "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editRegions",             "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editHolidays",            "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editDeclination",         "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"manageDatabase",          "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"viewSystemLog",           "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editSystemLog",           "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"viewEnvironment",         "type"=>"admin", "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editGlobalCalendar",      "type"=>"cal",   "admin"=>1, "director"=>0, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"editGlobalDaynotes",      "type"=>"cal",   "admin"=>1, "director"=>0, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"viewUserProfiles",        "type"=>"user",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>1, "public"=>0),
            array ("p"=>"viewUserAbsenceCounts",   "type"=>"user",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"editAllUserAllowances",   "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editGroupUserAllowances", "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"editAllUserProfiles",     "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editGroupUserProfiles",   "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"editAllUserCalendars",    "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editGroupUserCalendars",  "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"editOwnUserCalendars",    "type"=>"user",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>1, "public"=>0),
            array ("p"=>"editAllUserDaynotes",     "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"editGroupUserDaynotes",   "type"=>"user",  "admin"=>1, "director"=>0, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"viewCalendar",            "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>1, "public"=>1),
            array ("p"=>"viewAllUserCalendars",    "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>1),
            array ("p"=>"viewGroupUserCalendars",  "type"=>"view",  "admin"=>1, "director"=>0, "manager"=>1, "assistant" =>1, "user"=>1, "public"=>0),
            array ("p"=>"viewYearCalendar",        "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>1, "public"=>0),
            array ("p"=>"viewAnnouncements",       "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>1, "public"=>0),
            array ("p"=>"useMessageCenter",        "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>1, "public"=>0),
            array ("p"=>"viewStatistics",          "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
            array ("p"=>"viewAllGroups",           "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>0, "assistant" =>0, "user"=>0, "public"=>0),
            array ("p"=>"viewFastEdit",            "type"=>"view",  "admin"=>1, "director"=>1, "manager"=>1, "assistant" =>1, "user"=>0, "public"=>0),
         );

$types = array (
            "admin",
            "cal",
            "user",
            "view",
         );

$roles = array (
            "admin",
            "director",
            "manager",
            "assistant",
            "user",
            "public",
         );

/**
 * Set the scheme to load
 */
$scheme="Default";
if (isset($_REQUEST['scheme'])) $scheme = $_REQUEST['scheme'];

/**
 * ========================================================================
 * ACTIVATE
 */
if ( isset($_POST['btn_permActivate']) ) {

   $C->saveConfig("permissionScheme",$_POST['sel_scheme']);
   /**
    * Log this event
    */
   $LOG->log("logPermission",$L->checkLogin(),"log_perm_activated", $_POST['sel_scheme']);
   header("Location: ".$_SERVER['PHP_SELF']."?scheme=".$_POST['sel_scheme']);
   die();
}
/**
 * ========================================================================
 * DELETE
 */
else if ( isset($_POST['btn_permDelete']) ) {

   if ($_POST['sel_scheme']!="Default") {
      $P->deleteScheme($_POST['sel_scheme']);
      $C->saveConfig("permissionScheme","Default");
      /**
       * Log this event
       */
      $LOG->log("logPermission",$L->checkLogin(),"log_perm_deleted", $_POST['sel_scheme']);
      header("Location: ".$_SERVER['PHP_SELF']."?scheme=Default");
      die();
   }
}
/**
 * ========================================================================
 * RESET, CREATE
 * Reset Default permission scheme or create a new with standard settings
 */
else if ( isset($_POST['btn_permReset']) OR isset($_POST['btn_permCreate']) ) 
{
   $error=FALSE;
   $event = "log_perm_reset";
   if ( isset($_POST['btn_permCreate']) ) {
      if (!preg_match('/^[a-zA-Z0-9-]*$/', $_POST['txt_newScheme'])) {
         $error=TRUE;
         $err_short=$LANG['err_input_caption'];
         $err_long=$LANG['err_input_perm_invalid_1'];
         $err_long.=$_POST['txt_newScheme'];
         $err_long.=$LANG['err_input_perm_invalid_2'];
         $err_module=$_SERVER['SCRIPT_NAME'];
         $err_btn_close=FALSE;
      }
      else {
         $scheme = $_POST['txt_newScheme'];
         if ($P->schemeExists($scheme)) {
            $error=TRUE;
            $err_short=$LANG['err_input_caption'];
            $err_long=$LANG['err_input_perm_exists_1'];
            $err_long.=$_POST['txt_newScheme'];
            $err_long.=$LANG['err_input_perm_exists_2'];
            $err_module=$_SERVER['SCRIPT_NAME'];
            $err_btn_close=FALSE;
         }
      }
     $event = "log_perm_created";
   }

   if (!$error) {
      /**
       * First, delete the existing scheme entries
       */
      $P->deleteScheme($scheme);

      /**
       * Then create new entries based on default array
       */
      foreach($perms as $perm) {
         foreach($roles as $role) {
            $P->setPermission($scheme,$perm['p'],$role,$perm[$role]);
         }
      }

      /**
       * Log this event
       */
      $LOG->log("logPermission",$L->checkLogin(), $event, $scheme);
      header("Location: ".$_SERVER['PHP_SELF']."?scheme=".$scheme);
      die();
   }
}
/**
 * ========================================================================
 * APPLY
 */
else if ( isset($_POST['btn_permApply']) ) {

   foreach($perms as $perm) {
      foreach($roles as $role) {
         if ( isset($_POST['chk_'.$perm['p'].'_'.$role]) && $_POST['chk_'.$perm['p'].'_'.$role] )
            $P->setPermission($scheme,$perm['p'],$role,1);
         else
            $P->setPermission($scheme,$perm['p'],$role,0);
      }
   }
   /**
    * Make sure no admin locks himself out of editing the permission scheme
    */
   $P->setPermission($scheme,"editPermissionScheme","admin",1);
   /**
    * Log this event
    */
   $LOG->log("logPermission",$L->checkLogin(),"log_perm_changed", $scheme);
   header("Location: ".$_SERVER['PHP_SELF']."?scheme=".$scheme);
   die();
}

if (isset($_POST['sel_scheme'])) header("Location: ".$_SERVER['PHP_SELF']."?scheme=".$_POST['sel_scheme']);

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_permissions'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Permissions';
}
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <form class="form" name="form-permissions" method="POST" action="<?=$_SERVER['PHP_SELF']."?scheme=".$scheme?>">
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="<?=count($roles)+1?>">
               <?php printDialogTop($LANG['perm_title'].$scheme, $help, "ico_locked.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-menu" colspan="<?=count($roles)+1?>" style="text-align: left;">
               <input name="btn_permApply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">&nbsp;
               <input name="btn_permReset" type="submit" class="button" value="<?=$LANG['btn_reset']?>" onclick="return confirmSubmit('<?=$LANG['perm_reset_confirm']?>')">&nbsp;
               <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
            </td>
         </tr>

         <?php $style="2";
         foreach ($types as $type) { ?>
         <!-- PERMISSION GROUP: <?=$type?> -->
            <tr>
               <td class="dlg-caption" style="text-align: left;">
                  <img id="<?=$type?>.img" class="noprint" alt="Toggle" title="Toggle section..." src="themes/<?=$theme?>/img/hide_section.gif" style="vertical-align: middle;" border="0" onclick="toggletr('<?=$type?>',<?=count($perms)?>);">
                  <?=$LANG['perm_col_perm_'.$type]?>
               </td>
               <?php foreach ($roles as $role) { ?>
               <td class="dlg-caption-tt" style="text-align: center;" title="<?=$LANG['perm_col_'.$role.'_tt']?>"><?=$LANG['perm_col_'.$role]?></td>
               <?php } ?>
            </tr>

            <?php
            $i=0;
            foreach ($perms as $perm) {
               if ($style=="1") $style="2"; else $style="1";
               if ($perm['type']==$type) {
                  $i++;
                  ?>
                  <tr id="<?=$type?>-<?=$i?>">
                     <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                        <span class="config-key"><?=$LANG['perm_perm_'.$perm['p'].'_title']?></span><br>
                        <span class="config-comment"><?=$LANG['perm_perm_'.$perm['p'].'_desc']?></span>
                     </td>
                     <?php foreach ($roles as $role) { ?>
                     <td class="config-row<?=$style?>" style="text-align: center;">
                        <input name="chk_<?=$perm['p']?>_<?=$role?>" id="chk_<?=$perm['p']?>_<?=$role?>" value="chk_<?=$perm['p']?>_<?=$role?>" type="checkbox" <?=(($P->isAllowed($scheme,$perm['p'],$role))?"CHECKED":"")?>>
                     </td>
                     <?php } ?>
                  </tr>
               <?php } ?>
            <?php } ?>

            <tr>
               <td class="dlg-menu" colspan="<?=count($roles)+1?>" style="text-align: left;">
                  <input name="btn_permApply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">&nbsp;
                  <input name="btn_permReset" type="submit" class="button" value="<?=$LANG['btn_reset']?>" onclick="return confirmSubmit('<?=$LANG['perm_reset_confirm']?>')">&nbsp;
                  <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
               </td>
            </tr>
         <?php } ?>

      </table>
      </form>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
