<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * homepage_inc.php
 *
 * Conditionally included by index.php
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) {
   $help .= 'Home+Page';
}
?>
<div id="content">
   <div id="content-content">
      <table class="dlg">
         <tr>
            <td class="dlg-header">
               <?php printDialogTop($LANG['welcome_title'], $help, "ico_teamcal.png"); ?>
            </td>
         </tr>
         <tr>
            <td class="dlg-body" style="padding: 8px;">
               <?php
               $image=$C->readConfig("welcomeIcon");
               
               if ($image!="No") 
               { ?>
                  <img src="img/homepage/<?=$image?>" alt="" align="left" style="padding: 0px 10px 10px 0px;">
               <?php } ?>
               
               <div style="font-weight: bold; font-size: 110%; padding-bottom: 8px;"><?=stripslashes($C->readConfig("welcomeTitle"))?></div>
               <?=nl2br(html_entity_decode(stripslashes($C->readConfig("welcomeText"))))?>
            </td>
         </tr>
      </table>
   </div>
</div>
