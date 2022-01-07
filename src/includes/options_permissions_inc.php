<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_permissions_inc.php
 *
 * Displays the options bar for the permissions page
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
<!-- Select scheme -->
&nbsp;&nbsp;<?=$LANG['perm_sel_scheme']?>&nbsp;
<script type="text/javascript">var sel_scheme_cache;</script>
<select id="sel_scheme" name="sel_scheme" class="select" onclick="sel_scheme_cache=this.value" onchange="if (confirm('<?=$LANG['perm_select_confirm']?>')) this.form.submit(); else this.value=sel_scheme_cache;">
  <?php
   $schemes = $P->getSchemes();
   foreach ($schemes as $sch) { ?>
      <option value="<?=$sch?>" <?=(($sch==$scheme)?'SELECTED':'')?>><?=$sch?></option>
   <?php } ?>
</select>
&nbsp;&nbsp;<input name="btn_permActivate" type="submit" class="button" value="<?=$LANG['btn_activate']?>" onclick="return confirmSubmit('<?=$LANG['perm_activate_confirm']?>')">
<?php if ($scheme != "Default") { ?>
&nbsp;&nbsp;<input name="btn_permDelete" type="submit" class="button" value="<?=$LANG['btn_delete']?>" onclick="return confirmSubmit('<?=$LANG['perm_delete_confirm']?>')">
<?php } ?>

<!-- Create scheme -->
&nbsp;&nbsp;<?=$LANG['perm_create_scheme']?>&nbsp;
<input name="txt_newScheme" id="txt_newScheme" maxlength="80" size="40" type="text" class="text" value="">
&nbsp;&nbsp;<input name="btn_permCreate" type="submit" class="button" value="<?=$LANG['btn_create']?>">
