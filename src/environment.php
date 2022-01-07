<?php
/**
 * environment.php
 *
 * Displays the environment page
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

require_once( "models/config_model.php" );
require_once( "models/user_model.php" );
$C = new Config_model;
$U = new User_model;
$error=FALSE;

/**
 * Check if allowed
 */
if (!isAllowed("viewEnvironment")) showError("notallowed");

/**
 * HTML title. Will be shown in browser tab.
 */
$CONF['html_title'] = $LANG['html_title_environment'];
/**
 * User manual page
 * This page has no manual entry for security reasons
 */

require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <table class="dlg">
         <tr>
            <td class="dlg-header" colspan="3">
               <?php printDialogTop($LANG['env_title'],"","ico_env.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body">
               <div align="CENTER">
               <table class="list">
                  <tr>
                     <td class="listhead" colspan="2"><?=$LANG['env_config']?></td>
                  </tr>
                  <?php
                  $class = "1";
                  //echo ("<script type=\"text/javascript\">alert(\"".$CONF['debug_hide_db_info']."\");</script>");
                  foreach ($CONF as $key => $tcc) {
                     $value="";
                     if (($key == "db_server" || $key == "db_name" || $key == "db_user" || $key == "db_pass") && $C->readConfig("debugHide")) {
                        $tcc = "(hidden)";
                     }
                     if ($key == "db_pass") $tcc = "********";
                     if (is_array($tcc)) {
                        foreach ($tcc as $tccval) $value .= $tccval.", ";
                     }
                     else
                        $value = $tcc;
                     echo  "<tr><td class=\"list" . $class . "\">" . $key . "</td><td class=\"list" . $class . "\">" . $value . "</td></tr>\n";
                     if ($class == "1") $class = "2";
                     else $class = "1";
                  }
                  echo "<tr><td class=\"list" . $class . "\">Language file</td><td class=\"list" . $class . "\">" . $CONF['app_root'] . "languages/" . $CONF['options']['lang'] . ".tcpro.php</td></tr>\n";
                  ?>
               </table>
               <br>&nbsp;
               <table class="list">
                  <tr>
                     <td class="listhead" colspan="2"><?=$LANG['env_language'].": ".$CONF['options']['lang']?></td>
                  </tr>
                  <?php
                  $langkeys=array_keys($LANG);
                  sort($langkeys);
                  foreach ($langkeys as $lkey) {
                     echo "<tr><td class=\"list".$class."\">".$lkey."</td><td class=\"list".$class."\">".$LANG[$lkey]."</td></tr>\n";
                     if ($class == "1") $class = "2"; else $class = "1";
                  }
                  ?>
               </table>
               <br>&nbsp;
               </div>
            </td>
         </tr>
      </table>
   </div>
</div>
<?php require("includes/footer_inc.php"); ?>
