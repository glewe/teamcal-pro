<?php
/**
 * database.php
 *
 * Displays the database maintenance page
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
require_once( "models/announcement_model.php" );
require_once( "models/avatar_model.php" );
require_once( "models/config_model.php");
require_once( "models/daynote_model.php" );
require_once( "models/group_model.php" );
require_once( "models/holiday_model.php" );
require_once( "models/login_model.php" );
require_once( "models/log_model.php" );
require_once( "models/month_model.php" );
require_once( "models/permission_model.php" );
require_once( "models/region_model.php" );
require_once( "models/styles_model.php" );
require_once( "models/template_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_announcement_model.php" );
require_once( "models/user_group_model.php" );
require_once( "models/user_option_model.php" );

$A  = new Absence_model;
$AG = new Absence_group_model;
$AN = new Announcement_model;
$AV = new Avatar_model;
$B  = new Allowance_model;
$C  = new Config_model;
$G  = new Group_model;
$H  = new Holiday_model;
$L  = new Login_model;
$LOG = new Log_model;
$M  = new Month_model;
$N  = new Daynote_model;
$P  = new Permission_model;
$R  = new Region_model;
$S  = new Styles_model;
$T  = new Template_model;
$U  = new User_model;
$UA = new User_announcement_model;
$UG = new User_group_model;
$UO = new User_option_model;

$message = false;

/**
 * Check if allowed
 */
if (!isAllowed("manageDatabase")) showError("notallowed");

$monthnames = $CONF['monthnames'];
$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today = getdate();
$curryear = $today['year']; // numeric value, 4 digits
$currmonth = $today['mon']; // numeric value

$maxsize = "1000000";

/**
 * =========================================================================
 * CLEANUP
 */
if ( isset($_POST['btn_dbmaint_clean']) ) {

   /**
    * Clean old templates older than year.month ...
    */
   if ( strlen($_POST['clean_year']) && strlen($_POST['clean_month']) ) 
   {
      if ( $_POST['cleanup_confirm']=="CLEANUP" ) 
      {
         if ( isset($_POST['chkDBCleanupUsers']) ) 
         {
            /**
             * Delete Templates
             */
            $query  = "DELETE FROM `".$T->table."` WHERE " .
                      "`year`<".intval($_POST['clean_year'])." OR " .
                      "(`year`=".intval($_POST['clean_year'])." AND `month`<=".intval($_POST['clean_month']).")";
            $result = $T->db->db_query($query);
            /**
             * Delete Daynotes
             */
            $keydate=intval($_POST['clean_year'].$_POST['clean_month']."31");
            $query  = "DELETE FROM `".$N->table."` WHERE `yyyymmdd`<=".$keydate." AND `username`<>'all'";
            $result = $N->db->db_query($query);
         }
         
         if ( isset($_POST['chkDBCleanupMonths']) ) 
         {
            /**
             * Delete Month Templates
             */
            $keydate=intval($_POST['clean_year'].$_POST['clean_month']);
            $query  = "DELETE FROM `".$M->table."` WHERE `yearmonth`<=".$keydate;
            $result = $M->db->db_query($query);
            /**
             * Delete Daynotes
             */
            $keydate=intval($_POST['clean_year'].$_POST['clean_month']."31");
            $query  = "DELETE FROM `".$N->table."` WHERE `yyyymmdd`<=".$keydate;
            $result = $N->db->db_query($query);
         }
         
         if ( isset($_POST['chkDBOptimize']) ) 
         {
            /**
             * Optimize tables
             */
            $A->optimize();
            $AG->optimize();
            $AN->optimize();
            $B->optimize();
            $C->optimize();
            $G->optimize();
            $H->optimize();
            $LOG->optimize();
            $M->optimize();
            $N->optimize();
            $P->optimize();
            $R->optimize();
            $S->optimize();
            $T->optimize();
            $U->optimize();
            $UA->optimize();
            $UG->optimize();
            $UO->optimize();
         }
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_cleanup_before", $_POST['clean_year'].$_POST['clean_month']);
         
         /**
          * Prepare confirmation message
          */
         $message     = true;
         $msg_type    = 'success';
         $msg_title   = $LANG['success'];
         $msg_caption = $LANG['admin_dbmaint_cleanup_caption'];
         $msg_text    = $LANG['admin_dbmaint_cleanup_confirm'];
      } 
      else 
      {
         /**
          * Prepare failure message
          */
         $message     = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['admin_dbmaint_cleanup_caption'];
         $msg_text    = $LANG['err_input_dbmaint_clean_success'];
      }
   } 
   else 
   {
      /**
       * Prepare failure message
       */
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['admin_dbmaint_cleanup_caption'];
      $msg_text    = $LANG['err_input_dbmaint_clean'];
   }
}
/**
 * =========================================================================
 * DELETE RECORDS
 */
