<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * db_model.php
 * 
 * Interface to the TeamCal Pro database
 *
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

/**
 * Make sure the class hasn't been loaded yet
 */
if (!class_exists("Db_model")) 
{
   /**
    * Interface to the TeamCal Pro database
    * @package TeamCalPro
    */
   class Db_model 
   {
      var $db_type = '';
      var $db_server = '';
      var $db_name = '';
      var $db_user = '';
      var $db_pass = '';
      var $db_persistent = '';
      var $db_errortxt = '';
      static $db_handle = '';
      static $query_cache = array();
      
      // ---------------------------------------------------------------------
      /**
       * Constructor reading server and database information from the
       * configuration file. 
       */
      function Db_model() 
      {
         global $CONF;
         $this->db_type = $CONF['db_type'];
         $this->db_server = $CONF['db_server'];
         $this->db_name = $CONF['db_name'];
         $this->db_user = $CONF['db_user'];
         $this->db_pass = $CONF['db_pass'];
         $this->db_persistent = $CONF['db_persistent'];
         $this->db_connect();
      }

      // ---------------------------------------------------------------------
      /**
       * Connects to the database server and to the database
       */
      function db_connect() 
      {
         if (!Db_model::$db_handle) 
         {
            switch ($this->db_type) 
            {
               case 1 : // MySQL
               if ($this->db_persistent) 
               {
                  Db_model::$db_handle = @ mysql_pconnect($this->db_server, $this->db_user, $this->db_pass);
               } 
               else 
               {
                  Db_model::$db_handle = @ mysql_connect($this->db_server, $this->db_user, $this->db_pass);
               }
               if (!Db_model::$db_handle) 
               {
                  $errtxt = "Connecting to mySQL server " . $this->db_server . " failed.";
                  $this->db_error($errtxt, "db_connect()", true);
                  return;
               }
               if (!@ mysql_select_db($this->db_name, Db_model::$db_handle)) 
               {
                  $errtxt = "
                  <p>Error: Connection to MySQL database " . $this->db_server . "/" . $this->db_name . " failed.</p>\n
                  <p>Code: " . @ mysql_errno(Db_model::$db_handle) . "</p>\n
                  <p>Message: " . @ mysql_error(Db_model::$db_handle) . "</p>\n";
                  $this->db_error($errtxt, "db_connect()", true);
                  return;
               }
               
               //
               // Allow big selects
               //
               $result = mysql_query("SET SQL_BIG_SELECTS=1", Db_model::$db_handle);
               if (!$result)
               {
                  $errtxt = "<p>Error: A problem was encountered while executing this query:</p>\n<p><pre>SET SQL_BIG_SELECTS=1</pre></p>\n";
                  $this->db_error($errtxt, "db_connect()", true);
               }
               
               /*
                * The following code will make sure the database knows that all is UTF8.
                * The problem is that for existing TCP installations that will upgrade to this
                * release/code will see all their special characters look weird in TCP. They
                * would have to re-edit and save them in TCP.
                * Decision whether to activate this code or not is pending.
                *
               //
               // Tell database to be UTF8
               //
               $db_version = mysql_get_server_info();
               if (intval(substr($db_version, 0, 1))<5)
               {
                  //
                  // Database version below 5
                  //
                  $result = mysql_query("SET NAMES UTF8", Db_model::$db_handle);
                  if (!$result)
                  {
                     $errtxt = "<p>Error: A problem was encountered while executing this query:</p>\n<p><pre>SET NAMES UTF8</pre></p>\n";
                     $this->db_error($errtxt, "db_connect()", true);
                  }                  
               }
               else 
               {
                  if (!mysql_set_charset('utf8', Db_model::$db_handle)) 
                  {
                     $errtxt = "<p>Error: A problem was encountered while setting the character set to UTF8</p>\n<p><pre>mysql_set_charset('utf8')</pre></p>";
                     $this->db_error($errtxt, "db_connect()", true);
                  }
               }
               */
               
               break;
            }
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Executes a query on the database
       * 
       * @param string $query String containing the query to be executed. Will be initialized to empty if not passed
       * @return integer Result of the query
       */
      function db_query($query = '') 
      {
         switch ($this->db_type) 
         {
            case 1 : // MySQL
            $upp_query = strtoupper($query);
            if (strpos($upp_query, 'UPDATE') || strpos($upp_query, 'INSERT') || strpos($upp_query, 'DELETE') || strpos($upp_query, 'TRUNCATE')) 
            {
               // We are changing the database so throw away the cache
               Db_model::$query_cache = array();               
            }
            if (!array_key_exists($query, Db_model::$query_cache)) 
            {
               $result = mysql_query($query, Db_model::$db_handle);
               if (!$result) 
               {
                  $errtxt = "<p>Error: A problem was encountered while executing this query:</p>\n<p><pre>".$query."</pre></p>\n";
                  $this->db_error($errtxt, "db_query(), Line 125", true);
               }
               Db_model::$query_cache[$query] = $result;
            } 
            else 
            {
               $result = Db_model::$query_cache[$query]; 
               if (!$result) 
               {
                  $errtxt = "<p>Error: A problem was encountered while executing this query:</p>\n<p><pre>".$query."</pre></p>\n";
                  $this->db_error($errtxt, "db_query(), Line 135", true);
               }
               else 
               { 
                  if (is_resource($result)) 
                  {
                     if (mysql_num_rows($result) > 0) 
                     {
                        mysql_data_seek($result, 0);
                     }
                  }
               }
            }
            break;
         }
         return $result;
      }

      // ---------------------------------------------------------------------
      /**
       * Returns the number of records based on the result of a query
       * 
       * @param integer $result Result of the query 
       * @return integer Number of records matching the query
       */
      function db_numrows($result) 
      {
         switch ($this->db_type) 
         {
            case 1 : // MySQL
            return mysql_num_rows($result);
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Returns an array containing the matching records of a query
       * 
       * @param integer $result Result of the query 
       * @param integer $type  MYSQL_ASSOC, MYSQL_NUM or MYSQL_BOTH, defining the type of index for the returned array 
       * @return array Array of records matching the query
       */
      function db_fetch_array(& $result, $type = MYSQL_BOTH) 
      {
         switch ($this->db_type) 
         {
            case 1 : // MySQL
            return mysql_fetch_array($result, $type);
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Sending an error message to the browser
       * 
       * @param string $errtxt Error text to display 
       * @param string $func Name of the method in which this error ocurred
       * @param boolean $die Switch whether to die with this error or to procede after displayed
       */
      function db_error($errtxt, $func, $die, $statement="") 
      {
         $this->db_errortxt = "
         <p style=\"background: #990000; color: #ffffff; font-weight: bold; padding: 8px;\">TeamCal Pro Database Error</p>\n
         <p><span style=\"font-weight: bold;\">Module: </span>db_model.php</p>\n
         <p><span style=\"font-weight: bold;\">Class: </span>Db_model</p>\n
         <p><span style=\"font-weight: bold;\">Function: </span>".$func."</p>\n
         <p><span style=\"font-weight: bold;\">Error: </span>".$errtxt."</p>\n";

         switch ($this->db_type)
         {
            case 1 : // MySQL
               $this->db_errortxt .= "<p><span style=\"font-weight: bold;\">SQL Error: </span><pre>". @ mysql_error(Db_model::$db_handle) . "</pre></p>\n";
               break;
         }
         
         if ($die)
         {
            $this->db_errortxt .= "<p><span style=\"color: #dd0000; font-weight: bold;\">Execution halted</p>\n";
            die($this->db_errortxt);
         }
         else
         {
            echo $this->db_errortxt;
         }
      }
   }
}
?>
