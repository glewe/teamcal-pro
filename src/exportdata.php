<?php
/**
 * exportdata.php
 *
 * Launches the database export based on REQUEST parameters
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
 * Includes
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");

require_once( "models/csv_model.php" );
require_once( "models/xml_model.php" );
require_once( "models/config_model.php" );
require_once( "models/user_model.php" );
$C = new Config_model;
$U  = new User_model;

/**
 * Check if allowed
 */
if (!isAllowed("manageDatabase")) showError("notallowed", TRUE);

$mydb = new Db_model;
$tables = array (
   $CONF['db_table_absence'],
   $CONF['db_table_absence_group'],
   $CONF['db_table_allowance'],
   $CONF['db_table_announcements'],
   $CONF['db_table_config'],
   $CONF['db_table_daynotes'],
   $CONF['db_table_groups'],
   $CONF['db_table_holidays'],
   $CONF['db_table_log'],
   $CONF['db_table_months'],
   $CONF['db_table_options'],
   $CONF['db_table_permissions'],
   $CONF['db_table_regions'],
   $CONF['db_table_styles'],
   $CONF['db_table_templates'],
   $CONF['db_table_users'],
   $CONF['db_table_user_announcement'],
   $CONF['db_table_user_group'],
   $CONF['db_table_user_options'],
   $CONF['db_table_archive_users'],
   $CONF['db_table_archive_user_group'],
   $CONF['db_table_archive_user_options'],
   $CONF['db_table_archive_templates'],
   $CONF['db_table_archive_daynotes'],
   $CONF['db_table_archive_allowance'],
   $CONF['db_table_archive_user_announcement']
);


$mydb->db_connect();
unset($backup);
$backup = "";

if (!isset($_REQUEST['format'])) $format="sql";
else $format = trim($_REQUEST['format']);

if (!isset($_REQUEST['what'])) $what="all";
else $what = trim($_REQUEST['what']);

if ($_REQUEST['type']=="download") 
{
   header("Content-type: application/force-download");
   header("Content-Disposition: attachment; filename=tcpro_dbexport_".date('Ymd_His').".".$format);
}
else 
{
   header('Content-Type: text/plain');
}

$table_status = $mydb->db_query("SHOW TABLE STATUS");
while($all = mysql_fetch_assoc($table_status)) 
{
   $tbl_stat[$all['Name']] = $all['Auto_increment'];
}

foreach($tables as $table) 
{
   if ($what=="all" || $what==$table) 
   {
      $show = $mydb->db_query("SHOW TABLE STATUS FROM ".$mydb->db_name." LIKE '".$table."'");
      while($tabs = mysql_fetch_row($show)) 
      {
         $backup .= PrintOut($backup,$tabs[0],$tbl_stat[$tabs[0]],$format);
      }
   }
}

switch ($format) 
{
   case 'csv':
      echo "# TeamCal Pro CSV Export\n# Date: ".date('d-m-Y')."\n# Time: ".date('H:i:s')."\n# Server: ".$_SERVER['SERVER_NAME']."\n# Database: ".$mydb->db_name."\n\n";
      echo $backup;
      break;
   case 'sql':
      echo "# TeamCal Pro MySQL Export\n# Date: ".date('d-m-Y')."\n# Time: ".date('H:i:s')."\n# Server: ".$_SERVER['SERVER_NAME']."\n# Database: ".$mydb->db_name."\n\n";
      echo $backup;
      break;
   case 'xml':
      echo "<!-- TeamCal Pro XML Export -->\n<!-- Date: ".date('d-m-Y')." -->\n<!-- Time: ".date('H:i:s')." -->\n<!-- Server: ".$_SERVER['SERVER_NAME']." -->\n<!-- Database: ".$mydb->db_name." -->\n\n";
      echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Database Name=\"".$mydb->db_name."\">\n".$backup."\n</Database>";
      break;
   default:
      echo "Wrong Format";
      break;
}

/**
 * Returns the Tcpro tables in SQL, CSV or XML format
 *
 * @param string $output Header text of dump (currently overwritten by function)
 * @param string $tbl Database table to dump
 * @param integer $stats Show auto increment value
 * @param string $format sql, csv or xml. Defaults to sql.
 * @return string Database dump text
 */
function PrintOut($output,$tbl,$stats,$format='sql') 
{
   global $CONF;

   switch ($format) 
   {
      case 'sql':
      $output  = "--\n-- Table structure for `$tbl`\n--\n\n";
      $output .= "DROP TABLE IF EXISTS `$tbl`;\n";
      $output .= "CREATE TABLE `$tbl` ( ";
      $res = mysql_query("SHOW CREATE TABLE $tbl");
      
      while($al = mysql_fetch_assoc($res)) 
      {
         $str = str_replace("CREATE TABLE `$tbl` (", "", $al['Create Table']);
         $str = str_replace(",", ",", $str);
         $str2 = str_replace("`) ) TYPE=MyISAM ", "`)\n ) TYPE=MyISAM ", $str);
         if ($stats) {$str2 = $str2." AUTO_INCREMENT=".$stats;}
         $output .= $str2.";\n\n";
      }
      
      $output .= "-- \n-- Dumping data for table `".$tbl."`\n-- \n\n";
      $data = mysql_query("SELECT * FROM $tbl");
      
      while($dt = mysql_fetch_row($data)) 
      {
         $output .= "INSERT INTO `$tbl` VALUES('$dt[0]'";
         for($i=1; $i<sizeof($dt); $i++) 
         {
            $dt[$i] = mysql_real_escape_string($dt[$i]);
            $output .= ", '$dt[$i]'";
         }
         $output .= ");\n";
      }
      $output .= "\n-- --------------------------------------------------------\n\n";
      break;

      case 'xml':
      $s2x=new sql2xml($CONF['db_server'],"3306",$CONF['db_name'],$CONF['db_user'],$CONF['db_pass']);
      $result=mysql_query("SELECT * FROM ".$tbl);
      $meta=mysql_fetch_field($result);
      $xmldoc=new Xml_model($meta->table);
      while ($row=mysql_fetch_array($result,MYSQL_NUM)) $xmldoc->addElement($row,$result);
      $output = $xmldoc->getXMLDocument();
      break;

      case 'csv':
      $s2c=new Sql2Csv($CONF['db_server'],"3306",$CONF['db_name'],$CONF['db_user'],$CONF['db_pass']);
      $result=mysql_query("SELECT * FROM ".$tbl);
      $meta = mysql_fetch_field($result);
      $csvdoc = new Csv_model($meta->table);
      $csvdoc->addHeadrow($result);
      while ($row = mysql_fetch_array($result, MYSQL_NUM)) $csvdoc->addElement($row, $result);
      $output = $csvdoc->getCSVDocument();
      $output = "\n# Table: ".$tbl."\n".$output;
      break;

   }
   return $output;
}
?>
