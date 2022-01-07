<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * menu_inc.php
 *
 * Displays the TeamCal Pro menu on every main page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

require_once("models/user_announcement_model.php");

$L  = new Login_model;
$UA = new User_announcement_model;
$UL = new User_model;

/**
 * Build menu flags based on permissions
 */
$m = buildMenu();
?>

<!-- MENU BAR ============================================================= -->
<div id="menubar">
   <div id="menubar-content">

      <?php
      /**
       * User icon, Announcement icon
       */
      if ($user=$L->checkLogin()) { ?>
         <?php $UL->findByName($user);

         if( $UL->checkUserType($CONF['UTUSER']) ) {
            $utype = $LANG['status_ut_user'];
            $icon = "ico_usr";
         }

         if( $UL->checkUserType($CONF['UTMANAGER']) ) {
            require_once( $CONF['app_root']."models/user_group_model.php" );
            $UG = new User_group_model;
            $groups='';
            $queryUG  = "SELECT `groupname` FROM `".$CONF['db_table_user_group']."` WHERE `username`='".$UL->username."' AND `type`='manager' ORDER BY `groupname`;";
            $resultUG = $UG->db->db_query($queryUG);
            while ( $rowUG = $UG->db->db_fetch_array($resultUG) ){
               $groups.=stripslashes($rowUG['groupname']).", ";
               }
            $groups=substr($groups,0,strlen($groups)-2);
            $utype = $LANG['status_ut_manager']." ".$groups;
            $icon = "ico_usr_manager";
         }

         if( $UL->checkUserType($CONF['UTDIRECTOR']) ) {
            $utype = $LANG['status_ut_director'];
            $icon = "ico_usr_director";
         }

         if( $UL->checkUserType($CONF['UTASSISTANT']) ) {
            $utype = $LANG['status_ut_assistant'];
            $icon = "ico_usr_assistant";
         }

         if( $UL->checkUserType($CONF['UTADMIN']) ) {
            $utype = $LANG['status_ut_admin'];
            $icon = "ico_usr_admin";
         }
         if ( !$UL->checkUserType($CONF['UTMALE']) ) $icon .= "_f.png";
         else $icon .= ".png";
         ?>
         <div style="float: left; position: relative; top: 4px; left: 10px; margin-right: 6px;">
            <img src="themes/<?=$theme?>/img/<?=$icon?>" alt="" title="<?=$LANG['status_logged_in']?> <?=$user?> (<?=$utype?>)">
            <?php 
            if (isAllowed("viewAnnouncements")) {
               $uas=$UA->getAllForUser($UL->username);
               if (count($uas)) { ?>
             	   <a href="announcement.php?uaname=<?=$UL->username?>"><img src="themes/<?=$theme?>/img/ico_bell.png" alt="" title="<?=$LANG['mnu_announcements']?>"></a>
           	   <?php }
            } ?>
         </div>
         <?php 
      }
      else { ?>
         <div style="float: left; position: relative; top: 4px; left: 10px; margin-right: 6px;">
            <img src="themes/<?=$theme?>/img/ico_usr_grey.png" alt="" title="<?=$LANG['status_logged_out']?>">
         </div>
      <?php } 
      ?>
   
      <div id="myMenuID" style="float: left; position: relative; left: 7px;"></div>
      <script type="text/javascript">
      <!--
      var myMenu =
      [
         [null,'<?=$LANG['mnu_teamcal']?>',null,null,null,
            <?php if ($m['mnu_teamcal_login']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_login.png" />','<?=$LANG['mnu_teamcal_login']?>','login.php',null,null],
            <?php }
            if ($m['mnu_teamcal_register']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_register.png" />','<?=$LANG['mnu_teamcal_register']?>','javascript:openPopup(\'register.php\',\'login\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=420,height=550\');',null,null],
            <?php }
            if ($m['mnu_teamcal_logout']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_logout.png" />','<?=$LANG['mnu_teamcal_logout']?>','index.php?action=logout',null,null],
            <?php } ?>
         ],
         _cmSplit,
         [null,'<?=$LANG['mnu_view']?>',null,null,null,
            ['<img src="themes/<?=$theme?>/img/menu/ico_home.png" />','<?=$LANG['mnu_view_homepage']?>','index.php?action=welcome',null,null],
            <?php if ($m['mnu_view_calendar']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_calendar.png" />','<?=$LANG['mnu_view_calendar']?>','calendar.php',null,null],
            <?php }
            if ($m['mnu_view_yearcalendar']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_calendar.png" />','<?=$LANG['mnu_view_yearcalendar']?>','showyear.php',null,null],
            <?php }
            if ($m['mnu_view_announcement']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_announcement.png" />','<?=$LANG['mnu_view_announcement']?>','announcement.php',null,null],
            <?php }
            if ($m['mnu_view_statistics']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_statistics.png" />','<?=$LANG['mnu_view_statistics']?>...',null,null,null,
               ['<img src="themes/<?=$theme?>/img/menu/ico_statistics.png" />','<?=$LANG['mnu_view_statistics_g']?>','statistics.php',null,null],
               ['<img src="themes/<?=$theme?>/img/menu/ico_statistics.png" />','<?=$LANG['mnu_view_statistics_r']?>','statisticsu.php',null,null],
            ]
            <?php } ?>
         ],
         _cmSplit,
         <?php if ($m['mnu_tools'] ) { ?>
         [null,'<?=$LANG['mnu_tools']?>',null,null,null,
            <?php if ($m['mnu_tools_profile'] AND $luser=$L->checkLogin()) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_usr.png" />','<?=$LANG['mnu_tools_profile']?>','javascript:openPopup(\'editprofile.php?referrer=index&username=<?=$luser?>\',\'profile\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=640,height=720\');',null,null],
            <?php }
            if ($m['mnu_tools_message']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_message.png" />','<?=$LANG['mnu_tools_message']?>','message.php',null,null],
            <?php }
            if ($m['mnu_tools_webmeasure']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_calc.png" />','<?=$LANG['mnu_tools_webmeasure']?>','javascript:openPopup(\'http://measure.lewe.com\',\'message\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=820,height=480\');',null,null],
            <?php }
            if ($m['mnu_tools_admin']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_configure.png" />','<?=$LANG['mnu_tools_admin']?>...',null,null,null,
               <?php if ($m['mnu_tools_admin_config']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_configure.png" />','<?=$LANG['mnu_tools_admin_config']?>','config.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_perm']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_permissions.png" />','<?=$LANG['mnu_tools_admin_perm']?>','permissions.php',null,null],
               <?php }
               if ( ($m['mnu_tools_admin_config'] OR $m['mnu_tools_admin_perm'])
                     AND
                    ($m['mnu_tools_admin_users'] OR
                     $m['mnu_tools_admin_groups'] OR
                     $m['mnu_tools_admin_usergroups'] OR
                     $m['mnu_tools_admin_absences'] OR
                     $m['mnu_tools_admin_regions'] OR
                     $m['mnu_tools_admin_holidays'] OR
                     $m['mnu_tools_admin_declination'] OR
                     $m['mnu_tools_admin_database'])
                  ) { ?>
               _cmSplit,
               <?php }
               if ($m['mnu_tools_admin_users']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_usr.png" />','<?=$LANG['mnu_tools_admin_users']?>','userlist.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_groups']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_usr_member.png" />','<?=$LANG['mnu_tools_admin_groups']?>','groups.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_usergroups']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_usr_member.png" />','<?=$LANG['mnu_tools_admin_usergroups']?>','groupassign.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_absences']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_absences.png" />','<?=$LANG['mnu_tools_admin_absences']?>','abslist.php',null,null],
               ['<img src="themes/<?=$theme?>/img/menu/ico_absences.png" />','<?=$LANG['mnu_tools_admin_absences_edit']?>','absences.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_regions']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_region.png" />','<?=$LANG['mnu_tools_admin_regions']?>','regions.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_holidays']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_calendar.png" />','<?=$LANG['mnu_tools_admin_holidays']?>','holidays.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_declination']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_declination.png" />','<?=$LANG['mnu_tools_admin_declination']?>','declination.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_database']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_database.png" />','<?=$LANG['mnu_tools_admin_database']?>','database.php',null,null],
               <?php }
               if ( ($m['mnu_tools_admin_users'] OR
                     $m['mnu_tools_admin_groups'] OR
                     $m['mnu_tools_admin_usergroups'] OR
                     $m['mnu_tools_admin_absences'] OR
                     $m['mnu_tools_admin_regions'] OR
                     $m['mnu_tools_admin_holidays'] OR
                     $m['mnu_tools_admin_declination'] OR
                     $m['mnu_tools_admin_database'])
                     AND
                     ($m['mnu_tools_admin_systemlog'] OR
                      $m['mnu_tools_admin_env'])
                  ) { ?>
               _cmSplit,
               <?php }
               if ($m['mnu_tools_admin_systemlog']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_log.png" />','<?=$LANG['mnu_tools_admin_systemlog']?>','log.php',null,null],
               <?php }
               if ($m['mnu_tools_admin_env']) { ?>
               ['<img src="themes/<?=$theme?>/img/menu/ico_env.png" />','<?=$LANG['mnu_tools_admin_env']?>','environment.php',null,null],
               ['<img src="themes/<?=$theme?>/img/menu/ico_php.png" />','<?=$LANG['mnu_tools_admin_phpinfo']?>','phpinfo.php',null,null],
               <?php } ?>
            ],
            <?php } ?>
         ],
         <?php } ?>
         _cmSplit,
         [null,'<?=$LANG['mnu_help']?>',null,null,null,
            <?php if ($m['mnu_help_legend']) { ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_legend.png" />','<?=$LANG['mnu_help_legend']?>','javascript:openPopup(\'legend.php\',\'legend\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=500,height=540\');',null,null],
            <?php } ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_help.png" />','<?=$LANG['mnu_help_help']?>','javascript:window.open(\'<?=$C->readConfig("userManual")?>\').void();',null,null],
            <?php
            /**
             * You may not disable or alter the About dialog nor its menu item here.
             */
            ?>
            ['<img src="themes/<?=$theme?>/img/menu/ico_calendar.png" />','<?=$LANG['mnu_help_about']?>','javascript:openPopup(\'about.php\',\'about\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,titlebar=0,resizable=0,dependent=1,width=580,height=390\');',null,null],
         ],
      ];
      cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
      -->
      </script>
   </div>
