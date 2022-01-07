<?php
/**
 * abesences.php
 *
 * Displays the absence types configuration page
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
require_once("models/absence_group_model.php");
require_once("models/config_model.php");
require_once("models/group_model.php");
require_once("models/log_model.php");
require_once("models/login_model.php");
require_once("models/user_model.php");

$A = new Absence_model;
$AG = new Absence_group_model;
$C = new Config_model;
$G = new Group_model;
$L = new Login_model;
$LOG = new Log_model;
$U = new User_model;

/**
 * Check if allowed
 */
if (!isAllowed("editAbsenceTypes")) showError("notallowed");

/**
 * Read all absence types and select first in array
 */
$absences = $A->getAll();
$absid = $absences[0]['id'];
if ( isset($_REQUEST['absid']) ) $absid = $_REQUEST['absid'];
$A->get($absid);

/**
 * ========================================================================
 * CREATE
 */
if ( isset($_POST['btn_absCreate']) ) {

   if (isset($_POST['txt_create_name']) AND !empty($_POST['txt_create_name'])) {
      if (!preg_match('/^[a-zA-Z0-9-_\x20]*$/', $_POST['txt_create_name'])) {
        showError("input",$LANG['err_input_abs_name']);
      }
      else {
         $A->name = $_POST['txt_create_name'];
         $A->symbol = "A";
         $A->icon = "No";
         $A->color = "000000";
         $A->bgcolor = "FFFFFF";
         $A->factor = 1;
         $A->allowance = 0;
         $A->show_in_remainder = 1;
         $A->show_totals = 1;
         $A->approval_required = 0;
         $A->counts_as_present = 0;
         $A->manager_only = 0;
         $A->hide_in_profile = 0;
         $A->confidential = 0;
         $A->create();
         $absid = $A->getLastId();
   
         /**
          * Assign it to all groups by default
          */
         $groups = $G->getAll();
         foreach ($groups as $group) {
            $AG->assign($absid,$group['groupname']);
         }
               
         /**
          * Create the theme css files so they include it's colors
          */
         $themearray = getFolders('themes');
         foreach ($themearray as $theme) {
            createCSS($theme["name"]);
         }
          
         $absences = $A->getAll();
         $A->get($absid);
         
         /**
          * Log this event
          */
         $LOG->log("logAbsence",$L->checkLogin(),"log_abs_created", $A->name." (".$absid.")");
         header("Location: ".$_SERVER['PHP_SELF']."?absid=".$absid);
         die();
      }
   }
   else {
      showError("input",$LANG['err_input_abs_no_name']);
   }
}
/**
 * ========================================================================
 * APPLY
 */
