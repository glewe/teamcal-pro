<?php
/**
 * register.php
 *
 * Displays and runs the user registration dialog
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

session_start(); // Session needed to get the stored security code

/**
 * Includes
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");

require_once ("models/config_model.php");
require_once ("models/group_model.php");
require_once ("models/login_model.php");
require_once ("models/log_model.php");
require_once ("models/user_model.php");
require_once ("models/user_group_model.php");
require_once ("models/user_option_model.php");

$C = new Config_model;
$G = new Group_model;
$L = new Login_model;
$LOG = new Log_model;
$U = new User_model;
$UA = new User_model;
$UB = new User_model;
$UG = new User_group_model;
$UO = new User_option_model;

$error = "";
$information = "";

if ( isset($_POST['btn_submit']) AND in_array($_POST['lst_group'],$G->getGroups()) ) {
   if (!strlen($_POST['txt_lastname']) || !strlen($_POST['txt_username']) || !strlen($_POST['txt_email']) || !strlen($_POST['txt_password']) || !strlen($_POST['txt_code'])) {
      $error = $LANG['register_error_incomplete'];
   }
   else if (!preg_match('/^[a-zA-Z0-9]*$/', $_POST['txt_username'])) {
      $error = $LANG['register_error_username_format'];
   }
   else if (!validEmail(trim($_POST['txt_email']))) {
      $error = $LANG['register_error_email'];
   }
   else {
      $number = $_POST['txt_code'];
      if (md5($number) == $_SESSION['image_random_value']) {
         if ($U->findByName(trim($_POST['txt_username']))) {
            $error = $LANG['register_error_username'];
         }
         else {
            /**
             * Create the user.
             */
            $U->username = trim(strip_tags($_POST['txt_username']));
            $U->password = crypt(trim(strip_tags($_POST['txt_password'])), $CONF['salt']);
            $U->firstname = trim(strip_tags($_POST['txt_firstname']));
            $U->lastname = trim(strip_tags($_POST['txt_lastname']));
            $U->email = trim($_POST['txt_email']);
            $U->clearUserType($CONF['UTADMIN']);
            $U->clearUserType($CONF['UTDIRECTOR']);
            $U->setUserType($CONF['UTMALE']);
            $U->setUserType($CONF['UTUSER']);
            $U->clearStatus($CONF['USLOCKED']);
            $U->clearStatus($CONF['USHIDDEN']);
            $U->clearStatus($CONF['USLOGLOC']);
            $U->notify = 0;
            $U->create();

            $UG->createUserGroupEntry($U->username, trim($_POST['lst_group']), "member");

            switch ($_POST['opt_gender']) {
               case "ut_male" :
                  $U->setUserType($CONF['UTMALE']);
                  break;
               case "ut_female" :
                  $U->clearUserType($CONF['UTMALE']);
                  break;
               default :
                  $U->setUserType($CONF['UTMALE']);
                  break;
            }

            $UO->create($U->username, "owngroupsonly", "no");
            $UO->create($U->username, "showbirthday", "no");
            $UO->create($U->username, "ignoreage", "no");
            $UO->create($U->username, "notifybirthday", "no");
            $UO->create($U->username, "language", "english");
            $UO->create($U->username, "defgroup", "All");

            /**
             * Prepare email to admin
             */
            $UA->findByName("admin");
            $asubject = $LANG['register_admin_mail_subject'];
            $amessage = $LANG['register_admin_mail_greeting'];
            $amessage .= $LANG['register_admin_mail_message'];
            $amessage = str_replace("[LASTNAME]",$U->lastname,$amessage);
            $amessage = str_replace("[FIRSTNAME]",$U->firstname,$amessage);
            $amessage = str_replace("[USERNAME]",$U->username,$amessage);

            $fullname = $U->firstname . " " . $U->lastname;
            $LOG->log("logRegistration", $L->checkLogin(), "log_user_registered", $U->username . " (" . $fullname . ")");

            $information = $LANG['register_success'];

            $subject = $LANG['register_mail_subject'];
            $message = $LANG['register_mail_greeting'];

            if ($C->readConfig("emailConfirmation")) {
               /**
                * eMail confirmation required
                */
               $U->setStatus($CONF['USLOCKED']);
               $U->setStatus($CONF['USHIDDEN']);
               $U->update($U->username);
               $alphanum = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
               $verifycode = substr(str_shuffle($alphanum), 0, 32);
               $UO->create($U->username, "verifycode", $verifycode);
               $message .= $LANG['register_mail_verify_1'];
               $message .= $CONF['app_url']."/verify.php?username=".$U->username."&verify=".$verifycode."\n\n";
               $message2 = $LANG['register_mail_verify_2a'];
               $information .= $LANG['register_success_verify'];
               $amessage .= $LANG['register_admin_mail_message_1'];
            }

            if ($C->readConfig("adminApproval")) {
               /**
                * Admin approval required
                */
               $U->setStatus($CONF['USLOCKED']);
               $U->setStatus($CONF['USHIDDEN']);
               $U->update($U->username);
               $UO->create($U->username, "approval", "notyet");
               $message2 = $LANG['register_mail_verify_2b'];
               $information .= $LANG['register_success_approval'];
               $amessage .= $LANG['register_admin_mail_message_2'];
            }

            /**
             * eMail to Admin
             */
            $amessage .= $LANG['register_admin_mail_message_3'];
            $ato = $UA->email;
            sendEmail($ato, $asubject, $amessage);

            /**
             * eMail to User
             */
            $message .= $message2;
            $message .= $LANG['register_mail_verify_3'];
            $message = str_replace("[USERNAME]",$U->username,$message);
            $message = str_replace("[PASSWORD]",trim($_POST['txt_password']),$message);
            $to = $U->email;
            sendEmail($to, $subject, $message);
         }
      }
      else {
         $error = $LANG['register_error_code'];
      }
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_register'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual")).'User+Registration';

