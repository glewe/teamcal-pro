<?php
/**
 * message.php
 *
 * Displays and runs the message dialog
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

require_once( "models/announcement_model.php" );
require_once( "models/config_model.php" );
require_once( "models/group_model.php" );
require_once( "models/log_model.php" );
require_once( "models/login_model.php" );
require_once( "models/user_model.php" );
require_once( "models/user_announcement_model.php" );
require_once( "models/user_group_model.php" );

$AN  = new Announcement_model;
$C   = new Config_model;
$G   = new Group_model;
$LOG = new Log_model;
$L   = new Login_model;
$U   = new User_model;
$UA  = new User_announcement_model;
$UL  = new User_model;
$UG  = new User_group_model;

/**
 * Check if allowed
 */
if (!isAllowed("useMessageCenter")) showError("notallowed", TRUE);

$msgsent = false;
$user=$L->checkLogin();
/**
 * =========================================================================
 * SEND
 */
if (isset($_POST['btn_send'])) 
{
   if ($_POST['opt_msgtype']=="email" AND $C->readConfig("emailNotifications")) 
   {
      /**
       * Send as e-Mail
       */
      $to="";
      switch ($_POST['sendto']) 
      {
      case "all":
         $query  = "SELECT * FROM `".$U->table."`;";
         $result = $U->db->db_query($query);
         while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) if (strlen($row['email'])) $to.=$row['email'].',';
         break;

      case "group":
         $query = "SELECT ".$U->table.".* FROM ".$U->table.",".$UG->table .
                  " WHERE ".$UG->table.".groupname='".$_POST['groupto']."'" .
                  " AND ".$U->table.".username=".$UG->table.".username";
         $result = $U->db->db_query($query);
         while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) if (strlen($row['email'])) $to.=$row['email'].",";
         break;

      case "user":
         if (isset($_POST['userto'])) 
         {
            foreach ($_POST['userto'] as $uto) if ($U->findByName($uto) && strlen($U->email) ) $to.=$U->email.", ";
            $to = substr($to,0,strlen($to)-2); // remove the last ", "
         }
         else 
         {
            showError("input",$LANG['message_sendto_err']);
            die;
         }
         break;
      }

      $user=$L->checkLogin();
      $UL->findByName($user);
      if (strlen($UL->email)) $from=ltrim(mb_encode_mimeheader($UL->firstname." ".$UL->lastname))." <".$UL->email.">"; else $from='';

      if ( sendEmail($to, stripslashes($_POST['subject']), stripslashes($_POST['msg']), $from) ) 
      {
         $msgsent = true;
         $LOG->log("logAnnouncement",$L->checkLogin(),"log_msg_email", $UL->username." to ".$to);
      }
      

   }
   elseif ($_POST['opt_msgtype']=="silent" OR $_POST['opt_msgtype']=="popup") 
   {
      /**
       * Send as Announcement
       */
      if ( strlen($_POST['msg']) ) {
         $tstamp = date("YmdHis");
         $user=$L->checkLogin();
         $UL->findByName($user);
         $mmsg = str_replace("\r\n", "<br>", $_POST['msg']);
         $message = $_POST['subject']."<br><br>".$mmsg."<br><br>[".ltrim($UL->firstname." ".$UL->lastname)."]";
         if ($_POST['opt_msgtype']=="silent") $silent=1; else $silent=0;
         if ($_POST['opt_msgtype']=="popup") $popup=1; else $popup=0;
         if (!$popup && !$silent) $silent=1;
         $AN->save($tstamp,$message,$popup,$silent);
         
         switch ($_POST['sendto']) 
         {
         case "all":
            $to = "all";
            $query  = "SELECT username FROM `".$U->table."`;";
            $result = $U->db->db_query($query);
            while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) $UA->assign($tstamp,$row['username']);
            break;
            
         case "group":
            $to = "group '".$_POST['groupto']."'";
            $query = "SELECT ".$U->table.".* FROM ".$U->table.",".$UG->table .
                     " WHERE ".$UG->table.".groupname='".$_POST['groupto']."'" .
                     " AND ".$U->table.".username=".$UG->table.".username";
            $result = $U->db->db_query($query);
            while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) $UA->assign($tstamp,$row['username']);
            break;
            
         case "user":
            if (isset($_POST['userto'])) 
            {
               $to = "user(s) ";
               foreach ($_POST['userto'] as $uto) 
               {
                  $to .= "'".$uto."', ";
                  if ( $U->findByName($uto) ) $UA->assign($tstamp,$U->username);
               }
            }
            break;
         }
         $msgsent = true;
         $LOG->log("logAnnouncement",$L->checkLogin(),"log_msg_ann", $tstamp.$LANG['log_msg_ann_by'].$UL->username." to ".$to);
      }
   }
}

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_message'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Message+Center';
}
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <form name="message" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="3">
               <?php printDialogTop($LANG['message_title'], $help, "ico_message.png"); ?>
            </td>
         </tr>
            
         <tr>
            <td class="dlg-menu-top" colspan="2" style="text-align: left;">
               <input name="btn_send" type="submit" class="button" value="<?=$LANG['btn_send']?>">
               <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
            </td>
         </tr>
         
         <?php $style="2"; ?> 

         <!-- Message type -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="width: 60%;">
               <span class="config-key"><?=$LANG['message_type']?></span><br>
               <span class="config-comment"><?=$LANG['message_type_desc']?></span>
            </td>
            <td class="config-row<?=$style?>">
               <?php if ($C->readConfig("emailNotifications")) { ?>
               <input style="vertical-align: bottom; margin-right: 8px;" name="opt_msgtype" type="radio" value="email" checked><?=$LANG['message_type_email']?><br>
               <?php } ?>
               <input style="vertical-align: bottom; margin-right: 8px;" name="opt_msgtype" type="radio" value="silent"><?=$LANG['message_type_announcement_silent']?><br>
               <input style="vertical-align: bottom; margin-right: 8px;" name="opt_msgtype" type="radio" value="popup"><?=$LANG['message_type_announcement_popup']?><br>
            </td>
         </tr>
         
         <!-- Recipient -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="width: 60%;">
               <span class="config-key"><?=$LANG['message_sendto']?></span><br>
               <span class="config-comment"><?=$LANG['message_sendto_desc']?></span>
            </td>
            <td class="config-row<?=$style?>">

               <table>
                  <tr>
                     <td style="vertical-align: top; padding-bottom: 8px;">
                        <?php if (isAllowed("viewAllGroups")) { ?>
                           <input style="vertical-align: bottom;" name="sendto" id="sendtoall" type="radio" class="input" value="all"><?=$LANG['message_sendto_all']?>&nbsp;
                        <?php } ?>
                     </td>
                     <td style="vertical-align: top; padding-bottom: 8px;">&nbsp;</td>
                  </tr>
                  <tr>
                     <td style="vertical-align: top; padding-bottom: 8px;">
                        <input style="vertical-align: bottom;" name="sendto" id="sendtogroup" type="radio" class="input" value="group"><?=$LANG['message_sendto_group']?>&nbsp;
                     </td>
                     <td style="vertical-align: top; padding-bottom: 8px;">
                        <select name="groupto" id="groupto" class="select">
                        <?php
                        $groups=$G->getAll(TRUE); // TRUE = exclude hidden
                        foreach( $groups as $group ) {
                           if (isAllowed("viewAllGroups")) {
                              if ($UO->true($user, "owngroupsonly")) {
                                 if ( $UG->isMemberOfGroup($user, $group['groupname']) OR $UG->isGroupManagerOfGroup($user, $group['groupname'])) { ?>
                                    <option class="option" value="<?=$group['groupname']?>"><?=$group['groupname']?></option>
                                 <?php }
                              } ?>
                              <option class="option" value="<?=$group['groupname']?>"><?=$group['groupname']?></option>
                           <?php }
                        }
                        ?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td style="vertical-align: top; padding-bottom: 8px;">
                        <input style="vertical-align: bottom;" name="sendto" id="sendtouser" type="radio" class="input" value="user" CHECKED><?=$LANG['message_sendto_user']?>&nbsp;
                     </td>
                     <td style="vertical-align: top; padding-bottom: 8px;">
                        <select name="userto[]" id="userto" class="select" multiple="multiple" size="5">
                        <?php
                        $users = $U->getAll();
                        foreach ($users as $row) {
                           if ( isset($user) && $user!=$row['username'] ) {
                              if ( $row['firstname']!="" ) $showname = $row['lastname'].", ".$row['firstname'];
                              else $showname = $row['lastname']; ?>
                              <option class="option" value="<?=$row['username']?>"><?=$showname?></option>
                           <?php }
                        }
                        ?>
                        </select>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         
         <!-- Message -->
         <?php if ($style=="1") $style="2"; else $style="1"; ?>
         <tr>
            <td class="config-row<?=$style?>" style="width: 60%;">
               <span class="config-key"><?=$LANG['message_msg']?></span><br>
               <span class="config-comment"><?=$LANG['message_msg_desc']?></span>
            </td>
            <td class="config-row<?=$style?>">
               <table>
                  <tr>
                     <td style="vertical-align: middle;"><strong><?=$LANG['message_msg_subject']?></strong></td>
                     <td>
                        <input name="subject" id="subject" size="53" type="text" class="text" value="<?=$LANG['message_msg_subject_sample']?>"><br>
                     </td>
                  </tr>
                  <tr>
                     <td style="vertical-align: top;"><strong><?=$LANG['message_msg_body']?></strong></td>
                     <td>
                        <textarea name="msg" id="msg" class="text" ROWS="10" COLS="50"><?=$LANG['message_msg_body_sample'] . "\r\n"?></textarea>
                        <br>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         
         <tr>
            <td class="dlg-menu" colspan="2" style="text-align: left;">
               <input name="btn_send" type="submit" class="button" value="<?=$LANG['btn_send']?>">
               <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
            </td>
         </tr>
         
      </table>
      </form>
   </div>
</div>
<?php 
if ($msgsent) echo ("<script type=\"text/javascript\">alert(\"" . $LANG['message_msgsent'] . "\")</script>");
require("includes/footer_inc.php"); 
?>
