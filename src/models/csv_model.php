<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * csv_model.php
 * 
 * Provides classes to deal with CSV parsing
 *
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

if (!class_exists("Csv_model")) 
{
	class Csv_model 
	{
	   var $headrow;
	   var $body;
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Class constructor
	    */
	   function Csv_model() 
	   {
	      $this->headrow = "";
	      $this->body = "";
	   }
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Creates the CSV header line with the field names
	    * 
	    * @param integer $rows MySQL query result (contains the number of fields)
	    */
	   function addHeadrow($rows) 
	   {
	      $out = '';
	      for ($i = 0; $i < mysql_num_fields($rows); $i++) 
	      {
	         $meta = mysql_fetch_field($rows, $i);
	         $out .= "\"".$meta->name . "\";";
	      }
	      $out = substr_replace($out, ";", strlen($out) - 1, 1);
	      $out .= "\n";
	      $this->body = $this->body . $out;
	   }
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Writes each field value of a row
	    * 
	    * @param integer $rows MySQL query result
	    * @param array $row Array of field values
	    */
	   function addElement($row, $rows) 
	   {
	      $out = '';
	      for ($i = 0; $i < mysql_num_fields($rows); $i++) 
	      {
	         $meta = mysql_fetch_field($rows, $i);
	         if ($meta->name == "password") 
	         {
	            $out .= "\"********\";";
	         } 
	         else 
	         {
	            if (strlen($row[$i])) $out .= "\"".$row[$i] . "\";";
	            else $out .= ";";
	         }
	      }
	      $out = substr_replace($out, ";", strlen($out) - 1, 1);
	      $out .= "\n";
	      $this->body = $this->body . $out;
	   }
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Returns the CSV text
	    * 
	    * @return string CSV text (header row and body)
	    */
	   function getCSVDocument() 
	   {
	      return $this->headrow . $this->body;
	   }
   }
}


if (!class_exists("Sql2Csv")) 
{
	class Sql2Csv 
	{
	   var $connection;
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Class constructor. Connects to the database server and selects the database.
	    */
	   function Sql2Csv($dbhost, $dbport, $dbname, $dbuser, $dbpwd) 
	   {
	      $this->connection = mysql_connect($dbhost.":".$dbport, $dbuser, $dbpwd);
	      mysql_select_db($dbname, $this->connection);
	   }
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Exports a table to a CSV file
	    * 
	    * @param string $dbtable Table name
	    * @param string $filename File name
	    */
	   function exportTable($dbtable, $filename) 
	   {
	      $result = mysql_query("SELECT * FROM " . $dbtable);
	      if (!$result) die('Query failed: "SELECT * FROM '.$dbtable.'"'.mysql_error());
	      $this->export($result, $filename);
	   }
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Launches the export of a query to a CSV file
	    * 
	    * @param string $query MySQL query
	    * @param string $filename File name
	    */
	   function exportCustomTable($query, $filename) 
	   {
	      $rows = mysql_query($query);
	      $this->export($rows, $filename);
	   }
	   
	   // ---------------------------------------------------------------------
	   /** 
	    * Exports a query to a CSV file
	    * 
	    * @param string $result Result handle of MySQL query
	    * @param string $filename File name
	    */
	   function export($result, $filename) 
	   {
	      $meta = mysql_fetch_field($result);
	      $csvdoc = new Csv_model($meta->table);
	
	      $csvdoc->addHeadrow($result);
	      while ($row = mysql_fetch_array($result, MYSQL_NUM)) 
	      {
	         $csvdoc->addElement($row, $result);
	      }
	      
	      header("Content-type: application/force-download");
	      header("Content-Disposition: attachment; filename=tcpro_".$meta->table."_".date('Ymd_His').".csv");
	      echo $csvdoc->getCSVDocument();
             
	      /** 
	       * I am not exporting it from here. Just returning the text
	       */
	      // $fileHandle = fopen($filename, "w");
	      // fwrite($fileHandle, $csvdoc->getCSVDocument());
	      // fclose($fileHandle);
	   }
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Returns a CSV document from a database table
	    * 
	    * @param string $dbtable Table name
	    */
	   function getTableAsCsv($dbtable) 
	   {
	      $rows = mysql_query("SELECT * FROM " . $dbtable);
	      $meta = mysql_fetch_field($rows);
	      $csvdoc = new Csv_model($meta->table);
	      while ($row = mysql_fetch_array($rows, MYSQL_NUM)) 
	      {
	         $csvdoc->addElement($row, $rows);
	      }
	      return $csvdoc->getCSVDocument();
	   }
   }
}


