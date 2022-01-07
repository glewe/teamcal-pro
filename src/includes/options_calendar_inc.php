<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_calendar_inc.php
 *
 * Displays the options bar for the calendar page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

if ($C->readConfig("userSearch")) 
{ ?>
   &nbsp;&nbsp;<?=$LANG['cal_user_search']?>&nbsp;
   <input name="txt_calSearchUser" id="txt_calSearchUser" size="30" type="text" class="text" value="<?=$calSearchUser?>">
   <input name="btn_usrSearch" type="submit" class="button" value="<?=$LANG['btn_search']?>">
<?php }

if ($C->readConfig("showGroup")) 
{
   $selectedGroup=$CONF['options']['groupfilter']; ?>
   <!-- Group filter drop down -->
   &nbsp;&nbsp;<?=$LANG['nav_groupfilter']?>&nbsp;
   <select id="groupfilter" name="groupfilter" class="select">
      <option value="All" <?=($selectedGroup=="All"?"SELECTED":"")?>><?=$LANG['drop_group_all']?></option>
      <option value="Allbygroup" <?=($selectedGroup=="Allbygroup"?"SELECTED":"")?>><?=$LANG['drop_group_allbygroup']?></option>
      <?php
      $G = new Group_model;
      $groups=$G->getAll(TRUE); // TRUE = exclude hidden
      foreach( $groups as $group ) 
      {
         if (!isAllowed("viewAllGroups")) 
         {
            if ($UG->isMemberOfGroup($user,$group['groupname']) OR $UG->isGroupManagerOfGroup($user,$group['groupname'])) 
            { ?>
               <option value="<?=$group['groupname']?>" <?=(($selectedGroup==$group['groupname'])?'SELECTED':'')?>><?=$group['groupname']?></option>
            <?php }
         }
         else 
         {
            if ($UO->true($user,"owngroupsonly") AND $UG->isMemberOfGroup($user,$group['groupname'])) 
            { ?>
               <option value="<?=$group['groupname']?>" <?=(($selectedGroup==$group['groupname'])?'SELECTED':'')?>><?=$group['groupname']?></option>
            <?php } 
            else 
            { ?> 
               <option value="<?=$group['groupname']?>" <?=(($selectedGroup==$group['groupname'])?'SELECTED':'')?>><?=$group['groupname']?></option>
            <?php } 
         }
      }
      ?>
   </select>
<?php } ?>
   
<?php if ($C->readConfig("showRegion")) 
{
   $selectedRegion=$CONF['options']['region']; ?>
   <!-- Region drop down -->
   &nbsp;&nbsp;<?=$LANG['nav_regionfilter']?>&nbsp;
   <select name="regionfilter" class="select">
      <?php
      $R = new Region_model;
      $regions = $R->getAll();
      foreach ($regions as $reg) 
      { ?>
         <option value="<?=$reg['regionname']?>" <?=(($selectedRegion==$reg['regionname'])?"SELECTED":"")?>><?=$reg['regionname']?></option>
      <?php } ?>
   </select>
<?php } ?>
   
   
<?php if ($C->readConfig("showToday")) 
{
   $selectedAbsence=$CONF['options']['absencefilter']; ?>
   <!-- Absence filter drop down -->
   &nbsp;&nbsp;<?=$LANG['nav_absencefilter']?>&nbsp;
   <select id="absencefilter" name="absencefilter" class="select">
      <option value="All" <?=($selectedAbsence=="All"?"SELECTED":"")?>><?=$LANG['drop_group_all']?></option>
      <?php
      $A = new Absence_model;
      $absences = $A->getAll();
      foreach ($absences as $abs) 
      { ?>
         <option value="<?=$abs['id']?>" <?=(($selectedAbsence==$abs['id'])?' SELECTED':'')?>><?=$abs['name']?></option>
      <?php } ?>
   </select>
<?php } ?>