else if ( isset($_POST['btn_dbmaint_del']) ) 
{
   if ( isset($_POST['del_confirm']) AND $_POST['del_confirm']=="DELETE" ) 
   {
      if ( isset($_POST['chkDBDeleteUsers']) ) 
      {
         $query  = "DELETE FROM `".$U->table."` WHERE `username`<> 'admin'";
         $U->db->db_query($query);
         $query  = "DELETE FROM `".$UO->table."` WHERE `username`<> 'admin'";
         $UO->db->db_query($query);
         $query  = "DELETE FROM `".$N->table."` WHERE `username`!='all'";
         $N->db->db_query($query);
         $query  = "TRUNCATE TABLE `".$T->table."`";
         $T->db->db_query($query);
         $query  = "TRUNCATE TABLE `".$B->table."`";
         $B->db->db_query($query);
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_users");
      }

      if ( isset($_POST['chkDBDeleteGroups']) ) 
      {
         $query  = "TRUNCATE TABLE `".$G->table."`";
         $G->db->db_query($query);
         $query  = "TRUNCATE TABLE `".$UG->table."`";
         $UG->db->db_query($query);
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_groups");
      }

      if ( isset($_POST['chkDBDeleteHolidays']) ) 
      {
         $query  = "DELETE FROM `".$H->table."` WHERE `cfgname`<>'wend' AND `cfgname`<>'busi'";
         $H->db->db_query($query);
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_hol");
      }

      if ( isset($_POST['chkDBDeleteRegions']) ) 
      {
         $query  = "DELETE FROM `".$N->table."` WHERE `region`<>'default'";
         $result = $N->db->db_query($query);
         $query  = "DELETE FROM `".$M->table."` WHERE `region`<>'default'";
         $result = $M->db->db_query($query);
         $query  = "DELETE FROM `".$R->table."` WHERE `regionname`<>'default'";
         $result = $R->db->db_query($query);
                  /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_regions");
      }

      if ( isset($_POST['chkDBDeleteAbsence']) ) 
      {
         $query  = "DELETE FROM `".$A->table."` WHERE `cfgname`<> 'present'";
         $A->db->db_query($query);
         /**
          * With no absence types it does not make sense to keep any
          * user templates or absence2group assignments.
          */
         $query  = "TRUNCATE TABLE `".$T->table."`";
         $T->db->db_query($query);
         $query  = "TRUNCATE TABLE `".$B->table."`";
         $B->db->db_query($query);
         $query  = "TRUNCATE TABLE `".$AG->table."`";
         $AG->db->db_query($query);
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_abs");
      }

      if ( isset($_POST['chkDBDeleteDaynotes']) ) 
      {
         $query  = "DELETE FROM `".$N->table."` WHERE `username`='all'";
         $N->db->db_query($query);
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_daynotes");
      }

      if ( isset($_POST['chkDBDeleteAnnouncements']) ) 
      {
         $AN->clearAnnouncements();
         $UA->deleteAll();
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_ann");
      }

      if ( isset($_POST['chkDBDeleteOrphAnnouncements']) ) 
      {
         $announcements = $AN->getAll();
         foreach ($announcements as $row) {
            if (!count($UA->getAllForTimestamp($row['timestamp']))) $AN->delete($row['timestamp']);
         }
         /**
          * Log this event
          */
         $LOG->log("logAnnouncement",$L->checkLogin(),"log_db_delete_ann_orph");
      }

      if ( isset($_POST['chkDBDeleteLog']) ) 
      {
         $LOG->clear();
         
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_log");
      }

      if ( isset($_POST['chkDBDeleteArchive']) ) 
      {
         $U->deleteAll(TRUE);
         $UG->deleteAll(TRUE);
         $UO->deleteAll(TRUE);
         $T->deleteAll(TRUE);
         $N->deleteAll(TRUE);
         $B->deleteAll(TRUE);
         $UA->deleteAll(TRUE);
         
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_archive");
      }

      if ( isset($_POST['chkDBDeletePermissionSchemes']) ) 
      {
         $query = "DELETE FROM ".$P->table." WHERE scheme != 'Default';";
         $P->db->db_query($query);
         /**
          * Log this event
          */
         $LOG->log("logDatabase",$L->checkLogin(),"log_db_delete_perm");
      }
      
      /**
       * Prepare confirmation message
       */
      $message     = true;
      $msg_type    = 'success';
      $msg_title   = $LANG['success'];
      $msg_caption = $LANG['admin_dbmaint_del_caption'];
      $msg_text    = $LANG['admin_dbmaint_del_confirm_popup'];
   } 
   else 
   {
      /**
       * Prepare failure message
       */
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['admin_dbmaint_del_caption'];
      $msg_text    = $LANG['err_input_dbmaint_del'];
   }
}
/**
 * =========================================================================
 * EXPORT
 */
