<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * header_app_inc.php
 *
 * Included on the main pages to display the application header. This file can
 * be used to display an individual logo.
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */
?>
<div id="header">
   <?php
   if ($C->readConfig("appLogo")=="default" OR $C->readConfig("appLogo")=="") $logo = "themes/".$theme."/img/logo.gif";
   else $logo = $CONF['app_homepage_dir'].$C->readConfig("appLogo");
   ?>
   <img src="<?=$logo?>" alt="">
</div>

<div id="subheader">
   <div id="subheader-content"><?=html_entity_decode($C->readConfig("appSubTitle"))?></div>
</div>

<table class="noscreen" style="width: 100%; border-bottom: 1px solid #555555;">
   <tr>
      <td style="text-align: left; font-size: 18px;"><strong><?=$LANG['print_title']?></strong>&nbsp;&nbsp;(<?=date("j. F Y, H:i")?>)</td>
   </tr>
</table>
