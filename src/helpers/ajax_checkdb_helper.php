<?php
/**
 * ajax_checkdb_helper.php
 *
 * Launched via Ajax, this routine checks the accessibility of a MySQL database.
 * Used by the installation page.
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

header("Cache-Control: no-cache");
header("Pragma: no-cache");
$db_handle = @ mysql_connect($_REQUEST['server'], $_REQUEST['user'], $_REQUEST['pass']);

if (!$db_handle) {
   $msg = "<div style=\"font-weight: bold; color: #AA0000;\">Connect to mySQL server failed. ".$_REQUEST['server']."|".$_REQUEST['user']."|".$_REQUEST['pass']."</div>";
}
else {
   if (!@ mysql_select_db($_REQUEST['db'], $db_handle)) {
      $msg = "<div style=\"font-weight: bold; color: #AA0000;\">Connect to mySQL server successful but database not found.</div>";
   }
   else {
      $msg = "<div style=\"font-weight: bold; color: #00AA00;\">Connect to mySQL server and database successful.</div>";
      if ($result = mysql_query("SELECT * FROM ".$_REQUEST['prefix']."tc_users WHERE `username`=\"admin\";", $db_handle)) {
         $msg .= "<div style=\"font-weight: bold; color: #AA0000;\">A TeamCal Pro database with that prefix seems to already exist. It will be overwritten.</div>";
      }
   }
}

echo $msg;
?>