else if (isset($_POST['btn_export'])) 
{
   switch ($_POST['exp_table']) 
   {
      case 'exp_all':      $what="all"; break;
      case 'exp_absence':  $what=$A->table; break;
      case 'exp_group':    $what=$G->table; break;
      case 'exp_holiday':  $what=$H->table; break;
      case 'exp_region':   $what=$R->table; break;
      case 'exp_log':      $what=$LOG->table; break;
      case 'exp_month':    $what=$M->table; break;
      case 'exp_styles':   $what=$S->table; break;
      case 'exp_template': $what=$T->table; break;
      case 'exp_user':     $what=$U->table; break;
      default:             $what="all"; break;
   }

   switch ($_POST['exp_format']) 
   {
      case 'exp_format_csv': $format="csv"; break;
      case 'exp_format_sql': $format="sql"; break;
      case 'exp_format_xml': $format="xml"; break;
      default:               $format="sql"; break;
   }

   switch ($_POST['exp_output']) 
   {
      case 'exp_output_browser': $type="browser"; break;
      case 'exp_output_file':    $type="download"; break;
      default:                   $type=""; break;
   }

   /**
    * Log this event
    */
   $LOG->log("logDatabase",$L->checkLogin(),"log_db_export", "$format | $what | $type");
   header('Location: exportdata.php?format='.$format.'&what='.$what.'&type='.$type);
}
/**
 * =========================================================================
 * RESTORE
 */
