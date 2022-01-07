<?php
/**
 * groups.php
 *
 * Displays the groups administration page
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

require_once("models/absence_model.php");
require_once("models/absence_group_model.php" );
require_once("models/config_model.php");
require_once("models/group_model.php" );
require_once("models/log_model.php" );
require_once("models/login_model.php" );
require_once("models/user_model.php" );
require_once("models/user_group_model.php" );

$A = new Absence_model;
$AG = new Absence_group_model;
$C = new Config_model;
$G = new Group_model;
$L = new Login_model;
$LOG = new Log_model;
$U  = new User_model;
$UG  = new User_group_model;

$message = false;

/**
 * Check if allowed
 */
if (!isAllowed("manageGroups")) showError("notallowed");

$monthnames = $CONF['monthnames'];
$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today = getdate();
$curryear = $today['year']; // numeric value, 4 digits
$currmonth = $today['mon']; // numeric value

/**
 * =========================================================================
 * ADD
 */
if ( isset($_POST['btn_grp_add']) ) 
{
   if (trim($_POST['grp_nameadd'])!='') 
   {
      $G->groupname=preg_replace("/[^A-Za-z0-9_]/i",'',trim($_POST['grp_nameadd']));
      $G->description=htmlspecialchars($_POST['grp_descadd'],ENT_QUOTES);
      $G->options=0x000000;

      if (isset($_POST['chkMinPresent'])) 
      {
         $G->setOptions($CONF['G_MIN_PRESENT']);
         if (is_numeric(trim($_POST['grp_min_present']))) $G->min_present = trim($_POST['grp_min_present']); else $G->min_present = 1;
      }

      if (isset($_POST['chkMaxAbsent'])) 
      {
         $G->setOptions($CONF['G_MAX_ABSENT']);
         if (is_numeric(trim($_POST['grp_max_absent']))) $G->max_absent = trim($_POST['grp_max_absent']); else $G->max_absent = 1;
      }
      if (isset($_POST['chkHide'])) $G->setOptions($CONF['G_HIDE']);

      $G->create();
      
      /**
       * Assign all absence types to this group by default
       */
      $absences = $A->getAll();
      foreach ($absences as $Arow) $AG->assign($Arow['id'],$G->groupname);
      
      /**
       * Send notification mails
       */
      sendNotification("groupadd",$_POST['grp_nameadd'],"");
      
      /**
       * Log this event
       */
      $LOG->log("logGroup",$L->checkLogin(),"log_group_created", $G->groupname." ".$G->description);
   }
   else 
   {
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['err_input_caption'];
      $msg_text    = $LANG['err_input_group_add'];
   }
}
/**
 * =========================================================================
 * UPDATE
 */
else if ( isset($_POST['btn_grp_update']) ) 
{
   if (trim($_POST['grp_name'])!='')
   {
      /**
       * Delete group
       */
      $G->deleteByName($_POST['grp_namehidden']);
      
      /**
       * Recreate with new values
       */
      $G->groupname=preg_replace("/[^A-Za-z0-9_]/i",'',trim($_POST['grp_name']));
      $G->description=htmlspecialchars($_POST['grp_desc'],ENT_QUOTES);
      $G->options=0x000000;
      if (isset($_POST['chkMinPresent'])) $G->setOptions($CONF['G_MIN_PRESENT']);
      if (is_numeric(trim($_POST['grp_min_present']))) $G->min_present = trim($_POST['grp_min_present']); else $G->min_present = 1;
      if (isset($_POST['chkMaxAbsent'])) $G->setOptions($CONF['G_MAX_ABSENT']);
      if (is_numeric(trim($_POST['grp_max_absent']))) $G->max_absent = trim($_POST['grp_max_absent']); else $G->max_absent = 1;
      if (isset($_POST['chkHide'])) $G->setOptions($CONF['G_HIDE']);
      $G->create();
   
      /**
       * If the group name changed we need to go through all team members of
       * this group and change it there as well.
       * Also, the absence type assigments for that group have to be updated.
       */
      if ($_POST['grp_name'] != $_POST['grp_namehidden']) 
      {
         $query= "UPDATE `".$UG->table."` SET `groupname` = '".$G->groupname."' WHERE `groupname` = '".$_POST['grp_namehidden']."';";
         $result = $UG->db->db_query($query);
         $query= "UPDATE `".$AG->table."` SET `group` = '".$G->groupname."' WHERE `group` = '".$_POST['grp_namehidden']."';";
         $result = $AG->db->db_query($query);
      }
   
      /**
       * Send notification mails
       */
      sendNotification("groupchange",$_POST['grp_namehidden'],"");
      
      /**
       * Log this event
       */
      $LOG->log("logGroup",$L->checkLogin(),"log_group_updated", $G->groupname." ".$G->description);
   }
   else 
   {
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['err_input_caption'];
      $msg_text    = $LANG['err_input_group_update'];
   }
}
/**
 * =========================================================================
 * DELETE
 */
