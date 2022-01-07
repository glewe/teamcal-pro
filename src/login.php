<?php
/**
 * login.php
 *
 * Displays and runs the login dialog
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
 * Load user configuration file
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");

/**
 * Load models
 */
require_once ("models/announcement_model.php");
require_once ("models/config_model.php");
require_once ("models/login_model.php");
require_once ("models/log_model.php");
require_once ("models/user_model.php");
require_once ("models/user_announcement_model.php");
require_once ("models/user_option_model.php");

/**
 * Create model instances
 */
$AN = new Announcement_model;
$C = new Config_model;
$L = new Login_model;
$LOG = new Log_model;
$U = new User_model;
$UA = new User_announcement_model;
$UO = new User_option_model;

/**
 * Initiate view variables
 */
if (isset ($_REQUEST['target'])) $target = $_REQUEST['target']; else $target = '';
$errors = '';
$uname = '';

/**
 * Process form
 */
if (isset($_POST['btn_login'])) {
   $pword = '';
   if (isset($_POST['uname'])) $uname = $_POST['uname'];
   if (isset($_POST['pword'])) $pword = $_POST['pword'];
   
   switch ($L->login($uname,$pword)) {
      case 0 :
      /**
       * Successful login
       */
      $errors = $LANG['login_error_0'];
      $LOG->log("logLogin", $uname, "log_login_success");

      if ($UO->true($uname,"notifybirthday")) {
         $bdayalarm = '';
         $users=$U->getAll();
         foreach($users as $usr) {
            if ( $UO->true($usr['username'],"showbirthday") ) {
               if($UO->true($usr['username'],"ignoreage")) {
                  $birthdate=date("Y-m-d",strtotime($usr['birthday']));
                  if ($birthdate==date("Y-m-d")) {
                     $bdayalarm.= $usr['firstname']." ".$usr['lastname'].": ".$birthdate ."<br>";
                  }
               } else {
                  $birthdate=date("Y-m-d",strtotime($usr['birthday']));
                  $dayofbirth=date("d M",strtotime($usr['birthday']));
                  $age=intval(date("Y"))-intval(substr($usr['birthday'],0,4));
                  if ($dayofbirth==date("d M")) {
                     $bdayalarm.= $usr['firstname']." ".$usr['lastname'].": ".$birthdate ." (".$LANG['cal_age'].": ".$age.")<br>";
                  }
               }
            }
         }
         if (strlen($bdayalarm)) {
            $bdayalarm = "<strong>".$LANG['ann_bday_title'].date("Y-m-d")."</strong><br><br>".$bdayalarm."<br>&nbsp;";
            $tstamp = date("Ymd")."000000";
            if ($AN->read($tstamp)) {
               $UA->unassign($tstamp,$uname);
               $AN->delete($tstamp);
            }
            $AN->save($tstamp,$bdayalarm,1,0);
            $UA->assign($tstamp,$uname);
         }
         if ( file_exists("installation.php") && $uname=="admin" ) {
            $tstamp = date("YmdHis");
            $message = "<span style=\"background-color: #AA0000; color: #FFFFFF; font-weight: bold; padding: 4px;\">".$LANG['err_instfile_title']."</span><br><br>" .
                       "<span style=\"color: #0000AA;\">".$LANG['err_instfile']."<br><br>".
                       "[TeamCal Pro Installation]</span>";
            $popup=1;
            $silent=0;
            $AN->save($tstamp,$message,$popup,$silent);
            $UA->assign($tstamp,$uname);
         }
      }
      if (isset($_POST['hidden_target']) AND !empty($_POST['hidden_target']))
      {
         header("Location: ".$_POST['hidden_target']);
      }
      else
      {
         header("Location: index.php?action=".$C->readConfig("homepage"));
      }
      break;

      case 1 :
      /**
       * Username or password missing
       */
      $errors = $LANG['login_error_1'];
      $LOG->log("logLogin", $uname, "log_login_missing");
      break;

      case 2 :
      /**
       * Username unknown
       */
      $errors = $LANG['login_error_2'];
      $LOG->log("logLogin", $uname, "log_login_unknown");
      break;

      case 3 :
      /**
       * Account is locked
       */
      $errors = $LANG['login_error_3'];
      $LOG->log("logLogin", $uname, "log_login_locked");
      break;

      case 4 :
      case 5 :
      /**
       * 4: Password incorrect 1st time
       * 5: Password incorrect 2nd or higher time
       */
      $U->findByName($uname);
      $errors = $LANG['login_error_4a'];
      $errors .= strval($U->bad_logins);
      $errors .= $LANG['login_error_4b'];
      $errors .= $C->readConfig("badLogins");
      $errors .= $LANG['login_error_4c'];
      $errors .= $C->readConfig("gracePeriod");
      $errors .= $LANG['login_error_4d'];
      $LOG->log("logLogin", $uname, "log_login_pwd");
      break;

      case 6 :
      /**
       * Login disabled due to too many bad login attempts
       */
      $now = date("U");
      $U->findByName($uname);
      $errors = $LANG['login_error_6a'];
      $errors .= strval(intval($C->readConfig("gracePeriod")) - ($now - $U->bad_logins_start));
      $errors .= $LANG['login_error_6b'];
      $LOG->log("logLogin", $uname, "log_login_attempts");
      break;

      case 7 :
      /**
       * Password incorrect (no bad login count)
       */
      $errors = $LANG['login_error_7'];
      $LOG->log("logLogin", $uname, "log_login_pwd");
      break;

      case 8 :
      /**
       * Account not verified
       */
      $errors = $LANG['login_error_8'];
      $LOG->log("logLogin", $uname, "log_login_not_verified");
      break;

      case 91 :
      /**
       * LDAP error: password missing
       */
      $errors = $LANG['login_error_91'];
      $LOG->log("logLogin", $uname, "log_login_ldap_pwd_missing");
      break;

      case 92 :
      /**
       * LDAP error: bind failed
       */
      $errors = $LANG['login_error_92'];
      $LOG->log("logLogin", $uname, "log_login_ldap_bind_failed");
      break;

      case 93 :
      /**
       * LDAP error: Unable to connect
       */
      $errors = $LANG['login_error_93'];
      $LOG->log("logLogin", $uname, "log_login_ldap_connect_failed");
      break;
      
      case 94 :
      /**
       * LDAP error: Start of TLS encryption failed
       */
      $errors = $LANG['login_error_94'];
      $LOG->log("logLogin", $uname, "log_login_ldap_tls_failed");
      break;
      
      case 95 :
      /**
       * LDAP error: Username not found
       */
      $errors = $LANG['login_error_95'];
      $LOG->log("logLogin", $uname, "log_login_ldap_username");
      break;
      
      case 96 :
      /**
       * LDAP error: LDAP search bind failed
       */
      $errors = $LANG['login_error_96'];
      $LOG->log("logLogin", $uname, "log_login_ldap_search_bind_failed");
      break;
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_login'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Login';
}
require("includes/header_html_inc.php" );
require("includes/header_app_inc.php" );
require("includes/menu_inc.php" );
?>
<div id="content">
   <div id="content-content">
      <form name="login" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
         <input type="hidden" name="hidden_target" value="<?=$target?>" />
         <table class="dlg">
            <tr>
               <td class="dlg-header" colspan="3">
                  <?php printDialogTop($LANG['login_login'], $help, "ico_login.png"); ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-body" style="padding: 20px 33% 20px 33%;">
                  <table>
                     <tr>
                        <td><strong><?=$LANG['login_username']?></strong></td>
                        <td><input name="uname" id="uname" size="30" type="text" class="text" value="<?=(strlen($uname))?$uname:"";?>"></td>
                     </tr>
                     <tr>
                        <td><strong><?=$LANG['login_password']?></strong></td>
                        <td><input name="pword" id="pword" size="30" type="password" class="text" value=""></td>
                     </tr>
                     <tr>
                        <td>&nbsp;</td>
                        <td><input name="btn_login" type="submit" class="button" value="<?=$LANG['btn_login']?>"></td>
                     </tr>
                     <?php if (strlen($errors)) { ?>
                     <tr>
                        <td>&nbsp;</td>
                        <td><div class="erraction"><?=$errors?></div><td>
                     </tr>
                     <?php } ?>
                  </table>
               </td>
            </tr>
            <tr>
               <td class="dlg-menu">
                   <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
               </td>
            </tr>
         </table>
      </form>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