else if ( isset($_POST['btn_absApply']) ) {
    
   if (!empty($_POST['txt_name'])) {
      if (!preg_match('/^[a-zA-Z0-9-_\x20]*$/', $_POST['txt_name'])) {
         showError("input",$LANG['err_input_abs_name']);
      }
      else {
         $A->name = $_POST['txt_name'];
      }
   }
   
   if (!empty($_POST['txt_symbol'])) {
      if (!preg_match('/^[a-zA-Z0-9-=+*#$%&*()_]*$/', $_POST['txt_symbol'])) {
         showError("input",$LANG['err_input_abs_symbol']);
      }
      else {
         $A->symbol = $_POST['txt_symbol'];
      }
   }
    
   if (!empty($_POST['txt_color'])) {
      if (!preg_match('/^[a-fA-F0-9]*$/', $_POST['txt_color'])) {
         showError("input",$LANG['err_input_abs_color']);
      }
      else {
         $A->color = $_POST['txt_color'];
      }
   }
   
   if (!empty($_POST['txt_bgcolor'])) {
      if (!preg_match('/^[a-fA-F0-9]*$/', $_POST['txt_bgcolor'])) {
         showError("input",$LANG['err_input_abs_color']);
      }
      else {
         $A->bgcolor = $_POST['txt_bgcolor'];
      }
   }

   if ( isset($_POST['chk_bgtransparent']) && $_POST['chk_bgtransparent'] ) $A->bgtransparent=1; else $A->bgtransparent=0;
    
   if (isset($_POST['txt_factor']) AND strlen($_POST['txt_factor'])) {
      if (!is_numeric($_POST['txt_factor'])) {
         showError("input",$LANG['err_input_abs_factor']);
      }
      else {
         $A->factor = $_POST['txt_factor'];
      }
   }

   $A->counts_as = $_POST['sel_absCountsAs'];
    
   if (isset($_POST['txt_allowance']) AND strlen($_POST['txt_allowance'])) {
      if (!is_numeric($_POST['txt_allowance'])) {
         showError("input",$LANG['err_input_abs_allowance']);
      }
      else {
         $A->allowance = $_POST['txt_allowance'];
      }
   }
    
   $A->icon = $_POST['sel_icon'];
   if ( isset($_POST['chk_show_in_remainder']) && $_POST['chk_show_in_remainder'] ) $A->show_in_remainder=1; else $A->show_in_remainder=0;
   if ( isset($_POST['chk_show_totals']) && $_POST['chk_show_totals'] )             $A->show_totals=1;       else $A->show_totals=0;
   if ( isset($_POST['chk_approval_required']) && $_POST['chk_approval_required'] ) $A->approval_required=1; else $A->approval_required=0;
   if ( isset($_POST['chk_counts_as_present']) && $_POST['chk_counts_as_present'] ) $A->counts_as_present=1; else $A->counts_as_present=0;
   if ( isset($_POST['chk_manager_only']) && $_POST['chk_manager_only'] )           $A->manager_only=1;      else $A->manager_only=0;
   if ( isset($_POST['chk_hide_in_profile']) && $_POST['chk_hide_in_profile'] )     $A->hide_in_profile=1;   else $A->hide_in_profile=0;
   if ( isset($_POST['chk_confidential']) && $_POST['chk_confidential'] )           $A->confidential=1;      else $A->confidential=0;
   if ( isset($_POST['chk_admin_allowance']) && $_POST['chk_admin_allowance'] )     $A->admin_allowance=1;   else $A->admin_allowance=0;
     
   $A->update($_POST['txt_absid']);
   
   $absences = $A->getAll();
   $absid=$_POST['txt_absid'];
   
   /**
    * Assign it to the selected groups
    */
   if (isset($_POST['abs_groups'])) {
      $AG->unassignAbs($_POST['txt_absid']);
      
      foreach ($_POST['abs_groups'] as $group) {
         $AG->assign($_POST['txt_absid'],$group);
      }
   }
    
   /**
    * Create the theme css files so it includes this absence type
    */
   $themearray = getFolders('themes');
   
   foreach ($themearray as $theme) {
      createCSS($theme["name"]);
   }
    
   /**
    * Log this event
    */
   $LOG->log("logAbsence",$L->checkLogin(),"log_abs_updated", $A->name." (".$_POST['txt_absid'].")");
   header("Location: ".$_SERVER['PHP_SELF']."?absid=".$_POST['txt_absid']);
   die();
}
/**
 * ========================================================================
 * DELETE
 */
else if (isset($_POST['btn_absDelete'])) {
   
   $A->delete($_POST['txt_absid']);
   $absences = $A->getAll();
    
   /**
    * Log this event
    */
   $LOG->log("logAbsence",$L->checkLogin(),"log_abs_deleted", $A->name." (".$_POST['txt_absid'].")");
   header("Location: ".$_SERVER['PHP_SELF']);
   die();
}

/**
 * Check whether a different scheme was selected
 */
if (isset($_POST['sel_abs'])) {
   header("Location: ".$_SERVER['PHP_SELF']."?absid=".$_POST['sel_abs']);
   die();
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_absences'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Absence+Types';
}