else if ( isset($_POST['btn_rest_rest']) ) 
{
   if (strlen($_FILES['sqlfile']['name'])) 
   {
      $updir = $CONF['app_root'].'sql/';
      $upfile = $updir . "tcpro_dbrestore_".date('Ymd_His').".sql";

      if (move_uploaded_file($_FILES['sqlfile']['tmp_name'], $upfile)) 
      {
         /**
          * Restore database from file
          */
         $db = new Db_model;
         $db->db_connect();
         if ($file_content = file($upfile)) 
         {
            $query = "";
            foreach($file_content as $sql_line) 
            {
               $tsl = trim($sql_line);
               if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) 
               {
                  $query .= $sql_line;
                  if(preg_match("/;\s*$/", $sql_line)) 
                  {
                     $result = $db->db_query($query);
                     if (!$result) die(mysql_error());
                     $query = "";
                     $found=true;
                  }
               }
            }
            
            if (!$found) 
            {
               $message     = true;
               $msg_type    = 'error';
               $msg_title   = $LANG['error'];
               $msg_caption = $LANG['admin_dbmaint_rest_caption'];
               $msg_text    = $LANG['admin_dbmaint_msg_001'];
            }
            else 
            {
               $message     = true;
               $msg_type    = 'success';
               $msg_title   = $LANG['success'];
               $msg_caption = $LANG['admin_dbmaint_rest_caption'];
               $msg_text    = $LANG['admin_dbmaint_msg_002'];
               
               /**
                * Log this event
                */
               $LOG->log("logDatabase",$L->checkLogin(),"log_db_restore", $_FILES['sqlfile']['name']);
            }
         }
      }
      else 
      {
         $message     = true;
         $msg_type    = 'error';
         $msg_title   = $LANG['error'];
         $msg_caption = $LANG['admin_dbmaint_rest_caption'];
         $msg_text    = $LANG['admin_dbmaint_msg_003'];
      }
   }
   else 
   {
      $message     = true;
      $msg_type    = 'error';
      $msg_title   = $LANG['error'];
      $msg_caption = $LANG['admin_dbmaint_rest_caption'];
      $msg_text    = $LANG['admin_dbmaint_msg_004'];
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_database'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) 
{
   $help .= 'Database+Management';
}
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">

      <!-- Message -->
      <?php if ($message) echo jQueryPopup($msg_type, $msg_title, $msg_caption, $msg_text); ?>
                        
      <!--  DATABASE MANAGEMENT ================================================= -->
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="4">
               <?php printDialogTop($LANG['admin_dbmaint_title'], $help, "ico_database.png"); ?>
            </td>
         </tr>

         <tr>
            <td class="dlg-body">
               <div id="tabs">
                  <ul>
                     <li><a href="#tabs-1"><?=$LANG['admin_dbmaint_tab_cleanup']?></a></li>
                     <li><a href="#tabs-2"><?=$LANG['admin_dbmaint_tab_delete']?></a></li>
                     <li><a href="#tabs-3"><?=$LANG['admin_dbmaint_tab_export']?></a></li>
                     <li><a href="#tabs-4"><?=$LANG['admin_dbmaint_tab_restore']?></a></li>
                   </ul>

                  <!-- =======================================================
                       CLEANUP
                  -->
                  <div id="tabs-1">
                     <table class="dlg">
                        <tr>
                           <td class="dlg-caption" colspan="4"><?=$LANG['admin_dbmaint_cleanup_caption']?></td>
                        </tr>
                        <tr>
                           <td class="dlg-help" colspan="4">
                              <form class="form" name="form-db-clean" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                              <table>
                                 <tr>
                                    <td>
                                       <?=$LANG['admin_dbmaint_cleanup_year']?>&nbsp;<input name="clean_year" type="text" class="text" size="6" maxlength="4" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                                       <?=$LANG['admin_dbmaint_cleanup_month']?>&nbsp;<input name="clean_month" type="text" class="text" size="6" maxlength="2" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                                       <?=$LANG['admin_dbmaint_cleanup_hint']?>
                                       <br>
                                    </td>
                                 </tr>
                              </table>
                              <table>
                                 <tr>
                                    <td><input name="chkDBCleanupUsers" id="chkDBCleanupUsers" type="checkbox" value="chkDBCleanupUsers" CHECKED></td>
                                    <td><?=$LANG['admin_dbmaint_cleanup_chkUsers']?></td>
                                 </tr>
                                 <tr>
                                    <td><input name="chkDBCleanupMonths" id="chkDBCleanupMonths" type="checkbox" value="chkDBCleanupMonths" CHECKED></td>
                                    <td><?=$LANG['admin_dbmaint_cleanup_chkMonths']?></td>
                                 </tr>
                                 <tr>
                                    <td><input name="chkDBOptimize" id="chkDBOptimize" type="checkbox" value="chkDBOptimize" CHECKED></td>
                                    <td><?=$LANG['admin_dbmaint_cleanup_chkOptimize']?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <p>
                                       <?=$LANG['admin_dbmaint_cleanup_confirm']?>&nbsp;<input name="cleanup_confirm" type="text" class="text" size="6" maxlength="7" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                                       <input name="btn_dbmaint_clean" type="submit" class="button" value="<?=$LANG['btn_delete_records']?>">
                                       </p>
                                       <p><?=$LANG['admin_dbmaint_cleanup_note']?></p>
                                    </td>
                                 </tr>
                              </table>
                              </form>
                           </td>
                        </tr>
                     </table>
                  </div>

                  <!-- =======================================================
                       DELETE
                  -->
                  <div id="tabs-2">
                     <table class="dlg">
                        <tr>
                           <td class="dlg-caption" colspan="4"><?=$LANG['admin_dbmaint_del_caption']?></td>
                        </tr>
                        <tr>
                           <td class="dlg-help" colspan="4">
                              <form class="form" name="form-db-maint" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                              <table>
                                 <tr><td><input name="chkDBDeleteUsers" id="chkDBDeleteUsers" type="checkbox" value="chkDBDeleteUsers"></td><td><?=$LANG['admin_dbmaint_del_chkUsers']?></td></tr>
                                 <tr><td><input name="chkDBDeleteGroups" id="chkDBDeleteGroups" type="checkbox" value="chkDBDeleteGroups"></td><td><?=$LANG['admin_dbmaint_del_chkGroups']?></td></tr>
                                 <tr><td><input name="chkDBDeleteHolidays" id="chkDBDeleteHolidays" type="checkbox" value="chkDBDeleteHolidays"></td><td><?=$LANG['admin_dbmaint_del_chkHolidays']?></td></tr>
                                 <tr><td><input name="chkDBDeleteRegions" id="chkDBDeleteRegions" type="checkbox" value="chkDBDeleteRegions"></td><td><?=$LANG['admin_dbmaint_del_chkRegions']?></td></tr>
                                 <tr><td><input name="chkDBDeleteAbsence" id="chkDBDeleteAbsence" type="checkbox" value="chkDBDeleteAbsence"></td><td><?=$LANG['admin_dbmaint_del_chkAbsence']?></td></tr>
                                 <tr><td><input name="chkDBDeleteDaynotes" id="chkDBDeleteDaynotes" type="checkbox" value="chkDBDeleteDaynotes"></td><td><?=$LANG['admin_dbmaint_del_chkDaynotes']?></td></tr>
                                 <tr><td><input name="chkDBDeleteAnnouncements" id="chkDBDeleteAnnouncements" type="checkbox" value="chkDBDeleteAnnouncements"></td><td><?=$LANG['admin_dbmaint_del_chkAnnouncements']?></td></tr>
                                 <tr><td><input name="chkDBDeleteOrphAnnouncements" id="chkDBDeleteOrphAnnouncements" type="checkbox" value="chkDBDeleteOrphAnnouncements"></td><td><?=$LANG['admin_dbmaint_del_chkOrphAnnouncements']?></td></tr>
                                 <tr><td><input name="chkDBDeleteLog" id="chkDBDeleteLog" type="checkbox" value="chkDBDeleteLog"></td><td><?=$LANG['admin_dbmaint_del_chkLog']?></td></tr>
                                 <tr><td><input name="chkDBDeleteArchive" id="chkDBDeleteArchive" type="checkbox" value="chkDBDeleteArchive"></td><td><?=$LANG['admin_dbmaint_del_chkArchive']?></td></tr>
                                 <tr><td><input name="chkDBDeletePermissionSchemes" id="chkDBDeletePermissionSchemes" type="checkbox" value="chkDBDeletePermissionSchemes"></td><td><?=$LANG['admin_dbmaint_del_pschemes']?></td></tr>
                                 <tr><td colspan="2">
                                 <br>
                                 &nbsp;<?=$LANG['admin_dbmaint_del_confirm']?>&nbsp;<input name="del_confirm" type="text" class="text" size="6" maxlength="6" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                                 <input name="btn_dbmaint_del" type="submit" class="button" value="<?=$LANG['btn_delete_records']?>">
                                 </td></tr>
                              </table>
                              </form>
                           </td>
                        </tr>
                    </table>
                 </div>

                  <!-- =======================================================
                       EXPORT
                  -->
                  <div id="tabs-3">
                     <table class="dlg">
                        <tr>
                           <td class="dlg-caption" colspan="4"><?=$LANG['admin_dbmaint_exp_caption']?></td>
                        </tr>
                        <tr>
                           <td class="dlg-help" colspan="4">
                              <form enctype="multipart/form-data" class="form" name="form-db-rest" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                              <table>
                                 <tr>
                                    <td>
                                       <?=$LANG['exp_table']?>&nbsp;
                                       <select name="exp_table" id="exp_table" class="select">
                                          <option class="option" value="exp_all" SELECTED><?=$LANG['exp_table_all']?></option>
                                          <option class="option" value="exp_absence"><?=$LANG['exp_table_absence']?></option>
                                          <option class="option" value="exp_group"><?=$LANG['exp_table_group']?></option>
                                          <option class="option" value="exp_holiday"><?=$LANG['exp_table_holiday']?></option>
                                          <option class="option" value="exp_region"><?=$LANG['exp_table_region']?></option>
                                          <option class="option" value="exp_log"><?=$LANG['exp_table_log']?></option>
                                          <option class="option" value="exp_month"><?=$LANG['exp_table_month']?></option>
                                          <option class="option" value="exp_template"><?=$LANG['exp_table_template']?></option>
                                          <option class="option" value="exp_user"><?=$LANG['exp_table_user']?></option>
                                       </select>
                                    </td>
                                 </tr>
                              </table>
                              <br>
                              <table>
                                 <tr>
                                    <td style="vertical-align: top; padding-right: 20px;">
                                       <strong><?=$LANG['exp_format']?></strong><br>
                                       <input name="exp_format" id="exp_format_csv" type="radio" value="exp_format_csv"><?=$LANG['exp_format_csv']?><br>
                                       <input name="exp_format" id="exp_format_sql" type="radio" value="exp_format_sql" CHECKED><?=$LANG['exp_format_sql']?><br>
                                       <input name="exp_format" id="exp_format_xml" type="radio" value="exp_format_xml"><?=$LANG['exp_format_xml']?><br>
                                       <br>
                                       <input name="btn_export" type="submit" class="button" value="<?=$LANG['btn_export']?>">
                                    </td>
                                    <td style="vertical-align: top;">
                                       <strong><?=$LANG['exp_output']?></strong><br>
                                       <input name="exp_output" id="exp_output_browser" type="radio" value="exp_output_browser"><?=$LANG['exp_output_browser']?><br>
                                       <input name="exp_output" id="exp_output_file" type="radio" value="exp_output_file" CHECKED><?=$LANG['exp_output_file']?><br>
                                    </td>
                                 </tr>
                              </table>
                              </form>
                           </td>
                        </tr>
                     </table>
                  </div>

                  <!-- =======================================================
                       RESTORE
                  -->
                  <div id="tabs-4">
                     <table class="dlg">
                        <tr>
                           <td class="dlg-caption" colspan="4"><?=$LANG['admin_dbmaint_rest_caption']?></td>
                        </tr>
                        <tr>
                           <td class="dlg-help" colspan="4">
                              <form enctype="multipart/form-data" class="form" name="form-db-rest" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                              <table>
                                 <tr>
                                    <td>
                                       <?=$LANG['admin_dbmaint_rest_comment']?><br><br>
                                       <input class="text" type="hidden" name="MAX_FILE_SIZE" value="<?PHP echo $maxsize; ?>">
                                       <input class="text" type="file" name="sqlfile" size="46"><br><br>
                                       <input name="btn_rest_rest" type="submit" class="button" value="<?=$LANG['btn_restore']?>">
                                    </td>
                                 </tr>
                              </table>
                              </form>
                           </td>
                        </tr>
                     </table>
                  </div>
                  
               </div>
            </td>
         </tr>
      </table>
   </div>
</div>
<script type="text/javascript">$(function() { $( "#tabs" ).tabs(); });</script>
<?php require("includes/footer_inc.php"); ?>
