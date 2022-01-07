<?php
/**
 * phpinfo.php
 *
 * Displays the phpinfo page
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

require_once("models/config_model.php" );
require_once("models/user_model.php" );

$C = new Config_model;
$U = new User_model;
$error=FALSE;

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_phpinfo'];

/**
 * Check if allowed
 */
if (!isAllowed("viewEnvironment")) showError("notallowed");

require("includes/header_html_inc.php" );
require("includes/header_app_inc.php" );
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="3">
               <?php printDialogTop($LANG['php_title'],"","ico_php.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body">
               <iframe src ="phpinfoshow.php" width="100%" height="800"></iframe>
            </td>
         </tr>
      </table>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
