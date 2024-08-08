<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * config.default.php
 *
 * Contains the default config parameters. Is being read by the installation
 * script and used to create config.tcpro.php.
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

global $CONF;
unset($CONF);

/**===========================================================================
 * PERSONALIZATION
 * You can alter these entries to personalize your TeamCal Pro installation.
 * You might also want to look at the indludes/header.application.inc.php
 * file if you want to display your own header image.
 * Sample:
 * $CONF['app_root'] = $_SERVER['DOCUMENT_ROOT']."/tcpro/";
 * $CONF['app_url'] = "http://www.myserver.com/tcpro";
 */
$CONF['app_root'] = $_SERVER['DOCUMENT_ROOT']."/lewe/tcpro/src/";
$CONF['app_url'] = "http://localhost/lewe/tcpro/app";
$CONF['app_avatar_dir'] = 'img/avatar/';
$CONF['app_icon_dir'] = 'img/icons/';
$CONF['app_homepage_dir'] = 'img/homepage/';

$CONF['html_title'] = 'Lewe TeamCal Pro'; // Default HTML title. Change if u like.

/**===========================================================================
 * DATABASE
 * Currently only db_type=1 (MySQL) is supported.
 */
$CONF['db_type']   = 1;
$CONF['db_persistent'] = false;
$CONF['db_server'] ="localhost";
$CONF['db_name']   ="oncall";
$CONF['db_user']   ="root";
$CONF['db_pass']   ="";
/**
 * Table names
 */
$CONF['db_table_prefix']            = "my_";
$CONF['db_table_absence']           = $CONF['db_table_prefix'].'tc_absences';
$CONF['db_table_absence_group']     = $CONF['db_table_prefix'].'tc_absence_group';
$CONF['db_table_allowance']         = $CONF['db_table_prefix'].'tc_allowances';
$CONF['db_table_announcements']     = $CONF['db_table_prefix'].'tc_announcements';
$CONF['db_table_config']            = $CONF['db_table_prefix'].'tc_config';
$CONF['db_table_daynotes']          = $CONF['db_table_prefix'].'tc_daynotes';
$CONF['db_table_groups']            = $CONF['db_table_prefix'].'tc_groups';
$CONF['db_table_holidays']          = $CONF['db_table_prefix'].'tc_holidays';
$CONF['db_table_log']               = $CONF['db_table_prefix'].'tc_log';
$CONF['db_table_months']            = $CONF['db_table_prefix'].'tc_months';
$CONF['db_table_options']           = $CONF['db_table_prefix'].'tc_options';
$CONF['db_table_permissions']       = $CONF['db_table_prefix'].'tc_permissions';
$CONF['db_table_regions']           = $CONF['db_table_prefix'].'tc_regions';
$CONF['db_table_styles']            = $CONF['db_table_prefix'].'tc_styles';
$CONF['db_table_templates']         = $CONF['db_table_prefix'].'tc_templates';
$CONF['db_table_users']             = $CONF['db_table_prefix'].'tc_users';
$CONF['db_table_user_announcement'] = $CONF['db_table_prefix'].'tc_user_announcement';
$CONF['db_table_user_group']        = $CONF['db_table_prefix'].'tc_user_group';
$CONF['db_table_user_options']      = $CONF['db_table_prefix'].'tc_user_options';

$CONF['db_table_archive_users']             = $CONF['db_table_prefix'].'tc_archive_users';
$CONF['db_table_archive_user_group']        = $CONF['db_table_prefix'].'tc_archive_user_group';
$CONF['db_table_archive_user_options']      = $CONF['db_table_prefix'].'tc_archive_user_options';
$CONF['db_table_archive_templates']         = $CONF['db_table_prefix'].'tc_archive_templates';
$CONF['db_table_archive_daynotes']          = $CONF['db_table_prefix'].'tc_archive_daynotes';
$CONF['db_table_archive_allowance']         = $CONF['db_table_prefix'].'tc_archive_allowances';
$CONF['db_table_archive_user_announcement'] = $CONF['db_table_prefix'].'tc_archive_user_announcement';

