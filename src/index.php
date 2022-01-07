<?php
/**
 * index.php
 *
 * Initial TeamCal Pro page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

/**
 * Set parent flag to control access to child scripts
 */
define( '_VALID_TCPRO', 1 );

/**
 * Load the config and helpers
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");

/**
 * Load models that we need here
 */
require_once ("models/announcement_model.php" );
require_once ("models/config_model.php");
require_once ("models/login_model.php");
require_once ("models/log_model.php");
require_once ("models/user_model.php");
require_once ("models/user_announcement_model.php" );

/**
 * Create model instances
 */
$AN  = new Announcement_model;
$C   = new Config_model;
$L   = new Login_model;
$LOG = new Log_model;
$U   = new User_model;
$UA  = new User_announcement_model;

/**
 * Get the URL action request
 */
$display = $C->readConfig("homepage");
if (isset ($_REQUEST['action'])) 
{
   switch ($_REQUEST['action']) 
   {
      case 'welcome' :
         $display = "welcome";
         break;

      case 'calendar' :
         $display = "calendar";
         break;

      case 'logout' :
         $L->logout();
         $LOG->log("logLogin", $L->checkLogin(), "log_logout");
         header("Location: ".$_SERVER['PHP_SELF']);
         die();
         break;

      default:
         $display = $C->readConfig("homepage");
         break;
   }
}

/**
 * If someone is logged in and there is a popup announcement for him then
 * this overrules the content request.
 */
if ($luser=$L->checkLogin()) 
{
   $uas=$UA->getAllForUser($luser);
   $foundpopup=false;
   
   foreach($uas as $ua) 
   {
      $AN->read($ua['ats']);
      if ($AN->popup) 
      {
         $foundpopup=true;
         break;
      }
   }
    
   if ($foundpopup) 
   {
      /**
       * Found popup announcements. Show announcement page if not more than 20
       * seconds have passed since login. Otherwise, if the user does not 
       * remove his announcement, the popup would be shown everytime the 
       * calendar or homepage is displayed.
       */
      $U->findByName($luser);
      $nowstamp = date("YmdHis");
      $userstamp=$U->last_login;
      $userstamp=str_replace("-",'',$userstamp);
      $userstamp=str_replace(" ",'',$userstamp);
      $userstamp=str_replace(":",'',$userstamp);
      
      if ( (floatval($nowstamp)-20) < floatval($userstamp) AND isAllowed("viewAnnouncements") ) 
      {
         header("Location: announcement.php?uaname=".$luser);
         die();
      }
   }
}

/**
 * Show content
 */
if ( $display=="calendar" AND isAllowed("viewCalendar")) 
{
   header("Location: calendar.php");
   die();
}
else 
{
   $CONF['html_title'] = $LANG['html_title_homepage'];
   require("includes/header_html_inc.php");
   require("includes/header_app_inc.php");
   require("includes/menu_inc.php");
   include("includes/homepage_inc.php");
   require("includes/footer_inc.php");
}
?>
