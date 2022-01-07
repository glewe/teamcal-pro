<?php
/**
 * editprofile.php
 *
 * Displays the edit user dialog
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
require_once( "models/absence_group_model.php" );
require_once( "models/allowance_model.php" );
require_once( "models/avatar_model.php" );
require_once( "models/config_model.php");
require_once( "models/daynote_model.php" );
require_once( "models/group_model.php" );
require_once( "models/holiday_model.php" );
require_once( "models/login_model.php" );
require_once( "models/log_model.php" );
require_once( "models/region_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_group_model.php" );
require_once( "models/user_option_model.php" );

$A   = new Absence_model;
$AG  = new Absence_group_model;
$AV  = new Avatar_model;
$B   = new Allowance_model;
$C   = new Config_model;
$G   = new Group_model;
$L   = new Login_model;
$LOG = new Log_model;
$N   = new Daynote_model;
$R   = new Region_model;
$T   = new Template_model;
$U   = new User_model;
$UG  = new User_group_model;
$UL  = new User_model;
$UO  = new User_option_model;

$error=false;
$grouprights=false;
$message = false;

if ($user=$L->checkLogin()) $UL->findByName($user);
if (isset($_REQUEST['username'])) $U->findByName(stripslashes($_REQUEST['username']));

/**
 * Check authorization
 */
$allowed=FALSE;
if ( $user == $U->username ) 
{
   $allowed=true;
}
else if ( $UG->shareGroups($user, $U->username) ) 
{
   if (isAllowed("editGroupUserProfiles")) 
   {
      if ($UG->isGroupManagerOfUser($user, $U->username) OR !$UG->isGroupManagerOfUser($U->username, $user) OR $UL->checkUserType($CONF['UTADMIN']))
      {
         $allowed=TRUE;
      }
   }
}
else 
{
   if (isAllowed("editAllUserProfiles")) $allowed=TRUE;
}

if (!$allowed) showError("notallowed", TRUE);

/**
 * Default period for absence count
 */
$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today     = getdate();
$countfrom = $C->readConfig("defperiodfrom");
$countto = $C->readConfig("defperiodto");


/**
 * =========================================================================
 * APPLY
 */
