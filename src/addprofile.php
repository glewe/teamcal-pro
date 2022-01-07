<?php
/**
 * addprofile.php
 *
 * Displays the dialog to add a user
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

require_once ("models/absence_model.php");
require_once ("models/allowance_model.php");
require_once ("models/config_model.php");
require_once ("models/daynote_model.php");
require_once ("models/group_model.php");
require_once ("models/login_model.php");
require_once ("models/log_model.php");
require_once ("models/template_model.php");
require_once ("models/user_model.php");
require_once ("models/user_group_model.php");
require_once ("models/user_option_model.php");

$A = new Absence_model;
$B = new Allowance_model;
$C = new Config_model;
$G = new Group_model;
$L = new Login_model;
$LOG = new Log_model;
$N = new Daynote_model;
$R = new Region_model;
$T = new Template_model;
$U = new User_model;
$UL = new User_model;
$UG = new User_group_model;
$UO = new User_option_model;

/**
 * Check authorization
 */
if (!isAllowed("manageUsers")) showError("notallowed", TRUE);

$msg = false;

/**
 * =========================================================================
 * ADD
 */
if (isset ($_POST['btn_add'])) {

   $username = trim($_POST['username']);
   if (!preg_match('/^[a-zA-Z0-9.]*$/', $username)) 
   {
      $msg = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['add_profile_title'];
      $msg_text    = $LANG['error_user_nospecialchars'];
   }
   elseif ($res = $U->findByName($username)) 
   {
      $msg = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['add_profile_title'];
      $msg_text    = $LANG['error_user_exists'];
      $_REQUEST['action'] = "add";
   } 
   else 
   {
      $U->username = $username;
      if (strlen($_POST['password'])) 
      {
         $pwcheckResult = '';
         $pwerror = false;
         if (strlen($pwcheckResult=$L->passwordCheck($U->username, '', $_POST['password'], $_POST['password2'])))
         {
            $pwerror = true;
            $msg     = true;
            $msg_type    = 'error';
            $msg_title   = $LANG['error'];
            $msg_caption = $LANG['add_profile_title'];
            $msg_text    = $pwcheckResult;
         }
         else
         {
            $U->password = crypt($_POST['password'],$CONF['salt']);
            $U->last_pw_change = date("Y-m-d H:i:s");
            $U->clearStatus($CONF['USCHGPWD']);
         }
      }
      
      if (!$pwerror && !$msg) 
      {
         $U->lastname    = htmlspecialchars($_POST['lastname'], ENT_QUOTES);
         $U->firstname   = htmlspecialchars($_POST['firstname'], ENT_QUOTES);
         $U->title       = htmlspecialchars($_POST['title'], ENT_QUOTES);
         $U->position    = htmlspecialchars($_POST['position'], ENT_QUOTES);
         $U->phone       = htmlspecialchars($_POST['phone'], ENT_QUOTES);
         $U->mobile      = htmlspecialchars($_POST['mobile'],ENT_QUOTES);
         $U->email       = $_POST['email'];
         $U->birthday    = str_replace("-","",$_POST['birthday']);
         $U->idnumber    = htmlspecialchars($_POST['idnumber'], ENT_QUOTES);
         $U->custom1     = htmlspecialchars($_POST['custom1'],ENT_QUOTES);
         $U->custom2     = htmlspecialchars($_POST['custom2'],ENT_QUOTES);
         $U->custom3     = htmlspecialchars($_POST['custom3'],ENT_QUOTES);
         $U->custom4     = htmlspecialchars($_POST['custom4'],ENT_QUOTES);
         $U->custom5     = htmlspecialchars($_POST['custom5'],ENT_QUOTES);
         $U->customFree  = addslashes(str_replace("\r\n","<br>",trim($_POST['customFree'])));
         $U->customPopup = addslashes(str_replace("\r\n","<br>",trim($_POST['customPopup'])));

         /**
          * Set user gender
          */
         switch ($_POST['opt_gender']) 
         {
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
         else $UO->save($U->username,"language",$CONF['options']['lang']);

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

         /**
          * Clear user type
          */
         $U->clearUserType($CONF['UTADMIN']);
         $U->clearUserType($CONF['UTDIRECTOR']);
         $U->clearUserType($CONF['UTMANAGER']);
         $U->clearUserType($CONF['UTASSISTANT']);
         $U->clearUserType($CONF['UTTEMPLATE']);
         
         /**
          * Set user type
          */
         switch ($_POST['opt_usertype']) 
         {
            case "ut_admin" :
               $U->setUserType($CONF['UTADMIN']);
               break;
            case "ut_director" :
               $U->setUserType($CONF['UTDIRECTOR']);
               break;
            case "ut_assistant" :
               $U->setUserType($CONF['UTASSISTANT']);
               break;
            case "ut_user" :
               $U->setUserType($CONF['UTUSER']);
               break;
            case "ut_template" :
               $U->setUserType($CONF['UTTEMPLATE']);
               break;
         }

         /**
          * Set group memberships and manager type for this user
          */
         foreach ($_POST as $key => $value) 
         {
            if ($key {0} == "X") 
            {
               $theGroup = substr($key, 1);
               if (isset ($_POST["M".$theGroup])) 
               {
                  switch ($_POST["M".$theGroup]) 
                  {
                     case "ismember" :
                        $UG->createUserGroupEntry($U->username, $theGroup, "member");
                        break;
                     case "ismanager" :
                        $UG->createUserGroupEntry($U->username, $theGroup, "manager");
                        $U->setUserType($CONF['UTMANAGER']);
                        break;
                     default :
                        break;
                  }
               }
            }
         }

         /**
          * Set user status
          */
         foreach ($_POST as $key => $value) 
         {
            switch ($key) 
            {
               case "us_locked" :
                  $U->setStatus($CONF['USLOCKED']);
                  break;
               case "us_logloc" :
                  $U->bad_logins = intval($C->readConfig("badLogins"));
                  $U->bad_logins_start = date("U");
                  $U->setStatus($CONF['USLOGLOC']);
                  $U->update($U->username);
                  break;
               case "us_hidden" :
                  $U->setStatus($CONF['USHIDDEN']);
                  break;
            }
         }

         /**
          * Clear the notification bit-map
          * Then overwrite the updated check marks
          */
         $U->notify = 0;
         foreach ($_POST as $key => $value) 
         {
            switch ($key) 
            {
               case "notify_team" :
                  $U->notify += $CONF['userchg'];
                  break;
               case "notify_groups" :
                  $U->notify += $CONF['groupchg'];
                  break;
               case "notify_month" :
                  $U->notify += $CONF['monthchg'];
                  break;
               case "notify_absence" :
                  $U->notify += $CONF['absencechg'];
                  break;
               case "notify_holiday" :
                  $U->notify += $CONF['holidaychg'];
                  break;
               case "notify_usercal" :
                  $U->notify += $CONF['usercalchg'];
                  $U->notify_group = $_POST['lbxNotifyGroup'];
                  break;
            }
         }
         $U->create();
         
         /**
          * Send notification e-Mails. First to the user himself then to
          * others who asked for it.
          */
         sendAccountCreatedMail($username, $_POST['password']);
         $fullname = $U->firstname . " " . $U->lastname;
         sendNotification("useradd", $fullname, "");

         /**
          * Log this event
          */
         $LOG->log("logUser", $L->checkLogin(), "log_user_added", $U->username . " (" . $fullname . ")");

         jsCloseAndReload("userlist.php");
      } // endif !$pwerror
   }

}
/**
 * =========================================================================
 * DONE
 */
elseif (isset ($_POST['btn_done'])) 
{
   jsCloseAndReload("userlist.php");
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_addprofile'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'User+Profile';
}

require("includes/header_html_inc.php");
?>
<div id="content">
   <div id="content-content">

      <!-- Message -->
      <?php if ($msg) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <script type="text/javascript">$(function() { $( "#tabs" ).tabs(); });</script>
      <form name="userprofile" method="POST" action="<?=$_SERVER['PHP_SELF']."?username=".$U->username?>">
         <table class="dlg">
            <tr>
               <td class="dlg-header">
                  <?php printDialogTop($LANG['add_profile_title'], $help, "ico_users.png"); ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-body">
                  <div id="tabs">
                     <ul>
                        <li><a href="#tabs-1"><?=$LANG['tab_personal_data']?></a></li>
                        <li><a href="#tabs-2"><?=$LANG['tab_membership']?></a></li>
                        <li><a href="#tabs-3"><?=$LANG['tab_options']?></a></li>
                        <li><a href="#tabs-4"><?=$LANG['tab_privileges']?></a></li>
                        <li><a href="#tabs-5"><?=$LANG['tab_other']?></a></li>
                     </ul>

                     <!-- PERSONAL DATA -->
                     <div id="tabs-1">
                        <fieldset><legend><?=$LANG['frame_personal_details']?></legend>
                           <table class="dlg-frame">
                              <tr>
                                 <td class="dlg-body" width="120"><strong><?=$LANG['show_profile_uname']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="username" id="username" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_password']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="password" id="password" size="50" type="password" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_verify_password']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="password2" id="password2" size="50" type="password" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_lname']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="lastname" id="lastname" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_fname']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="firstname" id="firstname" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_usertitle']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="title" id="title" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_position']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="position" id="position" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_idnumber']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="idnumber" id="idnumber" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_phone']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="phone" id="phone" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_mobile']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="mobile" id="mobile" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_email']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="email" id="email" size="50" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_birthday']?></strong></td>
                                 <td class="dlg-body">
                                    <table style="border-collapse: collapse; border: 0px;">
                                       <tr>
                                          <td>
                                             <script type="text/javascript">$(function() { $( "#birthday" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });</script>
                                             <input name="birthday" id="birthday" size="10" maxlength="10" type="text" class="text" value="">
                                          </td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['show_profile_gender']?></strong></td>
                                 <td class="dlg-body">
                                    <input name="opt_gender" id="utmale" type="radio" value="ut_male" CHECKED><?=$LANG['show_profile_male']?>
                                    &nbsp;&nbsp;
                                    <input name="opt_gender" id="utfemale" type="radio" value="ut_female"><?=$LANG['show_profile_female']?>
                                 </td>
                              </tr>
                           </table>
                        </fieldset>
                     </div>

                     <!-- MEMBERSHIP -->
                     <div id="tabs-2">
                        <fieldset><legend><?=$LANG['frame_user_groupmember']?></legend>
                           <table style="width: 99%;">
                              <tr>
                                 <td class="dlg-frame-body"><strong><?=$LANG['tab_membership_group']?></strong></td>
                                 <td class="dlg-frame-bodyc"><strong><img src="themes/<?=$theme?>/img/ico_usr.png" alt=""><?=$LANG['tab_membership_member']?></strong></td>
                                 <td class="dlg-frame-bodyc"><strong><img src="themes/<?=$theme?>/img/ico_usr_manager.png" alt=""><?=$LANG['tab_membership_manager']?></strong></td>
                              </tr>
                              <?php
                              $rowstyle = 1;
                              $groups = $G->getAll();
                              foreach ($groups as $row) {
                                 $G->findByName($row['groupname']);
                                 $membership = "";
                                 $ismember = "DISABLED";
                                 $ismanager = "DISABLED";
                                 if ($UG->isMemberOfGroup($U->username, $G->groupname)) {
                                    $membership = "CHECKED";
                                    if ($UG->type == "manager") {
                                       $ismember = "";
                                       $ismanager = "CHECKED";
                                    } else {
                                       $ismember = "CHECKED";
                                       $ismanager = "";
                                    }
                                 }
                                 echo '<tr class="row' . $rowstyle . '">
                                          <td class="dlg-frame-body">
                                             <input name="X'.$G->groupname.'" type="checkbox" onclick="javascript:toggleGrp(\''.$G->groupname.'\');" '.$membership.'>'.$G->groupname.'
                                          </td>
                                          <td class="dlg-frame-bodyc">
                                             <input name="M'.$G->groupname.'" value="ismember" type="radio" '.$ismember.'>
                                          </td>
                                          <td class="dlg-frame-bodyc">
                                             <input name="M'.$G->groupname.'" value="ismanager" type="radio" '.$ismanager.'>
                                          </td>
                                       </tr>
                                       ';
                                       if ($rowstyle) $rowstyle = 0; else $rowstyle = 1;
                                    }
                              ?>
                           </table>
                        </fieldset>
                     </div>

                     <!-- OPTIONS -->
                     <div id="tabs-3">
                        <fieldset><legend><?=$LANG['frame_uo']?></legend>
                           <table style="width: 100%;">
                              <tr>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_owngroups" id="uo_owngroups" type="checkbox" value="uo_owngroups" <?=$UO->find($U->username,"owngroupsonly")?"CHECKED":""?> ><?=$LANG['uo_owngroupsonly']?><br>
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_showbirthday" id="uo_showbirthday" type="checkbox" value="uo_showbirthday" onclick="javascript: var obj = document.getElementById('thisid'); if (document.forms[0].uo_showbirthday.checked==true) { document.forms[0].uo_ignoreage.disabled=false; obj.style.color = '#333333';  } else { document.forms[0].uo_ignoreage.disabled=true; document.forms[0].uo_ignoreage.checked=false; obj.style.color = '#BBBBBB'; }"><?=$LANG['uo_showbirthday']?><br>
                                 	<input style="vertical-align: middle; margin-right: 8px; margin-left: 26px;" name="uo_ignoreage" id="uo_ignoreage" type="checkbox" value="uo_ignoreage"><span id="thisid"><?=$LANG['uo_ignoreage']?></span><br>
                                    <script type="text/javascript">
                                       <!--
                                       var obj = document.getElementById('thisid');
                                       if (document.forms[0].uo_showbirthday.checked==true) {
                                          document.forms[0].uo_ignoreage.disabled=false;
                                          obj.style.color = '#333333';
                                       } else {
                                          document.forms[0].uo_ignoreage.disabled=true;
                                          document.forms[0].uo_ignoreage.checked=false;
                                          obj.style.color = '#BBBBBB';
                                       }
                                       -->
                                    </script>
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_notifybirthday" id="uo_notifybirthday" type="checkbox" value="uo_notifybirthday"><?=$LANG['uo_notifybirthday']?><br>
                                 </td>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <table>
                                       <tr>
                                          <td><?=$LANG['uo_language']?></td>
                                          <td>
                                             <select name="uo_language" id="uo_language" class="select">
                                             <?php
                                                $array = getLanguages(); // Collects language name of all installed language files
                                                foreach ($array as $langfile) {
                                                   if ($langfile == $CONF['options']['lang'])
                                                      echo ("<option value=\"" . $CONF['options']['lang'] . "\" selected>" . $CONF['options']['lang'] . "</option>");
                                                   else
                                                      echo ("<option value=\"" . $langfile . "\">" . $langfile . "</option>");
                                                }
                                             ?>
                                             </select>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><?=$LANG['uo_defgroup']?>:</td>
                                          <td>
                                             <select name="uo_defgroup" id="uo_defgroup" class="select">
                                                <option class="option" value="default" selected>default</option>
                                                <option class="option" value="All"><?=$LANG['drop_group_all']?></option>
                                                <option class="option" value="Allbygroup"><?=$LANG['drop_group_allbygroup']?></option>
                                                <?php
                                                $groups=$G->getAll(TRUE); // TRUE = exclude hidden
                                                foreach( $groups as $group ) { ?>
                                                   <option value="<?=$group['groupname']?>"><?=$group['groupname']?></option>
                                                <?php } ?>
                                             </select>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><?=$LANG['uo_deftheme']?>:</td>
                                          <td>
                                             <select name="uo_deftheme" id="uo_deftheme" class="select">
                                                <option class="option" value="default" style="padding-right: 10px;" selected>default</option>
                                                <?php
                                                $themearray = getFolders('themes');
                                                sort($themearray);
                                                foreach( $themearray as $mytheme ) { ?>
                                                   <option value="<?=$mytheme['name']?>" style="padding-right: 10px;"><?=$mytheme['name']?></option>
                                                <?php } ?>
                                             </select>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td><?=$LANG['uo_defregion']?>:</td>
                                          <td>
                                             <select name="uo_defregion" id="uo_defregion" class="select">
                                                <option class="option" value="default" selected>default</option>
                                                <?php
                                                $regions = $R->getAll("regionname");
                                                foreach ($regions as $row) { ?>
                                                   <option class="option" value="<?=$row['regionname']?>"><?=$row['regionname']?></option>
                                                <?php } ?>
                                             </select>
                                          </td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <input style="vertical-align: middle; margin-right: 8px;" name="uo_showInGroups" id="uo_showInGroups" type="checkbox" value="uo_owngroups"><?=$LANG['uo_showInGroups']?><br>
                                    <?=$LANG['uo_showInGroups_comment']?>:
                                 </td>
                                 <td class="dlg-frame-body" style="width: 50%; vertical-align: top;">
                                    <select name="sel_showInGroups[]" id="sel_showInGroups" class="select" multiple="multiple" size="6">
                                    <?php
                                    $groups = $G->getAll();
                                    foreach ($groups as $grp) { ?>
                                       <option class="option" value="<?=$grp['groupname']?>"><?=$grp['groupname']?></option>
                                    <?php } ?>
                                    </select>
                                 </td>
                              </tr>
                           </table>
                        </fieldset>

                        <!-- MAIL NOTIFICATION -->
                        <fieldset><legend><?=$LANG['frame_mail_notification']?></legend><span class="dlg">
                           <strong><?=$LANG['notify_caption']?></strong><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_team" id="notify_team" type="checkbox" value="notify_team"><?=$LANG['notify_team']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_groups" id="notify_groups" type="checkbox" value="notify_groups"><?=$LANG['notify_groups']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_month" id="notify_month" type="checkbox" value="notify_month"><?=$LANG['notify_month']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_absence" id="notify_absence" type="checkbox" value="notify_month"><?=$LANG['notify_absence']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_holiday" id="notify_holiday" type="checkbox" value="notify_month"><?=$LANG['notify_holiday']?><br>
                           <input style="vertical-align: middle; margin-right: 8px;" name="notify_usercal" id="notify_usercal" type="checkbox" value="notify_member"><?=$LANG['notify_usercal']?>&nbsp;<?=$LANG['notify_ofgroup']?>&nbsp;
                           <select name="lbxNotifyGroup" id="lbxNotifyGroup" class="select">
                              <option class="option" value="All"><?=$LANG['drop_group_all']?></option>
                              <?php
                              $groups=$G->getAll(TRUE); // TRUE = exclude hidden
                              foreach( $groups as $group ) { ?>
                                 <option value="<?=$group['groupname']?>"><?=$group['groupname']?></option>
                              <?php } ?>
                           </select>
                        </span></fieldset>
                     </div>

                     <!-- ACCOUNT OPTIONS -->
                     <div id="tabs-4">
                        <fieldset><legend><?=$LANG['frame_user_type']?></legend>
                           <table>
                              <tr>
                                 <td class="dlg-frame-body" colspan="3"><b><?=$LANG['ut_caption']?></b></td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="opt_usertype" id="utadmin" type="radio" value="ut_admin">
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_admin']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="opt_usertype" id="utdirector" type="radio" value="ut_director">
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_director']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="opt_usertype" id="utassistant" type="radio" value="ut_assistant">
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_assistant']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body" width="24">
                                    <input name="opt_usertype" id="utuser" type="radio" value="ut_user" CHECKED>
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_user']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="opt_usertype" id="uttemplate" type="radio" value="ut_template">
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['ut_template']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                           </table>
                        </fieldset>

                        <fieldset><legend><?=$LANG['frame_user_status']?></legend>
                           <table>
                              <tr>
                                 <td class="dlg-frame-body" colspan="3"><strong><?=$LANG['us_caption']?></strong><br></td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="us_locked" id="us_locked" type="checkbox" value="us_locked">
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['us_locked']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="us_logloc" id="us_logloc" type="checkbox" value="us_logloc">
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['us_logloc']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                              <tr>
                                 <td class="dlg-frame-body">
                                    <input name="us_hidden" id="us_hidden" type="checkbox" value="us_hidden">
                                 </td>
                                 <td class="dlg-frame-body"><?=$LANG['us_hidden']?></td>
                                 <td class="dlg-frame-body">&nbsp;</td>
                              </tr>
                           </table>
                        </fieldset>
                     </div>

                     <!-- OTHER -->
                     <div id="tabs-5">
                        <fieldset><legend><?=$LANG['other_title']?></legend>
                           <table class="dlg-frame">
                              <tr>
                                 <td class="dlg-body"><strong><?=$C->readConfig("userCustom1")?></strong></td>
                                 <td class="dlg-body">
                                    <input name="custom1" id="custom1" size="50" maxlength="80" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$C->readConfig("userCustom2")?></strong></td>
                                 <td class="dlg-body">
                                    <input name="custom2" id="custom2" size="50" maxlength="80" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$C->readConfig("userCustom3")?></strong></td>
                                 <td class="dlg-body">
                                    <input name="custom3" id="custom3" size="50" maxlength="80" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$C->readConfig("userCustom4")?></strong></td>
                                 <td class="dlg-body">
                                    <input name="custom4" id="custom4" size="50" maxlength="80" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$C->readConfig("userCustom5")?></strong></td>
                                 <td class="dlg-body">
                                    <input name="custom5" id="custom5" size="50" maxlength="80" type="text" class="text" value="">
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['other_customFree']?></strong></td>
                                 <td class="dlg-body">
                                    <textarea name="customFree" id="customFree" class="text" cols="47" rows="6"></textarea>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="dlg-body"><strong><?=$LANG['other_customPopup']?></strong></td>
                                 <td class="dlg-body">
                                    <textarea name="customPopup" id="customPopup" class="text" cols="47" rows="6"></textarea>
                                 </td>
                              </tr>
                           </table>
                        </fieldset>
                     </div>
                  </div> <!-- End Tab Container -->
               </td>
            </tr>

            <tr>
               <td class="dlg-menu">
                  <input name="btn_add" type="submit" class="button" value="<?=$LANG['btn_add']?>">&nbsp;
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
require("includes/footer_inc.php");
?>