/**===========================================================================
 * LDAP AUTHENTICATION
*
* PHP Requirements
* You will need to get and compile LDAP client libraries from either
* OpenLDAP or Bind9.net in order to compile PHP with LDAP support.
*
* PHP Installation
* LDAP support in PHP is not enabled by default. You will need to use the
* --with-ldap[=DIR] configuration option when compiling PHP to enable LDAP
* support. DIR is the LDAP base install directory. To enable SASL support,
* be sure --with-ldap-sasl[=DIR] is used, and that sasl.h exists on the system.
*/
$CONF['LDAP_YES']   = 0;                   // Use LDAP authentication
$CONF['LDAP_ADS']   = 0;                   // Set to 1 when authenticating against an Active Directory
$CONF['LDAP_HOST']  = "ldap.mydomain.com"; // LDAP host name
$CONF['LDAP_PORT']  = "389";               // LDAP port
$CONF['LDAP_PASS']  = 'XXXXXXXX';          // SA associated password
$CONF['LDAP_DIT']   = "cn=<service account>,ou=fantastic_four,ou=superheroes,dc=marvel,dc=comics"; // Directory Information Tree (Relative Distinguished Name)
$CONF['LDAP_SBASE'] = "ou=superheroes,ou=characters,dc=marvel,dc=comics"; // Search base, location in the LDAP dirctory to search
$CONF['LDAP_TLS']   = 0; // To avoid "Undefined index: LDAP_TLS" error message for LDAP bind to Active Directory


/**===========================================================================
 * ATTENTION!
 * Don't change anything below this line unless you know what you're doing!
 */

/**
 * USER
 */
$CONF['UTVIEWER']          = 0x00;     // Flag: User Type: None (just Viewer)
$CONF['UTUSER']            = 0x01;     // Flag: User Type: User
$CONF['UTMANAGER']         = 0x02;     // Flag: User Type: Group Manager
$CONF['UTADMIN']           = 0x04;     // Flag: User Type: Administrator
$CONF['UTMALE']            = 0x08;     // Flag: User Type: Male (if not set=female)
$CONF['UTDIRECTOR']        = 0x10;     // Flag: User Type: Director
$CONF['UTTEMPLATE']        = 0x20;     // Flag: User Type: Template (absences copied to all other users of the same group)
$CONF['UTASSISTANT']       = 0x40;     // Flag: User Type: Assistant

$CONF['USLOCKED']          = 0x01;     // Flag: User Status: Account locked
$CONF['USCHGPWD']          = 0x02;     // Flag: User Status: Must change password (not used)
$CONF['USLOGLOC']          = 0x04;     // Flag: User Status: Login locked for grace period
$CONF['USHIDDEN']          = 0x08;     // Flag: User Status: User will not be displayed in the calendar

/**
 * HOLIDAY OPTIONS
 */
$CONF['H_BUSINESSDAY']     = 0x000001; // Flag: Holiday counts as business day

/**
 * GROUP OPTIONS
 */
$CONF['G_HIDE']            = 0x000001; // Flag: Hide group from calendar
$CONF['G_MIN_PRESENT']     = 0x000002; // Flag: Check for minimum members present
$CONF['G_MAX_ABSENT']      = 0x000004; // Flag: Check for maximum members absent

/**
 * REGION OPTIONS
 */
$CONF['R_HIDE']            = 0x000001; // Flag: Hide region

/**
 * DECLINATION
 */
$CONF['DECL_OFF']          = 0x000000; // Flag: Decline off
$CONF['DECL_ON']           = 0x000001; // Flag: Decline on
$CONF['DECL_BASE_ALL']     = 0x000002; // Flag: Threshold base 'All'
$CONF['DECL_BASE_GROUP']   = 0x000004; // Flag: Threshold base 'Group'
$CONF['DECL_NOTIFY_USER']  = 0x000008; // Flag: Notify User
$CONF['DECL_NOTIFY_MANGR'] = 0x000010; // Flag: Notify Group Manager
$CONF['DECL_NOTIFY_DIREC'] = 0x000020; // Flag: Notify Director(s)
$CONF['DECL_NOTIFY_ADMIN'] = 0x000040; // Flag: Notify Administrator
$CONF['DECL_BEFORE_ON']    = 0x000080; // Flag: Block before date flag

/**
 * NOTIFICATION E-MAIL
 */
$CONF['userchg']           = 0x01;     // Flag: Notification mail on user changes
$CONF['groupchg']          = 0x02;     // Flag: Notification mail on group changes
$CONF['monthchg']          = 0x04;     // Flag: Notification mail on month changes
$CONF['usercalchg']        = 0x08;     // Flag: Notification mail on user calendar changes
$CONF['absencechg']        = 0x10;     // Flag: Notification mail on absence changes
$CONF['holidaychg']        = 0x20;     // Flag: Notification mail on holiday changes

/**
 * ENCRYPTION
   Salt is a string that is used encrypt the passwords. You can change salt
 * to any other 9 char string
 */
$CONF['salt'] ='s7*9fgJ#R';

/**
 * These english monthnames are used regardless of chosen language to compute
 * dates and times.
 */
$CONF['monthnames'] = array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
$CONF['weekdays']   = array(1=>"Mo","Tu","We","Th","Fr","Sa","Su");

require ("config.version.php");
?>
