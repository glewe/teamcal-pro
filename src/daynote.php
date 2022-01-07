<?php
/**
 * daynote.php
 *
 * Displays the daynote dialog
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
require_once( "models/daynote_model.php" );
require_once( "models/login_model.php" );
require_once( "models/log_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );
require_once( "models/permission_model.php" );

$C = new Config_model;
$L = new Login_model;
$LOG = new Log_model;
$N = new Daynote_model;
$U = new User_model;
$UG= new User_group_model;
$P= new Permission_model;

$allowed = false;
$message = false;

$user=$L->checkLogin();

/**
 * Check authorization
 */
if (!isset($_REQUEST['daynotefor'])) 
{
   /**
    * No user specified. Just display a not allowed message.
    */
   showError("notarget", TRUE);
}

if ( strtolower($_REQUEST['daynotefor'])=="all") 
{
   if (isAllowed("editGlobalDaynotes"))
   {
      $allowed=TRUE;
   }
   else 
   {
      showError("notallowed", TRUE);
   }
}
else 
{
   /**
    * Personal daynote. Let's see if allowed...
    */
   if ($user==$_REQUEST['daynotefor'] OR isAllowed("editAllUserDaynotes")) 
   {
      $allowed=TRUE;
   }
   else if ($UG->shareGroups($user, $_REQUEST['daynotefor']) AND isAllowed("editGroupUserDaynotes") AND !$UG->isGroupManagerOfUser($_REQUEST['daynotefor'], $user) ) 
   {
      $allowed=TRUE;
   }
   else 
   {
      showError("notallowed", TRUE);
   }
}

if (isset ($_REQUEST['region'])) $region = $_REQUEST['region']; else $region = "default";

/**
 * Let's see if we have a note for this day already
 */
$daynote_exists = false;
if ( strtolower($_REQUEST['daynotefor'])=="all" ) 
{
   if ( $N->findByDay($_REQUEST['date'],"all",$region) ) $daynote_exists = true;
}
else 
{
   /**
    * Look for a user-specific daynote for this day And once you're at it get
    * the users full name.
    */
   if ( $N->findByDay($_REQUEST['date'],$_REQUEST['daynotefor'],$region) ) $daynote_exists = true;
   if ( !$U->findByName($_REQUEST['daynotefor']) ) 
   {
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['err_input_caption'];
      $msg_text    = $LANG['err_input_daynote_nouser'].$LANG['err_input_daynote_date'].$_REQUEST['date']."\\n".$LANG['err_input_daynote_username'].$_REQUEST['daynotefor']."\\n";
   }
   else 
   {
      $daynote_user = $U->firstname." ".$U->lastname;
   }
}

/**
 * =========================================================================
 * SAVE
 */
if (isset($_POST['btn_save'])) 
{
   if ( strlen($_POST['daynote']) ) 
   {
      $N->daynote = str_replace("\r\n","<br>",trim($_POST['daynote']));
      $N->update();
      /**
       * Log this event
       */
      $LOG->log("logDaynote",$L->checkLogin(),"log_daynote_updated", $_REQUEST['date']." - ".$_REQUEST['daynotefor']." - ".$region." : ".substr($N->daynote,0,20)."...");
   }
   else 
   {
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['err_input_caption'];
      $msg_text    = $LANG['err_input_daynote_save'];
   }
}
/**
 * =========================================================================
 * CREATE
 */
else if (isset($_POST['btn_create'])) 
{
   if ( strlen($_POST['daynote']) ) 
   {
      $N->yyyymmdd = $_REQUEST['date'];
      $N->daynote = str_replace("\r\n","<br>",trim($_POST['daynote']));
      $N->username = $_REQUEST['daynotefor'];
      $N->region = $region;
      $N->create();
      /**
       * Log this event
       */
      $LOG->log("logDaynote",$L->checkLogin(),"log_daynote_created", $_REQUEST['date']." - ".$_REQUEST['daynotefor']." - ".$region." : ".substr($N->daynote,0,20)."...");
      $daynote_exists=true;
   }
   else 
   {
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['err_input_caption'];
      $msg_text    = $LANG['err_input_daynote_create'];
   }
}
/**
 * =========================================================================
 * DELETE
 */
else if (isset($_POST['btn_delete'])) 
{
   if ( $N->findByDay($_REQUEST['date'],$_REQUEST['daynotefor'],$region) ) 
   {
      $N->deleteByDay($_REQUEST['date'],$_REQUEST['daynotefor'],$region);
      /**
       * Log this event
       */
      $LOG->log("logDaynote",$L->checkLogin(),"log_daynote_deleted", $_REQUEST['date']." - ".$_REQUEST['daynotefor']." - ".$region." : ".substr($N->daynote,0,20)."...");
      $daynote_exists=false;
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_daynote'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Daynote';
}
require( "includes/header_html_inc.php" );
?>
<div id="content">
   <div id="content-content">
      
      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <form name="message" method="POST" action="<?=$_SERVER['PHP_SELF']."?date=".$_REQUEST['date']."&amp;daynotefor=".$_REQUEST['daynotefor']."&amp;region=".$region."&amp;datestring=".$_REQUEST['datestring']?>">
         <table class="dlg">
            <tr>
               <td class="dlg-header" colspan="3">
                  <?php
                  $title=$LANG['daynote_edit_title'].$_REQUEST['datestring']." (".$LANG['month_region'].": ".$region.")";
                  if ( $_REQUEST['daynotefor']!="all" ) $title .= " ".$LANG['daynote_edit_title_for']." ".$daynote_user;
                  printDialogTop($title, $help, "ico_daynote.png");
                  ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-body">
                  <table class="dlg-frame">
                     <tr>
                        <td class="dlg-body"><strong><?=$LANG['daynote_edit_msg_caption']?></strong><br>
                        <?=$LANG['daynote_edit_msg_hint']?></td>
                     </tr>
                     <tr>
                        <td class="dlg-body">
                           <textarea name="daynote" id="daynote" class="text" cols="50" rows="6"><?php if ( $daynote_exists ) echo str_replace("<br>","\r\n",stripslashes(trim($N->daynote))); else echo str_replace("<br>","\r\n",$LANG['daynote_edit_msg']); ?></textarea>
                           <br>
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="dlg-menu">
                  <?php
                  if ($daynote_exists) { ?>
                     <input name="btn_save" type="submit" class="button" value="<?=$LANG['btn_save']?>">
                     <input name="btn_delete" type="submit" class="button" value="<?=$LANG['btn_delete']?>">
                  <?php } else { ?>
                     <input name="btn_create" type="submit" class="button" value="<?=$LANG['btn_create']?>">
                  <?php } ?>
                  <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
                  <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
                  <input name="btn_done" type="button" class="button" onclick="javascript:closeme();" value="<?=$LANG['btn_done']?>">
               </td>
            </tr>
         </table>
      </form>
   </div>
</div>
<?php
require( "includes/footer_inc.php" );
?>