if (isset($_POST['btn_apply'])) 
{
   /**
    * Set password
    */
   if ( strlen($_POST['password']) ) 
   {
      $pwcheckResult = '';
      $pwerror = false;
      if (strlen($pwcheckResult=$L->passwordCheck($U->username, $U->password, $_POST['password'], $_POST['password2'])))
      {
         $pwerror = true;
         $message     = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['edit_profile_title'];
         $msg_text    = $pwcheckResult;
      }
      else 
      {
         $U->password = crypt($_POST['password'],$CONF['salt']);
         $U->last_pw_change = date("Y-m-d H:i:s");
         $U->clearStatus($CONF['USCHGPWD']);
      }
   }

   if ( !$pwerror ) 
   {
      $U->lastname    = htmlspecialchars($_POST['lastname'],ENT_QUOTES);
      $U->firstname   = htmlspecialchars($_POST['firstname'],ENT_QUOTES);
      $U->title       = htmlspecialchars($_POST['title'],ENT_QUOTES);
      $U->position    = htmlspecialchars($_POST['position'],ENT_QUOTES);
      $U->phone       = htmlspecialchars($_POST['phone'],ENT_QUOTES);
      $U->mobile      = htmlspecialchars($_POST['mobile'],ENT_QUOTES);
      $U->email       = $_POST['email'];
      $U->birthday    = str_replace("-","",$_POST['birthday']);
      $U->idnumber    = htmlspecialchars($_POST['idnumber'],ENT_QUOTES);
      $U->custom1     = htmlspecialchars($_POST['custom1'],ENT_QUOTES);
      $U->custom2     = htmlspecialchars($_POST['custom2'],ENT_QUOTES);
      $U->custom3     = htmlspecialchars($_POST['custom3'],ENT_QUOTES);
      $U->custom4     = htmlspecialchars($_POST['custom4'],ENT_QUOTES);
      $U->custom5     = htmlspecialchars($_POST['custom5'],ENT_QUOTES);
      $U->customFree  = addslashes(str_replace("\r\n","<br>",trim($_POST['customFree'])));
      $U->customPopup = addslashes(str_replace("\r\n","<br>",trim($_POST['customPopup'])));

      /**
       * Set gender
       */
      switch ($_POST['opt_gender']) 
      {
         case "ut_male":
            $U->setUserType($CONF['UTMALE']);
            break;
         case "ut_female":
            $U->clearUserType($CONF['UTMALE']);
            break;
         default:
            $U->setUserType($CONF['UTMALE']);
            break;
      }

      /**
       * Set user options
       */
      if (isset($_POST['uo_owngroups']) AND $_POST['uo_owngroups'] ) $UO->save($U->username,"owngroupsonly","yes");
      else $UO->save($U->username,"owngroupsonly","no");

      if (isset($_POST['uo_showbirthday']) AND $_POST['uo_showbirthday'] ) $UO->save($U->username,"showbirthday","yes");
      else $UO->save($U->username,"showbirthday","no");

      if (isset($_POST['uo_ignoreage']) AND $_POST['uo_ignoreage'] ) $UO->save($U->username,"ignoreage","yes");
      else $UO->save($U->username,"ignoreage","no");

      if (isset($_POST['uo_notifybirthday']) AND $_POST['uo_notifybirthday'] ) $UO->save($U->username,"notifybirthday","yes");
      else $UO->save($U->username,"notifybirthday","no");

      if (isset($_POST['uo_language']) AND $_POST['uo_language'] ) $UO->save($U->username,"language",$_POST['uo_language']);
      else $UO->save($U->username,"language",$C->readConfig("defaultLanguage"));

      if (isset($_POST['uo_defgroup']) AND $_POST['uo_defgroup'] ) $UO->save($U->username,"defgroup",$_POST['uo_defgroup']);
      else $UO->save($U->username,"defgroup",$C->readConfig("defgroupfilter"));

      if (isset($_POST['uo_defregion']) AND $_POST['uo_defregion'] ) $UO->save($U->username,"defregion",$_POST['uo_defregion']);
      else $UO->save($U->username,"defregion",$C->readConfig("defregion"));

      if (isset($_POST['uo_deftheme']) AND $_POST['uo_deftheme'] ) $UO->save($U->username,"deftheme",$_POST['uo_deftheme']);
      else $UO->save($U->username,"deftheme",$C->readConfig("theme"));

      if (isset($_POST['uo_showInGroups']) AND $_POST['uo_showInGroups'] ) 
      {
         if (isset($_POST['sel_showInGroups'])) 
         {
            $sgrps = "";
            foreach ($_POST['sel_showInGroups'] as $sgrp) 
            {
               if ($G->findByName($sgrp)) $sgrps.=$G->groupname.",";
            }
            $sgrps = substr($sgrps,0,-1); // remove the last ", "
            $UO->save($U->username,"showInGroups",$sgrps);
         }
      }
      else $UO->save($U->username,"showInGroups","no");

      if (isAllowed("manageUsers") AND $U->username != 'admin') 
      {
         /**
          * Set user type
          */
         $U->clearUserType($CONF['UTADMIN']);
         $U->clearUserType($CONF['UTDIRECTOR']);
         $U->clearUserType($CONF['UTASSISTANT']);
         $U->clearUserType($CONF['UTUSER']);
         $U->clearUserType($CONF['UTTEMPLATE']);
          
         switch ($_POST['opt_usertype']) 
         {
            case "ut_admin":
               $U->setUserType($CONF['UTADMIN']);
               break;
            case "ut_director":
               $U->setUserType($CONF['UTDIRECTOR']);
               break;
            case "ut_assistant":
               $U->setUserType($CONF['UTASSISTANT']);
               break;
            case "ut_user":
               $U->setUserType($CONF['UTUSER']);
               break;
            case "ut_template":
               $U->setUserType($CONF['UTTEMPLATE']);
               break;
         }
         
         /**
          * Set user status
          */
         $U->bad_logins = 0;
         $U->bad_logins_start = "";
         $U->last_login = substr($U->last_login, 0, 19); // stripping time zone at end if any
         $U->clearStatus($CONF['USLOCKED']);
         $U->clearStatus($CONF['USLOGLOC']);
         $U->clearStatus($CONF['USHIDDEN']);
         
         foreach($_POST as $key=>$value) 
         {
            switch ($key) 
            {
               case "us_locked":
               $U->setStatus($CONF['USLOCKED']);
               break;
               
               case "us_logloc":
               $U->bad_logins = intval($C->readConfig("badLogins"));
               $U->bad_logins_start = date("U");
               $U->setStatus($CONF['USLOGLOC']);
               $U->update($U->username);
               break;
               
               case "us_hidden":
               $U->setStatus($CONF['USHIDDEN']);
               break;
            }
         }
      }

      /**
       * Set group membership and manager type
       * First, delete all group memberships for this user and clear the manager flag
       */
      if (isAllowed("manageGroupMemberships")) 
      {
	      $UG->deleteByUser($U->username);
	      $U->clearUserType($CONF['UTMANAGER']);
	      $isManager = FALSE;
	      //
	      // Now loop thru all groups and set new memberships
	      //
	      foreach($_POST as $key=>$value) 
	      {
	         if ($key{0}=="X") 
	         {
	            $theGroup=substr($key,1);
	            if ( isset($_POST["M".$theGroup]) ) 
	            {
	               //echo "<script type=\"text/javascript\">alert(\"Debug: ".$theGroup."\");</script>";
	               switch ($_POST["M".$theGroup]) 
	               {
	                  case "ismember":
	                  if (!$UG->isMemberOfGroup($U->username,$theGroup)) {
	                     $UG->createUserGroupEntry($U->username,$theGroup,"member");
	                  }
	                  else {
	                     $UG->updateUserGroupType($U->username,$theGroup,"member");
	                  }
	                  break;
	                  
	                  case "ismanager":
	                  if (!$UG->isMemberOfGroup($U->username,$theGroup)) {
	                     $UG->createUserGroupEntry($U->username,$theGroup,"manager");
	                  }
	                  else {
	                     $UG->updateUserGroupType($U->username,$theGroup,"manager");
	                  }
	                  $isManager = TRUE;
	                  break;
	                  
	                  default:
	                  break;
	               }
	            }
	         }
	      }
	      if ($isManager) $U->setUserType($CONF['UTMANAGER']);
      }

      /**
       * Set notification options
       */
      $U->notify=0;
      foreach($_POST as $key=>$value) 
      {
         switch ($key) 
         {
            case "notify_team":
            $U->notify+=$CONF['userchg'];
            break;
            
            case "notify_groups":
            $U->notify+=$CONF['groupchg'];
            break;
            
            case "notify_month":
            $U->notify+=$CONF['monthchg'];
            break;
            
            case "notify_absence":
            $U->notify+=$CONF['absencechg'];
            break;
            
            case "notify_holiday":
            $U->notify+=$CONF['holidaychg'];
            break;
            
            case "notify_usercal":
            $U->notify+=$CONF['usercalchg'];
            $U->notify_group = $_POST['lbxNotifyGroup'];
            break;
         }
      }

      /**
       * Deploy the changes
       */
      $U->update($U->username);

      /**
       * Send notification e-mails
       */
      $fullname = $U->firstname." ".$U->lastname;
      sendNotification("userchange",$fullname,"");

      /**
       * Set $messsage to true if you wanna see a Javascript popup after update
       */
      $message     = false;
      $msg_type    = 'success';
      $msg_title   = $LANG['success'];
      $msg_caption = $LANG['edit_profile_title'];
      $msg_text    = $LANG['profile_updated'];

      /**
       * Log this event
       */
      $LOG->log("logUser",$L->checkLogin(),"log_user_updated", $U->username);
      header("Location: ".$_SERVER['PHP_SELF']."?referrer=".$_REQUEST['referrer']."&username=".$U->username);
      die();

   } // endif !$pwdmismatch
}
/**
 * =========================================================================
 * ABSENCE UPDATE
 */
