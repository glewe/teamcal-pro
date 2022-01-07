<?php
/**
 * verify.php
 *
 * Verifies a user registration
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
require_once ("models/log_model.php");
require_once ("models/user_model.php");
require_once ("models/user_option_model.php");

$C = new Config_model;
$LOG = new Log_model;
$U = new User_model;
$UA = new User_model;
$UO = new User_option_model;

$error = FALSE;
$info = FALSE;

/**
 * Check URL request parameter
 */
if (!isset ($_REQUEST['verify']) || !isset ($_REQUEST['username']) || strlen($_REQUEST['verify'])<>32 || !in_array($_REQUEST['username'],$U->getUsernames()) ) {
   /*
    * Link is incomplete or corrupt
    */
   showError("notarget", TRUE);
}
else {
   $rverify = trim($_REQUEST['verify']);
   $ruser = trim($_REQUEST['username']);
   if ($fverify = $UO->find($ruser, "verifycode")) {
      if ($fverify == $rverify) {
         /**
          * Found the user and a matching verify code
          */
         $UO->deleteUserOption($ruser, "verifycode");
         $info = $LANG['verify_info_success'];
         $U->findByName($ruser);
         $fullname = $U->firstname . " " . $U->lastname;
         if ($C->readConfig("adminApproval")) {
            /**
             * Success but admin needs to approve
             */
            $UA->findByName("admin");
            $subject = $LANG['verify_mail_subject'];
            $message = $LANG['verify_mail_greeting'];
            $message .= $LANG['verify_mail_message'];
            $message = str_replace("[USERNAME]",$U->username,$message);
            $to = $UA->email;
            sendEmail($to, $subject, $message);

            $info .= $LANG['verify_info_approval'];
            $LOG->log("logRegistration", $U->username, "log_user_verify_approval", $U->username . " (" . $fullname . ")");
         }
         else {
            /**
             * Success and no approval needed. Unlock and unhide user.
             */
            $U->clearStatus($CONF['USLOCKED']);
            $U->clearStatus($CONF['USHIDDEN']);
            $U->update($U->username);
            $LOG->log("logRegistration", $U->username, "log_user_verify_unlocked", $U->username . " (" . $fullname . ")");
         }
      }
      else {
         /**
          * Found the user but verify code does not match
          */
         $error = $LANG['verify_err_match'];
         $LOG->log("logRegistration", $U->username, "log_user_verify_mismatch", $U->username . " (" . $fullname . "): ".$rverify);
      }
   }
   else {
      /**
       * Found no verify code or there is none for this user
       */
      if (!$U->findByName($ruser)) {
         /**
          * No surprise, the user dos not exist
          */
         $error = $LANG['verify_err_user'];
         $LOG->log("logRegistration", $ruser, "log_user_verify_usr_notexist", $ruser." : ".$rverify);
      }
      else {
         /**
          * Verfiy code does not exist
          */
         $error = $LANG['verify_err_code'];
         $LOG->log("logRegistration", $ruser, "log_user_verify_code_notexist", $ruser . " : ".$rverify);
      }
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_verify'];
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="3">
               <?php printDialogTop($LANG['verify_title'],"","ico_register.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body" style="padding-left: 8px;">
               <?php if ( strlen($error) ) { ?>
                  <fieldset><legend><?=$LANG['verify_result']?></legend>
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
               <?php } elseif (strlen($info)) { ?>
                  <fieldset><legend><?=$LANG['verify_result']?></legend>
                     <table class="dlg-frame">
                        <tr>
                           <td class="dlg-body" colspan="2">
                              <div class="class">
                              <?=$info?>
                              </div>
                           </td>
                        </tr>
                     </table>
                  </fieldset>
               <?php } ?>
               <br>
            </td>
         </tr>
      </table>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