</div>

<?php if (!isset($err_short)) 
{
	/**
	 * ============================================================================
	 * OPTIONS BAR
	 */
	$optionitems=FALSE;
	if (substr_count($_SERVER['PHP_SELF'],"calendar.php")) {
	   $action=$_SERVER['PHP_SELF']."?".setRequests();
	}
	else if (substr_count($_SERVER['PHP_SELF'],"permissions.php") AND isset($scheme)) {
	   $action=$_SERVER['PHP_SELF']."?scheme=".$scheme;
	}
	else if (substr_count($_SERVER['PHP_SELF'],"userlist.php") AND isset($searchuser) AND isset($searchgroup) AND isset($sort)) {
	   $action=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;searchgroup=".$searchgroup."&amp;sort=".$sort;
	}
	else if (substr_count($_SERVER['PHP_SELF'],"groupassign.php") AND isset($searchuser) AND isset($sort)) {
	   $action=$_SERVER['PHP_SELF']."?searchuser=".$searchuser."&amp;sort=".$sort;
	}
	else if (substr_count($_SERVER['PHP_SELF'],"absences.php") AND isset($absid)) {
	   $action=$_SERVER['PHP_SELF']."?absid=".$absid;
	}
	else {
	   $action=$_SERVER['PHP_SELF'];
	}
	?>
	<!-- OPTIONS BAR ========================================================== -->
	<div id="optionsbar">
	   <form class="form" method="POST" name="form_options" action="<?=$action?>">
	      <span id="optionsbar-content">
	      <?php
	      /**
	       * CALENDAR
	       * Group, Region, Absence, Start-year, Start-month, Number of months
	       */
	   	if (substr_count($_SERVER['PHP_SELF'],"calendar.php") AND isAllowed("viewCalendar")) {
	         include ($CONF['app_root']."includes/options_calendar_inc.php");
	         $optionitems=TRUE;
	      } 
	
	      /**
	       * YEAR CALENDAR
	       * Year, User
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"showyear.php") AND isAllowed("viewYearCalendar")) {
	         include ($CONF['app_root']."includes/options_showyear_inc.php");
	         $optionitems=TRUE;
	      }
	      
	      /**
	       * GLOBAL STATISTICS
	       * Standard Period, Custom Period, Group, Absence
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"statistics.php") AND isAllowed("viewStatistics")) {
	         include ($CONF['app_root']."includes/options_statistics_inc.php");
	         $optionitems=TRUE;
	      }
	
	      /**
	       * LOG
	       * Standard Period, Custom Period
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"log.php") AND isAllowed("viewSystemLog")) {
	         include ($CONF['app_root']."includes/options_log_inc.php");
	         $optionitems=TRUE;
	      }
	
	      /**
	       * REMAINDER STATISTICS
	       * Group, User
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"statisticsu.php") AND isAllowed("viewStatistics")) {
	         include ($CONF['app_root']."includes/options_statisticsu_inc.php");
	         $optionitems=TRUE;
	      }
	
	      /**
	       * OPTION BUTTONS
	       * Select scheme, Create scheme
	       */
	      if ( $optionitems ) { ?>
	         <input name="btn_apply" type="submit" class="button" value="<?=$LANG['btn_apply']?>">
	         <input name="btn_reset" type="button" class="button" onclick="javascript:document.location.href='<?=$_SERVER['PHP_SELF']?>'" value="<?=$LANG['btn_reset']?>">
	      <?php }
	
	      /**
	       * PERMISSIONS
	       * Select scheme, Create scheme
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"permissions.php")AND isAllowed("editPermissionScheme")) {
	         include ($CONF['app_root']."includes/options_permissions_inc.php");
	      }
	
	      /**
	       * USERLIST
	       * Search, Group
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"userlist.php")AND isAllowed("manageUsers")) {
	         include ($CONF['app_root']."includes/options_userlist_inc.php");
	      }
	
	      /**
	       * GROUP ASSIGN
	       * Search
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"groupassign.php")AND isAllowed("manageGroupMemberships")) {
	         include ($CONF['app_root']."includes/options_groupassign_inc.php");
	      }
	
	      /**
	       * ABSENCES
	       * Select, Create
	       */
	      if (substr_count($_SERVER['PHP_SELF'],"absences.php")AND isAllowed("editAbsenceTypes")) {
	         include ($CONF['app_root']."includes/options_absences_inc.php");
	      }
	      ?>
	      </span>
	   </form>
	</div>
<?php } ?>

<!-- CONTENT ============================================================== -->
