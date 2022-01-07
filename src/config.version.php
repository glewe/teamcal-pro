<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * config.version.php
 *
 * Contains the version info
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

/**===========================================================================
 * PRODUCT, AUTHOR, COPYRIGHT, LICENSE INFORMATION
 * Do not change this information. It is protected by the license agreement.
 * To personalize your installation open the TeamCal Pro Configuration in the
 * Tools->Administration menu.
 */
date_default_timezone_set('UTC');

$CONF['app_name'] = "TeamCal Pro";
$CONF['app_version'] = "3.6.020";
$CONF['app_help_root'] = "https://georgelewe.atlassian.net/wiki/display/TCP036/";
$CONF['app_version_date'] = "2016-04-04";
$CONF['app_year'] = "2004";
$CONF['app_curr_year'] = date('Y');
$CONF['app_author'] = "George Lewe";
$CONF['app_author_url'] = "http://www.lewe.com";
$CONF['app_author_email'] = "george@lewe.com";
$CONF['app_copyright'] = "&copy; ".$CONF['app_year']."-".$CONF['app_curr_year']." by <a href=\"mailto:".$CONF['app_author_email']."?subject=".$CONF['app_name']."&nbsp;".$CONF['app_version']."\" class=\"copyright\">".$CONF['app_author']."</a>.";
$CONF['app_copyright_html'] = "(c) ".$CONF['app_year']."-".$CONF['app_curr_year']." by ".$CONF['app_author'].", (".$CONF['app_author_url'].")";
$CONF['app_footer_pwd'] = "Powered by ".$CONF['app_name']." ".$CONF['app_version']." &copy; ".$CONF['app_year']."-".$CONF['app_curr_year']." by <a href=\"http://www.lewe.com\" class=\"copyright\" target=\"_blank\">".$CONF['app_author']."</a>";
$CONF['app_license_html'] =
"This program is open source software; it may be used, redistributed
and/or modified under the terms of the TeamCal Pro license which is
based on the GNU General Public License v3 as published by the Free
Software Foundation (but not identical).
TeamCal Pro license: http://tcpro.lewe.com/doc/license.txt
GPL license:         http://tcpro.lewe.com/doc/gpl.txt

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTIBILITY or FITNESS FOR A PARTICULAR PURPOSE.\n";
?>
