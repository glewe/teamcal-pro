<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_statisticsu_inc.php
 *
 * Displays the options bar for the remainder statistics page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: ".$_POST['optPeriod']."\");</script>";

?>
<!-- Group -->
&nbsp;&nbsp;<?=$LANG['stat_choose_group']?>&nbsp;
<select name="sel_group" class="select">
   <option class="option" value="All" <?=($statgroup=="All"?"selected":"")?>><?=$LANG['drop_group_all']?></option>
   <?php
   $groups = $G->getAll();
   $selectedGroup=$statgroup;
   foreach ($groups as $grp) {
      $G->findByName($grp['groupname']);
      if (!$G->checkOptions($CONF['G_HIDE']) ) { ?>
         <option value="<?=$grp['groupname']?>" <?=(($grp['groupname']==$selectedGroup)?'SELECTED':'')?>><?=$grp['groupname']?></option>
      <?php }
   } ?>
</select>

<!-- User -->
&nbsp;&nbsp;<?=$LANG['nav_user']?>&nbsp;
<select name="sel_user" class="select">
   <option class="option" value="All" <?=($statuser=="%"?"SELECTED":"")?>><?=$LANG['drop_group_all']?></option>
   <?php
   $users = $U->getAllButAdmin();
   foreach ($users as $row) { ?>
      <option value="<?=$row['username']?>" <?=(($statuser==$row['username'])?'SELECTED':'')?>><?=$row['lastname']?>, <?=$row['firstname']?></option>
   <?php } ?>
</select>
