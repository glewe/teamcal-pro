<?php
/**
 * upload.php
 *
 * Displays and runs the file upload dialog
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

require_once ("models/upload_model.php");

$C   = new Config_model;
$UPL = new Upload_model;
$error=FALSE;
$upload=FALSE;

/**
 * Check if allowed
 */
if (!isAllowed("editAbsenceTypes")) showError("notallowed", TRUE);

/**
 * Check what type of Upload is desired
 */
switch ($_REQUEST['target']) {
   case "avatar":
      $imgdir = $CONF['app_avatar_dir'];
      $max_size = 1024 * 250; // the max. size for uploading
      $UPL->upload_dir = $CONF['app_root'] . $imgdir;
      $UPL->extensions = array (
         ".gif",
         ".jpg",
         ".png"
      );
      $UPL->max_length_filename = 50;
      $UPL->rename_file = true;
      break;
   case "icon":
      $imgdir = $CONF['app_icon_dir'];
      $max_size = 1024 * 250; // the max. size for uploading
      $UPL->upload_dir = $CONF['app_root'] . $imgdir;
      $UPL->extensions = array (
         ".gif",
         ".jpg",
         ".png"
      );
      $UPL->max_length_filename = 50;
      $UPL->rename_file = true;
      break;
   case "homepage":
      $imgdir = $CONF['app_homepage_dir'];
      $max_size = 1024 * 250; // the max. size for uploading
      $UPL->upload_dir = $CONF['app_root'] . $imgdir;
      $UPL->extensions = array (
         ".gif",
         ".jpg",
         ".png"
      );
      $UPL->max_length_filename = 50;
      $UPL->rename_file = true;
      break;
   default:
      jsCloseAndReload("index.php");
      break;
}

if ($CONF['options']['lang']=="deutsch") $UPL->language="de";

/**
 * =========================================================================
 * UPLOAD
 */
if (isset ($_POST['btn_upload'])) {
   $UPL->the_temp_file = $_FILES['fil_filename']['tmp_name'];
   $UPL->the_file = $_FILES['fil_filename']['name'];
   $UPL->http_error = $_FILES['fil_filename']['error'];
   $UPL->do_filename_check = "y";
   if ($UPL->upload()) {
      $full_path = $UPL->upload_dir . $UPL->file_copy;
      $info = $UPL->get_uploaded_file_info($full_path);
      $upload=TRUE;
   }
}
/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_upload'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Icon+Upload';
}
require("includes/header_html_inc.php");
?>
<body>
   <div id="content">
      <div id="content-content">
         <table class="dlg">
            <tr>
               <td class="dlg-header">
                  <?php printDialogTop($LANG['upload_title'].":&nbsp;".$LANG['upload_type_'.$_REQUEST['target']], $help, "ico_upload.png"); ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-body">
                  <form name="form1" enctype="multipart/form-data" method="post" action="<?=$_SERVER['PHP_SELF']?>?target=<?=$_REQUEST['target']?>">
                     <input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_size?>"><br>
                  <table class="dlg-frame">
                     <tr>
                        <td class="dlg-body"><strong><?=$LANG['upload_maxsize']?>:</strong></td>
                        <td class="dlg-body"><?=$max_size?> bytes</td>
                     </tr>
                     <tr>
                        <td class="dlg-body"><strong><?=$LANG['upload_extensions']?>:</strong></td>
                        <td class="dlg-body"><?php echo implode("&nbsp;&nbsp;", $UPL->extensions); ?></td>
                     </tr>
                     <tr>
                        <td class="dlg-body"><strong><?=$LANG['upload_file']?>:</strong></td>
                        <td class="dlg-body"><input type="file" name="fil_filename" size="30" class="text"></td>
                     </tr>
                     <tr>
                        <td class="dlg-body">&nbsp;</td>
                        <td class="dlg-body"><input class="button" type="submit" name="btn_upload" value="<?=$LANG['btn_upload']?>"></td>
                     </tr>
                  </table>
                  </form>
                  <?php if ($upload) { ?>
                  <table style="border-top: 1px solid #BBBBBB; width: 100%;">
                     <tr>
                        <td style="text-align: left; vertical-align: top;">
                           <img src="<?=$imgdir.$UPL->the_new_file?>" alt="">
                        </td>
                        <td>
                           <?=$UPL->show_error_string()?><br>
                           <?php if (isset($info)) echo nl2br($info); ?>
                        </td>
                     </tr>
                  </table>
                  <?php } ?>
               </td>
            </tr>
            <tr>
               <td class="dlg-menu">
                  <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
                  <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
               </td>
            </tr>
         </table>
      </div>
   </div>
<?php require("includes/footer_inc.php"); ?>