elseif (isset($_POST['btn_abs_update'])) 
{
   $countfrom = stripslashes($_POST['cntfrom']);
   $countto = stripslashes($_POST['cntto']);
   
   $absences = $A->getAll();
   foreach ($absences as $abs) 
   {
      $A->get($abs['id']);
      if ( isset($_POST['lastyear-'.$A->id]) && isset($_POST['allowance-'.$A->id]) ) 
      {
         if ( is_numeric($_POST['lastyear-'.$A->id]) && is_numeric($_POST['allowance-'.$A->id]) ) 
         {
            $newlastyear = floatval($_POST['lastyear-'.$A->id]);
            $newallowance = floatval($_POST['allowance-'.$A->id]);
            if ($B->find($U->username,$A->id)) 
            {
               /**
                * This user has an individual allowance record for this
                * absence type. Let's update it...
                */
               $B->lastyear=$newlastyear;
               $B->curryear=$newallowance;
               $B->update();
            }
            else 
            {
               /**
                * This user does not have an individual allowance record
                * for this absence type yet. Let's create one if a left
                * over from last year was specified or if the allowance
                * differs from the general allowance for this absence type.
                */
               if ( $newlastyear<>0 || $newallowance<>floatval($A->allowance)) 
               {
                  $B->username=$U->username;
                  $B->absid=$A->id;
                  $B->lastyear=$newlastyear;
                  $B->curryear=$newallowance;
                  $B->create();
               }
            }
         }
         else 
         {
            echo "<script type=\"text/javascript\">alert(\"".$LANG['err_allowance_not_numeric']."\");</script>";
         }
      }
   }
   /**
    * Log this event
    */
   $LOG->log("logUser",$L->checkLogin(),"log_user_allow_updated", $U->username);
}
/**
 * =========================================================================
 * AVATAR UPDATE
 */
elseif ( isset($_POST['btn_avatar_upload']) ) 
{
   $AV->save($U->username);
   if ($AV->message)
   {
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['err_avatar_upload'];
      $msg_text    = $AV->message;
   }
   else 
   {
      /**
       * Log this event
       */
      $LOG->log("logUser",$L->checkLogin(),"log_user_avatar_updloaded", $U->username);
      header("Location: ".$_SERVER['PHP_SELF']."?referrer=".$_REQUEST['referrer']."&username=".$U->username);
      die();
   }
}
/**
 * =========================================================================
 * DONE
 */