require("includes/header_html_inc.php");
?>
<div id="content">
   <div id="content-content">
      <form name="login" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
         <table class="dlg">
            <tr>
               <td class="dlg-header">
                  <?php printDialogTop($LANG['register_title'], $help, "ico_register.png"); ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-body" style="padding-left: 8px;">
                  <fieldset><legend><?=$LANG['register_frame']?></legend>
                     <table class="dlg-frame">
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_lastname']?></strong></td>
                           <td class="dlg-body">
                              <input name="txt_lastname" id="txt_lastname" size="30" type="text" class="text" value="<?=(isset($_POST['txt_lastname']) && strlen($_POST['txt_lastname']))?$_POST['txt_lastname']:""?>">
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_firstname']?></strong></td>
                           <td class="dlg-body">
                              <input name="txt_firstname" id="txt_firstname" size="30" type="text" class="text" value="<?=(isset($_POST['txt_firstname']) && strlen($_POST['txt_firstname']))?$_POST['txt_firstname']:""?>">
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_username']?></strong></td>
                           <td class="dlg-body">
                              <input name="txt_username" id="txt_username" size="30" type="text" class="text" value="<?=(isset($_POST['txt_username']) && strlen($_POST['txt_username']))?$_POST['txt_username']:""?>">
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_email']?></strong></td>
                           <td class="dlg-body">
                              <input name="txt_email" id="txt_email" size="30" type="text" class="text" value="<?=(isset($_POST['txt_email']) && strlen($_POST['txt_email']))?$_POST['txt_email']:""?>">
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_password']?></strong></td>
                           <td class="dlg-body">
                              <input name="txt_password" id="txt_password" size="30" type="password" class="text" value="">
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_password2']?></strong></td>
                           <td class="dlg-body">
                              <input name="txt_password2" id="txt_password2" size="30" type="password" class="text" value="">
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['show_profile_gender']?></strong></td>
                           <td class="dlg-body">
                              <?php
                              $checked_male="";
                              $checked_female="";
                              if (isset ($_POST['btn_submit'])) {
                                 switch ($_POST['opt_gender']) {
                                    case "ut_male" :
                                       $checked_male="CHECKED";
                                       break;
                                    case "ut_female" :
                                       $checked_female="CHECKED";
                                       break;
                                 }
                              }
                              ?>
                              <input name="opt_gender" id="utmale" type="radio" value="ut_male" <?=$checked_male?>><?=$LANG['show_profile_male']?>
                              &nbsp;&nbsp;
                              <input name="opt_gender" id="utfemale" type="radio" value="ut_female" <?=$checked_female?>><?=$LANG['show_profile_female']?>
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_group']?></strong></td>
                           <td class="dlg-body">
                              <select name="lst_group" id="lst_group" class="select">
                              <?php
                              $selected="";
                              $groups = $G->getAll();
                              foreach ($groups as $row) {
                                 if ( isset($_POST['lst_group']) && strlen($_POST['lst_group']) && ($row['groupname']==$_POST['lst_group']) ) $selected=" SELECTED";
                                 echo "<option class=\"option\" value=\"".$row['groupname']."\"".$selected.">".$row['groupname']."</option>\n\r";
                                 $selected="";
                              }
                              ?>
                              </select>
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body">&nbsp;</td>
                           <td style="vertical-align: top;">
                              <img src="helpers/randomimage_helper.php" alt="" align="bottom">
                           </td>
                        </tr>
                        <tr>
                           <td class="dlg-body"><strong><?=$LANG['register_code']?></strong></td>
                           <td style="vertical-align: top;">
                              <input name="txt_code" id="txt_code" size="8" type="text" class="text" value="">
                           </td>
                        </tr>
                     </table>
                  </fieldset>

                  <?php if ( strlen($error) ) { ?>
                  <fieldset><legend><?=$LANG['register_result']?></legend>
                     <table class="dlg-frame">
                        <tr>
                           <td class="dlg-body" colspan="2">
                              <div class="erraction">
                              <?=$error?>
                              </div>
                           </td>
                        </tr>
                     </table>
                  </fieldset>
                  <?php } elseif (strlen($information)) { ?>
                  <fieldset><legend><?=$LANG['register_result']?></legend>
                     <table class="dlg-frame">
                        <tr>
                           <td class="dlg-body" colspan="2">
                              <div class="class">
                              <?=$information?>
                              </div>
                           </td>
                        </tr>
                     </table>
                  </fieldset>
                  <?php } ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-menu">
                   <input name="btn_submit" type="submit" class="button" value="<?=$LANG['btn_submit']?>">
                   <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
                   <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
               </td>
            </tr>
         </table>
      </form>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
