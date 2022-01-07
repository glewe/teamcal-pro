<?php
/**
 * userlist.php
 *
 * Displays and runs the user administration page
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

require_once( "models/allowance_model.php" );
require_once( "models/avatar_model.php" );
require_once( "models/config_model.php" );
require_once( "models/daynote_model.php" );
require_once( "models/log_model.php" );
require_once( "models/login_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_announcement_model.php" );
require_once( "models/user_group_model.php" );
require_once( "models/user_option_model.php" );

$AV = new Avatar_model;
$A = new Allowance_model;
$C = new Config_model;
$G = new Group_model;
$L = new Login_model;
$LOG = new Log_model;
$N  = new Daynote_model;
$T  = new Template_model;
$U  = new User_model;
$U1  = new User_model;
$UA = new User_announcement_model;
$UG = new User_group_model;
$UO = new User_option_model;

$message = false;

/**
 * Check if allowed
 */
if (!isAllowed("manageUsers")) showError("notallowed");

/**
 * Initiate the search parameters
 */
$sort="ascu";
if ( isset($_REQUEST['sort']) ) $sort = $_REQUEST['sort'];

$searchuser="";
if ( isset($_REQUEST['searchuser']) ) $searchuser = mysql_real_escape_string(trim($_REQUEST['searchuser']));

$searchgroup="All";
if ( isset($_REQUEST['searchgroup']) ) $searchgroup = trim($_REQUEST['searchgroup']);

if ( isset($_POST['btn_usrReset'])) {
   $searchuser="";
   $searchgroup="All";
}

/**
 * =========================================================================
 * ARCHIVE
 */
if ( isset($_POST['btn_usr_archive']) AND isset($_POST['chk_user_active']) ) 
{
   $selected_users = $_POST['chk_user_active'];
   /**
    * Check if one or more users already exists in any archive table.
    * If so, we will not archive anything.
    */
   $exists = FALSE;
   foreach($selected_users as $su=>$value)
   {
      if (!archiveUser($value)) $exists=TRUE;
   }
   
   if (!$exists)
   {
      /**
       * Prepare error message
       */
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['btn_archive_selected'];
      $msg_text    = $LANG['confirmation_archive_selected_users'];
   }
   else 
   {
      /**
       * Prepare failure message
       */
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['btn_archive_selected'];
      $msg_text    = $LANG['confirmation_archive_selected_users_failed'];
   }
}
/**
 * =========================================================================
 * DELETE ACTIVE
 */
else if ( isset($_POST['btn_usr_del']) AND isset($_POST['chk_user_active']) ) 
{
   $selected_users = $_POST['chk_user_active'];
   foreach($selected_users as $su=>$value) 
   {
      deleteUser($value);
   }
   
   /**
    * Prepare confirmation message
    */
   $message     = true;
   $msg_type    = 'success';
   $msg_title   = $LANG['success'];
   $msg_caption = $LANG['btn_delete_selected'];
   $msg_text    = $LANG['confirmation_delete_selected_users'];
}
/**
 * =========================================================================
 * DELETE ARCHIVED
 */
else if ( isset($_POST['btn_usr_del_archived']) AND isset($_POST['chk_user_archived']) ) 
{
   $selected_users = $_POST['chk_user_archived'];
   
   foreach($selected_users as $su=>$value) 
   {
      deleteUser($value, TRUE);
   }
   
   /**
    * Prepare confirmation message
    */
   $message     = true;
   $msg_type    = 'success';
   $msg_title   = $LANG['success'];
   $msg_caption = $LANG['btn_delete_selected'];
   $msg_text    = $LANG['confirmation_delete_selected_users'];
}
/**
 * =========================================================================
 * RESET PASSWORD
 */