else if ( isset($_POST['btn_grp_delete']) ) 
{
   $G->deleteByName($_POST['grp_namehidden']);
   $AG->unassignGroup($_POST['grp_namehidden']);
   $UG->deleteByGroup($_POST['grp_namehidden']);

   /**
    * Send notification mails
    */
   sendNotification("groupdelete",$_POST['grp_namehidden'],"");
   
   /**
    * Log this event
    */
   $LOG->log("logGroup",$L->checkLogin(),"log_group_deleted", $_POST['grp_namehidden']);
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_groups'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Groups';
}
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      
      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <!--  GROUPS =========================================================== -->
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="3">
               <?php printDialogTop($LANG['admin_group_title'], $help, "ico_group.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-caption">
               <table style="border-collapse: collapse; border: 0px; width: 100%;">
                  <tr>
                     <td width="40" style="text-align: center;">&nbsp;</td>
                     <td width="140"><?=$LANG['column_shortname']?></td>
                     <td width="250"><?=$LANG['column_description']?></td>
                     <td width="80"><?=$LANG['column_min_present']?></td>
                     <td width="70"><?=$LANG['column_max_absent']?></td>
                     <td><?=$LANG['column_hide']?></td>
                     <td style="text-align: right;"><?=$LANG['column_action']?></td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <td>
               <!-- Add new group -->
               <form class="form" name="form-grp-add" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                  <table style="border-collapse: collapse; border: 0px; width: 100%;">
                     <tr>
                        <td class="dlg-row1" style="text-align: center; width: 30px;"><img src="themes/<?=$theme?>/img/ico_add.png" alt="Group" title="Group" align="middle"></td>
                        <td class="dlg-row1" style="width: 130px;"><input name="grp_nameadd" size="16" type="text" class="text" id="grp_nameadd" value=""></td>
                        <td class="dlg-row1" style="width: 240px;"><input name="grp_descadd" size="34" type="text" class="text" id="grp_descadd" value=""></td>
                        <td class="dlg-row1" style="width: 70px;"><input name="chkMinPresent" type="checkbox" value="chkMinPresent"><input name="grp_min_present" size="1" maxlength="3" type="text" class="text" value=""></td>
                        <td class="dlg-row1" style="width: 70px;"><input name="chkMaxAbsent" type="checkbox" value="chkMaxAbsent"><input name="grp_max_absent" size="1" maxlength="3" type="text" class="text" value=""></td>
                        <td class="dlg-row1"><input name="chkHide" type="checkbox" value="chkHide"></td>
                        <td class="dlg-row1" style="width: 200px; text-align: right;"><input name="btn_grp_add" type="submit" class="button" value="<?=$LANG['btn_add']?>"></td>
                     </tr>
                  </table>
               </form>
               <?php
               $i=1;
               $printrow=1;
               $groups = $G->getAll();
               foreach ($groups as $row) 
               {
                  $G->findByName(stripslashes($row['groupname']));
                  if ($printrow==1) $printrow=2; else $printrow=1;
                  ?>
                  <!-- <?=$G->groupname?> -->
                  <form class="form" name="form-grp-<?=$i?>" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                     <table style="border-collapse: collapse; border: 0px; width: 100%;">
                        <tr>
                           <td class="dlg-row<?=$printrow?>" style="text-align: center; width: 30px;"><img src="themes/<?=$theme?>/img/ico_group.png" alt="Group" title="Group" align="middle"></td>
                           <td class="dlg-row<?=$printrow?>" style="width: 130px;"><input name="grp_namehidden" type="hidden" class="text" value="<?=$G->groupname?>"><input name="grp_name" size="16" type="text" class="text" value="<?=$G->groupname?>"></td>
                           <td class="dlg-row<?=$printrow?>" style="width: 240px;"><input name="grp_desc" size="34" type="text" class="text" value="<?=$G->description?>"></td>
                           <td class="dlg-row<?=$printrow?>" style="width: 70px;"><input name="chkMinPresent" type="checkbox" value="chkMinPresent" <?=($G->checkOptions($CONF['G_MIN_PRESENT'])?'CHECKED':'')?>><input name="grp_min_present" size="1" maxlength="3" type="text" class="text" value="<?=$G->min_present?>"></td>
                           <td class="dlg-row<?=$printrow?>" style="width: 70px;"><input name="chkMaxAbsent" type="checkbox" value="chkMaxAbsent" <?=($G->checkOptions($CONF['G_MAX_ABSENT'])?'CHECKED':'')?>><input name="grp_max_absent" size="1" maxlength="3" type="text" class="text" value="<?=$G->max_absent?>"></td>
                           <td class="dlg-row<?=$printrow?>"><input name="chkHide" type="checkbox" value="chkHide" <?=($G->checkOptions($CONF['G_HIDE'])?'CHECKED':'')?>></td>
                           <td class="dlg-row<?=$printrow?>" style="width: 200px; text-align: right;">
                              <input name="btn_grp_update" type="submit" class="button" value="<?=$LANG['btn_update']?>">&nbsp;
                              <input name="btn_grp_delete" type="submit" class="button" value="<?=$LANG['btn_delete']?>" onclick="return confirmSubmit('<?=$LANG['eg_delete_confirm']?>')">
                           </td>
                        </tr>
                     </table>
                  </form>
                  <?php 
                  $i+=1;
               }
               ?>
            </td>
         </tr>
      </table>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