elseif (isset($_POST['btn_done'])) 
{
   if (isset($_REQUEST['referrer']))
      jsCloseAndReload($_REQUEST['referrer'].".php");
   else
      jsCloseAndReload("userlist.php");
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_editprofile'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) 
{
   $help .= 'User+Profile';
}
require( "includes/header_html_inc.php" );
?>
<script type="text/javascript">$(function() { $( "#tabs" ).tabs(); });</script>
<div id="content">
   <div id="content-content">
      
      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <form enctype="multipart/form-data" name="userprofile" method="POST" action="<?=$_SERVER['PHP_SELF']."?referrer=".$_REQUEST['referrer']."&amp;username=".$U->username?>">
         <table class="dlg">
            <tr>
               <td class="dlg-header">
                  <?php printDialogTop($LANG['edit_profile_title'].": ".$U->title." ".$U->firstname." ".$U->lastname, $help, "ico_users.png"); ?>
               </td>
            </tr>

            <tr>
               <td class="dlg-body">
                  <div id="tabs">
                     <ul>
                        <li><a href="#tabs-1"><?=$LANG['tab_personal_data']?></a></li>
                        <li><a href="#tabs-2"><?=$LANG['tab_options']?></a></li>
                        <li><a href="#tabs-3"><?=$LANG['tab_absences']?></a></li>
                        <?php if ($C->readConfig("showAvatars")) { ?>
                        <li><a href="#tabs-4"><?=$LANG['tab_avatar']?></a></li>
                        <?php } ?>
                        <li><a href="#tabs-5"><?=$LANG['tab_other']?></a></li>
                        <li><a href="#tabs-6"><?=$LANG['tab_membership']?></a></li>
                        <?php if (isAllowed("manageUsers")) { ?>
                        <li><a href="#tabs-7"><?=$LANG['tab_privileges']?></a></li>
                        <?php } ?>
                     </ul>

                     <!-- PERSONAL DATA -->
                     <div id="tabs-1">
                        <fieldset><legend><?=$LANG['frame_personal_details']?></legend>
                           <table class="dlg-frame">
                              <tr>
                                 <td class="dlg-body" width="80"><?=$LANG['show_profile_name']?></td>
                                 <td class="dlg-body2"><?=$U->firstname." ".$U->lastname?></td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_uname']?></td>
                                 <td class="dlg-body2"><?=$U->username?></td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_password']?></td>
                                 <td class="dlg-body2">
                                    <input name="password" id="password" size="50" type="password" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_verify_password']?></td>
                                 <td class="dlg-body2">
                                    <input name="password2" id="password2" size="50" type="password" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_lname']?></td>
                                 <td class="dlg-body2">
                                    <input name="lastname" id="lastname" size="50" type="text" class="text" value="<?=$U->lastname?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_fname']?></td>
                                 <td class="dlg-body">
                                    <input name="firstname" id="firstname" size="50" type="text" class="text" value="<?=$U->firstname?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_usertitle']?></td>
                                 <td class="dlg-body">
                                    <input name="title" id="title" size="50" type="text" class="text" value="<?=$U->title?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_position']?></td>
                                 <td class="dlg-body">
                                    <input name="position" id="position" size="50" type="text" class="text" value="<?=$U->position?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_idnumber']?></td>
                                 <td class="dlg-body">
                                    <input name="idnumber" id="idnumber" size="50" type="text" class="text" value="<?=$U->idnumber?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_phone']?></td>
                                 <td class="dlg-body">
                                    <input name="phone" id="phone" size="50" type="text" class="text" value="<?=$U->phone?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_mobile']?></td>
                                 <td class="dlg-body">
                                    <input name="mobile" id="mobile" size="50" type="text" class="text" value="<?=$U->mobile?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_email']?></td>
                                 <td class="dlg-body">
                                    <input name="email" id="email" size="50" type="text" class="text" value="<?=$U->email?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_birthday']?></td>
                                 <td class="dlg-body">
                                    <script type="text/javascript">$(function() { $( "#birthday" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                                    <input name="birthday" id="birthday" size="10" maxlength="10" type="text" class="text" value="<?=substr($U->birthday,0,4)."-".substr($U->birthday,4,2)."-".substr($U->birthday,6,2);?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['show_profile_gender']?></td>
                                 <td class="dlg-body">
                                    <input name="opt_gender" id="utmale" type="radio" value="ut_male" <?php if ( $U->checkUserType($CONF['UTMALE']) ) echo "CHECKED"; ?> ><?=$LANG['show_profile_male']?>
                                    &nbsp;&nbsp;
                                    <input name="opt_gender" id="utfemale" type="radio" value="ut_female" <?php if ( !$U->checkUserType($CONF['UTMALE']) ) echo "CHECKED"; ?> ><?=$LANG['show_profile_female']?>
                                 </td>
                              </tr>
                           </table>
                        </fieldset>
                     </div>

                     <!-- USER OPTIONS -->
                     <div id="tabs-2">
                        <fieldset><legend><?=$LANG['frame_uo']?></legend>
                           <table style="width: 100%;">
                              <tr>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_owngroups" id="uo_owngroups" type="checkbox" value="uo_owngroups" <?=$UO->true($U->username,"owngroupsonly")?"CHECKED":""?> ><?=$LANG['uo_owngroupsonly']?><br>
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_showbirthday" id="uo_showbirthday" type="checkbox" value="uo_showbirthday" onclick="javascript: var obj=document.getElementById('thisid'); if (document.forms[0].uo_showbirthday.checked==true) { document.forms[0].uo_ignoreage.disabled=false; obj.style.color = '#333333'; } else { document.forms[0].uo_ignoreage.disabled=true; document.forms[0].uo_ignoreage.checked=false; obj.style.color = '#BBBBBB'; }" <?=$UO->true($U->username,"showbirthday")?"CHECKED":""?> ><?=$LANG['uo_showbirthday']?><br>
                                    <input style="vertical-align: middle; margin-left: 26px; margin-right: 8px;" name="uo_ignoreage" id="uo_ignoreage" type="checkbox" value="uo_ignoreage" <?=$UO->true($U->username,"ignoreage")?"CHECKED":""?>><span id="thisid"><?=$LANG['uo_ignoreage']?></span><br>
                                    <script type="text/javascript">
                                    var obj = document.getElementById('thisid');
                                    if (document.forms[0].uo_showbirthday.checked==true) {
                                       document.forms[0].uo_ignoreage.disabled=false;
                                       obj.style.color = '#333333';
                                    } else {
                                       document.forms[0].uo_ignoreage.disabled=true;
                                       document.forms[0].uo_ignoreage.checked=false;
                                       obj.style.color = '#BBBBBB';
                                    }
                                    </script>
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_notifybirthday" id="uo_notifybirthday" type="checkbox" value="uo_notifybirthday" <?=$UO->true($U->username,"notifybirthday")?"CHECKED":""?> ><?=$LANG['uo_notifybirthday']?><br>
                                    <br>
                                 </td>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <table>
                                       <tr>
                                          <td>
                                             <?=$LANG['uo_language']?>:
                                          </td>
                                          <td>
                                             <select name="uo_language" id="uo_language" class="select">
                                             <?php
                                             $array = getLanguages();
                                             if ($thisdeflang=$UO->find($U->username,"language")) $deflang=$thisdeflang;
                                             else $deflang=$C->readConfig("defaultLanguage"); ?> 
                                             <option class="option" value="default" <?=($deflang=="default"?"SELECTED":"")?>>default</option>
                                             <?php 
                                             foreach ($array as $langfile) { ?>
                                                <option class="option" value="<?=$langfile?>" <?=(($deflang==$langfile)?"SELECTED":"")?>><?=$langfile?></option>
                                             <?php } ?>
                                             </select>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <?=$LANG['uo_defgroup']?>:
                                          </td>
                                          <td>
                                             <select name="uo_defgroup" id="uo_defgroup" class="select">
                                             <?php
                                             if ($thisdefgroup=$UO->find($U->username,"defgroup")) $defgroup=$thisdefgroup;
                                             else $defgroup="default";
                                             ?>
                                             <option class="option" value="default" <?=($defgroup=="default"?"SELECTED":"")?>>default</option>
                                             <option class="option" value="All" <?=($defgroup=="All"?"SELECTED":"")?>><?=$LANG['drop_group_all']?></option>
                                             <option class="option" value="Allbygroup" <?=($defgroup=="Allbygroup"?"SELECTED":"")?>><?=$LANG['drop_group_allbygroup']?></option>
                                             <?php
                                             $groups=$G->getAll(TRUE); // TRUE = exclude hidden
                                             foreach( $groups as $group ) {
                                                if (!isAllowed("viewAllGroups") OR $UO->true($user, "owngroupsonly")) {
                                                   if ( $UG->isMemberOfGroup($user, $group['groupname']) OR
                                                        $UG->isGroupManagerOfGroup($user, $group['groupname'])
                                                      ) {
                                                      if ($defgroup==$group['groupname'])
                                                         echo ("<option value=\"" . $group['groupname'] . "\" SELECTED=\"selected\">" . $group['groupname'] . "</option>");
                                                      else
                                                         echo ("<option value=\"" . $group['groupname'] . "\" >" . $group['groupname'] . "</option>");
                                                   }
                                                }
                                                else {
                                                   if ($defgroup==$group['groupname'])
                                                      echo ("<option value=\"" . $group['groupname'] . "\" SELECTED=\"selected\">" . $group['groupname'] . "</option>");
                                                   else
                                                      echo ("<option value=\"" . $group['groupname'] . "\" >" . $group['groupname'] . "</option>");
                                                }
                                             }
                                             ?>
                                             </select>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <?=$LANG['uo_deftheme']?>:
                                          </td>
                                          <td>
                                             <?php if ($C->readConfig("allowUserTheme")) { ?>
                                             <select name="uo_deftheme" id="uo_deftheme" class="select">
                                             <?php
                                             $themearray = getFolders('themes');
                                             $themearray[]['name']="default";
                                             sort($themearray);
                                             if ($thisdeftheme=$UO->find($U->username,"deftheme")) $deftheme=$thisdeftheme;
                                             else $deftheme="default";
                                             foreach( $themearray as $mytheme ) {
                                                if ($mytheme['name']==$deftheme) {
                                                   echo "<option value=\"".$mytheme['name']."\" SELECTED=\"selected\">".$mytheme['name']."</option>";
                                                }
                                                else
                                                   echo "<option value=\"".$mytheme['name']."\">".$mytheme['name']."</option>";
                                             }
                                             ?>
                                             </select>
                                             <?php } ?>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>
                                             <?=$LANG['uo_defregion']?>:
                                          </td>
                                          <td>
                                             <select name="uo_defregion" id="uo_defregion" class="select">
                                             <?php
                                             if ($thisdefregion=$UO->find($U->username,"defregion")) $defregion=$thisdefregion; 
                                             else $defregion="default";
                                             ?>
                                             <option class="option" value="default" <?=($defregion=="default"?"SELECTED":"")?>>default</option>
                                             <?php
                                             $regions = $R->getAll("regionname");
                                             foreach ($regions as $row) {
                                                $R->findByName(stripslashes($row['regionname']));
                                                if ($R->regionname!="default") {
                                                   if ($defregion==$R->regionname) echo ("<option value=\"" . $defregion . "\" SELECTED=\"selected\">" . $defregion . "</option>");
                                                   else                            echo ("<option value=\"" . $R->regionname . "\" >" . $R->regionname . "</option>");
                                                }
                                             }
                                             ?>
                                             </select>
                                          </td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_showInGroups" id="uo_showInGroups" type="checkbox" value="uo_owngroups" <?=$UO->true($U->username,"showInGroups")?"CHECKED":""?> ><?=$LANG['uo_showInGroups']?><br>
                                    <?=$LANG['uo_showInGroups_comment']?>:
                                 </td>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <select name="sel_showInGroups[]" id="sel_showInGroups" class="select" multiple="multiple" size="6">
                                    <?php
                                    $groups = $G->getAll();
                                    $sgroups = $UO->find($U->username,"showInGroups");
                                    foreach ($groups as $grp) {
                                       if (strpos($sgroups,$grp['groupname'])!==FALSE) {
                                          $sel=" SELECTED";
                                       }
                                       else {
                                          $sel="";
                                       }
                                       echo "<option class=\"option\" value=\"".$grp['groupname']."\" ".$sel.">".$grp['groupname']."</option>";
                                    }
                                    ?>
                                    </select>
                                 </td>
                              </tr>
                           </table>
                        </fieldset>
                        <br>

                        <!-- NOTIFICATION -->
                        <fieldset><legend><?=$LANG['frame_mail_notification']?></legend><span class="dlg">
                           <strong><?=$LANG['notify_caption']?></strong><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_team" id="notify_team" type="checkbox" value="notify_team" <?=($U->notify&$CONF['userchg'])==$CONF['userchg']?"CHECKED":"" ?> ><?=$LANG['notify_team']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_groups" id="notify_groups" type="checkbox" value="notify_groups" <?=($U->notify&$CONF['groupchg'])==$CONF['groupchg']?"CHECKED":"" ?> ><?=$LANG['notify_groups']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_month" id="notify_month" type="checkbox" value="notify_month" <?=($U->notify&$CONF['monthchg'])==$CONF['monthchg']?"CHECKED":"" ?> ><?=$LANG['notify_month']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_absence" id="notify_absence" type="checkbox" value="notify_month" <?=($U->notify&$CONF['absencechg'])==$CONF['absencechg']?"CHECKED":"" ?> ><?=$LANG['notify_absence']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_holiday" id="notify_holiday" type="checkbox" value="notify_month" <?=($U->notify&$CONF['holidaychg'])==$CONF['holidaychg']?"CHECKED":"" ?> ><?=$LANG['notify_holiday']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_usercal" id="notify_usercal" type="checkbox" value="notify_member" <?=($U->notify&$CONF['usercalchg'])==$CONF['usercalchg']?"CHECKED":"" ?> ><?=$LANG['notify_usercal']?>&nbsp;<?=$LANG['notify_ofgroup']?>&nbsp;
                           <select name="lbxNotifyGroup" id="lbxNotifyGroup" class="select">
                              <option class="option" value="All"><?=$LANG['drop_group_all']?></option>
                              <?php
                              $groups=$G->getAll(TRUE); // TRUE = exclude hidden
                              foreach( $groups as $group ) {
                                 if (!isAllowed("viewAllGroups") OR $UO->true($user, "owngroupsonly")) {
                                    if ( $UG->isMemberOfGroup($user, $group['groupname']) OR
                                         $UG->isGroupManagerOfGroup($user, $group['groupname'])
                                       ) {
                                       if ($U->notify_group==$group['groupname'])
                                          echo ("<option value=\"" . $group['groupname'] . "\" SELECTED=\"selected\">" . $group['groupname'] . "</option>");
                                       else
                                          echo ("<option value=\"" . $group['groupname'] . "\" >" . $group['groupname'] . "</option>");
                                    }
                                 }
                                 else {
                                    if ($U->notify_group==$group['groupname'])
                                       echo ("<option value=\"" . $group['groupname'] . "\" SELECTED=\"selected\">" . $group['groupname'] . "</option>");
                                    else
                                       echo ("<option value=\"" . $group['groupname'] . "\" >" . $group['groupname'] . "</option>");
                                 }
                              }
                              ?>
                           </select>
                        </span></fieldset>
                     </div>

                     <!-- ABSENCES -->
                     <div id="tabs-3">
                     <?php include( "includes/absencecount_inc.php" ); ?>
                     </div>

                     <!-- AVATAR -->
                     <?php if($C->readConfig("showAvatars")) { ?>
                     <div id="tabs-4">
                        <fieldset><legend><?=$LANG['ava_title']?></legend>
                           <table style="width: 99%;">
                              <tr>
                                 <td class="dlg-body" style="width: 120px;">
                                    <?php
                                    if ($AV->find($U->username)) { ?>
                                       <img  style="padding-right: 10px;" src="<?=$AV->path.$AV->filename.".".$AV->fileextension?>" align="top" border="0" alt="<?=$U->username?>" title="<?=$U->username?>">
                                    <?php }
                                    else { ?>
                                       <img src="<?=$AV->path?>noavatar.gif" align="top" border="0" alt="No avatar" title="No avatar">
                                    <?php } ?>
                                 </td>
                                 <td class="dlg-body">
                                    <?php
                                    echo $LANG['ava_upload']."<br><br>";
                                    ?>
                                    <input class="text" type="hidden" name="MAX_FILE_SIZE" value="<?php echo $AV->maxSize; ?>">
                                    <input class="text" type="file" name="imgfile" size="40"><br><br>
                                    <input name="btn_avatar_upload" type="submit" class="button" value="<?php echo $LANG['btn_upload']; ?>">
                                    <br>
                                    <br>
                                 </td>
                              </tr>
                           </table>
                        </fieldset>
                     </div>
                     <?php } ?>

                     <!-- OTHER -->
                     <div id="tabs-5">
                        <fieldset><legend><?=$LANG['other_title']?></legend>
                           <table class="dlg-frame">
                              <tr>
                                 <td class="dlg-body"><?=$C->readConfig("userCustom1")?></td>
                                 <td class="dlg-body">
                                    <input name="custom1" id="custom1" size="50" maxlength="80" type="text" class="text" value="<?=$U->custom1?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$C->readConfig("userCustom2")?></td>
                                 <td class="dlg-body">
                                    <input name="custom2" id="custom2" size="50" maxlength="80" type="text" class="text" value="<?=$U->custom2?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$C->readConfig("userCustom3")?></td>
                                 <td class="dlg-body">
                                    <input name="custom3" id="custom3" size="50" maxlength="80" type="text" class="text" value="<?=$U->custom3?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$C->readConfig("userCustom4")?></td>
                                 <td class="dlg-body">
                                    <input name="custom4" id="custom4" size="50" maxlength="80" type="text" class="text" value="<?=$U->custom4?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$C->readConfig("userCustom5")?></td>
                                 <td class="dlg-body">
                                    <input name="custom5" id="custom5" size="50" maxlength="80" type="text" class="text" value="<?=$U->custom5?>">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['other_customFree']?></td>
                                 <td class="dlg-body">
                                    <textarea name="customFree" id="customFree" class="text" cols="47" rows="6"><?php if (strlen(trim($U->customFree))) echo stripslashes(str_replace("<br>","\r\n",trim($U->customFree))); else echo "";?></textarea>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><?=$LANG['other_customPopup']?></td>
                                 <td class="dlg-body">
                                    <textarea name="customPopup" id="customPopup" class="text" cols="47" rows="6"><?php if (strlen(trim($U->customPopup))) echo stripslashes(str_replace("<br>","\r\n",trim($U->customPopup))); else echo "";?></textarea>
                                 </td>
                              </tr>
                           </table>
                        </fieldset>
                     </div>

                     <!-- MEMBERSHIP -->
                     <div id="tabs-6">
                        <fieldset><legend><?=$LANG['frame_user_groupmember']?></legend>
                           <table style="width: 99%;">
                              <tr>
                                 <td class="dlg-body"><?=$LANG['tab_membership_group']?></td>
                                 <td class="dlg-body" style="text-align: center;"><img src="themes/<?=$theme?>/img/ico_usr.png" alt="">&nbsp;<?=$LANG['tab_membership_member']?></td>
                                 <td class="dlg-body" style="text-align: center;"><img src="themes/<?=$theme?>/img/ico_usr_manager.png" alt="">&nbsp;<?=$LANG['tab_membership_manager']?></td>
                              </tr>
                              <?php
                                 $rowstyle=1;
                                 $groups = $G->getAll();
                                 foreach ($groups as $row) {
                                    $membership="";
                                    $ismember="DISABLED";
                                    $ismanager="DISABLED";
                                    if ($UG->isMemberOfGroup($U->username,$row['groupname'])) {
                                       $membership="CHECKED";
                                       if ($UG->type=="manager") {
                                          $ismember="";
                                          $ismanager="CHECKED";
                                       }else{
                                          $ismember="CHECKED";
                                          $ismanager="";
                                       }
                                    }
                                    $gname = $row['groupname'];
                                    $G->findByName($row['groupname']);
                                    if ($G->checkOptions($CONF['G_HIDE'])) $txthidden = "&nbsp;".$LANG['profile_group_hidden']; else $txthidden="";
                                    if (isAllowed("manageGroupMemberships")) {
                                       echo '<tr class="row'.$rowstyle.'">
                                          <td class="row'.$rowstyle.'">
                                             <input name="X'.$gname.'" type="checkbox" onclick="javascript:toggleGrp(\''.$gname.'\');" '.$membership.'>'.$gname.$txthidden.'
                                          </td>
                                          <td class="row'.$rowstyle.'">
                                             <input name="M'.$gname.'" value="ismember" type="radio" '.$ismember.'>
                                          </td>
                                          <td class="row'.$rowstyle.'">
                                             <input name="M'.$gname.'" value="ismanager" type="radio" '.$ismanager.'>
                                          </td>
                                       </tr>
                                       ';
                                    }
                                    else {
                                       echo '<tr class="row'.$rowstyle.'">
                                          <td class="row'.$rowstyle.'">'.$gname.$txthidden.'</td>
                                          <td class="row'.$rowstyle.'" style="text-align: center;">';
                                             if ($ismember=="CHECKED")
                                                echo '<img src="img/icons/checkmark.png" alt="">';
                                             else
                                                echo '&nbsp;';
                                          echo '</td>
                                          <td class="row'.$rowstyle.'" style="text-align: center;">';
                                             if ($ismanager=="CHECKED")
                                                echo '<img src="img/icons/checkmark.png" alt="">';
                                             else
                                                echo '&nbsp;';
                                          echo '</td>
                                       </tr>
                                       ';
                                    }
                                    if ($rowstyle) $rowstyle=0; else $rowstyle=1;
                                 }
                              ?>
                           </table>
                        </fieldset>
                     </div>

                     <!-- ACCOUNT -->
                     <?php if (isAllowed("manageUsers")) { ?>
                     <div id="tabs-7">
                        <fieldset><legend><?=$LANG['frame_user_type']?></legend>
                           <table>
                              <tr>
                                 <td class="dlg-frame-body" colspan="3"><?=$LANG['ut_caption']?><br>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="opt_usertype" id="utadmin" type="radio" value="ut_admin" <?php if ( $U->checkUserType($CONF['UTADMIN']) ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_admin']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body" width="20">
                                    <input name="opt_usertype" id="utdirector" type="radio" value="ut_director" <?php if ( $U->checkUserType($CONF['UTDIRECTOR']) ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_director']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body" width="20">
                                    <input name="opt_usertype" id="utassistant" type="radio" value="ut_assistant" <?php if ( $U->checkUserType($CONF['UTASSISTANT']) ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_assistant']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body" width="20">
                                    <input name="opt_usertype" id="utuser" type="radio" value="ut_user" <?php if ( $U->checkUserType($CONF['UTUSER']) ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_user']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="opt_usertype" id="uttemplate" type="radio" value="ut_template" <?php if ( $U->checkUserType($CONF['UTTEMPLATE']) ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_template']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                           </table>
                        </fieldset>
                        <br>

                        <!-- USER STATUS -->
                        <fieldset><legend><?=$LANG['frame_user_status']?></legend>
                           <table>
                              <tr>
                                 <td class="dlg-frame-body" colspan="3"><?=$LANG['us_caption']?><br>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="us_locked" id="us_locked" type="checkbox" value="us_locked" <?php if ( $U->status & $CONF['USLOCKED'] ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['us_locked']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="us_logloc" id="us_logloc" type="checkbox" value="us_logloc" <?php if ( $U->status & $CONF['USLOGLOC'] ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['us_logloc']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="us_hidden" id="us_hidden" type="checkbox" value="us_hidden" <?php if ( $U->status & $CONF['USHIDDEN'] ) echo "CHECKED"; ?> >
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['us_hidden']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                           </table>
                        </fieldset>
                     </div>
                     <?php } ?>

                  </div>
               </td>
            </tr>
            <tr>
               <td class="dlg-menu">
                  <input name="btn_apply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">
                  <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
                  <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
                  <input name="btn_done" type="submit" class="button" value="<?=$LANG['btn_done']?>">
               </td>
            </tr>
         </table>
      </form>
   </div>
</div>
<?php
require( "includes/footer_inc.php");
?>
