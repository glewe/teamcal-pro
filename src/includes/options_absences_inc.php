<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_abesences_inc.php
 *
 * Displays the options bar for the absences page
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
<!-- Select absence -->
&nbsp;&nbsp;<?=$LANG['abs_sel_abs']?>&nbsp;
<script type="text/javascript">var sel_absid_cache;</script>
<select id="sel_abs" name="sel_abs" class="select" onclick="sel_absid_cache=this.value" onchange="if (confirm('<?=$LANG['abs_sel_confirm']?>')) this.form.submit(); else this.value=sel_absid_cache;" style="background-image: url(<?=$CONF['app_icon_dir'].$A->icon?>); background-size: 16px 16px; background-repeat: no-repeat; background-position: 2px 2px; padding: 2px 0px 0px 22px;">
   <?php
   $absences = $A->getAll();
   foreach ($absences as $abs) { ?>
      <option style="background-image: url(<?=$CONF['app_icon_dir'].$abs['icon']?>); background-size: 16px 16px; background-repeat: no-repeat; padding-left: 20px;" value="<?=$abs['id']?>" <?=(($abs['id']==$A->id)?"SELECTED":"")?>><?=$abs['name']?></option>
   <?php } 
   ?>
</select>

<!-- Create absence -->
&nbsp;&nbsp;<?=$LANG['abs_create_abs']?>&nbsp;
<input name="txt_create_name" id="txt_create_name" maxlength="80" size="40" type="text" class="text" value="">
&nbsp;&nbsp;<input name="btn_absCreate" type="submit" class="button" value="<?=$LANG['btn_create']?>">
&nbsp;&nbsp;<input name="btn_absList" type="button" class="button" value="<?=$LANG['btn_abs_list']?>" onclick="javascript:window.location.href='abslist.php';">&nbsp;
