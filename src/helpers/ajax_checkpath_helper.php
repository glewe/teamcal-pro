<?php
/**
 * ajax_checkpath_helper.php
 *
 * Launched via Ajax, this routine checks the accessibility of the TeamCal Pro
 * root directory and URL. Used by the installation page.
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
$db_handle = @ mysql_connect($_REQUEST['reldirserver'], $_REQUEST['user'], $_REQUEST['pass']);

if (!@file_exists($_SERVER['DOCUMENT_ROOT'].$_REQUEST['reldir']."installation.php")) {
   $msg = "<div style=\"font-weight: bold; color: #AA0000;\">The Root Directory is not correct. Can't find file \"".$_SERVER['DOCUMENT_ROOT'].$_REQUEST['reldir']."installation.php\".</div>";
}
else {
   $msg = "<div style=\"font-weight: bold; color: #00AA00;\">The Root Directory seems to be correct.</div>";
}

if (!@fopen($_REQUEST['url']."/installation.php", 'r')) {
   $msg .= "<div style=\"font-weight: bold; color: #AA0000;\">The URL is not correct. Can't find file \"".$_REQUEST['url']."/installation.php\".</div>";
}
else {
   $msg .= "<div style=\"font-weight: bold; color: #00AA00;\">The URL seems to be correct.</div>";
}

echo $msg;
?>
