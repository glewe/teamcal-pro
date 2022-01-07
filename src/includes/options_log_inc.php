<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_log_inc.php
 *
 * Displays the options bar for the log page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: ".$periodType."\");</script>";

?>
<!-- Standard Period -->
&nbsp;&nbsp;<input name="optPeriod" type="radio" class="radio" style="vertical-align: middle;" value="standard" <?=(($C->readConfig("logoption")=='standard')?'CHECKED':'')?>><?=$LANG['stat_choose_period']?>&nbsp;
<select name="period" id="period" class="select">
   <option class="option" value="curr_month" <?=(($C->readConfig("logperiod")=="curr_month")?'selected':'')?>><?=$LANG['stat_period_month']?></option>
   <option class="option" value="curr_quarter" <?=(($C->readConfig("logperiod")=="curr_quarter")?'selected':'')?>><?=$LANG['stat_period_quarter']?></option>
   <option class="option" value="curr_half" <?=(($C->readConfig("logperiod")=="curr_half")?'selected':'')?>><?=$LANG['stat_period_half']?></option>
   <option class="option" value="curr_year" <?=(($C->readConfig("logperiod")=="curr_year")?'selected':'')?>><?=$LANG['stat_period_year']?></option>
   <option class="option" value="curr_all" <?=(($C->readConfig("logperiod")=="curr_all")?'selected':'')?>><?=$LANG['ea_groups_all']?></option>
</select>

<!-- Custom Period -->
&nbsp;&nbsp;<input name="optPeriod" type="radio" class="radio" style="vertical-align: middle;" value="custom" <?=(($C->readConfig("logoption")=='custom')?'CHECKED':'')?>><?=$LANG['stat_choose_custom_period']?>&nbsp;
<script type="text/javascript">
   $(function() { 
      $( "#rangefrom" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" });
      $( "#rangeto" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd" }); 
   });
</script>
<input name="rangefrom" id="rangefrom" size="10" maxlength="10" type="text" class="text" style="padding-top: 1px;" value="<?=$C->readConfig("logfrom")?>">
<input name="rangeto" id="rangeto" size="10" maxlength="10" type="text" class="text" style="padding-top: 1px;" value="<?=$C->readConfig("logto")?>">