<?php if ($C->readConfig("showStart")) 
{
   $selectedMonth=$CONF['options']['month_id']; 
   $selectedYear=$CONF['options']['year_id']; 
   $selectedAmount=$CONF['options']['show_id'];
    
   $tz = $C->readConfig("timeZone");
   if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
   else date_default_timezone_set ($tz);
   $today = getdate();
   $curryear = $today['year'];
   ?>
   &nbsp;&nbsp;<?=$LANG['nav_start_with']?>&nbsp;
   <!-- Month drop down -->
   <select id="month_id" name="month_id" class="select">
      <option value="1" <?=$selectedMonth == "1"?' SELECTED':''?> ><?=$LANG['monthnames'][1]?></option>
      <option value="2" <?=$selectedMonth == "2"?' SELECTED':''?> ><?=$LANG['monthnames'][2]?></option>
      <option value="3" <?=$selectedMonth == "3"?' SELECTED':''?> ><?=$LANG['monthnames'][3]?></option>
      <option value="4" <?=$selectedMonth == "4"?' SELECTED':''?> ><?=$LANG['monthnames'][4]?></option>
      <option value="5" <?=$selectedMonth == "5"?' SELECTED':''?> ><?=$LANG['monthnames'][5]?></option>
      <option value="6" <?=$selectedMonth == "6"?' SELECTED':''?> ><?=$LANG['monthnames'][6]?></option>
      <option value="7" <?=$selectedMonth == "7"?' SELECTED':''?> ><?=$LANG['monthnames'][7]?></option>
      <option value="8" <?=$selectedMonth == "8"?' SELECTED':''?> ><?=$LANG['monthnames'][8]?></option>
      <option value="9" <?=$selectedMonth == "9"?' SELECTED':''?> ><?=$LANG['monthnames'][9]?></option>
      <option value="10" <?=$selectedMonth == "10"?' SELECTED':''?> ><?=$LANG['monthnames'][10]?></option>
      <option value="11" <?=$selectedMonth == "11"?' SELECTED':''?> ><?=$LANG['monthnames'][11]?></option>
      <option value="12" <?=$selectedMonth == "12"?' SELECTED':''?> ><?=$LANG['monthnames'][12]?></option>
   </select>

   <!-- Year drop down -->
   <select id="year_id" name="year_id" class="select">
      <option value="<?=$curryear-1?>" <?=$selectedYear==$curryear-1?' SELECTED':''?> ><?=$curryear-1?></option>
      <option value="<?=$curryear?>" <?=$selectedYear==$curryear?' SELECTED':''?> ><?=$curryear?></option>
      <option value="<?=$curryear+1?>" <?=$selectedYear==$curryear+1?' SELECTED':''?> ><?=$curryear+1?></option>
      <option value="<?=$curryear+2?>" <?=$selectedYear==$curryear+2?' SELECTED':''?> ><?=$curryear+2?></option>
   </select>

   <!-- Amount of months to show drop down -->
   <select id="show_id" name="show_id" class="select">
      <option value="1" <?=$selectedAmount=="1"?' SELECTED':''?>><?=$LANG['drop_show_1_months']?></option>
      <option value="2" <?=$selectedAmount=="2"?' SELECTED':''?>><?=$LANG['drop_show_2_months']?></option>
      <option value="3" <?=$selectedAmount=="3"?' SELECTED':''?>><?=$LANG['drop_show_3_months']?></option>
      <option value="6" <?=$selectedAmount=="6"?' SELECTED':''?>><?=$LANG['drop_show_6_months']?></option>
      <option value="12" <?=$selectedAmount=="12"?' SELECTED':''?>><?=$LANG['drop_show_12_months']?></option>
   </select>
   
   <!-- Help -->
   <input name="btn_calHelp" type="button" class="button" onclick="javascript:window.open('<?=$help?>').void();" value="<?=$LANG['btn_help']?>">
   
<?php } ?>
