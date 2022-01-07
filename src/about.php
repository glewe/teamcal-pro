<?php
/**
 * about.php
 *
 * This file displays the About window.
 * You may not alter, disable or remove the About dialog information nor
 * the corresponding $CONF variables.
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 * 
 */

/**
 * Set parent flag to control access to child scripts
 */
define( '_VALID_TCPRO', 1 );

/**
 * Include configuration
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
   <head>
      <title>Lewe TeamCal Pro</title>
      <meta http-equiv="Content-type" content="text/html;charset=iso-8859-1">
      <meta http-equiv="Content-Style-Type" content="text/css">
      <style type="text/css" media="screen">
         body           { background-color: #F0F0F0; color: #000000; font-family: "segoe ui", arial, helvetica, sans-serif; font-size: 13px; margin: 0px; padding: 0px; width: 100%; height: 100%; }
         .button        { -moz-box-shadow:inset 0px 1px 0px 0px #ffffff; -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff; box-shadow:inset 0px 1px 0px 0px #ffffff; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #cecece) ); background:-moz-linear-gradient( center top, #ededed 5%, #cecece 100% ); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#cecece'); background-color:#ededed; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; border:1px solid #dcdcdc; display:inline-block; color:#000000; font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:8pt; font-weight:normal; padding:2px 14px; text-decoration:none; text-shadow:1px 1px 0px #ffffff; }
         .button:hover  { background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #cecece), color-stop(1, #ededed) ); background:-moz-linear-gradient( center top, #cecece 5%, #ededed 100% ); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#cecece', endColorstr='#ededed'); background-color:#dfdfdf; }
         .button:active { position:relative; top:1px; }
      </style>
   </head>
   <body>
      <table style="border-collapse: collapse; margin: 0px; padding: 0px; width: 100%; height: 100%;">
         <tr>
            <td style="background-color: #F7F7F7; width: 260px; padding-top: 16px; vertical-align: top; text-align: center;"><img src="img/Calendar-icon-200.png" alt="TeamCal Pro"></td>
            <td style="background-color: #F7F7F7;">
               <p style="font-weight: bold; font-size: 30px; margin: 10px 0px 10px 0px;">TeamCal Pro</p>
               <p>
               <strong><?=$LANG['about_version']?>:</strong>&nbsp;&nbsp;<?=$CONF['app_version']?><br>
               <strong><?=$LANG['about_copyright']?>:</strong>&nbsp;&nbsp;&copy;2004-<?=$CONF['app_curr_year']?> by <a class="about" href="http://www.lewe.com/" target="_blank"><?=$CONF['app_author']?></a><br>
               <strong><?=$LANG['about_license']?>:</strong>&nbsp;&nbsp;GNU General Public License v3 (<a href="http://www.gnu.org/licenses/gpl.html" target="_blank">GPLv3</a>)<br>
               <br>
               <strong><?=$LANG['about_credits']?>:</strong><br>
               &#8226;&nbsp;jQuery UI Team <?=$LANG['about_for']?> <a href="http://www.jqueryui.com/" target="_blank">jQuery UI</a><br>
               &#8226;&nbsp;Stefan Petre <?=$LANG['about_for']?> <a href="http://www.eyecon.ro/colorpicker/" target="_blank">jQuery Color Picker</a><br>
               &#8226;&nbsp;Heng Yuan <?=$LANG['about_for']?> <a href="http://www.cs.ucla.edu/~heng/JSCookMenu/" target="_blank">JSCookMenu</a><br>
               &#8226;&nbsp;David Vignoni <?=$LANG['about_for']?> <a href="http://www.icon-king.com" target="_blank">Nuvola Icons</a><br>
               &#8226;&nbsp;dAKirby309 <?=$LANG['about_for']?> <a href="http://www.iconarchive.com/show/windows-8-metro-icons-by-dakirby309.html" target="_blank">Windows 8 Metro Icons</a><br>
               &#8226;&nbsp;Everaldo Coelho <?=$LANG['about_for']?> <a href="http://www.iconfinder.com/icondetails/3755/16/agenda_calendar_date_event_icon" target="_blank">the nice icon on the left</a><br>
               &#8226;&nbsp;<?=$LANG['about_misc']?><br>
               &nbsp;
               </p>
            </td>
         </tr>
         <tr>
            <td colspan="2" style="text-align: right; padding: 20px; margin: 0px;">
               <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
            </td>
         </tr>
      </table>
   </body>
</html>
