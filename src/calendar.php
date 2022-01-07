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

//echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * Set parent flag to control access to child scripts
 */
define( '_VALID_TCPRO', 1 );

/**
 * Load config and helpers
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
require_once ("helpers/showmonth_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");

/**
 * Load models that we need here
 */
require_once ("models/absence_model.php" );
require_once ("models/announcement_model.php" );
require_once ("models/config_model.php");
require_once ("models/group_model.php");
require_once ("models/login_model.php");
require_once ("models/log_model.php");
require_once ("models/region_model.php");
require_once ("models/template_model.php");
require_once ("models/user_model.php");
require_once ("models/user_announcement_model.php" );
require_once ("models/user_group_model.php" );
require_once ("models/user_option_model.php");

/**
 * Create model instances
 */
$A   = new Absence_model;
$AN  = new Announcement_model;
$C   = new Config_model;
$G   = new Group_model;
$L   = new Login_model;
$LOG = new Log_model;
$R   = new Region_model;
$T   = new Template_model;
$U   = new User_model;
$UA  = new User_announcement_model;
$UG  = new User_group_model;
$UO  = new User_option_model;

/**
 * Show error if not allowed
 */
if (!isAllowed("viewCalendar")) showError("notallowed"); 

/**
 * Get user sort direction
 */
$sort="ASC";
if (isset($_REQUEST['sort']) AND strtoupper($_REQUEST['sort'])=="DESC") {
   $sort="DESC"; 
}

/**
 * Get paging info
 */
$page = 1;
if ( isset($_REQUEST['page']) AND is_numeric($_REQUEST['page'])) {
   $page=intval($_REQUEST['page']);
}

/**
 * Date info
 */
$monthnames = $CONF['monthnames'];
$groupfilter = $CONF['options']['groupfilter'];
$month_id = intval($CONF['options']['month_id']);
$year_id = intval($CONF['options']['year_id']);
$show_id = intval($CONF['options']['show_id']);
$region = $CONF['options']['region'];
$calSearchUser = "%";

// ============================================================================
/*
 * Process Fast Edit form if submitted
 */
if ( isset($_POST['btn_fastedit_apply']) ) {
   /**
    * Loop thru each listbox
    */
   foreach($_POST as $key=>$value) {
      
      if (substr($key,0,8)== "sel_abs_" ) {
         /*
          * Explode the key
          */
         $pieces = explode('_', $key);
         $feuser = $pieces[2];
         $feyear = $pieces[3];
         $femonth = $pieces[4];
         $feday = $pieces[5];
         /*
          * Check whether the listbox was changed by comapring its value to the
          * hidden field that holds the original value. Its name has the same
          * suffix as the listbox's name
          */
         $hidkey='hid_abs_'.$feuser.'_'.$feyear.'_'.$femonth.'_'.$feday;
         
         if ($_POST[$hidkey]!=$value) {
            $T->setAbsence($feuser, $feyear, $femonth, $feday, $value);
            /**
             * Log this event
             */
            $LOG->log("logUser",$L->checkLogin(),"log_cal_fastedit", "'".$feuser."': ".$feyear."-".$femonth."-".$feday.": ".$A->getName($value));
         }
      }
   }
}
// ============================================================================
/*
 * Process User Search
 */
if ( isset($_POST['btn_usrSearch']) ) 
{
	if (strlen($_POST['txt_calSearchUser']))
	{
		$calSearchUser = trim(preg_replace('/<\\?.*(\\?>|$)/Us', '',$_POST['txt_calSearchUser']));
	}
	else 
	{
		$calSearchUser = "%";
	}
}

/**
 * Show header
 */
$CONF['html_title'] = $LANG['html_title_calendar'];
/**
 * User manual page
 */
$help = urldecode($C->readConfig("userManual"));
if (urldecode($C->readConfig("userManual"))==$CONF['app_help_root']) 
{
   $help .= 'Calendar';
}
require("includes/header_html_inc.php");
require("includes/header_app_inc.php");
require("includes/menu_inc.php");
?>
<div id="content">
   <div id="content-content">
      <?php
      /*
       * The Javascript and Form is only needed when Fast Edit is on
       */
      if ($C->readConfig("fastEdit") AND isAllowed("viewFastEdit")) { ?>
         <script type="text/javascript">
            /*
             * This script prepares Fast Edit and the background images
             * of the absence list boxes so they show the abs icon.
             * The global variables are used to store the usernames
             * and all absence icons. The listbox background image is
             * switches then by index passed by the value of the selected
             * entry.
             */
            var jsusers = new Array(); 
            var viewMode = true;
            /*
             * Now load all absence icons in the rest of the array
             */ 
            var absicon = new Array();
            <?php
            $absences = $A->getAll();
            foreach ($absences as $abs) {
               echo "absicon[".$abs['id']."]='".$CONF['app_icon_dir'].$abs['icon']."';\r\n"; 
            }
            ?>
            /*
             * This function switches the listbox background image based on
             * it selected value. It represents the abs id which is the index
             * for this icon array here.
             */
            function switchAbsIcon(ele, image) { 
               document.getElementById(ele).style.backgroundImage="url('"+image+"')";
            }
         </script>
         <form name="form-fastedit" class="form" method="POST" action="<?=$_SERVER['PHP_SELF']."?".setRequests()?>">
      <?php }
       
      for ($i = 1; $i<= $show_id; $i++) 
      {
      	echo showMonth(strval($year_id), $monthnames[$month_id], $groupfilter, $sort, $page, $calSearchUser);
         if ($month_id == 12) {
            $year_id += 1;
            $month_id = 1;
         }
         else {
            $month_id += 1;
         }
      }
      
      if ($C->readConfig("fastEdit") AND isAllowed("viewFastEdit")) { ?>
         </form>
      <?php } ?>
   </div>
</div>
<?php 
/**
 * Show footer
 */
require("includes/footer_inc.php");
?>
