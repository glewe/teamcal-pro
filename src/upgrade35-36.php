<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--
===============================================================================
TEAMCAL PRO
___________________________________________________________________________

Application: TeamCal Pro 3.6.000
Date:        2013-03-20
Author:      George Lewe
Copyright:   (c) 2004-2013 by George Lewe, (http://www.lewe.com)
             All rights reserved.
___________________________________________________________________________

This program is open source software; it may be used, redistributed
and/or modified under the terms of the TeamCal Pro license which is
based on the GNU General Public License as published by the Free
Software Foundation (but not identical).
TeamCal Pro license: http://www.lewe.com/tcpro/license.txt
GPL license:         http://www.lewe.com/tcpro/gpl.txt

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTIBILITY or FITNESS FOR A PARTICULAR PURPOSE.

===============================================================================
-->
<html>
   <head>
      <title>Lewe TeamCal Pro</title>
      <meta http-equiv="Pragma" content="no-cache">
      <meta http-equiv="Content-type" content="text/html;charset=iso-8859-1">
      <meta http-equiv="Content-Style-Type" content="text/css">
      <meta name="copyright" content="(c) 2004-2013 by George Lewe, (http://www.lewe.com)">
      <style type="text/css" media="screen">
         body { background-color: #767676; color: #FFFFFF; font-family: tahoma, arial, helvetica, sans-serif; font-size: 14px; padding: 0px; margin: 0px; }
         p { font-family: tahoma, arial, helvetica, sans-serif; text-align: left; text-decoration: none; }
         ul { font-family: tahoma, arial, helvetica, sans-serif; text-align: left; text-decoration: none; }
         hr { color: #555555; }
      </style>
   </head>
   <body>
   <div style="border: 1px solid #000000; background-color: #FFB300; padding: 4px; font-weight: bold;">TeamCal Pro Update Helper 3.5 =&gt; 3.6</div>
<?php
define( '_VALID_TCPRO', 1 );
require_once ("models/db_model.php");
require ("config.tcpro.php");

$DB = new Db_model;
$abstable_old = $CONF['db_table_prefix']."tc_absence";
$abstable_new = $CONF['db_table_prefix']."tc_absences";
$absgrptable_old = $CONF['db_table_prefix']."tc_absence_group";
$absgrptable_new = $CONF['db_table_prefix']."tc_absence_group";
$alltable_old = $CONF['db_table_prefix']."tc_allowance";
$alltable_new = $CONF['db_table_prefix']."tc_allowances";
$tpltable_old = $CONF['db_table_prefix']."tc_templates";
$tpltable_new = $CONF['db_table_prefix']."tc_templates";

$update_absences = TRUE;
$update_absence_group = TRUE;
$update_allowances = TRUE;
$update_templates = TRUE;

$query = "SHOW TABLES LIKE '".$abstable_old."_old'";
$result = $DB->db_query($query);
if ($DB->db_numrows($result)) {
   print "<strong>Old absence table exists. Not doing anything. Recover your old databse before running again.</li>";
   die();
}

/**
 * ABSENCE TYPE OPTIONS
 */
$CONF['A_SHOWREMAIN']      = 0x000001;     // Flag: Include absence type in remainder
$CONF['A_APPROVAL']        = 0x000002;     // Flag: Approval needed
$CONF['A_SHOWTOTAL']       = 0x000004;     // Flag: Include absence total in remainder section
$CONF['A_PRESENCE']        = 0x000008;     // Flag: This absence type counts as 'present'
$CONF['A_MGR_ONLY']        = 0x000010;     // Flag: This absence type is for managers only
$CONF['A_HIDE_IN_PROFILE'] = 0x000020;     // Flag: Hide this absence type in regular user profiles (Absences tab)
$CONF['A_CONFIDENTIAL']    = 0x000040;     // Flag: Cannot be used or seen by regular users

//
// ABSENCE TABLE
//
if ($update_absences) {
   print "<strong>ABSENCE TABLE</strong></br>";
   print "<ul>";
   print "<li>Renaming old table...</li>";
   $query="RENAME TABLE `".$abstable_old."` TO `".$abstable_old."_old`;";
   $result = $DB->db_query($query);
   print "<li>Creating new table...</li>";
   $query="
   CREATE TABLE IF NOT EXISTS `".$abstable_new."` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(80) NOT NULL,
   `symbol` char(1) NOT NULL DEFAULT 'A',
   `icon` varchar(80) NOT NULL,
   `color` varchar(6) NOT NULL,
   `bgcolor` varchar(6) NOT NULL,
   `factor` float NOT NULL,
   `allowance` float NOT NULL,
   `show_in_remainder` tinyint(1) NOT NULL,
   `show_totals` tinyint(1) NOT NULL,
   `approval_required` tinyint(1) NOT NULL,
   `counts_as_present` tinyint(1) NOT NULL,
   `manager_only` tinyint(1) NOT NULL,
   `hide_in_profile` tinyint(1) NOT NULL,
   `confidential` tinyint(1) NOT NULL,
   PRIMARY KEY (`id`)
   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
   $result = $DB->db_query($query);
   
   print "<li>Migrating old absences...</li>";
   print "<li style=\"list-style-type:none;\"><ul>";
   $query = "SHOW TABLES LIKE '".$abstable_new."'";
   $result = $DB->db_query($query);
   if (!$DB->db_numrows($result)) {
      print "<li>New table already exists. Truncating it...</li>";
      $query = "TRUNCATE '".$abstable_new."'";
      $result = $DB->db_query($query);
   }
   $query = "SELECT * FROM `".$abstable_old."_old`;";
   $result = $DB->db_query($query);
   $absarray = array();
   while ($row=$DB->db_fetch_array($result)) {
      if ($row['cfgname']!="present") {
         $name = $row['dspname'];
         $symbol = $row['cfgsym'];
         if (strlen($row['iconfile'])) $icon=$row['iconfile']; else $icon = "No";
         $color = $row['dspcolor'];
         $bgcolor = $row['dspbgcolor'];
         $factor = $row['factor'];
         $allowance = $row['allowance'];
         if ($row['options'] & intval($CONF['A_SHOWREMAIN'])) $show_in_remainder = 1; else $show_in_remainder = 0;
         if ($row['options'] & intval($CONF['A_SHOWTOTAL'])) $show_totals = 1; else $show_totals = 0;
         if ($row['options'] & intval($CONF['A_APPROVAL'])) $approval_required = 1; else $approval_required = 0;
         if ($row['options'] & intval($CONF['A_PRESENCE'])) $counts_as_present = 1; else $counts_as_present = 0;
         if ($row['options'] & intval($CONF['A_MGR_ONLY'])) $manager_only = 1; else $manager_only = 0;
         if ($row['options'] & intval($CONF['A_HIDE_IN_PROFILE'])) $hide_in_profile = 1; else $hide_in_profile = 0;
         if ($row['options'] & intval($CONF['A_CONFIDENTIAL'])) $confidential = 1; else $confidential = 0;
   
         $query = "INSERT INTO `".$abstable_new."` ";
         $query .= "(
                     `name`,
                     `symbol`,
                     `icon`,
                     `color`,
                     `bgcolor`,
                     `factor`,
                     `allowance`,
                     `show_in_remainder`,
                     `show_totals`,
                     `approval_required`,
                     `counts_as_present`,
                     `manager_only`,
                     `hide_in_profile`,
                     `confidential`
                    ) ";
          
         $query .= "VALUES (
                      '".$name."',
                      '".$symbol."',
                      '".$icon."',
                      '".$color."',
                      '".$bgcolor."',
                      '".$factor."',
                      '".$allowance."',
                      '".$show_in_remainder."',
                      '".$show_totals."',
                      '".$approval_required."',
                      '".$counts_as_present."',
                      '".$manager_only."',
                      '".$hide_in_profile."',
                      '".$confidential."'
                      )";
         $result2 = $DB->db_query($query);
   
         $result3 = mysql_query('SHOW TABLE STATUS LIKE "'.$abstable_new.'";');
         $row3 = mysql_fetch_assoc($result3);
   
         $absarray[$row['cfgsym']] = intval($row3['Auto_increment'])-1;
         print "<li>Migrated old absence '".$row['dspname']." (".$row['cfgsym'].")' into new absence with ID=".(intval($row3['Auto_increment'])-1)."</li>";
      }
   }
   print "</ul></li>";
   print "</ul>...done.";
   print "<hr>";
}
   
//
// ABSENCE-GROUP TABLE
//
if ($update_absence_group) {
   print "<strong>ABSENCE-GROUP TABLE</strong></br>";
   print "<ul>";
   print "<li>Renaming old table...</li>";
   $query="RENAME TABLE `".$absgrptable_old."` TO `".$absgrptable_old."_old`;";
   $result = $DB->db_query($query);
   print "<li>Creating new table...</li>";
   $query="
   CREATE TABLE IF NOT EXISTS `".$absgrptable_new."` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `absid` int(11) DEFAULT NULL,
     `group` varchar(40) DEFAULT NULL,
     PRIMARY KEY (`id`)
   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
   $result = $DB->db_query($query);
   
   print "<li>Migrating old absence-group entries...</li>";
   print "<li style=\"list-style-type:none;\"><ul>";
   $query = "SHOW TABLES LIKE '".$absgrptable_new."'";
   $result = $DB->db_query($query);
   if (!$DB->db_numrows($result)) {
      print "<li>New table already exists. Truncating it...</li>";
      $query = "TRUNCATE '".$absgrptable_new."'";
      $result = $DB->db_query($query);
   }
   $query = "SELECT * FROM `".$absgrptable_old."_old`;";
   $result = $DB->db_query($query);
   while ($row=$DB->db_fetch_array($result)) {
      $absid = $absarray[$row['absence']];
      $group = $row['group'];
      $query2 = "INSERT INTO `".$absgrptable_new."` (`absid`,`group`) VALUES ('".$absid."','".$group."')";
      $result2 = $DB->db_query($query2);
      print "<li>Migrated old absence-group entry '".$row['absence']."=>".$row['group']."' into '".$absid."=>".$group."'</li>";
   }
   print "</ul></li>";

   print "</ul>...done.";
   print "<hr>";
}
   
//
// ALLOWANCE TABLE
//
if ($update_allowances) {
   print "<strong>ALLOWANCE TABLE</strong></br>";
   print "<ul>";
   print "<li>Renaming old table...</li>";
   $query="RENAME TABLE `".$alltable_old."` TO `".$alltable_old."_old`;";
   $result = $DB->db_query($query);
   print "<li>Creating new table...</li>";
   $query="
   CREATE TABLE IF NOT EXISTS `".$alltable_new."` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `username` varchar(40) NOT NULL,
     `absid` int(11) NOT NULL,
     `lastyear` smallint(6) DEFAULT '0',
     `curryear` smallint(6) DEFAULT '0',
     PRIMARY KEY (`id`)
   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
   $result = $DB->db_query($query);
   
   print "<li>Migrating old allowances...</li>";
   print "<li style=\"list-style-type:none;\"><ul>";
   $query = "SHOW TABLES LIKE '".$alltable_new."'";
   $result = $DB->db_query($query);
   if (!$DB->db_numrows($result)) {
      print "<li>New table already exists. Truncating it...</li>";
      $query = "TRUNCATE '".$alltable_new."'";
      $result = $DB->db_query($query);
   }
   $query = "SELECT * FROM `".$alltable_old."_old`;";
   $result = $DB->db_query($query);
   while ($row=$DB->db_fetch_array($result)) {
      $username = $row['username'];
      $absid = $absarray[$row['abssym']];
      $lastyear = $row['lastyear'];
      $curryear = $row['curryear'];
      $query2 = "INSERT INTO `".$alltable_new."` (`username`,`absid`,`lastyear`,`curryear`) VALUES ('".$username."','".$absid."','".$lastyear."','".$curryear."')";
      $result2 = $DB->db_query($query2);
      print "<li>Migrated old allowance entry '".$row['username']."|".$row['abssym']."|".$row['lastyear']."|".$row['curryear']."' into '".$username."|".$absid."|".$lastyear."|".$curryear."'</li>";
   }
   print "</ul></li>";

   print "</ul>";
   print "...done.<hr>";
}

//
// TEMPLATE TABLE
//
if ($update_templates) {
   print "<strong>TEMPLATES TABLE</strong></br>";
   print "<ul>";
   print "<li>Renaming old table...</li>";
   $query="RENAME TABLE `".$tpltable_old."` TO `".$tpltable_old."_old`;";
   $result = $DB->db_query($query);
   print "<li>Creating new table...</li>";
   $query="
   CREATE TABLE IF NOT EXISTS `".$tpltable_new."` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `username` varchar(40) DEFAULT NULL,
     `year` varchar(4) DEFAULT NULL,
     `month` char(2) DEFAULT NULL,
     `abs1` int(11) DEFAULT NULL,
     `abs2` int(11) DEFAULT NULL,
     `abs3` int(11) DEFAULT NULL,
     `abs4` int(11) DEFAULT NULL,
     `abs5` int(11) DEFAULT NULL,
     `abs6` int(11) DEFAULT NULL,
     `abs7` int(11) DEFAULT NULL,
     `abs8` int(11) DEFAULT NULL,
     `abs9` int(11) DEFAULT NULL,
     `abs10` int(11) DEFAULT NULL,
     `abs11` int(11) DEFAULT NULL,
     `abs12` int(11) DEFAULT NULL,
     `abs13` int(11) DEFAULT NULL,
     `abs14` int(11) DEFAULT NULL,
     `abs15` int(11) DEFAULT NULL,
     `abs16` int(11) DEFAULT NULL,
     `abs17` int(11) DEFAULT NULL,
     `abs18` int(11) DEFAULT NULL,
     `abs19` int(11) DEFAULT NULL,
     `abs20` int(11) DEFAULT NULL,
     `abs21` int(11) DEFAULT NULL,
     `abs22` int(11) DEFAULT NULL,
     `abs23` int(11) DEFAULT NULL,
     `abs24` int(11) DEFAULT NULL,
     `abs25` int(11) DEFAULT NULL,
     `abs26` int(11) DEFAULT NULL,
     `abs27` int(11) DEFAULT NULL,
     `abs28` int(11) DEFAULT NULL,
     `abs29` int(11) DEFAULT NULL,
     `abs30` int(11) DEFAULT NULL,
     `abs31` int(11) DEFAULT NULL,
     PRIMARY KEY (`id`),
     KEY `username` (`username`)
   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
   $result = $DB->db_query($query);
   
   print "<li>Migrating old templates...</li>";
   print "<li style=\"list-style-type:none;\"><ul>";
   $query = "SHOW TABLES LIKE '".$tpltable_new."'";
   $result = $DB->db_query($query);
   if (!$DB->db_numrows($result)) {
      print "<li>New table already exists. Truncating it...</li>";
      $query = "TRUNCATE '".$tpltable_new."'";
      $result = $DB->db_query($query);
   }
   $query = "SELECT * FROM `".$tpltable_old."_old`;";
   $result = $DB->db_query($query);
   while ($row=$DB->db_fetch_array($result)) {
      $username = $row['username'];
      $year = $row['year'];
      $month = $row['month'];
      for ($i=1; $i<=31; $i++) {
         ${'abs'.$i}=0;
      }
      for ($i=1; $i<=strlen($row['template']); $i++) {
         if ($row['template'][$i-1]!='.') ${'abs'.$i}=$absarray[$row['template'][$i-1]];
      }
      $query2 = "INSERT INTO `".$tpltable_new."` (`username`,`year`,`month`,`abs1`,`abs2`,`abs3`,`abs4`,`abs5`,`abs6`,`abs7`,`abs8`,`abs9`,`abs10`,`abs11`,`abs12`,`abs13`,`abs14`,`abs15`,`abs16`,`abs17`,`abs18`,`abs19`,`abs20`,`abs21`,`abs22`,`abs23`,`abs24`,`abs25`,`abs26`,`abs27`,`abs28`,`abs29`,`abs30`,`abs31`) ";
      $query2 .= "VALUES ('";
      $query2 .= $username . "','";
      $query2 .= $year . "','";
      $query2 .= $month . "','";
      for ($i=1; $i<=31; $i++) {
         $query2 .= ${'abs'.$i}."','";
      }
      $query2 = substr($query2,0,-2);
      $query2 .= ")";
      $result2 = $DB->db_query($query2);
      $output="<li>Migrated old template for '".$row['username']."|".$row['year']."|".$row['month']."|".$row['template']."' ===>  ".$username."|".$year."|".$month."||";
      for ($i=1; $i<=31; $i++) {
         $output .= ${'abs'.$i}."|";
      }
      $output .= "</li>";
      print $output;
   }
   print "</ul></li>";

   print "</ul>";
   print "...done.<hr>";
}
print "<strong>ALL DONE</strong><hr>";
?>
   </body>
</html>