else if ( isset($_POST['btn_usr_pwd_reset']) AND isset($_POST['chk_user_active']) ) 
{
   $selected_users = $_POST['chk_user_active'];
   foreach($selected_users as $su=>$value) 
   {
      /**
       * Find user and reset password
       */
      $U->findByName($value);
      $newpwd = generatePassword();
      $U->password = crypt($newpwd,$CONF['salt']);
      $U->last_pw_change = date("Y-m-d H:i:s");
      $U->update($U->username);
      $U->clearStatus($CONF['USCHGPWD']);
      
      /**
       * Send notification e-mail
       */
      $message = $LANG['notification_greeting'];
      $message .= $LANG['notification_usr_pwd_reset'];
      $message .= $LANG['notification_usr_pwd_reset_user'];
      $message .= $value;
      $message .= "\r\n\r\n";
      $message .= $LANG['notification_usr_pwd_reset_pwd'];
      $message .= $newpwd;
      $message .= "\r\n\r\n";
      $message .= $LANG['notification_sign'];
      $to = $U->email;
      $subject = stripslashes($LANG['notification_usr_pwd_subject']);
      sendEmail($to, $subject, $message);
      
      /**
       * Log this event
       */
      $LOG->log("logUser",$L->checkLogin(),"log_user_pwd_reset", $U->username);
      
      /**
       * Prepare confirmation message
       */
      $message     = true;
      $msg_type    = 'success';
      $msg_title   = $LANG['success'];
      $msg_caption = $LANG['btn_reset_password_selected'];
      $msg_text    = $LANG['confirmation_reset_password_selected'];
   }
}
/**
 * =========================================================================
 * RESTORE ARCHIVED
 */
