<?php
/**
 * viewprofile.php
 *
 * Displays the user profile dialog for viewing
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
require_once( "models/avatar_model.php" );
require_once( "models/config_model.php" );
require_once( "models/group_model.php" );
require_once( "models/login_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );

$A  = new Absence_model;
$AV = new Avatar_model;
$B  = new Allowance_model;
$C  = new Config_model;
$G  = new Group_model;
$L  = new Login_model;
$T  = new Template_model;
$U  = new User_model;
$UL = new User_model;
$UG = new User_group_model;

/**
 * Find logged in user
 */
if ($user = $L->checkLogin()) $UL->findByName($user);

if ( isset($_REQUEST['username']) ) $req_username = $_REQUEST['username'];
else $req_username=$user;

$message = false;

/**
 * Check if allowed
 */
if (!isAllowed("viewUserProfiles")) showError("notallowed", TRUE);

$U->findByName($req_username);

/**
 * Default period for absence count
 */
$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today     = getdate();
$countfrom = str_replace("-","",$C->readConfig("defperiodfrom"));
$countto = str_replace("-","",$C->readConfig("defperiodto"));

/**
 * Process form
 */
if ( isset($_POST['btn_send']) ) 
{
   $to = $U->email;
   sendEmail($to, stripslashes($_POST['subject']), stripslashes($_POST['msg']));
   $message     = true;
   $msg_type    = 'success';
   $msg_title   = $LANG['success'];
   $msg_caption = $LANG['view_profile_title'];
   $msg_text    = $LANG['message_msgsent'];
}
else if ( isset($_POST['btn_refresh']) ) 
{
   /**
    * Adjust period for absence count
    */
   $countfrom = stripslashes($_POST['cntfrom']);
   $countto = stripslashes($_POST['cntto']);
}
/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_viewprofile'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) 
{
   $help .= 'View+Profile';
}
require( "includes/header_html_inc.php" );
?>
<div id="content">
   <div id="content-content">
      
      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <form name="userprofile" method="POST" action="<?=$_SERVER['PHP_SELF']."?username=".$U->username?>">
         <table class="dlg">
            <tr>
               <td class="dlg-header">
                  <?php printDialogTop($LANG['view_profile_title'], $help, "ico_users.png"); ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-body">
                  <table class="dlg-frame">
                     <tr>
                        <td class="dlg-body" width="110" rowspan="9">
                           <?php
                           if ($AV->find($U->username)) {
                              echo "<img src=\"".$AV->path.$AV->filename.".".$AV->fileextension."\" align=\"top\" border=\"0\" alt=\"".$U->username."\" title=\"".$U->username."\">";
                           }
                           else {
                              echo "<img src=\"".$AV->path."noavatar.gif\" align=\"top\" border=\"0\" alt=\"No avatar\" title=\"No avatar\">";
                           }
                           ?>
                        </td>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_name']?></td>
                        <td class="dlg-body2"><b><?=$U->title." ".$U->firstname." ".$U->lastname?></b></td>
                     </tr>
                     <tr>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_uname']?></td>
                        <td class="dlg-body"><?=$U->username?></td>
                     </tr>
                     <tr>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_position']?></td>
                        <td class="dlg-body"><?=$U->position?></td>
                     </tr>
                     <tr>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_idnumber']?></td>
                        <td class="dlg-body"><?=$U->idnumber?></td>
                     </tr>
                     <tr>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_group']?></td>
                        <td class="dlg-body">
                           <?php
                           $ugroups = $UG->getAllforUser($U->username);
                           foreach ($ugroups as $row) {
                              $G->findByName($row['groupname']);
                              echo $row['groupname']." - ".$G->description." (".ucfirst($row['type']).")<br>";
                           }
                           ?>
                        </td>
                     </tr>
                     <tr>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_phone']?></td>
                        <td class="dlg-body"><?=$U->phone?></td>
                     </tr>
                     <tr>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_mobile']?></td>
                        <td class="dlg-body"><?=$U->mobile?></td>
                     </tr>
                     <tr>
                        <td class="dlg-body" width="80"><?=$LANG['show_profile_email']?></td>
                        <td class="dlg-body"><?=$U->email?></td>
                     </tr>
                  </table>
               </td>
            </tr>

            <?php if (isAllowed("viewUserAbsenceCounts") AND !$UG->isGroupManagerOfUser($U->username, $UL->username) ) { ?>
            <tr>
               <td class="dlg-bodyffc">
                  <div align="center">
                  <?php include( "includes/absencecount_inc.php" ); ?>
                  </div>
               </td>
            </tr>
            <?php } ?>

            <tr>
               <td class="dlg-menu">
                  <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
                  <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
               </td>
            </tr>
         </table>
      </form>
   </div>
</div>
<?php
require( "includes/footer_inc.php" );
?>
