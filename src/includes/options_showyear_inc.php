<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_showyear_inc.php
 *
 * Displays the options bar for the showyear page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

$tz = $C->readConfig("timeZone");
if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
else date_default_timezone_set ($tz);
$today     = getdate();
$curryear  = $today['year'];  // A full numeric representation of todays' year, 4 digits
$showyear = $curryear;
if ( isset($_REQUEST['showyear'])
      AND strlen($_REQUEST['showyear'])==4
      AND is_numeric($_REQUEST['showyear']) )
{
   $showyear = $_REQUEST['showyear'];
}
$selectedYear=$showyear;

$showuser='All';
if (isset($_REQUEST['showuser'])) $showuser = trim($_REQUEST['showuser']);
$selectedUser=$showuser;
?>
<!-- Year drop down -->
&nbsp;&nbsp;<?=$LANG['nav_year']?>&nbsp;
<select name="obar_year" class="select">
   <option value="<?=$curryear-1?>" <?=$selectedYear==$curryear-1?' SELECTED':''?> ><?=$curryear-1?></option>
   <option value="<?=$curryear?>" <?=$selectedYear==$curryear?' SELECTED':''?> ><?=$curryear?></option>
   <option value="<?=$curryear+1?>" <?=$selectedYear==$curryear+1?' SELECTED':''?> ><?=$curryear+1?></option>
   <option value="<?=$curryear+2?>" <?=$selectedYear==$curryear+2?' SELECTED':''?> ><?=$curryear+2?></option>
</select>

<!-- User drop down -->
&nbsp;&nbsp;<?=$LANG['nav_user']?>&nbsp;
<select name="obar_user" class="select">
   <?php
   /**
    * Fill the selection list based on what the logged in user may view
    */
   $luser = $L->checkLogin();
   $users = $U->getAllButAdmin();
   foreach ($users as $usr) {
      $allowed=FALSE;
      if ($usr['username']==$luser) {
         $allowed=TRUE;
      }
      else if ( !($usr['status']&$CONF['USHIDDEN']) AND $UG->shareGroups($usr['username'], $luser) ) {
         if (isAllowed("viewGroupUserCalendars")) {
            $allowed=TRUE;
         }
      }
      else if (!($usr['status']&$CONF['USHIDDEN'])) {
         if (isAllowed("viewAllUserCalendars")) {
            $allowed=TRUE;
         }
      }
      if ($allowed) {
         if ( $usr['firstname']!="" ) {
            $showname = $usr['lastname'].", ".$usr['firstname'];
         }
         else {
            $showname = $usr['lastname'];
         } ?>
         <option class="option" value="<?=$usr['username']?>" <?=(($selectedUser==$usr['username'])?'SELECTED':'')?>><?=$showname?></option>
      <?php }
   }
   ?>
</select>