else if ( isset($_POST['btn_usr_restore']) AND isset($_POST['chk_user_archived']) ) 
{
   $selected_users = $_POST['chk_user_archived'];
   /**
    * Check if one or more users already exists in any active table.
    * If so, we will not restore anything.
    */
   $exists = FALSE;
   foreach($selected_users as $su=>$value)
   {
      if (!restoreUser($value)) $exists=TRUE;
   }
   
   if (!$exists)
   {
      /**
       * Prepare confirmation message
       */
      $message     = true;
      $msg_type    = 'success';
      $msg_title   = $LANG['success'];
      $msg_caption = $LANG['btn_restore_selected'];
      $msg_text    = $LANG['confirmation_restore_selected_users'];
   }
   else 
   {
      /**
       * Prepare failure message
       */
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['btn_restore_selected'];
      $msg_text    = $LANG['confirmation_restore_selected_users_failed'];
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_userlist'];

/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Users';
}

require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
   
      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                           
      <form class="form" name="form-userlist" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
         <!--  USERS =========================================================== -->
         <?php $colspan="5"; ?>
         <table class="dlg">
            <tr>
               <td class="dlg-header" colspan="<?=$colspan?>">
                  <?php printDialogTop($LANG['admin_user_title'], $help, "ico_users.png"); ?>
               </td>
            </tr>
            
            <tr>
               <td class="dlg-body">
                  <div id="tabs">
                     <ul>
                        <li><a href="#tabs-1"><?=$LANG['tab_active_users']?></a></li>
                        <li><a href="#tabs-2"><?=$LANG['tab_archived_users']?></a></li>
                     </ul>
   
                     <!-- =======================================================
                          ACTIVE USERS
                     -->
                     <div id="tabs-1">
                     
                        <table class="dlg">
                           <tr>
                              <td class="dlg-caption" style="text-align: left; padding-left: 8px;"></td>
                              <td class="dlg-caption" style="text-align: left; padding-left: 8px;">
                                 <?php if ( $sort=="descu" ) { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=ascu"?>"><img src="themes/<?=$theme?>/img/asc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_asc']?>"></a>
                                 <?php }else { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=descu"?>"><img src="themes/<?=$theme?>/img/desc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_desc']?>"></a>
                                  <?php } ?>
                                  &nbsp;<?=$LANG['admin_user_user']?>
                              </td>
                              <td class="dlg-caption" style="text-align: center;"><?=$LANG['admin_user_attributes']?></td>
                              <td class="dlg-caption" style="text-align: left;">
                                 <?php if ( $sort=="descl" ) { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=ascl"?>"><img src="themes/<?=$theme?>/img/asc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_asc']?>"></a>
                                 <?php }else { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=descl"?>"><img src="themes/<?=$theme?>/img/desc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_desc']?>"></a>
                                  <?php } ?>
                                  <?=$LANG['admin_user_lastlogin']?>
                              </td>
                              <td class="dlg-caption" style="text-align: right; padding-right: 8px;"><?=$LANG['admin_user_action']?></td>
                           </tr>
                           <tr>
                              <td class="dlg-row1" colspan="<?=$colspan?>"><img src="themes/<?=$theme?>/img/ico_add.png" alt="Add" title="Add" align="middle" style="padding-right: 2px;">
                                 <input name="btn_usr_create" type="button" class="button" value="<?=$LANG['btn_create']?>" onclick="javascript:this.blur();openPopup('addprofile.php','addprofile','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=no,dependent=1,width=560,height=680');">&nbsp;&nbsp;
                                 <i><?=$LANG['admin_create_new_user']?></i>
                              </td>
                           </tr>
                           <tr>
                              <td class="dlg-row1" colspan="<?=$colspan?>"><img src="themes/<?=$theme?>/img/ico_import.png" alt="Import" title="Import" align="middle" style="padding-right: 2px;">
                                 <input name="btn_usr_import" type="button" class="button" value="<?=$LANG['btn_import']?>" onclick="javascript:this.blur();openPopup('userimport.php','addprofile','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=no,dependent=1,width=510,height=400');">&nbsp;&nbsp;
                                 <i><?=$LANG['admin_import_user']?></i>
                              </td>
                           </tr>
                           <?php
                              switch ($sort) 
                              {
                                 case "ascu":
                                 $ord1 = 'lastname';
                                 $ord2 = 'firstname';
                                 $sort1 = 'ASC';
                                 break;
               
                                 case "descu":
                                 $ord1 = 'lastname';
                                 $ord2 = 'firstname';
                                 $sort1 = 'DESC';
                                 break;
                                 
                                 case "ascl":
                                 $ord1 = 'last_login';
                                 $ord2 = 'lastname';
                                 $sort1 = 'ASC';
                                 break;
                                 
                                 case "descl":
                                 $ord1 = 'last_login';
                                 $ord2 = 'lastname';
                                 $sort1 = 'DESC';
                                 break;
                                 
                                 default: 
                                 $ord1 = 'lastname';
                                 $ord2 = 'firstname';
                                 $sort1 = 'ASC';
                                 break;
                              }
                              
                              if (strlen($searchuser)) 
                              {
                                 $userarray = $U->getAllLike($searchuser);
                              }
                              else 
                              {
                                 $userarray = $U->getAll($ord1, $ord2, $sort1);
                              }
                              
                              $botstyle  = "";
                              $botborder = "";
                              $numusers = count($userarray);
                              $ui=1;
                              $printrow=1;
                              foreach ($userarray as $row)
                              {
                                 if ($searchgroup!="All") 
                                 {
                                    if (!$UG->isMemberOfGroup($row['username'],$searchgroup)) continue;
                                 }
                                 
                                 $U->findByName($row['username']);
                                 if ( $U->firstname!="" ) $showname = $U->lastname.", ".$U->firstname; else $showname = $U->lastname;
                                 $templateUser = "";
                                 
                                 if ( $U->checkUserType($CONF['UTADMIN']) ) 
                                 {
                                    $icon = "ico_usr_admin";
                                    $icon_tooltip = $LANG['icon_admin'];
                                 }
                                 else if ( $U->checkUserType($CONF['UTDIRECTOR']) ) 
                                 {
                                    $icon = "ico_usr_director";
                                    $icon_tooltip = $LANG['icon_director'];
                                 }
                                 else if ( $U->checkUserType($CONF['UTMANAGER']) ) 
                                 {
                                    $icon = "ico_usr_manager";
                                    $icon_tooltip = $LANG['icon_manager'];
                                 }
                                 else if ( $U->checkUserType($CONF['UTASSISTANT']) ) 
                                 {
                                    $icon = "ico_usr_assistant";
                                    $icon_tooltip = $LANG['icon_assistant'];
                                 }
                                 else if ( $U->checkUserType($CONF['UTTEMPLATE']) ) 
                                 {
                                    $icon = "ico_users";
                                    $icon_tooltip = $LANG['icon_template'];
                                    $templateUser = $LANG['template_user'];
                                 }
                                 else 
                                 {
                                    $icon = "ico_usr";
                                    $icon_tooltip = $LANG['icon_user'];
                                 }
                                 
                                 if ( !$U->checkUserType($CONF['UTMALE']) ) $icon .= "_f.png";  else $icon .= ".png";
                                 if ( !$U->checkStatus($CONF['USLOCKED']) ) $lockedicon = "";   else $lockedicon = "ico_locked.png";
                                 if ( !$U->checkStatus($CONF['USHIDDEN']) ) $hiddenicon = "";   else $hiddenicon = "ico_delete.png";
                                 if ( !$U->checkStatus($CONF['USLOGLOC']) ) $loglocicon = "";   else $loglocicon = "ico_onhold.png";
                                 if ( !$UO->find($U->username,"verifycode") ) $verifyicon = ""; else $verifyicon = "ico_verify.png";
                  
                                 if ($printrow==1) $printrow=2; else $printrow=1;
                                 if ($ui==$numusers) {
                                    $botstyle  = " style=\"border-bottom: 1px solid #000000;\"";
                                    $botborder = " border-bottom: 1px solid #000000;";
                                 }
                                 ?>
                                 <!-- <?=$showname?> -->
                                 <tr>
                                    <td class="dlg-row<?=$printrow?>" style="width: 20px; text-align: center;"><?php if ($U->username!="admin") {?><input type="checkbox" name="chk_user_active[]" value="<?=$U->username?>"><?php }?></td>
                                    <td class="dlg-row<?=$printrow?>"><img src="themes/<?=$theme?>/img/<?=$icon?>" align="top" alt="" title="<?=$icon_tooltip?>" style="padding-right: 2px;\"><?=$showname?> (<?=$U->username?>) <?=$templateUser?></td>
                                    <td class="dlg-row<?=$printrow?>" style="text-align: center;">
                                    <?php  if (strlen($loglocicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$loglocicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_logloc']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php }
                                    if (strlen($lockedicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$lockedicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_locked']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php }
                                    if (strlen($hiddenicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$hiddenicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_hidden']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php }
                                    if (strlen($verifyicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$verifyicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_verify']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php } ?>
                                    </td>
                                    <td class="dlg-row<?=$printrow?>"><?=$U->last_login?></td>
                                    <td class="dlg-row<?=$printrow?>" style="text-align: right;">
                                       <input name="btn_usr_edit" type="button" class="button" value="<?=$LANG['btn_edit']?>" onclick="javascript:this.blur();openPopup('editprofile.php?referrer=userlist&amp;username=<?=$U->username?>','editprofile','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=no,dependent=1,width=640,height=720');">&nbsp;
                                    </td>
                                 </tr>
                                 <?php  $ui++;
                              }
                           ?>
                           <tr>
                              <td class="dlg-row<?=$printrow?>" <?=$botstyle?> colspan="<?=$colspan?>">
                                 <input type="checkbox" name="select-all-active" id="select-all-active" style="margin-right: 8px; vertical-align: middle;"><?=$LANG['select_all']?>&nbsp;
                                 <input name="btn_usr_del" type="submit" class="button" value="<?=$LANG['btn_delete_selected']?>" onclick="return confirmSubmit('<?=$LANG['user_delete_confirm']?>')">&nbsp;
                                 <input name="btn_usr_archive" type="submit" class="button" value="<?=$LANG['btn_archive_selected']?>" onclick="return confirmSubmit('<?=$LANG['user_archive_confirm']?>')">&nbsp;
                                 <input name="btn_usr_pwd_reset" type="submit" class="button" value="<?=$LANG['btn_reset_password_selected']?>" onclick="return confirmSubmit('<?=$LANG['user_pwd_reset_confirm']?>')">&nbsp;
                              </td>
                           </tr>
                        </table>
                     </div>
                     
                     <!-- =======================================================
                          ARCHIVED USERS
                     -->
                     <div id="tabs-2">
                     
                        <table class="dlg">
                        
                           <tr>
                              <td class="dlg-caption" style="text-align: left; padding-left: 8px;"></td>
                              <td class="dlg-caption" style="text-align: left; padding-left: 8px;">
                                 <?php if ( $sort=="descu" ) { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=ascu"?>"><img src="themes/<?=$theme?>/img/asc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_asc']?>"></a>
                                 <?php }else { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=descu"?>"><img src="themes/<?=$theme?>/img/desc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_desc']?>"></a>
                                  <?php } ?>
                                  &nbsp;<?=$LANG['admin_user_user']?>
                              </td>
                              <td class="dlg-caption" style="text-align: center;"><?=$LANG['admin_user_attributes']?></td>
                              <td class="dlg-caption" style="text-align: left;">
                                 <?php if ( $sort=="descl" ) { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=ascl"?>"><img src="themes/<?=$theme?>/img/asc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_asc']?>"></a>
                                 <?php }else { ?>
                                    <a href="<?=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=descl"?>"><img src="themes/<?=$theme?>/img/desc.png" border="0" align="top" alt="" title="<?=$LANG['log_sort_desc']?>"></a>
                                  <?php } ?>
                                  <?=$LANG['admin_user_lastlogin']?>
                              </td>
                              <td class="dlg-caption" style="text-align: right; padding-right: 8px;"></td>
                           </tr>
                           <?php
                              switch ($sort) 
                              {
                                 case "ascu":
                                 $ord1 = 'lastname';
                                 $ord2 = 'firstname';
                                 $sort1 = 'ASC';
                                 break;
               
                                 case "descu":
                                 $ord1 = 'lastname';
                                 $ord2 = 'firstname';
                                 $sort1 = 'DESC';
                                 break;
                                 
                                 case "ascl":
                                 $ord1 = 'last_login';
                                 $ord2 = 'lastname';
                                 $sort1 = 'ASC';
                                 break;
                                 
                                 case "descl":
                                 $ord1 = 'last_login';
                                 $ord2 = 'lastname';
                                 $sort1 = 'DESC';
                                 break;
                                 
                                 default: 
                                 $ord1 = 'lastname';
                                 $ord2 = 'firstname';
                                 $sort1 = 'ASC';
                                 break;
                              }
                              
                              if (strlen($searchuser)) 
                              {
                                 $archive_userarray = $U1->getAllLike($searchuser, TRUE);
                              }
                              else 
                              {
                                 $archive_userarray = $U1->getAll($ord1, $ord2, $sort1, TRUE);
                              }
                              
                              $botstyle  = "";
                              $botborder = "";
                              $numusers = count($archive_userarray);
                              $ui=1;
                              $printrow=1;
                              foreach ($archive_userarray as $rowa)
                              {
                                 $U1->findByName($rowa['username'], TRUE);
                                 if ( $U1->firstname!="" ) $showname = $U1->lastname.", ".$U1->firstname; else $showname = $U1->lastname;
                                 $templateUser = "";
                                 
                                 if ( $U1->checkUserType($CONF['UTADMIN']) ) 
                                 {
                                    $icon = "ico_usr_admin";
                                    $icon_tooltip = $LANG['icon_admin'];
                                 }
                                 else if ( $U1->checkUserType($CONF['UTDIRECTOR']) ) 
                                 {
                                    $icon = "ico_usr_director";
                                    $icon_tooltip = $LANG['icon_director'];
                                 }
                                 else if ( $U1->checkUserType($CONF['UTMANAGER']) ) 
                                 {
                                    $icon = "ico_usr_manager";
                                    $icon_tooltip = $LANG['icon_manager'];
                                 }
                                 else if ( $U1->checkUserType($CONF['UTASSISTANT']) ) 
                                 {
                                    $icon = "ico_usr_assistant";
                                    $icon_tooltip = $LANG['icon_assistant'];
                                 }
                                 else if ( $U1->checkUserType($CONF['UTTEMPLATE']) ) 
                                 {
                                    $icon = "ico_users";
                                    $icon_tooltip = $LANG['icon_template'];
                                    $templateUser = $LANG['template_user'];
                                 }
                                 else 
                                 {
                                    $icon = "ico_usr";
                                    $icon_tooltip = $LANG['icon_user'];
                                 }
                                 
                                 if ( !$U1->checkUserType($CONF['UTMALE']) ) $icon .= "_f.png";  else $icon .= ".png";
                                 if ( !$U1->checkStatus($CONF['USLOCKED']) ) $lockedicon = "";   else $lockedicon = "ico_locked.png";
                                 if ( !$U1->checkStatus($CONF['USHIDDEN']) ) $hiddenicon = "";   else $hiddenicon = "ico_delete.png";
                                 if ( !$U1->checkStatus($CONF['USLOGLOC']) ) $loglocicon = "";   else $loglocicon = "ico_onhold.png";
                                 if ( !$UO->find($U1->username,"verifycode") ) $verifyicon = ""; else $verifyicon = "ico_verify.png";
                  
                                 if ($printrow==1) $printrow=2; else $printrow=1;
                                 if ($ui==$numusers) {
                                    $botstyle  = " style=\"border-bottom: 1px solid #000000;\"";
                                    $botborder = " border-bottom: 1px solid #000000;";
                                 }
                                 ?>
                                 <!-- <?=$showname?> -->
                                 <tr>
                                    <td class="dlg-row<?=$printrow?>" style="width: 20px; text-align: center;"><?php if ($U1->username!="admin") {?><input type="checkbox" name="chk_user_archived[]" value="<?=$U1->username?>"><?php }?></td>
                                    <td class="dlg-row<?=$printrow?>"><img src="themes/<?=$theme?>/img/<?=$icon?>" align="top" alt="" title="<?=$icon_tooltip?>" style="padding-right: 2px;\"><?=$showname?> (<?=$U1->username?>) <?=$templateUser?></td>
                                    <td class="dlg-row<?=$printrow?>" style="text-align: center;">
                                    <?php  if (strlen($loglocicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$loglocicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_logloc']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php }
                                    if (strlen($lockedicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$lockedicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_locked']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php }
                                    if (strlen($hiddenicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$hiddenicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_hidden']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php }
                                    if (strlen($verifyicon)) { ?>
                                       <img src="themes/<?=$theme?>/img/<?=$verifyicon?>" width="16" height="16" align="top" alt="" style="padding-right: 2px;" title="<?=$LANG['tt_user_verify']?>">
                                    <?php } else { ?>
                                       &nbsp;
                                    <?php } ?>
                                    </td>
                                    <td class="dlg-row<?=$printrow?>"><?=$U1->last_login?></td>
                                    <td class="dlg-row<?=$printrow?>" style="text-align: right;"></td>
                                 </tr>
                                 <?php  $ui++;
                              }
                           ?>
                           <tr>
                              <td class="dlg-row<?=$printrow?>" <?=$botstyle?> colspan="<?=$colspan?>">
                                 <input type="checkbox" name="select-all-archived" id="select-all-archived" style="margin-right: 8px; vertical-align: middle;"><?=$LANG['select_all']?>&nbsp;
                                 <input name="btn_usr_del_archived" type="submit" class="button" value="<?=$LANG['btn_delete_selected']?>" onclick="return confirmSubmit('<?=$LANG['user_delete_confirm']?>')">&nbsp;
                                 <input name="btn_usr_restore" type="submit" class="button" value="<?=$LANG['btn_restore_selected']?>" onclick="return confirmSubmit('<?=$LANG['user_restore_confirm']?>')">&nbsp;
                              </td>
                           </tr>
                        </table>
                     </div>
                     
                  </div>
               </td>
            </tr>
         </table>
      </form>
   </div>
</div>
<script type="text/javascript">
$(function() { 
   $( "#tabs" ).tabs(); 
});                           
$('#select-all-active').click(function(event) {   
   if(this.checked) {
      // Tick each checkbox
      $(":checkbox[name='chk_user_active[]']").each(function() {
         this.checked = true;                        
      });
   }
   else {
      // Untick each checkbox
      $(":checkbox[name='chk_user_active[]']").each(function() {
         this.checked = false;                        
      });
   }
});
$('#select-all-archived').click(function(event) {   
   if(this.checked) {
      // Tick each checkbox
      $(":checkbox[name='chk_user_archived[]']").each(function() {
         this.checked = true;                        
      });
   }
   else {
      // Untick each checkbox
      $(":checkbox[name='chk_user_archived[]']").each(function() {
         this.checked = false;                        
      });
   }
});
</script>
<?php require("includes/footer_inc.php"); ?>