require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <form class="form" name="form-abs" method="POST" action="<?=$_SERVER['PHP_SELF']."?absid=".$A->id?>">
      <input name="txt_absid" type="hidden" class="text" value="<?=$A->id?>">
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="2">
               <?php printDialogTop($LANG['abs_title'].$A->name."' (ID=".$A->id.")", $help, "ico_absences.png"); ?>
            </td>
         </tr>
         
         <tr>
            <td class="dlg-menu" colspan="2" style="text-align: left;">
               <input name="btn_absApply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">&nbsp;
               <input name="btn_absDelete" type="submit" class="button" value="<?=$LANG['btn_delete']?>" onclick="if (confirm('<?=$LANG['abs_del_confirm'].$A->name?> (<?=$A->id?>)')) this.form.submit();" >&nbsp;
               <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
            </td>
         </tr>

         <?php $style="2"; ?> 

         <!-- Sample -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="width: 60%;">
               <span class="config-key"><?=$LANG['abs_sample']?></span><br>
               <span class="config-comment"><?=$LANG['abs_sample_desc']?></span>
            </td>
            <td class="config-row<?=$style?>">
            <?php if ($A->bgtransparent) $backgroundstyle = ""; else $backgroundstyle = "background-color: #" . $A->bgcolor;?>
            <div id="sample" style="color: #<?=$A->color?>; <?=$backgroundstyle?>; border: 1px solid #000000; width: 24px; height: 20px; text-align: center; padding: 4px 0px 0px 0px;">
               <?php if ($A->icon=="No") {?>
                  <?=$A->symbol?>
               <?php } else { ?>
                  <img src="<?=$CONF['app_icon_dir'].$A->icon?>" alt="" style="vertical-align: middle;">
               <?php } ?>
               </div>
            </td>
         </tr>
         
         <!-- Name -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="width: 60%;">
               <span class="config-key"><?=$LANG['abs_name']?></span><br>
               <span class="config-comment"><?=$LANG['abs_name_desc']?></span>
            </td>
            <td class="config-row<?=$style?>">
               <input class="text" name="txt_name" id="txt_name" type="text" size="50" maxlength="80" value="<?=$A->name?>">
            </td>
         </tr>
         
         <!-- Symbol -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="width: 60%;">
               <span class="config-key"><?=$LANG['abs_symbol']?></span><br>
               <span class="config-comment"><?=$LANG['abs_symbol_desc']?></span>
            </td>
            <td class="config-row<?=$style?>">
               <input class="text" name="txt_symbol" id="txt_symbol" type="text" size="2" maxlength="1" value="<?=$A->symbol?>" onchange="document.getElementById('sample').innerHTML=this.value;">
            </td>
         </tr>
         
         <!-- Icon -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_icon']?></span><br>
               <span class="config-comment"><?=$LANG['abs_icon_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%; vertical-align: top;">
               <script type="text/javascript">
                  function switchAbsIcon(image) { 
                     document.getElementById('sel_icon').style.backgroundImage="url('<?=$CONF['app_icon_dir']?>"+image+"')";
                     if (image!="No") {
                        document.getElementById('sample').innerHTML='<img src="<?=$CONF['app_icon_dir']?>'+image+'" alt="" style="vertical-align: middle;">';
                     }
                     else {
                        document.getElementById('sample').innerHTML='<?=$A->symbol?>';
                     }
                  }
               </script>
               <select id="sel_icon" name="sel_icon" class="select" onchange="javascript: switchAbsIcon(this.value);" style="background-image: url(<?=$CONF['app_icon_dir'].$A->icon?>); background-size: 16px 16px; background-repeat: no-repeat; background-position: 2px 2px; padding: 2px 0px 0px 22px;">
                  <option value="No" <?=(($A->icon=="No")?"SELECTED":"")?>><?=$LANG['no']?></option>
                  <?php
                  $fileTypes = array ("gif", "jpg", "png");
                  $imgFiles = getFiles($CONF['app_icon_dir']);
                  sort($imgFiles);
                  foreach ($imgFiles as $file) { ?>
                     <option style="background-image: url(<?=$CONF['app_icon_dir'].$file?>); background-size: 16px 16px; background-repeat: no-repeat; padding-left: 20px;" value="<?=$file?>" <?=(($A->icon==$file)?"SELECTED":"")?>><?=$file?></option>
                  <?php } ?>
               </select>
               &nbsp;<input name="btn_upload" type="button" class="button" onclick="javascript:this.blur();openPopup('upload.php?target=icon','upload','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=500,height=400');" value="<?=$LANG['btn_upload']?>">
               <?php if($A->icon!="No") { ?>
               <img src="<?=$CONF['app_icon_dir'].$A->icon?>" alt="" align="top" id="absIcon">
               <?php } ?>
            </td>
         </tr>

         <!-- Color -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_color']?></span><br>
               <span class="config-comment"><?=$LANG['abs_color_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input class="text" name="txt_color" id="txt_color" type="text" size="6" maxlength="6" value="<?=$A->color?>">
               <span id="color_sample" style="background-color: #<?=$A->color?>; margin: 0px 0px 0px 10px; padding: 4px;"><img src="img/blank.png" alt="" style="width: 20px; height: 20px;"></span>
            </td>
         </tr>

         <!-- Bgcolor -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_bgcolor']?></span><br>
               <span class="config-comment"><?=$LANG['abs_bgcolor_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input class="text" name="txt_bgcolor" id="txt_bgcolor" type="text" size="6" maxlength="6" value="<?=$A->bgcolor?>">
               <span id="bgcolor_sample" style="background-color: #<?=$A->bgcolor?>; margin: 0px 0px 0px 10px; padding: 4px;"><img src="img/blank.png" alt="" style="width: 20px; height: 20px;"></span>
            </td>
         </tr>

         <!-- Background transparent -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_bgtransparent']?></span><br>
               <span class="config-comment"><?=$LANG['abs_bgtransparent_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_bgtransparent" id="chk_bgtransparent" value="chk_bgtransparent" type="checkbox" <?=(intval($A->bgtransparent)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Factor -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_factor']?></span><br>
               <span class="config-comment"><?=$LANG['abs_factor_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input class="text" name="txt_factor" id="txt_factor" type="text" size="2" maxlength="4" value="<?=$A->factor?>">
            </td>
         </tr>

         <!-- Allowance -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_allowance']?></span><br>
               <span class="config-comment"><?=$LANG['abs_allowance_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input class="text" name="txt_allowance" id="txt_allowance" type="text" size="2" maxlength="4" value="<?=$A->allowance?>">
            </td>
         </tr>

         <!-- Counts as -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_counts_as']?></span><br>
               <span class="config-comment"><?=$LANG['abs_counts_as_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
					<select id="sel_absCountsAs" name="sel_absCountsAs" class="select">
					   <option value="0" <?=(($A->counts_as==0)?"SELECTED":"")?>>-</option>
					   <?php
					   $countsAsAbsences = $A->getAll();
					   foreach ($countsAsAbsences as $countsAsAbs) { 
							if ($countsAsAbs['id']!=$A->id) { ?>
					      	<option value="<?=$countsAsAbs['id']?>" <?=(($countsAsAbs['id']==$A->counts_as)?"SELECTED":"")?>><?=$countsAsAbs['name']?></option>
					      <?php }
					   } 
					   ?>
					</select>
            </td>
         </tr>

         <!-- Show in remainder -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_show_in_remainder']?></span><br>
               <span class="config-comment"><?=$LANG['abs_show_in_remainder_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_show_in_remainder" id="chk_show_in_remainder" value="chk_show_in_remainder" type="checkbox" <?=(intval($A->show_in_remainder)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Show totals -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_show_totals']?></span><br>
               <span class="config-comment"><?=$LANG['abs_show_totals_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_show_totals" id="chk_show_totals" value="chk_show_totals" type="checkbox" <?=(intval($A->show_totals)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Approval required -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_approval_required']?></span><br>
               <span class="config-comment"><?=$LANG['abs_approval_required_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_approval_required" id="chk_approval_required" value="chk_approval_required" type="checkbox" <?=(intval($A->approval_required)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Counts as present -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_counts_as_present']?></span><br>
               <span class="config-comment"><?=$LANG['abs_counts_as_present_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_counts_as_present" id="chk_counts_as_present" value="chk_counts_as_present" type="checkbox" <?=(intval($A->counts_as_present)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Manager only -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_manager_only']?></span><br>
               <span class="config-comment"><?=$LANG['abs_manager_only_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_manager_only" id="chk_manager_only" value="chk_manager_only" type="checkbox" <?=(intval($A->manager_only)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Hide in profile -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_hide_in_profile']?></span><br>
               <span class="config-comment"><?=$LANG['abs_hide_in_profile_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_hide_in_profile" id="chk_hide_in_profile" value="chk_hide_in_profile" type="checkbox" <?=(intval($A->hide_in_profile)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Confidential -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_confidential']?></span><br>
               <span class="config-comment"><?=$LANG['abs_confidential_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_confidential" id="chk_confidential" value="chk_confidential" type="checkbox" <?=(intval($A->confidential)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Admin allowance -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_admin_allowance']?></span><br>
               <span class="config-comment"><?=$LANG['abs_admin_allowance_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <input name="chk_admin_allowance" id="chk_admin_allowance" value="chk_admin_allowance" type="checkbox" <?=(intval($A->admin_allowance)?"CHECKED":"")?>>
            </td>
         </tr>

         <!-- Group assignments -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
               <span class="config-key"><?=$LANG['abs_groups']?></span><br>
               <span class="config-comment"><?=$LANG['abs_groups_desc']?></span>
            </td>
            <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
               <select name="abs_groups[]" id="abs_groups" class="select" multiple="multiple" size="6">
               <?php
               $groups = $G->getAll();
               foreach ($groups as $group) {
                  if ($AG->isAssigned($absid,$group['groupname'])) { $selected="selected"; } else { $selected=""; } ?>
                  <option class="option" value="<?=$group['groupname']?>" <?=$selected?>><?=$group['groupname']?></option>
               <?php } ?>
               </select>
            </td>
         </tr>

         <tr>
            <td class="dlg-menu" colspan="2" style="text-align: left;">
               <input name="btn_absApply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">&nbsp;
               <input name="btn_absDelete" type="submit" class="button" value="<?=$LANG['btn_delete']?>" onclick="if (confirm('<?=$LANG['abs_del_confirm'].$A->name?> (<?=$A->id?>)')) this.form.submit();" >&nbsp;
               <input name="btn_help" type="button" class="button" onclick="window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
            </td>
         </tr>

      </table>
      </form>
   </div>
</div>
<script type="text/javascript">
   $(function() { $( "#txt_color" ).ColorPicker({ onSubmit: function(hsb, hex, rgb, el) { $(el).val(hex.toUpperCase()); $(el).ColorPickerHide(); document.getElementById('color_sample').style.backgroundColor='#'+el.value; document.getElementById('sample').style.color='#'+el.value; }, onBeforeShow: function () { $(this).ColorPickerSetColor(this.value); } }) .bind('keyup', function(){ $(this).ColorPickerSetColor(this.value); }); });
   $(function() { $( "#txt_bgcolor" ).ColorPicker({ onSubmit: function(hsb, hex, rgb, el) { $(el).val(hex.toUpperCase()); $(el).ColorPickerHide(); document.getElementById('bgcolor_sample').style.backgroundColor='#'+el.value; document.getElementById('sample').style.backgroundColor='#'+el.value; }, onBeforeShow: function () { $(this).ColorPickerSetColor(this.value); } }) .bind('keyup', function(){ $(this).ColorPickerSetColor(this.value); }); });
</script>
<?php require("includes/footer_inc.php"); ?>