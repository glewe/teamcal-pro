<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * showmonth_helper.php
 *
 * Displays a month with all users and abesences. Big enough to reside in a
 * seperate file.
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: "\");</script>";

// ---------------------------------------------------------------------------
/**
 *  Displays a given month with users and absences based on the passed filters
 *
 * @param string $year Four digit year number
 * @param string $month Two digit month number
 * @param string $groupfilter Identifying the group (or 'All') to filter by
 * @param string $sortorder Indicating ascending or descending sort order
 */
function showMonth($year,$month,$groupfilter,$sortorder,$page=1,$calSearchUser='%') 
{
   global $_POST;
   global $CONF;
   global $LANG;
   global $theme;

   require_once( "models/absence_model.php" );
   require_once( "models/allowance_model.php" );
   require_once( "models/config_model.php" );
   require_once( "models/daynote_model.php" );
   require_once( "models/group_model.php" );
   require_once( "models/holiday_model.php" );
   require_once( "models/login_model.php" );
   require_once( "models/month_model.php" );
   require_once( "models/template_model.php" );
   require_once( "models/user_model.php" );
   require_once( "models/user_group_model.php" );
   require_once( "models/user_option_model.php" );

   $A  = new Absence_model;
   $AC = new Absence_model; // for Absence Count Array
   $AL = new Allowance_model;
   $C  = new Config_model;
   $G  = new Group_model;
   $H  = new Holiday_model;
   $L  = new Login_model;
   $M  = new Month_model;
   $N  = new Daynote_model;
   $N2 = new Daynote_model;
   $T  = new Template_model;
   $U  = new User_model;
   $UG = new User_group_model;
   $UL = new User_model; // user logged in
   $UO = new User_option_model;

   $pscheme = $C->readConfig("permissionScheme");
   $weekdays = $LANG['weekdays'];

   $showmonthBody='';
   $loggedIn = false;
   
   /**
    * Create a timestamp for the given year and month (using day 1 of the
    * month) and use it to get some relevant information using date() and
    * getdate()
    */
   $mytime = $month." 1,".$year;
   $myts = strtotime($mytime);
   $nofdays = date("t",$myts); // Get number of days in current month
   $mydate = getdate($myts);   // Get first weekday of the current month
   $weekday1 = $mydate['wday'];
   if ($weekday1=="0") $weekday1="7";
   $monthinteger = intval($mydate['mon']);
   $monthno = sprintf("%02d",intval($mydate['mon']));
   $monthname = $LANG['monthnames'][intval($monthno)]." ".$year; // Set the friendly name of the month

   /**
    * Now find out what today is and if it lies in the month we are about
    * to display
    */
   $tz = $C->readConfig("timeZone");
   if (!strlen($tz) OR $tz=="default") date_default_timezone_set ('UTC');
   else date_default_timezone_set ($tz);
   $today     = getdate();
   $daytoday   = $today['mday'];  // Numeric representation of todays' day of the month
   $monthtoday = $today['mon'];   // Numeric representation of todays' month
   $yeartoday  = $today['year'];  // A full numeric representation of todays' year, 4 digits
   $todaysmonth = false;
   
   if ( $mydate['mon']==$today['mon'] && $mydate['year']==$today['year'] ) 
   {
      $todaysmonth = true; // The current month is todays' month
   }

   /**
    * Set the repeat header count
    */
   $repHeadCnt = intval($C->readConfig("repeatHeaderCount"));
   if (!$repHeadCnt) $repHeadCnt = 10000;

   /**
    * See if someone is logged in and if so, what type?
    */
   $regularUser = TRUE;
   $userType = "regular";
   $userGroups = null;
   $managerOf = null;
   
   if ($user = $L->checkLogin()) 
   {
      $UL->findByName($user);
      $loggedIn = true;
		switch (true) 
		{
			case $UL->checkUserType($CONF['UTADMIN']):
				$regularUser = FALSE;
				$userType = "admin";
				break;
			case $UL->checkUserType($CONF['UTDIRECTOR']):
				$regularUser = FALSE;
				$userType = "director";
				break;
			case $UL->checkUserType($CONF['UTMANAGER']):
				$regularUser = FALSE;
				$userType = "manager";
				break;
		}
		$userGroups = $UG->getAllforUser2($user);
   }

   /**
    * See if this user is manager of one or more groups
    */
   if(!empty($userGroups)) 
   {
      foreach($userGroups as $key=>$value) 
      {
         if ($value == "manager") $managerOf[]=$key;
      }
   }

   /**
    * Read Month Template from global default region
    */
   $found = $M->findByName($CONF['options']['region'], $year.$monthno);
   if ( !$found ) 
   {
      /**
       * Seems there is no default template for this month yet.
       * Let's create a default one.
       */
      $M->region = $CONF['options']['region'];
      $M->yearmonth = $year.$monthno;
      $M->template = createMonthTemplate($year,$month);
      $M->create();
   }
   else if ( empty($M->template) ) 
   {
      /**
       * Seems there is an empty default template. That can't be.
       * Let's create a default one.
       */
      $M->template = createMonthTemplate($year,$month);
      $M->update($CONF['options']['region'], $year.$monthno);
   }
   
   if ($monthname && $nofdays && $M->template && $weekday1) 
   {
      $cols=0;
      
      /**
       * Declare Javascript array used later for Fast Edit toggle
       */
      echo '<script type="text/javascript">var jsusers = new Array();</script>';
            
      echo '<table class="month">';
      
      // =====================================================================
      /**
       * Row 1: Month Name
       */
      $monthHeader="<tr>\n\r";
      $monthHeader.="<td class=\"month\">" . trim($monthname) . "</td>\n\r";
      $cols++;
   
      if (isAllowed("editGlobalCalendar")) 
      {
         $monthHeader.="<td class=\"month-button\"><a href=\"javascript:openPopup('editmonth.php?lang=".$CONF['options']['lang']."&amp;region=".$CONF['options']['region']."&amp;Year=".$year."&amp;Month=".$month."','shop','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=no,dependent=1,width=1024,height=400');\"><img class=\"noprint\" src=\"themes/".$theme."/img/date.png\" width=\"16\" height=\"16\" border=\"0\" title=\"".$LANG['cal_img_alt_edit_month']."\" alt=\"".$LANG['cal_img_alt_edit_month']."\"></a></td>\n\r";
      }
      else 
      {
         $monthHeader.="<td class=\"month-button\">&nbsp;</td>\n\r";
      }
      $cols++;
   
      /**
       * Row 1: We have to add columns if the Remainder section is switched on
       */
      if (intval($C->readConfig("includeRemainder"))) 
      {
         /**
          * Go through each absence type, see wether its option is set
          * to be shown in the remainders. We need the count for the COLSPAN.
          */
         $cntRemainders=0;
         $cntTotals=0;
         $absences=$A->getAll();
         
         foreach($absences as $abs) 
         {
            if ($abs['show_in_remainder']) $cntRemainders++;
         }
         
         if ( $CONF['options']['remainder']=="show" && $cntRemainders ) 
         {
            $monthHeader.="<td class=\"remainder-title\" colspan=\"".$cntRemainders."\">".$LANG['remainder']."</td>\n\r";
            $cols+=$cntRemainders;
         }
      }

      /**
       * Row 1: We have to add columns if the Totals in the Remainder section is switched on
       */
      if (intval($C->readConfig("includeTotals"))) 
      {
         /**
          * Go through each absence type, see wether its option is set
          * to be shown in the remainders. We need the count for the COLSPAN.
          */
         $cntTotals=0;
         $absences=$AC->getAll();
         
         foreach ($absences as $abs) 
         {
            if ($abs['show_totals']) $cntTotals++;
         }
         
         if ( $CONF['options']['remainder']=="show" && $cntTotals ) 
         {
            $monthHeader.="<td class=\"remainder-title\" colspan=\"".$cntTotals."\">".$LANG['totals']."</td>\n\r";
            $cols+=$cntTotals;
         }
      }
      
      /**
       * Row 1: Day numbers
       */
      $businessDayCount = 0;
      for ($i=1; $i<=$nofdays; $i=$i+1) 
      {
         if ( $H->findBySymbol($M->template[$i-1]) ) 
         {
            if ($H->checkOptions($CONF['H_BUSINESSDAY'])) $businessDayCount++;
            if ( $H->cfgname=='busi' ) 
            {
               /**
                * It's a regular business day
                */
               if ( $todaysmonth && $i==intval($today['mday']) ) 
               {
                  $monthHeader.="<td class=\"todaynum\" title=\"".$H->dspname."\">".$i."</td>\n\r";
               }
               else 
               {
                  $monthHeader.="<td class=\"daynum\" title=\"".$H->dspname."\">".$i."</td>\n\r";
               }
            }
            else 
            {
               /**
                * It's a holiday or any other non-business day
                */
               if ( $todaysmonth && $i==intval($today['mday']) ) 
               {
                  $monthHeader.="<td class=\"todaynum-".$H->cfgname."\" title=\"".$H->dspname."\">".$i."</td>\n\r";
               }
               else 
               {
                  $monthHeader.="<td class=\"daynum-".$H->cfgname."\" title=\"".$H->dspname."\">".$i."</td>\n\r";
               }
            }
         }
         else 
         {
            if ( $todaysmonth && $i==intval($today['mday']) ) 
            {
               $monthHeader.="<td class=\"todaynum\">".$i."</td>\n\r";
            }
            else 
            {
               $monthHeader.="<td class=\"daynum\">".$i."</td>\n\r";
            }
         }
         $cols++;
      }
      $monthHeader.="</tr>\n\r";

      // =====================================================================
      /**
       * Row 2: Fwd/Bwd Buttons and Week numbers
       */
      if (intval($C->readConfig("showWeekNumbers"))) 
      {
         $wd = intval($weekday1);
      
         $colspan=0;
         $monthHeader.="<tr>\n\r";

         /**
          * Get the current URL and parse the query part into the bwd and fwd array
          */
         $urlarray = parse_url($_SERVER['PHP_SELF']."?".setRequests());
         parse_str(html_entity_decode($urlarray['query']),$bwdarray);
         parse_str(html_entity_decode($urlarray['query']),$fwdarray);
         
         /**
          * Change the bwd and fwd year/month based on the currently displayed month
          */
         if ($monthno==1)
         {
            $bwdarray['year_id'] = $year-1;
            $bwdarray['month_id'] = '12';
            $fwdarray['year_id'] = $year;
            $fwdarray['month_id'] = '2';
         }
         else if ($monthno==12) 
         {
            $bwdarray['year_id'] = $year;
            $bwdarray['month_id'] = '11';
            $fwdarray['year_id'] = $year+1;
            $fwdarray['month_id'] = '1';
         }
         else
         {
            $bwdarray['year_id'] = $year;
            $bwdarray['month_id'] = $monthno-1;
            $fwdarray['year_id'] = $year;
            $fwdarray['month_id'] = $monthno+1;
         }
         
         /**
          * Build the bwd and fwd URL query part
          */
         $bwdquery = http_build_query($bwdarray);
         $fwdquery = http_build_query($fwdarray);
          
         /**
          * Display the bwd and fwd buttons
          */
         $monthHeader.='<td class="title">
               <input title="'.$LANG['tt_page_bwd'].'" name="btn_bwd" type="submit" class="button" style="padding-top: 0px; padding-bottom: 0px;" value="&lt;&lt;" onclick="location.href=\'calendar.php?'.htmlentities($bwdquery).'\'">
               <input title="'.$LANG['tt_page_fwd'].'" name="btn_fwd" type="submit" class="button" style="padding-top: 0px; padding-bottom: 0px;" value="&gt;&gt;" onclick="location.href=\'calendar.php?'.htmlentities($fwdquery).'\'">
            </td>';
         
         if ( $CONF['options']['remainder']=="show" && $cntRemainders ) 
         {
            /**
             * Remainder section on: Add colspan
             */
            $monthHeader.="<td class=\"title-button\" colspan=\"".($cntRemainders+$cntTotals+1)."\">&nbsp;</td>\n\r";
         }
         else 
         {
            /**
             * Remainder section off
             */
            $monthHeader.="<td class=\"title-button\">&nbsp;</td>\n\r";
         }
      
         $firstDayOfWeeknumber = intval($C->readConfig("firstDayOfWeek"));
         if ($firstDayOfWeeknumber<1 || $firstDayOfWeeknumber>7) $firstDayOfWeeknumber = 1;
         $lastDayOfWeeknumber = $firstDayOfWeeknumber-1;
         if ($lastDayOfWeeknumber==0) $lastDayOfWeeknumber = 7;
         
         for ($i=1; $i<=$nofdays; $i=$i+1) 
         {
            if ($wd != $lastDayOfWeeknumber) 
            {
               $colspan++;
               $wd++;
               if ($wd==8) $wd = 1;
            }
            else 
            {
               $colspan++;
               $w=date("W",mktime(0,0,0,intval($mydate['mon']),$i,$year));
               $monthHeader.="<td class=\"weeknumber\" colspan=\"".$colspan."\">".sprintf("%d",$w)."</td>\n\r";
               $colspan=0;
               $wd++;
               if ($wd==8) $wd = 1;
            }
         }
         $w=date("W",mktime(0,0,0,intval($mydate['mon']),$i,$year));
         if ($colspan>0) $monthHeader.="<td class=\"weeknumber\" colspan=\"".$colspan."\">".sprintf("%d",$w)."</td>\n\r";
         $monthHeader.="</tr>\n\r";
      }
      
      // =====================================================================
      /**
       * Row 3: Weekdays
       */
      $x = intval($weekday1);
      $monthHeader.="<tr>\n\r";
      $monthHeader.="<td class=\"title\">";
      
      if ($sortorder=="ASC") 
      {
         $request = setRequests();
         $request .= "sort=DESC";
         $monthHeader.="<a href=\"".$_SERVER['PHP_SELF']."?action=calendar&amp;".$request."\">";
         $monthHeader.="<img class=\"noprint\" alt=\"".$LANG['log_sort_desc']."\" title=\"".$LANG['log_sort_desc']."\" src=\"themes/".$theme."/img/desc.png\" align=\"middle\" border=\"0\"></a>";
      }
      else 
      {
         $request = setRequests();
         $request .= "sort=ASC";
         $monthHeader.="<a href=\"".$_SERVER['PHP_SELF']."?action=calendar&amp;".$request."\">";
         $monthHeader.="<img class=\"noprint\" alt=\"".$LANG['log_sort_asc']."\" title=\"".$LANG['log_sort_asc']."\" src=\"themes/".$theme."/img/asc.png\" align=\"middle\" border=\"0\"></a>";
      }
      
      $monthHeader.="&nbsp;".$LANG['cal_caption_name'];
      $monthHeader.="</td>\n\r";
      $monthHeader.="<td class=\"title-button\">";
      
      /**
       * Row 3: Remainder section header
       */
      if (intval($C->readConfig("includeRemainder")) && $cntRemainders) 
      {
         
         if ( $CONF['options']['remainder']=="show" ) 
         {
            /**
             * The remainder section is expanded. Display the collapse button.
             */
            $request = setRequests();
            $request=str_replace("remainder=show","remainder=hide",$request);
            $monthHeader.="<a href=\"".$_SERVER['PHP_SELF']."?action=calendar&amp;".$request."\">";
            $monthHeader.="<img class=\"noprint\" alt=\"".$LANG['col_remainder']."\" title=\"".$LANG['col_remainder']."\" src=\"themes/".$theme."/img/hide_section.gif\" align=\"top\" border=\"0\"></a>";
            $monthHeader.="</td>\n\r";
            
            /**
             * Go through each absence type, see wether its option is set
             * to be shown in the remainders. Then display the remainder
             * title column.
             */
            $absences=$AC->getAll();
            foreach ($absences as $abs) 
            {
               if ($abs['show_in_remainder']) 
               {
                  $monthHeader.="<td class=\"day-a".$abs['id']."\" title=\"".$abs['name']."\" style=\"border-right: 1px dotted #000000;\">";
                  if ($abs['icon']!="No") 
                  {
                     $monthHeader.="<img class=\"noprint\" align=\"top\" alt=\"\" src=\"".$CONF['app_icon_dir'].$abs['icon']."\" width=\"16\" height=\"16\">";
                  }
                  else 
                  {
                     $monthHeader.=$abs['symbol'];
                  }
                  $monthHeader.="</td>\r\n";
               }
            }
      
            if ( intval($C->readConfig("includeTotals")) && $cntTotals ) 
            {
               /**
                * Go through each absence type, see wether its option is set
                * to be shown in the totals. Then display the totals
                * title column.
                */
               $absences=$AC->getAll();
               foreach ($absences as $abs) 
               {
                  if ($abs['show_totals']) 
                  {
                     $monthHeader.="<td class=\"day-a".$abs['id']."\" title=\"".$abs['name']."\" style=\"border-right: 1px dotted #000000;\">";
                     if ($abs['icon']!="No") 
                     {
                        $monthHeader.="<img class=\"noprint\" align=\"top\" alt=\"\" src=\"".$CONF['app_icon_dir'].$abs['icon']."\" width=\"16\" height=\"16\">";
                     }
                     else 
                     {
                        $monthHeader.=$abs['symbol'];
                     }
                     $monthHeader.="</td>\r\n";
                  }
               }
            }
         }
         else 
         {
            /**
             * The remainder section is collapsed. Display the expand button.
             */
            $request = setRequests();
            $request=str_replace("remainder=hide","remainder=show",$request);
            $monthHeader.="<a href=\"".$_SERVER['PHP_SELF']."?action=calendar&amp;".$request."\">";
            $monthHeader.="<img class=\"noprint\" alt=\"".$LANG['exp_remainder']."\" title=\"".$LANG['exp_remainder']."\" src=\"themes/".$theme."/img/show_section.gif\" align=\"top\" border=\"0\"></a>";
            $monthHeader.="</td>\n\r";
         }
      }

      /**
       * Row 3: Weekdays continued
       */
      $dayNotesExist = false;
      if ( $N->findAllByMonthUser($year,$monthno,$nofdays,"all",$CONF['options']['region']) ) $dayNotesExist = true; 
      $ttbody = "";
      for ($i=1; $i<=$nofdays; $i=$i+1) 
      {
         /*
          * Get general Daynote into $ttbody if one exists
          */
         if ($i<10) $dd="0".strval($i); else $dd=strval($i);
         
         if ( $dayNotesExist ) 
         {
            if (!empty($N->daynotes['all'][$year.$monthno.$dd])) 
            {
               $style="-note";
               /*
                * Prepare tooltip
                */
               $ttid = 'td-'.$year.$monthno.$i;
               $ttbody=$N->daynotes['all'][$year.$monthno.$dd];
               $ttcaption = $LANG['tt_title_dayinfo'];
               $ttcapicon = 'themes/'.$theme.'/img/ico_daynote.png';
            }
            else 
            {
               $ttbody="";
               $style="";
            }
         }
      
         if ( $H->findBySymbol($M->template[$i-1]) ) 
         {
            /*
             * Display cell
             */
            if ( $H->cfgname=='busi' ) 
            {
               if ( $todaysmonth && $i==intval($today['mday']) ) 
               {
                  if (strlen($ttbody)) 
                  {
                     $monthHeader.="<td class=\"toweekday".$style."\" id=\"".$ttid."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$weekdays[$x]."</td>\n\r";
                  }
                  else 
                  {
                     $monthHeader.="<td class=\"toweekday\">".$weekdays[$x]."</td>\n\r";
                  }
               } 
               else 
               {
                  if (strlen($ttbody)) 
                  {
                     $monthHeader.="<td class=\"weekday".$style."\" id=\"".$ttid."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$weekdays[$x]."</td>\n\r";
                  }
                  else 
                  {
                     $monthHeader.="<td class=\"weekday\">".$weekdays[$x]."</td>\n\r";
                  }
               }
            } 
            else 
            {
               if ( $todaysmonth && $i==intval($today['mday']) ) 
               {
                  if (strlen($ttbody)) 
                  {
                     $monthHeader.="<td class=\"toweekday-".$H->cfgname."".$style."\" id=\"".$ttid."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$weekdays[$x]."</td>\n\r";
                  }
                  else 
                  {
                     $monthHeader.="<td class=\"toweekday-".$H->cfgname."\">".$weekdays[$x]."</td>\n\r";
                  }
               } 
               else 
               {
                  if (strlen($ttbody)) 
                  {
                     $monthHeader.="<td class=\"weekday-".$H->cfgname."".$style."\" id=\"".$ttid."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$weekdays[$x]."</td>\n\r";
                  }
                  else 
                  {
                     $monthHeader.="<td class=\"weekday-".$H->cfgname."\">".$weekdays[$x]."</td>\n\r";
                  }
               }
            }
         } 
         else 
         {
            if ( $todaysmonth && $i==intval($today['mday']) ) 
            {
               if (strlen($ttbody)) 
               {
                  $monthHeader.="<td class=\"toweekday".$style."\" id=\"".$ttid."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$weekdays[$x]."</td>\n\r";
               }
               else 
               {
                  $monthHeader.="<td class=\"toweekday\">".$weekdays[$x]."</td>\n\r";
               }
            } 
            else 
            {
               if (strlen($ttbody)) 
               {
                  $monthHeader.="<td class=\"weekday".$style."\" id=\"".$ttid."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$weekdays[$x]."</td>\n\r";
               }
               else 
               {
                  $monthHeader.="<td class=\"weekday\">".$weekdays[$x]."</td>\n\r";
               }
            }
         }
         if($x<=6) $x+=1; else $x = 1;
      }
      $monthHeader.="</tr>\n\r";
      
      // =====================================================================
      /**
       * Write header into the output buffer
       */
      $showmonthBody .= $monthHeader;
      
      // =====================================================================
      /**
       * Select usernames based on filter requests and put them in an array
       */
      $users = array();
      $groupfilter = $CONF['options']['groupfilter'];

      if ($calSearchUser=="%")
      {
	      if ($groupfilter=="All") 
	      {
	         $query = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
	                  " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].
	                  " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
	                  " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username" .
	                  " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
	                  " ORDER BY ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
	         $result = $U->db->db_query($query);
	         $i=0;
	         while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) 
	         {
	            $users[$i]['group']=$row['group'];
	            $users[$i]['user']=$row['username'];
	            $i++;
	         }
	      }
	      else if ($groupfilter=="Allbygroup") 
	      {
	         if (intval($C->readConfig("hideManagers"))) 
	         {
	            $query = "SELECT DISTINCT ".$CONF['db_table_user_group'].".groupname, ".$CONF['db_table_user_group'].".username " .
	                     " FROM ".$CONF['db_table_user_group'].", ".$CONF['db_table_users'].", ".$CONF['db_table_groups'].
	                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
	                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
	                     " AND ".$CONF['db_table_user_group'].".username=".$CONF['db_table_users'].".username" .
	                     " AND ".$CONF['db_table_user_group'].".type!='manager'" .
	                     " ORDER BY ".$CONF['db_table_user_group'].".groupname ASC, ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
	         }
	         else 
	         {
	            $query = "SELECT DISTINCT ".$CONF['db_table_user_group'].".groupname, ".$CONF['db_table_user_group'].".username " .
	                     " FROM ".$CONF['db_table_user_group'].", ".$CONF['db_table_users'].", ".$CONF['db_table_groups'].
	                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
	                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
	                     " AND ".$CONF['db_table_user_group'].".username=".$CONF['db_table_users'].".username" .
	                     " ORDER BY ".$CONF['db_table_user_group'].".groupname ASC, ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
	         }
	         $result = $UG->db->db_query($query);
	         $i=0;
	         while ( $row = $UG->db->db_fetch_array($result,MYSQL_ASSOC) ) 
	         {
	            $users[$i]['group']=$row['groupname'];
	            $users[$i]['user']=$row['username'];
	            $i++;
	         }
	      }
	      else 
	      {
	         /*
	          * Get regular group members
  	          */
	         if (intval($C->readConfig("hideManagers"))) 
	         {
	            $query = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
	                     " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].
	                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
	                     " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username" .
	                     " AND ".$CONF['db_table_groups'].".groupname='".$groupfilter."'" .
	                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
	                     " AND ".$CONF['db_table_user_group'].".type!='manager'" .
	                     " ORDER BY ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
	         }
	         else 
	         {
	            $query = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
	                     " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].
	                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
	                     " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username" .
	                     " AND ".$CONF['db_table_groups'].".groupname='".$groupfilter."'" .
	                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
	                     " ORDER BY ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
	         }
	         $result = $U->db->db_query($query);
	         $i=0;
	         while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) 
	         {
	            $users[$i]['group']=$row['group'];
	            $users[$i]['user']=$row['username'];
	            $users[$i]['mship']="real";
	            $i++;
	         }
	         /*
	          * Get related user to this group (user option: show in other groups)
	          */
	         if (intval($C->readConfig("hideManagers"))) 
	         {
	            $query = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
	                     " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].",".$CONF['db_table_user_options'].
	                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
	                     " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username" .
	                     " AND (".$CONF['db_table_groups'].".groupname!='".$groupfilter."' AND " .
	                     "(".$CONF['db_table_users'].".username=".$CONF['db_table_user_options'].".username AND ".$CONF['db_table_user_options'].".option='showInGroups' AND ".$CONF['db_table_user_options'].".value LIKE '%".$groupfilter."%'))".
	                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
	                     " AND ".$CONF['db_table_user_group'].".type!='manager'" .
	                     " ORDER BY ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
	         }
	         else 
	         {
	            $query = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
	                     " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].",".$CONF['db_table_user_options'].
	                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
	                     " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username" .
	                     " AND (".$CONF['db_table_groups'].".groupname!='".$groupfilter."' AND " .
	                     "(".$CONF['db_table_users'].".username=".$CONF['db_table_user_options'].".username AND ".$CONF['db_table_user_options'].".option='showInGroups' AND ".$CONF['db_table_user_options'].".value LIKE '%".$groupfilter."%'))".
	                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
	                     " ORDER BY ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
	         }
	         $result = $U->db->db_query($query);
	         while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) 
	         {
	            $users[$i]['group']=$row['group'];
	            $users[$i]['user']=$row['username'];
	            $users[$i]['mship']="related";
	            $i++;
	         }
	      }
      }
      else 
      {
         /*
          * Get search user
          */
          if (intval($C->readConfig("hideManagers"))) 
          {
             $query = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
                     " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].
                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
                     " AND (".$CONF['db_table_users'].".username LIKE '%".$calSearchUser."%' ".
                     "    OR ".$CONF['db_table_users'].".lastname LIKE '%".$calSearchUser."%' ".
                     "    OR ".$CONF['db_table_users'].".firstname LIKE '%".$calSearchUser."%')".
                     " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username" .
                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
                     " AND ".$CONF['db_table_user_group'].".type!='manager'" .
                     " ORDER BY ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
          }
          else 
          {
             $query = "SELECT DISTINCT ".$CONF['db_table_users'].".*" .
                     " FROM ".$CONF['db_table_users'].",".$CONF['db_table_user_group'].",".$CONF['db_table_groups'].
                     " WHERE ".$CONF['db_table_users'].".username != 'admin'" .
                     " AND (".$CONF['db_table_users'].".username LIKE '%".$calSearchUser."%' ".
                     "    OR ".$CONF['db_table_users'].".lastname LIKE '%".$calSearchUser."%' ".
                     "    OR ".$CONF['db_table_users'].".firstname LIKE '%".$calSearchUser."%')".
                     " AND ".$CONF['db_table_users'].".username=".$CONF['db_table_user_group'].".username" .
                     " AND (".$CONF['db_table_groups'].".groupname=".$CONF['db_table_user_group'].".groupname AND (".$CONF['db_table_groups'].".options&1)=0 )" .
                     " ORDER BY ".$CONF['db_table_users'].".lastname ".$sortorder.",".$CONF['db_table_users'].".firstname ASC";
         }
         $result = $U->db->db_query($query);
         $i=0;
         while ( $row = $U->db->db_fetch_array($result,MYSQL_ASSOC) ) 
         {
            $users[$i]['group']=$row['group'];
            $users[$i]['user'] = $row['username'];
            $users[$i]['mship']="real";
            $i++;
         }
      }
      
      // =====================================================================
      /**
       * Check whether an absence filter was requested
       */
      if ($CONF['options']['absencefilter']!="All" AND $todaysmonth) 
      {
         $j=0;
         $subusers = array();
         for ($su=0; $su<count($users); $su++) 
         {
            $T = new Template_model;
            $found = $T->getTemplate($users[$su]['user'],$year,$monthno);
            if (!$found) 
            {
               /**
                * No template found for this user and month. Create one.
                */
               $T->username = $users[$su]['user'];
               $T->year = $year;
               $T->month = $monthno;
               for ($i=1; $i<=intval($nofdays); $i++ ) 
               {
                  $prop='abs'.$i;
                  $T->$prop = 0;
               }
               $T->create();
            }
      
            /**
             * Check if this user has the requested absence for today.
             * If so, add him to the subusers array.
             */
            $prop='abs'.(intval($today['mday']));
            if ( $T->$prop==$CONF['options']['absencefilter'] ) 
            {
               $subusers[$j]['group']=$users[$su]['group'];
               $subusers[$j]['user']=$users[$su]['user'];
               $j++;
            }
         }
         
         /**
          * Reinitiate the $users array and write all $subusers into it.
          * If there are no $subusers, $users will be empty too.
          */
         $users = array();
         for ($su=0; $su<count($subusers); $su++) 
         {
            $users[$su]['group']=$subusers[$su]['group'];
            $users[$su]['user']=$subusers[$su]['user'];
         }
      }
      
      // =====================================================================
      /**
       * Initialize the summary counts.
       * A) Create array $intSumPresentDay[], containing the sums of presents for each day of the month
       * B) Create array $intSumAbsentDay[], containing the sums of absences for each day of the month
       * C) Create array $arrAbsenceMonth[], one field for each absence type, containing the sum of it taken for the month
       * D) Create array $arrAbsenceDay[], one field for each absence type and day, containing the sum of it taken for the day
       */
      $intSumPresentMonth=0;
      $intSumAbsentMonth=0;
      for($x=0; $x<intval($nofdays); $x++) $intSumPresentDay[$x]=0; // Sum present per day
      for($x=0; $x<intval($nofdays); $x++) $intSumAbsentDay[$x]=0; // Sum absent per day
      $absences=$AC->getAll();
      foreach($absences as $abs) 
      {
         $arrAbsenceMonth[$abs['name']]=0;
         for($x=0; $x<intval($nofdays); $x++) 
         {
            $arrAbsenceDay[$abs['name']][$x]=0;
         }
      }
      
      // =====================================================================
      /**
       * USER LOOP
       * 
       * Loop through all users previously selected in the $users array.
       * Initialize the row count which is per user. It is used to repeat the month header
       * later based on repeatHeaderRowCount.
       */
      $intSumUser = count($users);
      $intUsersPerPage = intval($C->readConfig("usersPerPage"));
      if (!$intUsersPerPage) $intUsersPerPage = 10000;
      $intNumPages = ceil($intSumUser/$intUsersPerPage);
      $intPageUserCount=-1;
      $intCurrentUserCount=-1;
      $intDisplayPage = $page;
      $currentgroup='';
      $newarray = array();
      
      /**
       * Get all usernames in an array
       */
      foreach($users as $value) 
      {
         foreach($value as $key => $value2) 
         {
            if ($key == 'user') $newarray[] = mysql_real_escape_string($value2);
         }
      }
      $userset = join("','", $newarray);
      
      /**
       * Get all daynotes for this userlist
      */
      $dayNotesExist = 0;
      if ( $N2->findAllByMonth($year,$monthno,$nofdays,$userset) ) $dayNotesExist = 1;
      
      foreach ($users as $usr) 
      {
         $monthBody='';
      
         $U->findByName($usr['user']);
      
         /**
          * Permission to view this user?
          */
         $allowed=FALSE;
         if ( $user == $U->username )
         { 
            $allowed=TRUE;
         }
         else if ( $UG->shareGroups($user, $U->username) ) 
         {
            if (isAllowed("viewGroupUserCalendars")) $allowed=TRUE;
         }
         else 
         {
            if (isAllowed("viewAllUserCalendars")) $allowed=TRUE;
         }
              
         if ( $allowed AND !($U->status&$CONF['USHIDDEN']) ) 
         {
            $intCurrentUserCount++;
            $intPageUserCount++;
            /**
             * Repeat month header if repeat count reached
             * $repHeadCnt = amount of user rows before new header to be inserted
             * $intPageUserCount = current amount of user rows we have on this page already
             * $intSumUser = how many users
             */
            if ( $intPageUserCount!=0 AND (($intPageUserCount)%$repHeadCnt)==0 ) $showmonthBody .= $monthHeader;
            $intThisUsersPage = floor($intCurrentUserCount/$intUsersPerPage) + 1;
            if ( $intThisUsersPage < $intDisplayPage ) { $intPageUserCount=-1; continue; }
            if ( $intThisUsersPage > $intDisplayPage ) break;
            if ( $intPageUserCount == $intUsersPerPage ) break;
      
            if ( $groupfilter=="Allbygroup" ) 
            {
               if (!strlen($currentgroup) OR $currentgroup!=$usr['group']) 
               {
                  $currentgroup=$usr['group'];
                  $G->findByName($currentgroup);
                  $monthBody .= "<tr><td class=\"groupdelim\" colspan=\"".$cols."\">".$G->description."</td></tr>\n\r";
               }
            }
      
            if ( $U->firstname!="" ) $showname = stripslashes(ucwords(strtolower($U->lastname)).",&nbsp;".ucwords(strtolower($U->firstname)));
            else                     $showname = stripslashes($U->lastname);//user
      
            if (!strlen($showname)) $showname = $U->username;
            else                    $showname = mb_convert_encoding($showname, "UTF-8");
      
            $rowid = $U->username."_".$year."_".$monthno;
            
            $monthBody .= "<tr>\n\r";
            $monthBody .= "<td class=\"name\">\n\r";
            
            /**
             * Add user to Javascript array for Fast Edit toggle
             */
            $monthBody .= '<script type="text/javascript">jsusers['.$intCurrentUserCount.'] = "'.$U->username.'";</script>';
            
            /**
             * Get user icon if configured
             */
            if ($C->readConfig("showUserIcons")) 
            {
               /**
                * Select user icon, make it female if necessary and put it in the body
                */
               if ( $U->checkUserType($CONF['UTADMIN']) ) 
               {
                  $icon = "ico_usr_admin";
                  $icon_tooltip = $LANG['icon_admin'];
               }
               else if ( $U->checkUserType($CONF['UTMANAGER']) ) 
               {
                  $icon = "ico_usr_manager";
                  $icon_tooltip = $LANG['icon_manager'];
               }
               else if ( $U->checkUserType($CONF['UTASSISTANT']) ) 
               {
                  $icon = "ico_usr_assistant";
                  $icon_tooltip = $LANG['icon_assistant'];
               }
               else 
               {
                  $icon = "ico_usr";
                  $icon_tooltip = $LANG['icon_user'];
               }
               if ( !$U->checkUserType($CONF['UTMALE']) ) $icon .= "_f.png"; else $icon .= ".png";
         
               /**
                * If user is a related user (not member but shown in this group), just use grey icon
                */
               if ($groupfilter!="All" AND $groupfilter!="Allbygroup") 
               {
                  if ($usr['mship']=="related") 
                  {
                     $icon = "ico_usr_grey.png";
                     $icon_tooltip = $LANG['cal_tt_related_1'].$groupfilter.$LANG['cal_tt_related_2'];
                  }
               }
      
               /**
                * Get user avatar if configured (only works with icons enabled)
                */
               $avatar_link='';
               $avatar_close='';
               if ($C->readConfig("showAvatars")) 
               {
                  $avatar='';
                  $avatar_fullname=$U->title." ".$U->firstname." ".$U->lastname;
                  if( file_exists("img/avatar/".$U->username.".gif")) $avatar="<img src=\"img/avatar/".$U->username.".gif\" alt=\"\">";
                  else if( file_exists("img/avatar/".$U->username.".jpg")) $avatar="<img src=\"img/avatar/".$U->username.".jpg\" alt=\"\">";
                  else if( file_exists("img/avatar/".$U->username.".jpeg")) $avatar="<img src=\"img/avatar/".$U->username.".jpeg\" alt=\"\">";
                  else if( file_exists("img/avatar/".$U->username.".png")) $avatar="<img src=\"img/avatar/".$U->username.".png\" alt=\"\">";
                  if( strlen($avatar) ) 
                  {
                     /**
                      * Prepare tootlip
                      */
                     $ttid = 'ava-'.$rowid;
                     $ttbody = $avatar;
                     $ttcaption = $avatar_fullname;
                     $ttcapicon = '';
                     $monthBody .= '<div style="float: left;" id="'.$ttid.'">'.createPopup($ttid, $ttbody, $ttcaption, $ttcapicon);
                  }
                  else 
                  {
                     $monthBody .= '<div style="float: left;">';
                  }
               }
            
               $monthBody .= "<img src=\"themes/".$theme."/img/".$icon."\" title=\"".$icon_tooltip."\" alt=\"img\" style=\"border: 0px; padding-right: 2px; vertical-align: top;\"></div>";
            }
      
            /**
             * Check permission to edit the profile
             */
            $editProfile=FALSE;
            if ( $UL->username == $U->username )
            {
               $editProfile=TRUE;
            }
            else if ( $UG->shareGroups($UL->username, $U->username) )
            {
               if (isAllowed("editGroupUserProfiles"))
               {
                  if ($UG->isGroupManagerOfUser($UL->username, $U->username) OR !$UG->isGroupManagerOfUser($U->username, $UL->username))
                  {
                     $editProfile=TRUE;
                  }
               }
            }
            else
            {
               if (isAllowed("editAllUserProfiles")) $editProfile=TRUE;
            }
            
            /**
             * Check permission to view the profile
             */
            $viewProfile=FALSE;
            if (isAllowed("viewUserProfiles")) $viewProfile=TRUE;
      
            if($editProfile) 
            {
               $monthBody .= "&nbsp;<a title=\"".$LANG['tt_edit_profile']."\" class=\"name\" href=\"javascript:this.blur();openPopup('editprofile.php?referrer=index&amp;lang=".$CONF['options']['lang']."&amp;username=".addslashes($U->username)."','editprofile','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=600,height=680');\">".$showname."</a>\n\r";
            }
            else if($viewProfile) 
            {
               $monthBody .= "&nbsp;<a title=\"".$LANG['tt_view_profile']."\" class=\"name\" href=\"javascript:this.blur();openPopup('viewprofile.php?lang=".$CONF['options']['lang']."&amp;username=".addslashes($U->username)."','viewprofile','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,titlebar=0,resizable=0,dependent=1,width=480,height=580');\">".$showname."</a>\n\r";
            }
            else 
            {
               $monthBody .= $showname;
            }
            $monthBody .= "</td>\n\r";
      
            /**
             * Show the custom popup if one exists
             */
            if (!strlen($U->customPopup) OR !$viewProfile) 
            {
               $monthBody .= "<td class=\"name-button\">\n\r";
            }
            else 
            {
               /*
                * Prepare tooltip
                */
               $ttid = 'userinfo-'.$rowid;
               $ttbody = $U->customPopup;
               $ttcaption = $LANG['tt_title_userinfo'];
               $ttcapicon = 'themes/'.$theme.'/img/ico_daynote.png';
               $monthBody .= '<td class="name-button-note" id="'.$ttid.'">'.createPopup($ttid, $ttbody, $ttcaption, $ttcapicon);
            }
             
            /**
             * Check permission to edit calendar
             */
            $editCalendar=FALSE;
            if ( $UL->username == $U->username ) 
            {
               if (isAllowed("editOwnUserCalendars")) $editCalendar=TRUE;
            }
            else if ( $UG->shareGroups($UL->username, $U->username) AND !$UG->isGroupManagerOfUser($U->username, $UL->username) ) 
            {
               if (isAllowed("editGroupUserCalendars")) $editCalendar=TRUE;
            }
            else 
            {
               if (isAllowed("editAllUserCalendars")) $editCalendar=TRUE;
            }
      
            if ($editCalendar) 
            {
               if (!$thisregion = $UO->find($U->username,"defregion")) $thisregion = $CONF['options']['region'];
               $monthBody .= "<a href=\"javascript:openPopup('editcalendar.php?lang=".$CONF['options']['lang']."&amp;Year=".$year."&amp;Month=".$month."&amp;region=".$thisregion."&amp;Member=".addslashes($U->username)."','shop','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=no,dependent=1,width=1024,height=640');\"><img class=\"noprint\" src=\"themes/".$theme."/img/btn_edit.gif\" width=\"16\" height=\"16\" border=\"0\" title=\"".$LANG['cal_img_alt_edit_cal']."\" alt=\"".$LANG['cal_img_alt_edit_cal']."\"></a>\n\r";
            }
            $monthBody .= "</td>\n\r";
      
            /**
             * Try to find this users template for this month
             */
            $T = new Template_model;
            $found = $T->getTemplate($U->username,$year,$monthno);
            if (!$found) 
            {
               /**
                * No template found for this user and month yet.
                * Create a default one.
                */
               $T->username = $U->username;
               $T->year = $year;
               $T->month = $monthno;
               for ($i=1; $i<=intval($nofdays); $i++ ) 
               {
                  $prop='abs'.$i;      
                  $T->$prop = 0;
               }
               $T->create();
            }

            /**
             * Determine the region to show in this row
             */
            if ($C->readConfig("showUserRegion"))
            {
               $userRegion = $UO->find($U->username,'defregion');
               if ($userRegion == "default") $userRegion = $CONF['options']['region'];
                  
               /**
                * Read Month Template from determined region
                */
               $found = $M->findByName($userRegion, $year.$monthno);
               if ( !$found )
               {
                  /**
                   * Seems there is no default template for this month yet.
                   * Let's create a default one.
                   */
                  $M->region = $userRegion;
                  $M->yearmonth = $year.$monthno;
                  $M->template = createMonthTemplate($year,$month);
                  $M->create();
               }
               else if ( empty($M->template) )
               {
                  /**
                   * Seems there is an empty default template. That can't be.
                   * Let's create a default one.
                   */
                  $M->template = createMonthTemplate($year,$month);
                  $M->update($userRegion, $year.$monthno);
               }
            }
            
            /**
             * Show the remainder section for this user
             */
            if ( intval($C->readConfig("includeRemainder")) && $cntRemainders ) 
            {
               if ( $CONF['options']['remainder']=="show" ) 
               {
                  /**
                   * Go through each absence type, see wether its option is set
                   * to be shown in the remainders. Then display the remainder
                   * if the current user has editCalendar rights.
                   */
                  $absences=$AC->getAll();
                  foreach ($absences as $abs) 
                  {
                     $isConfidential = $abs['confidential'];
                     if ($U->username == $UL->username) $isSameUser = TRUE; else $isSameUser = FALSE;
                     if ($abs['show_in_remainder']) 
                     {
                        if (isAllowed("editAllUserCalendars") OR isAllowed("editGroupUserCalendars") OR isAllowed("editOwnUserCalendars")) 
                        {
                           if ( $AL->find($U->username,$abs['id']) ) 
                           {
                              $lastYearAllowance = $AL->lastyear;
                              $thisYearAllowance = $AL->curryear;
                           }
                           else 
                           {
                              $lastYearAllowance = 0;
                              $thisYearAllowance = $abs['allowance'];
                           }
      
                           $from = str_replace("-","",$C->readConfig("defperiodfrom"));
                           $to = str_replace("-","",$C->readConfig("defperiodto"));
                           if ($abs['counts_as'])
                           {
                              $thisYearTaken = countAbsence($U->username,$abs['id'],$from,$to,false,false);
                           }
                           else 
                           {
                              $thisYearTaken = countAbsence($U->username,$abs['id'],$from,$to,true,true);
                           }
                           $thisYearRemainder = $lastYearAllowance+$thisYearAllowance-$thisYearTaken;
                           $totalAllowance    = $lastYearAllowance+$thisYearAllowance;
                           $separator = "/";
                                         
                           if ( ($isConfidential AND $regularUser AND !$isSameUser) OR (!$thisYearRemainder AND !$totalAllowance) )
                           {
                              $thisYearTaken     = "&nbsp;";
                              $thisYearRemainder = "&nbsp;";
                              $totalAllowance    = "&nbsp;";
                              $separator = "";
                           }
      
                           if ( $thisYearRemainder < 0 ) 
                           {
                              $addStyle=" style=\"color: #FF0000;\"";
                           }
                           else
                           {
                              $addStyle="";
                           }
                           
                           if ( intval($C->readConfig("includeRemainderTotal")) ) 
                           {
                              $monthBody .= "<td class=\"remainder\"><span".$addStyle.">".$thisYearRemainder."</span>".$separator.$totalAllowance."</td>\n\r";
                           }
                           else 
                           {
                              $monthBody .= "<td class=\"remainder\"><span ".$addStyle.">".$thisYearRemainder."</span></td>\n\r";
                           }
                        }
                        else 
                        {
                           $monthBody .= "<td class=\"remainder\">&nbsp;</td>\n\r";
                        }
                     }
                  }
      
                  if ( intval($C->readConfig("includeTotals")) && $cntTotals ) 
                  {
                     /**
                      * Go through each absence type, see wether its option is set
                      * to be shown in the totals. Then display the total
                      * if the current user has editCalendar rights.
                      */
                     $first=true;
                     $absences=$AC->getAll();
                     foreach ($absences as $abs) 
                     {
                        $isConfidential = $abs['confidential'];
                        if ($U->username==$UL->username) $isSameUser = TRUE; else $isSameUser = FALSE;
                        if ($abs['show_totals']) 
                        {
                           if (isAllowed("editAllUserCalendars") OR isAllowed("editGroupUserCalendars") OR isAllowed("editOwnUserCalendars")) 
                           {
                              $thisTotal = countAbsence($U->username,$abs['id'],$year.$monthno.'01',$year.$monthno.$nofdays,false,true);
                              if ( $isConfidential AND $regularUser AND !$isSameUser ) $thisTotal = "&nbsp;";
                               
                              $addStyle="";
                              if ( $first ) 
                              {
                                 $addStyle=" style=\"border-left: 1px solid #000000;\"";
                                 $first=false;
                              }
                              $monthBody .= "<td class=\"totals\"".$addStyle."\">".$thisTotal."</td>\n\r";
                           }
                           else 
                           {
                              $monthBody .= "<td class=\"totals\">&nbsp;</td>\n\r";
                           }
                        }
                     }
                  }
               }
            }
      
            /**
             * Go through each day now
             */
            for($i=0; $i<intval($nofdays); $i++) 
            {
               $style="";
               $cellid = $rowid."_".($i+1);
               /**
                * Prepare tootlip
                */
               $ttid = 'userdayinfo-'.$cellid;
               $ttbody = '';
               $ttcaption = $LANG['tt_title_userdayinfo'];
               $ttcapicon = 'themes/'.$theme.'/img/ico_daynote.png';
               
               /**
                * Check Birthday
                */
               if (($i+1)<10) $dd="0".strval($i+1); else $dd=strval($i+1);
               if ( (substr($U->birthday,4)==$monthno.$dd) && ($UO->true($U->username,"showbirthday")) ) 
               {
                  $ttbody='<img src="img/icons/cake.png" alt="cake" style="vertical-align: bottom; padding-right: 4px;">';
                  if($UO->true($U->username,"ignoreage")) 
                  {
                     $birthdate=date("d M",strtotime($U->birthday));
                     $ttbody .= "* ".$LANG['cal_birthday'].": ".$birthdate.". * <br><br>";
                  } 
                  else 
                  {
                     $birthdate=date("d M Y",strtotime($U->birthday));
                     $dayofbirth=date("d M",strtotime($U->birthday));
                     $age=intval($year)-intval(substr($U->birthday,0,4));
                     $ttbody .= "* ".$LANG['cal_birthday'].": ".$birthdate.". (".$LANG['cal_age'].": ".$age.") * <br><br>";
                  }
                  $style="-bday";
               }
      
               /**
                * Check Daynote
                */
               if ( !intval($C->readConfig("hideDaynotes")) || !$regularUser ) 
               {
                  if ($dayNotesExist == 1) 
                  {
                     if(!empty($N2->daynotes[addslashes($U->username)][$year.$monthno.$dd])) 
                     {
                        /**
                         * The personal daynote is appended to $ttbody because it might
                         * contain a birthday text already. The style is overwritten.
                         * There can only be one marker.
                         */
                        $ttbody.=$N2->daynotes[$U->username][$year.$monthno.$dd];
                        if ($style=="-bday") $style="-bdaynote"; else $style="-note";
                     }
                  }
               }
      
               $prop='abs'.($i+1);
               if ($T->$prop) 
               {
                  $A->get($T->$prop);
                  $isAbsence=TRUE;
                  $countsAsPresent=$A->counts_as_present;
                  $isConfidential=$A->confidential;
               }
               else 
               {
                  $isAbsence=FALSE;
                  $countsAsPresent=TRUE;
                  $isConfidential=FALSE;
               }
               if ( $U->username == $UL->username ) $isSameUser = TRUE; else $isSameUser = FALSE;
      
               /**
                * Start tag for the current user and current day
                */
               $monthBody .= "<td id=\"userdayinfo-".$cellid."\" ";
               
               if ( !$isAbsence OR ($isAbsence AND $isConfidential AND $regularUser AND !$isSameUser) ) 
               {
                  /**
                   * This person is present or the viewer may not see this absence. Lets color the day as present.
                   * Also, add this to the presence count for the summary.
                   */
                  $abs_original='0';
                  
                  if (!$isAbsence) 
                  {
                     $intSumPresentMonth++;
                     $intSumPresentDay[$i]++;
                  }
      
                  $inner = "<div id=\"view-".$cellid."\" style=\"display: block;\">&nbsp;</div>";
                  
                  if ( $isAbsence AND $isConfidential AND $regularUser AND !$isSameUser AND $C->readConfig("markConfidential") ) $inner = "X";
      
                  if ( $H->findBySymbol($M->template[$i]) ) 
                  {
                     if ( $todaysmonth && $i+1<intval($today['mday']) ) 
                     {
                        /**
                         * Today's month and day is in the past
                         */
                        if (strlen($C->readConfig("pastDayColor"))) $pdcolor="style=\"background: #".$C->readConfig("pastDayColor").";\""; else $pdcolor="";
                        if (strlen($ttbody) && isAllowed("viewUserProfiles")) 
                        {
                           $monthBody .= "class=\"day-".$H->cfgname.$style."\" ".$pdcolor.">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$inner;
                        }
                        else 
                        {
                           $monthBody .= "class=\"day-".$H->cfgname."\" ".$pdcolor.">".$inner;
                        }
                     }
                     else if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                     {
                        /**
                         * Today's month and day is today
                         */
                        if (strlen($ttbody) && isAllowed("viewUserProfiles")) 
                        {
                           $monthBody .= "class=\"today-".$H->cfgname.$style."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$inner;
                        }
                        else 
                        {
                           $monthBody .= "class=\"today-".$H->cfgname."\">".$inner;
                        }
                     }
                     else 
                     {
                        /**
                         * All other days
                         */
                        if (strlen($ttbody) && isAllowed("viewUserProfiles")) 
                        {
                           $monthBody .= "class=\"day-".$H->cfgname.$style."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$inner;
                        }
                        else 
                        {
                           $monthBody .= "class=\"day-".$H->cfgname."\">".$inner;
                        }
                     }
                  }
                  else 
                  {
                     if ( $todaysmonth && $i+1<intval($today['mday']) ) 
                     {
                        /**
                         * Today's month and day is in the past
                         */
                        if (strlen($C->readConfig("pastDayColor"))) $pdcolor="style=\"background: #".$C->readConfig("pastDayColor").";\""; else $pdcolor="";
                        if (strlen($ttbody) && isAllowed("viewUserProfiles")) 
                        {
                           $monthBody .= "class=\"day".$style."\" ".$pdcolor."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$inner;
                        }
                        else 
                        {
                           $monthBody .= "class=\"day\" ".$pdcolor.">".$inner;
                        }
                     }
                     else if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                     {
                        /**
                         * Today's month and day is today
                         */
                        if (strlen($ttbody) && isAllowed("viewUserProfiles")) 
                        {
                           $monthBody .= "class=\"today".$style."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$inner;
                        }
                        else 
                        {
                           $monthBody .= "class=\"today\">".$inner;
                        }
                     } 
                     else 
                     {
                        /**
                         * All other days
                         */
                        if (strlen($ttbody) && isAllowed("viewUserProfiles")) 
                        {
                           $monthBody .= "class=\"day".$style."\">".createPopup($ttid, $ttbody, $ttcaption, $ttcapicon).$inner;
                        }
                        else 
                        {
                           $monthBody .= "class=\"day\">".$inner;
                        }
                     }
                  }
               }
               else 
               {
                  /**
                   * This person is not present. Let's add this absence to
                   * the counter for the summary. Then we gotta color the
                   * day according to the absence type and show
                   * its display symbol.
                   *
                   * Also, add this to the absence count for the summary if it does not count as 'present'.
                   * Otherwise we have to add this to the presence count.
                   */
                  $abs_original=$A->id;
                  
                  if ( $countsAsPresent ) 
                  {
                     $intSumPresentMonth++;
                     $intSumPresentDay[$i]++;
                  }
                  else 
                  {
                     $intSumAbsentMonth++;
                     $intSumAbsentDay[$i]++;
                     $arrAbsenceMonth[$A->name]++;
                     $arrAbsenceDay[$A->name][$i]++;
                  }
      
                  if ( $todaysmonth && $i+1==intval($today['mday']) )
                  {
                     $cssclass='today'.$style.'-a'.$A->id;
                     if ($A->bgtransparent) 
                     {
                        $H->findBySymbol($M->template[$i]);
                        $cssclass="today-".$H->cfgname.$style;
                     }
                  }
                  else
                  {
                     $cssclass='day'.$style.'-a'.$A->id;
                     if ($A->bgtransparent) 
                     {
                        $H->findBySymbol($M->template[$i]);
                        $cssclass="day-".$H->cfgname.$style;
                     }
                  }
                  
                  $monthBody .= "class=\"".$cssclass."\" >";
                  
                  if ( strlen($ttbody) && isAllowed("viewUserProfiles") ) $monthBody .= createPopup($ttid, $ttbody, $ttcaption, $ttcapicon);
                   
                  if ($A->icon!='No')
                     $inner = "<div id=\"view-".$cellid."\" style=\"display: block;\"><img title=\"".$A->name."\" align=\"top\" alt=\"\" src=\"".$CONF['app_icon_dir'].$A->icon."\" width=\"16\" height=\"16\"></div>";
                  else
                     $inner = "<div id=\"view-".$cellid."\" style=\"display: block;\">".$A->symbol."</div>";
                  
                  $monthBody .= $inner;
               }
               
               /**
                * Fast Edit select box
                * 
                * This <div> block is appended to the $inner div and is initially hidden. 
                * $inner then contains two <div>, one for view mode, one for edit mode.
                * Upon click on the fast edit icon Javascript will hide 'view' and show 'edit'. 
                * It contains a drop down list of all absence types with 'Present' selected.
                * Right before it we put a hidden field containing the original absence. Will
                * be used when submitting the form to only set the changed values.
                */
               if ($C->readConfig("fastEdit") AND isAllowed("viewFastEdit") AND $CONF['options']['absencefilter']=="All") 
               {
                  $form = '<input name="hid_abs_'.$cellid.'" type="hidden" class="text" value="'.$abs_original.'">'."\r\n";
                  $form .= '<select name="sel_abs_'.$cellid.'" id="sel_abs_'.$cellid.'" class="select" style="background-image: url('.(($isAbsence)?$CONF['app_icon_dir'].$A->icon:"img/pixel.gif").'); background-size: 16px 16px; background-repeat: no-repeat; background-position: 2px 2px; padding: 2px 0px 0px 22px;" onchange="javascript: switchAbsIcon(this.id,absicon[this.value]);">'."\r\n";
                  $form .= '<option style="background-image: url(img/pixel.gif); background-size: 16px 16px; background-repeat: no-repeat; padding-left: 20px;" value="0" '.((!$isAbsence)?"SELECTED":"").'>'.$LANG['cal_abs_present'].'</option>'."\r\n";
                  $absences = $A->getAll();
                  foreach ($absences as $abs) 
                  { 
                     $form .= '<option style="background-image: url('.$CONF['app_icon_dir'].$abs['icon'].'); background-size: 16px 16px; background-repeat: no-repeat; padding-left: 20px;" value="'.$abs['id'].'" '.(($isAbsence AND $abs['id']==$A->id)?"SELECTED":"").'>'.$abs['name'].'</option>'."\r\n";
                  }
                  $form .= '</select>'."\r\n";
                  $fedit = "<div id=\"edit-".$cellid."\" style=\"display: none;\">".$form."</div>\r\n";
                  $monthBody .= $fedit;
               }
                
               /**
                * End tag for the current user and current day
                */
               $monthBody .= "</td>\n\r";
               
            }
            $monthBody .= "</tr>\n\r";
      
            /**
             * Write body into output buffer
             */
            $showmonthBody .= $monthBody;
         }
         // end if ( !($U->status&$CONF['USHIDDEN']) )
      }
      // end foreach ($users as $usr)

      
      /**=====================================================================
       * Row: Fast Edit
       */
      if ($C->readConfig("fastEdit") AND isAllowed("viewFastEdit") AND $CONF['options']['absencefilter']=="All") 
      {
         $cspan = ' colspan="2"';
         if ( $CONF['options']['remainder']=="show" && $cntRemainders ) $cspan = ' colspan="'.($cntRemainders+$cntTotals+1+1).'"';
         $showmonthBody.="<tr>\n\r";
         $showmonthBody.="<td class=\"title\"".$cspan.">";
         $showmonthBody.="&nbsp;".$LANG['cal_fastedit'];
         $showmonthBody.='<input name="btn_fastedit_apply" type="submit" class="button" style="margin-left: 10px;" value="'.$LANG['btn_apply'].'">';
         $showmonthBody.="</td>\n\r";
         for ($i=1; $i<=$nofdays; $i=$i+1) 
         {
            $showmonthBody.="<td class=\"weekday\"><a href=\"javascript:toggleFastEdit('".$year."', '".$monthno."', '".$i."', jsusers);\"><img class=\"noprint\" src=\"themes/".$theme."/img/ico_edit.png\" width=\"16\" height=\"16\" border=\"0\" title=\"".$LANG['cal_fastedit_tt']."\" alt=\"ico_edit.png\"></a></td>\n\r";
         }
         $showmonthBody.="</tr>\n\r";
      }
      
      /**
       * Now print a summary row for this month
       * Summary Header
       */
      $summaryBody='';
      if ($C->readConfig("includeSummary")) 
      {
         $summaryBody .= "<tr>\n\r";
         $summaryBody .= "   <td class=\"title\" colspan=\"3\">";
         $summaryBody .= "      <b>".$LANG['sum_summary'].":</b>&nbsp;";
      
         $request = setRequests();
         if ($CONF['options']['summary']=="show") 
         {
            $request=str_replace("summary=show","summary=hide",$request);
            $summaryBody .= "<a href=\"".$_SERVER['PHP_SELF']."?action=calendar&amp;".$request."\">";
            $summaryBody .= "<img alt=\"".$LANG['col_summary']."\" title=\"".$LANG['col_summary']."\" src=\"themes/".$theme."/img/hide_section.gif\" align=\"top\" border=\"0\"></a>";
         }
         else 
         {
            $request=str_replace("summary=hide","summary=show",$request);
            $summaryBody .= "<a href=\"".$_SERVER['PHP_SELF']."?action=calendar&amp;".$request."\">";
            $summaryBody .= "<img alt=\"".$LANG['exp_summary']."\" title=\"".$LANG['exp_summary']."\" src=\"themes/".$theme."/img/show_section.gif\" align=\"top\" border=\"0\"></a>";
         }
      
         $summaryBody .= "   </td>\n\r";
         $summaryBody .= "   <td class=\"title-button\" colspan=\"".($cols-3)."\">".$businessDayCount." ".$LANG['sum_business_day_count']."</td>\n\r";
         $summaryBody .= "</tr>\n\r";
      
         if ($CONF['options']['summary']=="show") 
         {
            /**
             * Sum Present
             */
            $summaryBody .= "<tr>\n\r";
            $summaryBody .= "   <td class=\"name\"><b>".$LANG['sum_present']."</b></td>\n\r";
            $summaryBody .= "   <td class=\"name-button\">&nbsp;</td>\n\r";
            if ( intval($C->readConfig("includeRemainder")) && $CONF['options']['remainder']=="show" && $cntRemainders ) 
            {
               $summaryColSpan = $cntRemainders;
               if ( intval($C->readConfig("includeTotals")) ) $summaryColSpan+=$cntTotals;
               $summaryBody .= "<td class=\"day\" colspan=\"".$summaryColSpan."\"></td>\r\n";
            }
            for($i=0; $i<intval($nofdays); $i++) 
            {
               if ( $H->findBySymbol($M->template[$i]) ) 
               {
                  if ($H->checkOptions($CONF['H_BUSINESSDAY'])) $summaryValue = $intSumPresentDay[$i];
                  else $summaryValue = "&nbsp;";
                  if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                  {
                     $summaryBody .= "<td class=\"today-".$H->cfgname."-sum-present\">".$summaryValue."</td>\n\r";
                  } 
                  else 
                  {
                     $summaryBody .= "<td class=\"day-".$H->cfgname."-sum-present\">".$summaryValue."</td>\n\r";
                  }
               } 
               else 
               {
                  if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                  {
                     $summaryBody .= "<td class=\"today-sum-present\">".$intSumPresentDay[$i]."</td>\n\r";
                  } 
                  else 
                  {
                     $summaryBody .= "<td class=\"day-sum-present\">".$intSumPresentDay[$i]."</td>\n\r";
                  }
               }
            }
            $summaryBody .= "</tr>\n\r";
      
            /**
             * Sum Absent
             */
            $summaryBody .= "<tr>\n\r";
            $summaryBody .= "   <td class=\"name\"><b>".$LANG['sum_absent']."</b></td>\n\r";
            $summaryBody .= "   <td class=\"name-button\">&nbsp;</td>\n\r";
            if ( intval($C->readConfig("includeRemainder")) && $CONF['options']['remainder']=="show" && $cntRemainders ) 
            {
               $summaryColSpan = $cntRemainders;
               if ( intval($C->readConfig("includeTotals")) ) $summaryColSpan+=$cntTotals;
               $summaryBody .= "<td class=\"day\" colspan=\"".$summaryColSpan."\"></td>\r\n";
            }
            for($i=0; $i<intval($nofdays); $i++) 
            {
               if ( $H->findBySymbol($M->template[$i]) ) 
               {
                  if ($H->checkOptions($CONF['H_BUSINESSDAY'])) $summaryValue = $intSumAbsentDay[$i];
                  else $summaryValue = "&nbsp;";
                  if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                  {
                     $summaryBody .= "<td class=\"today-".$H->cfgname."-sum-absent\">".$summaryValue."</td>\n\r";
                  } 
                  else 
                  {
                     $summaryBody .= "<td class=\"day-".$H->cfgname."-sum-absent\">".$summaryValue."</td>\n\r";
                  }
               } 
               else 
               {
                  if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                  {
                     $summaryBody .= "<td class=\"today-sum-absent\">".$intSumAbsentDay[$i]."</td>\n\r";
                  } 
                  else 
                  {
                     $summaryBody .= "<td class=\"day-sum-absent\">".$intSumAbsentDay[$i]."</td>\n\r";
                  }
               }
            }
            $summaryBody .= "</tr>\n\r";
      
            /**
             * Delta: Present-Absent
             */
            $summaryBody .= "<tr>\n\r";
            $summaryBody .= "   <td class=\"name\"><b>".$LANG['sum_delta']."</b></td>\n\r";
            $summaryBody .= "   <td class=\"name-button\">&nbsp;</td>\n\r";
            if ( intval($C->readConfig("includeRemainder")) && $CONF['options']['remainder']=="show" && $cntRemainders ) 
            {
               $summaryColSpan = $cntRemainders;
               if ( intval($C->readConfig("includeTotals")) ) $summaryColSpan+=$cntTotals;
               $summaryBody .= "<td class=\"day\" colspan=\"".$summaryColSpan."\"></td>\r\n";
            }
            for($i=0; $i<intval($nofdays); $i++) 
            {
               if (($delta=$intSumPresentDay[$i]-$intSumAbsentDay[$i])>=0) $suffix="-positive";
               else $suffix="-negative";
               if ( $H->findBySymbol($M->template[$i]) ) 
               {
                  if ($H->checkOptions($CONF['H_BUSINESSDAY'])) $summaryValue = $delta;
                  else $summaryValue = "&nbsp;";
                  if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                  {
                     $summaryBody .= "<td class=\"today-".$H->cfgname."-sum-delta".$suffix."\">".$summaryValue."</td>\n\r";
                  } 
                  else 
                  {
                     $summaryBody .= "<td class=\"day-".$H->cfgname."-sum-delta".$suffix."\">".$summaryValue."</td>\n\r";
                  }
               } 
               else 
               {
                  if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                  {
                     $summaryBody .= "<td class=\"today-sum-delta".$suffix."\">".$delta."</td>\n\r";
                  } 
                  else 
                  {
                     $summaryBody .= "<td class=\"day-sum-delta".$suffix."\">".$delta."</td>\n\r";
                  }
               }
            }
            $summaryBody .= "</tr>\n\r";
      
            /**
             * Absence Summary Header
             */
            $summaryBody .= "<tr>\n\r";
            $summaryBody .= "   <td class=\"title\" colspan=\"".($cols-1)."\"><b>".$LANG['sum_absence_summary'].":</b></td>\n\r";
            $summaryBody .= "   <td class=\"title-button\">&nbsp;</td>\n\r";
            $summaryBody .= "</tr>\n\r";
      
            /**
             * Day Absences, one per row. Hide confidential ones to regular users.
             */
            $absences=$AC->getAll();
            foreach($absences as $abs) 
            {
               $countsAsPresent = $abs['counts_as_present'];
               $isConfidential = $abs['confidential'];
               /**
                * Only show those that do not count as 'present'
                */
               if ( (!$countsAsPresent AND !$isConfidential) OR (!$countsAsPresent AND $isConfidential AND !$regularUser) ) 
               {
                  $summaryBody .= "<tr>\n\r";
                  $summaryBody .= "   <td class=\"name\">".$abs['name']."</td>\n\r";
                  $summaryBody .= "   <td class=\"name-button\">".$arrAbsenceMonth[$abs['name']]."</td>\n\r";
                  if ( intval($C->readConfig("includeRemainder")) && $CONF['options']['remainder']=="show" && $cntRemainders ) 
                  {
                     $summaryColSpan = $cntRemainders;
                     if ( intval($C->readConfig("includeTotals")) ) $summaryColSpan+=$cntTotals;
                     $summaryBody .= "<td class=\"day\" colspan=\"".$summaryColSpan."\"></td>\r\n";
                  }
                  for($i=0; $i<intval($nofdays); $i++) 
                  {
                     if ( $H->findBySymbol($M->template[$i]) ) 
                     {
                        if ($H->checkOptions($CONF['H_BUSINESSDAY'])) $summaryValue = $arrAbsenceDay[$abs['name']][$i];
                        else $summaryValue = "&nbsp;";
                        if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                        {
                           $summaryBody .= "<td class=\"today-".$H->cfgname."-day-absent\">".$summaryValue."</td>\n\r";
                        }
                        else 
                        {
                           $summaryBody .= "<td class=\"day-".$H->cfgname."-day-absent\">".$summaryValue."</td>\n\r";
                        }
                     }
                     else 
                     {
                        if ( $todaysmonth && $i+1==intval($today['mday']) ) 
                        {
                           $summaryBody .= "<td class=\"today-day-absent\">".$arrAbsenceDay[$abs['name']][$i]."</td>\n\r";
                        }
                        else 
                        {
                           $summaryBody .= "<td class=\"day-day-absent\">".$arrAbsenceDay[$abs['name']][$i]."</td>\n\r";
                        }
                     }
                  }
                  $summaryBody .= "</tr>\n\r";
               }
            }
         }
      }
      $summaryBody .= "</table>\n\r";
      $summaryBody .= "<br>\n\r";
      
      /**
       * Write summary into output buffer
       */
      $showmonthBody .= $summaryBody;
      
      /**
       * Print paging buttons if required
       */
      if ($page) 
      {
         $urlparms = '?action=calendar&amp;lang='.$CONF['options']['lang'].'&amp;groupfilter='.$groupfilter.'&amp;year_id='.$year.'&amp;month_id='.$monthinteger.'&amp;sort='.$sortorder;
         if ($intNumPages>1) 
         {
            if ($intDisplayPage==1) 
            {
               $showmonthBody .= '<input type="button" value="'.$LANG['btn_prev'].'" disabled>&nbsp;';
            }
            else 
            {
               $showmonthBody .= '<input type="button" onclick="javascript:document.location.href=\''.$_SERVER['PHP_SELF'].$urlparms.'&amp;page='.($intDisplayPage-1).'\';" value="'.$LANG['btn_prev'].'">&nbsp;';
            }
      
            for ($i=1; $i<=$intNumPages; $i++) 
            {
               if ($intDisplayPage==$i) 
               {
                  $showmonthBody .= '<input type="button" style="color: #ffffff; font-weight: bold;" value="'.$intDisplayPage.'" disabled>&nbsp;';
               }
               else 
               {
                  $showmonthBody .= '<input type="button" onclick="javascript:document.location.href=\''.$_SERVER['PHP_SELF'].$urlparms.'&amp;page='.($i).'\';" value="'.$i.'">&nbsp;';
               }
            }
      
            if ($intDisplayPage==$intNumPages) 
            {
               $showmonthBody .= '<input type="button" value="'.$LANG['btn_next'].'" disabled>&nbsp;';
            }
            else 
            {
               $showmonthBody .= '<input type="button" onclick="javascript:document.location.href=\''.$_SERVER['PHP_SELF'].$urlparms.'&amp;page='.($intDisplayPage+1).'\';" value="'.$LANG['btn_next'].'">&nbsp;';
            }
         }
      }

   } // End if ($monthname && $nofdays && $M->template && $weekday1)

   /**
    * Ok, release the body...
    */
   return $showmonthBody;

} // End function showMonth
?>
