<?php
/**
 * userimport.php
 *
 * Displays and runs the user import dialog
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

require_once( "models/config_model.php");
require_once( "models/login_model.php" );
require_once( "models/group_model.php" );
require_once( "models/user_model.php" );
require_once( "models/csv_model.php" );

$C = new Config_model;
$CSV = new csvImport;
$G = new Group_model;
$L = new Login_model;
$U = new User_model;

$error=FALSE;

/**
 * Check authorization
 */
if (!isAllowed("manageUsers")) showError("notallowed", TRUE);

/**
 * Process form
 */
if ( isset($_POST['btn_import']) ) {
   $CSV->file_name = $_FILES['file_source']['tmp_name'];
   $lock_user = array_key_exists('chk_lockuser', $_POST);
   $hide_user = array_key_exists('chk_hideuser', $_POST);
   $CSV->import($_POST['list_defgroup'],$_POST['list_deflang'],$lock_user,$hide_user);
}
elseif ( isset($_POST['btn_done']) ) {
   jsCloseAndReload("userlist.php");
}
/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_userimport'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'User+Import';
}
require("includes/header_html_inc.php" );
?>
<body>
   <div id="content">
      <div id="content-content">
         <form method="post" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>">
            <table class="dlg">
               <tr>
                  <td class="dlg-header">
                     <?php printDialogTop($LANG['uimp_title'], $help, "ico_import.png"); ?>
                  </td>
               </tr>
               <tr>
                  <td class="dlg-body" style="padding-left: 10px;">
                     <fieldset><legend><?=$LANG['uimp_title']?></legend>
                        <table style="width: 100%;">
                           <tr>
                              <td class="dlg-body">
                                 <?=$LANG['uimp_import']."<br><br>"?>
                                 <table style="border: 0px; text-align: center">
                                    <tr>
                                       <td><?=$LANG['uimp_source']?></td>
                                       <td width="10">&nbsp;</td>
                                       <td>
                                          <input type="file" name="file_source" id="file_source" class="text" size="40" value="<?=$file_source?>">
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><?=$LANG['uimp_defgroup']?></td>
                                       <td width="10">&nbsp;</td>
                                       <td>
                                          <select name="list_defgroup" id="list_defgroup" class="select">
                                          <option class="option" value="">(none)</option>
                                          <?php
                                          $groups = $G->getAll();
                                          foreach ($groups as $row) {
                                             echo "<option class=\"option\" value=\"".$row['groupname']."\">".$row['groupname']."</option>";
                                          }
                                          ?>
                                          </select>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><?=$LANG['uimp_deflang']?></td>
                                       <td width="10">&nbsp;</td>
                                       <td>
                                          <select name="list_deflang" id="list_deflang" class="select">
                                          <?php
                                          $array = getLanguages();
                                          foreach ($array as $langfile) {
                                             if ($langfile == $CONF['options']['lang'])
                                                echo ("<option value=\"" . $CONF['options']['lang'] . "\" SELECTED=\"selected\">" . $CONF['options']['lang'] . "</option>");
                                             else
                                                echo ("<option value=\"" . $langfile . "\" >" . $langfile . "</option>");
                                          }
                                          ?>
                                          </select>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><?=$LANG['uimp_lockuser']?></td>
                                       <td width="10">&nbsp;</td>
                                       <td>
                                          <input name="chk_lockuser" id="chk_lockuser" type="checkbox" value="chk_lockuser" checked="checked">
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><?=$LANG['uimp_hideuser']?></td>
                                       <td width="10">&nbsp;</td>
                                       <td>
                                          <input name="chk_hideuser" id="chk_hideuser" type="checkbox" value="chk_hideuser" checked="checked">
                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="3">&nbsp;</td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                        </table>
                     </fieldset>
                  </td>
               </tr>
               <?php if (strlen($CSV->error)) { ?>
               <tr>
                  <td class="dlg-body" style="padding-left: 10px;">
                     <fieldset><legend><?=$LANG['uimp_error']?></legend>
                        <span style="color: #DD0000;"><?=$CSV->error?></span>
                     </fieldset>
                  </td>
               </tr>
               <?php }
               elseif ($CSV->count_imported || $CSV->count_skipped) { ?>
               <tr>
                  <td class="dlg-body" style="padding-left: 10px;">
                     <fieldset><legend><?=$LANG['uimp_success']?></legend>
                        <span style="color: #009900;">
                           <?=$CSV->count_imported.$LANG['uimp_success_1']?><br>
                           <?=$CSV->count_skipped.$LANG['uimp_success_2']?><br>
                        </span>
                     </fieldset>
                  </td>
               </tr>
               <?php } ?>
               <tr>
                  <td class="dlg-menu">
                     <input name="btn_import" type="submit" class="button" value="Import" onclick="javascript:var s=document.getElementById('file_source'); if(s!=null && s.value=='') {alert('<?=$LANG['uimp_error_file']?>'); s.focus(); return false;}">
                     <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
                     <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
                     <input name="btn_done" type="submit" class="button" value="<?=$LANG['btn_done']?>">
                  </td>
               </tr>
            </table>
         </form>
      </div>
   </div>
<?php require("includes/footer_inc.php"); ?>