if (!class_exists("CsvImport")) 
{
   require_once ("models/db_model.php");

	/** 
	 * Provides objects and methods to import from a CSV file
	 * 
	 * @package TeamCalPro
	 * @param string $dbtable Table name
	 */
	class CsvImport 
	{
	   var $table;
	   var $file_name;
	   var $error='';
	   var $log;
	   var $logtype;
	   var $count_imported=0;
	   var $count_skipped=0;
	   
	   var $username;
	   var $password;
	   var $firstname;
	   var $lastname;
	   var $title;
	   var $position;
	   var $group;
	   var $phone;
	   var $mobile;
	   var $email;
	   var $idnumber;
	   var $status;
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Class constructor
	    */
	   function CsvImport($file_name = "") 
	   {
	      global $CONF;
	      global $LANG;
	      $this->db = new Db_model;
	      $this->table = $CONF['db_table_users'];
	      $this->log = $CONF['db_table_log'];
	      $this->file_name = $file_name;
	   }
	
	   // ---------------------------------------------------------------------
	   /** 
	    * Parses a CSV file into the TeamCal Pro database
	    * 
	    * @param string $defgroup Default user group to assign the imported users to
	    * @param string $deflang Default language to set for each imported user
	    * @param boolean $lock Flag indicating whether to lock the user accounts or not
	    * @param boolean $hide Flag indicating whether to hide the user accounts or not
	    * @return boolean Success indicator
	    */
	   function import($defgroup, $deflang, $lock=true, $hide=true) 
	   {
	      /**
	       * The expected columns are:
	       *  0          1           2          3       4          5       6        7       8          9      10
	       * "username";"firstname";"lastname";"title";"position";"phone";"mobile";"email";"idnumber";"dob";"showb"
	       */
	      global $CONF;
	      global $LANG;
	      require_once( $CONF['app_root']."models/log_model.php" );
	      require_once( $CONF['app_root']."models/login_model.php" );
	      require_once( $CONF['app_root']."models/user_model.php" );
	      require_once( $CONF['app_root']."models/user_group_model.php" );
	      require_once( $CONF['app_root']."models/user_option_model.php" );
	      $L = new Login_model;
	      $LOG = new Log_model;
	      $U = new User_model;
	      $UG = new User_group_model;
	      $UO = new User_option_model;
	      
	      $result=true;
	      $fpointer = fopen($this->file_name, "r");
	      
	      if ($fpointer) 
	      {
	         while ($arr = fgetcsv($fpointer, 10 * 1024, ";")) 
	         {
	            if (is_array($arr) && !empty ($arr)) 
	            {
	               if (count($arr)<>11) 
	               {
	                  $this->error = $LANG['uimp_err_col_1'].$arr[0].$LANG['uimp_err_col_2'].count($arr).$LANG['uimp_err_col_3'];
	                  unset ($arr);
	                  fclose($fpointer);
	                  $result=false;
	                  return;
	               }
	               else 
	               {
	                  if ( !$U->findByName(trim($arr[0])) AND $arr[0]!="admin" AND preg_match('/^[a-zA-Z0-9]*$/',$arr[0]) ) 
	                  { 
	                     $U->username = trim($arr[0]);              
	                     $U->password = crypt("password", $CONF['salt']);
	                     $U->firstname = $arr[1];              
	                     $U->lastname = $arr[2];              
	                     $U->title = $arr[3];              
	                     $U->position = $arr[4];              
	                     $U->phone = $arr[5];              
	                     $U->mobile = $arr[6];              
	                     $U->email = $arr[7];              
	                     $U->idnumber = $arr[8];
	                     $U->birthday = $arr[9];
	                     $U->clearUserType($CONF['UTADMIN']);
	                     $U->clearUserType($CONF['UTDIRECTOR']);
	                     $U->setUserType($CONF['UTMALE']);
	                     $U->setUserType($CONF['UTUSER']);
	                     $U->clearStatus($CONF['USLOCKED']);
	                     $U->clearStatus($CONF['USLOGLOC']);
	                     $U->clearStatus($CONF['USHIDDEN']);
	                     if ($lock) $U->setStatus($CONF['USLOCKED']);              
	                     if ($hide) $U->setStatus($CONF['USHIDDEN']);
	                     $U->notify = 0;
	                     $U->last_pw_change = date("Y-m-d H:i:s");
	                     $U->create();
	                     
	                     if ($defgroup != null && $defgroup != "") {
	                        $UG->createUserGroupEntry($U->username, $defgroup, "member");
	                     }
	                     
	                     $UO->create($U->username, "owngroupsonly", "no");
	                     if (strtolower($arr[10])=="yes" || strtolower($arr[10])=="1") {
	                        $UO->create($U->username, "showbirthday", "yes");
	                     }
	                     else {
	                        $UO->create($U->username, "showbirthday", "no");
	                     }
	                     $UO->create($U->username, "ignoreage", "no");
	                     $UO->create($U->username, "notifybirthday", "no");
	                     $UO->create($U->username, "language", $deflang);
	                     $UO->create($U->username, "defgroup", "All");
	                     
	                     $fullname = $U->firstname . " " . $U->lastname;
	                     $LOG->log("logUser", $L->checkLogin(), "log_csv_import", $U->username . " (" . $fullname . ")");
	                     $this->count_imported++;
	                  }
	                  else 
	                  {
	                     $this->count_skipped++;
	                  }
	               }            
	            }
	         }
	         unset ($arr);
	         fclose($fpointer);
	      }
	   }
   }
}
?>
