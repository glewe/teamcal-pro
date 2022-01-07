<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_statistics_inc.php
 *
 * Displays the options bar for the global statistics page
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
<!-- Standard Period -->
&nbsp;&nbsp;<input name="optPeriod" type="radio" class="radio" style="vertical-align: middle;" value="standard" <?=(($periodType=='standard')?'CHECKED':'')?>><?=$LANG['stat_choose_period']?>&nbsp;
<select name="period" id="period" class="select">
   <option class="option" value="curr_month" <?=((isset($_POST['period']) AND $_POST['period']=="curr_month")?'selected':'')?>><?=$LANG['stat_period_month']?></option>
   <option class="option" value="curr_quarter" <?=((isset($_POST['period']) AND $_POST['period']=="curr_quarter")?'selected':'')?>><?=$LANG['stat_period_quarter']?></option>
   <option class="option" value="curr_half" <?=((isset($_POST['period']) AND $_POST['period']=="curr_half")?'selected':'')?>><?=$LANG['stat_period_half']?></option>
   <option class="option" value="curr_year" <?=((isset($_POST['period']) AND $_POST['period']=="curr_year")?'selected':'')?>><?=$LANG['stat_period_year']?></option>
   <option class="option" value="curr_period" <?=((isset($_POST['period']) AND $_POST['period']=="curr_period")?'selected':'')?>><?=$LANG['stat_period_period']?></option>
</select>

<!-- Custom Period -->
&nbsp;&nbsp;<input name="optPeriod" type="radio" class="radio" style="vertical-align: middle;" value="custom" <?=(($periodType=='custom')?'CHECKED':'')?>><?=$LANG['stat_choose_custom_period']?>&nbsp;
<script type="text/javascript">
   $(function() { $( "#rangefrom" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
   $(function() { $( "#rangeto" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); });
</script>
<?php
if (isset($_POST['rangefrom'])) $rangefromdate = $_POST['rangefrom']; else $rangefromdate = $yeartoday."-01-01";
if (isset($_POST['rangeto'])) $rangetodate = $_POST['rangeto']; else $rangetodate = $yeartoday."-12-31";
?>
<input name="rangefrom" id="rangefrom" size="10" maxlength="10" type="text" class="text" style="padding-top: 1px;" value="<?php echo $rangefromdate; ?>">
<input name="rangeto" id="rangeto" size="10" maxlength="10" type="text" class="text" style="padding-top: 1px;" value="<?php echo $rangetodate; ?>">

<!-- Region -->
&nbsp;&nbsp;<?=$LANG['nav_regionfilter']?>&nbsp;
<select name="statregion" class="select">
   <?php
   $regions = $R->getAll();
   $selectedRegion=$statregion;
   foreach ($regions as $reg) 
   { ?>
      <option value="<?=$reg['regionname']?>" <?=(($selectedRegion==$reg['regionname'])?"SELECTED":"")?>><?=$reg['regionname']?></option>
   <?php } ?>
</select>

<!-- Group -->
&nbsp;&nbsp;<?=$LANG['stat_choose_group']?>&nbsp;
<select name="periodgroup" id="periodgroup" class="select">
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

<!-- Absence -->
&nbsp;&nbsp;<?=$LANG['stat_choose_absence']?>&nbsp;
<select name="periodabsence" id="periodabsence" class="select">
   <option class="option" value="All" <?=($periodAbsence=="All"?"selected":"")?>><?=$LANG['drop_group_all']?></option>
   <?php
   $absences = $A->getAll();
   $selectedAbsence=$periodAbsence;
   foreach ($absences as $abs) { ?>
      <option value="<?=$abs['id']?>" <?=(($abs['id']==$selectedAbsence)?'SELECTED':'')?>><?=$abs['name']?></option>
   <?php } ?>
</select>
