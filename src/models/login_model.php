<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * login_model.php
 * 
 * Contains the class dealing with login functions
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
if (!class_exists("Login_model")) 
{
   require_once ("models/db_model.php");

   /**
    * Provides objects and methods to manage login activities
    * @package TeamCalPro
    */
   class Login_model 
   {
      var $user = '';
      var $salt = '';
      var $bad_logins = 0;
      var $grace_period = 0;
      var $min_pw_length = 0;
      var $pw_strength = 0;
      var $php_self = '';
      var $log = '';
      var $logtype = '';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Login_model() 
      {
         global $_POST;
         global $_SERVER;
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         require_once ($CONF['app_root'] . "models/config_model.php");
         require_once ($CONF['app_root'] . "models/user_option_model.php");

         $this->C = new Config_model;
         $this->db = new Db_model;
         $this->U = new User_model;
         $this->UO = new User_option_model;
         $this->salt = $CONF['salt'];
         $this->bad_logins = intval($this->C->readConfig("badLogins"));
         $this->grace_period = intval($this->C->readConfig("gracePeriod"));
         $this->min_pw_length = intval($this->C->readConfig("pwdLength"));
         $this->pw_strength = intval($this->C->readConfig("pwdStrength"));
         $this->php_self = $_SERVER['PHP_SELF'];
         $this->log = $CONF['db_table_log'];
      }

      // ---------------------------------------------------------------------
      /**
       * Checks the TeamCal cookie and if it exists and is valid and if the user
       * is logged in we get the user info from the database.
       * 
       * @return string Username of the user logged in, or emtpy
       */
      function checkLogin() 
      {
         global $CONF;

         /**
          * The following lines are added for backwards compatibility to PHP 4.1.0 or lower.
          */
         if (!isset ($_COOKIE)) 
         {
            global $HTTP_COOKIE_VARS;
            $_COOKIE = $HTTP_COOKIE_VARS;
         }

         /**
          * If the cookie is set, look up the username in the database
          */    
         if (isset ($_COOKIE['teamcal'])) 
         {
            //echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie is set\")</script>");
            $array = explode(":", $_COOKIE['teamcal']);
            //echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie array[0]=".$array[0]."\\nCookie array[1]=".$array[1]."\")</script>");
            if (!isset ($array[1])) $array[1] = '';
            if (crypt($array[0], $this->salt) === $array[1]) 
            {
               $this->U->findByName($array[0]);
               return $this->U->username;
            } 
            else {
               return false;
            }
         } 
         else {
            //echo ("<script type=\"text/javascript\">alert(\"[checkLogin]\\nCookie is NOT set\")</script>");
            return false;
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Based on the global config parameter 'pw_strength' Passwords must be:
       *  -min_pw_length long
       *  -can't match username forward or backward
       *  -mixed case
       *  -have 1 number
       *  -have 1 punctuation char
       *
       * @param string $uname Username trying to log in
       * @param string $pw Current password
       * @param string $pwnew1 New password
       * @param string $pwnew2 Repeated new password
       * @return string Empty if ok, or error message abot what went wrong
       */
      function passwordCheck($uname='', $pw='', $pwnew1='', $pwnew2='') 
      {
         global $LANG;
         
         if (!isset ($this->pw_strength)) $this->pw_strength = 0;
         $rstr = '';

         if (empty ($uname)) $rstr .= $LANG['pwchk_username'];
         if (empty ($pwnew1) || empty ($pwnew2)) $rstr .= $LANG['pwchk_confirm'];
         if ($pwnew1 != $pwnew2) $rstr .= $LANG['pwchk_mismatch'];

         /**
          * MINIMUM STRENGTH
          */
         if (strlen($pwnew1) < $this->min_pw_length) $rstr .= $LANG['pwchk_minlength'];

         if ($this->pw_strength > 0) 
         {
            /**
             * LOW STRENGTH
             * = anything allowed if min_pw_length and new<>old
             *
             * convert the password to lower case and strip out the 
             * common number for letter substitutions    
             * then lowercase the username as well.
             */
            if (strlen($pw)) $pw_lower = strtolower($pw);
            $pwnew1_lower = strtolower($pwnew1);
            $pwnew1_denum = strtr($pwnew1_lower, '5301!', 'seoll');
            $uname_lower = strtolower($uname);

            if (ereg($uname_lower, $pwnew1_denum)) $rstr .= $LANG['pwchk_notusername'];
            if (ereg(strrev($uname_lower), $pwnew1_denum)) $rstr .= $LANG['pwchk_notusername_backwards'];
            if (strlen($pw) AND ($pwnew1_lower == $pw_lower)) $rstr .= $LANG['pwchk_notold'];
            
            if ($this->pw_strength > 1) 
            {
               /**
                * MEDIUM STRENGTH
                */
               if (!ereg('[0-9]', $pwnew1)) $rstr .= $LANG['pwchk_number'];
                
               if ($this->pw_strength > 2) {
                  /**
                   * HIGH STRENGTH
                   */
                  if (!ereg('[a-z]', $pwnew1)) $rstr .= $LANG['pwchk_lower'];
                  if (!ereg('[A-Z]', $pwnew1)) $rstr .= $LANG['pwchk_upper'];
                  if (!ereg('[^a-zA-Z0-9]', $pwnew1)) $rstr .= $LANG['pwchk_punctuation'];
               }
            }
         }
         return $rstr;
      }

      // ---------------------------------------------------------------------
      /**
       * Returns the current password rules
       * @return string The current password rules
       */
      function pwRules() 
      {
         switch ($this->pw_strength) 
         {
            case 0 :
               $pws = "minimum";
               break;
            case 1 :
               $pws = "low";
               break;
            case 2 :
               $pws = "medium";
               break;
            case 3 :
               $pws = "maximum";
               break;
         }

         $errors = "<b>The Password \"level\" of TeamCal Pro is set to " . $pws . "</b>.<br>Passwords must be at least " . $this->min_pw_length . " characters long and a new password cannot be the same as the old one.";

         if ($this->pw_strength > 0) $errors .= "<br>The password cannot contain the username forward or backward. Also you can't use the numbers '53011' for the letters 'seoll'";
         if ($this->pw_strength > 1) $errors .= "The password must also contain at least one number";
         if ($this->pw_strength > 2) $errors .= "and it must contain one UPPER and one lower case letter and one punctuation character";
         if ($this->pw_strength > 0) $errors .= ".<br>";

         return $errors;
      }
      
      // ---------------------------------------------------------------------
      /**
       * LDAP authentication
       * (Thanks to Aleksandr Babenko for the original code.)
       * 
       * !!! Beta 2 mode !!! Use at own risk.
       *
       * retcode =   0 : successful LDAP authentication
       * retcode =  91 : password missing
       * retcode =  92 : LDAP user bind failed
       * retcode =  93 : Unable to connect to LDAP server
       * retcode =  94 : STARTTLS failed
       * retcode =  95 : No uid found
       * retcode =  96 : LDAP search bind failed
       * 
       * @param string $uidpass LDAP password
       * @return integer Authentication return code
       */
      function ldapVerify($uidpass) 
      { 
         global $CONF;
         
         $ldaprdn  = $CONF['LDAP_DIT'];
         $ldappass = $CONF['LDAP_PASS'];
         $ldaptls  = $CONF['LDAP_TLS'];
         $host = $CONF['LDAP_HOST'];
         $port = $CONF['LDAP_PORT'];
         $attr = array("dn", "uid"); //attributes to return
         $searchbase = $CONF['LDAP_SBASE'];
        
         /**
          * Force Fail on NULL password
          */
         if (!$uidpass) return 91;
        
         $ds=ldap_connect($host, $port);                      // Is always a ressource even if no connection possible (patch by Franz Gregor)
         ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);  // Use v3 when possible
         if (!@ldap_bind($ds)) return 93;                     // Test anonymous bind => Unable to connect to LDAP server (patch by Franz Gregor)
         if ($ldaptls && !ldap_start_tls($ds)) return 94;
         if (!@ldap_bind($ds,$ldaprdn,$ldappass)) return 96;  // (patch by Franz Gregor)

         /**
          * Search for user UID
          */
         if ($CONF['LDAP_ADS'])
         { 
            if (!$info = ldap_first_entry($ds, ldap_search($ds, $searchbase, "sAMAccountName=".$this->U->username, $attr))) return 95;
         }
         else
         { 
            if (!$info = ldap_first_entry($ds, ldap_search($ds, $searchbase, "uid=".$this->U->username, $attr))) return 95;
         }

         /**
          * Now authenticate the user using the user dn
          */
         $uiddn = ldap_get_dn($ds, $info);
         $ldapbind = ldap_bind($ds, $uiddn, $uidpass);
          
         /**
          * Close LDAP connection
          */
         ldap_close($ds);
       
         if ($ldapbind) 
            return 0;
         else 
            return 92; 
      }
      
      // ---------------------------------------------------------------------
      /**
       * TCPRO Authentication
       * Refactored local-database authentication method
       *
       * Return Codes
       * retcode =  0 : successful login
       * retcode =  4 : first bad login
       * retcode =  5 : second/higher bad login
       * retcode =  6 : too many bad logins
       * retcode =  7 : bad password
       *
       * @param string password
       * @return integer authentication return code
       */
      function tcproVerify($password) 
      {
         global $CONF;
         
         if (crypt($password, $this->salt) == $this->U->password) return 0; // Password correct
         if ($this->bad_logins == 0) return 7; // if we don't need to enumerate/manage bad logins, just return "bad password"
         
         if (!$this->U->bad_logins) 
         {
            /**
             * 1st bad login attempt, set the counter = 1 
             * Set the timestamp to seconds since UNIX epoch (makes checking grace period easy)
             */
            $this->U->bad_logins = 1;
            $this->U->bad_logins_start = date("U");
            $retcode = 4;
         } 
         elseif (++$this->U->bad_logins >= $this->bad_logins) 
         {
            /**
             * That's too much! I've had it now with your bad logins.
             * Login locked for grace period of time.
             */
            $this->U->bad_logins_start = date("U");
            $this->U->setStatus($CONF['USLOGLOC']);
            $retcode = 6;
         } 
         else 
         {
            /**
             * 2nd or higher bad login attempt
             */
            $retcode = 5;
         }
         $this->U->update($this->U->username);
         return $retcode;
      }
      
      // ---------------------------------------------------------------------
      /**
       * Login. Checks the login credentials and sets cookie 'teamcal' if accepted
       *
       * Return Codes
       * retcode =   0 : Success
       * retcode =   1 : Username and/or password missing
       * retcode =   2 : User not found
       * retcode =   3 : Account locked
       * retcode =   4 : Password incorrect 1st time
       * retcode =   5 : Password incorrect 2nd time or more
       * retcode =   6 : Login disabled and still in grace period
       * retcode =   7 : Password incorrect (no bad login count)
       * retcode =   8 : Account not verified
       * retcode =  91 : LDAP error: password missing
       * retcode =  92 : LDAP error: bind failed
       * retcode =  93 : LDAP error: unable to connect
       * retcode =  94 : LDAP error: Start of TLS encryption failed
       * retcode =  95 : LDAP error: Username not found
       * retcode =  96 : LDAP error: Search bind failed
       * 
       * @param string $loginname Username
       * @param string $loginpwd Password
       * @return integer Login return code
       */
      function login($loginname='', $loginpwd='') 
      {
         global $CONF;

         $logged_in = 0;
         $showForm = 0;
         $retcode = 0;
         $bad_logins_now = 0;

         if (empty($loginname) OR empty($loginpwd)) return 1;
         
         $now = date("U");
         
         if (!$this->U->findByName($loginname)) return 2; // User not found. If found U->username is now set.
         if ( $this->U->checkStatus($CONF['USLOCKED']) ) return 3; // Account is locked or not approved
         if ( $this->UO->find($loginname,"verifycode") ) return 8; // Account not verified.
         if ( $this->U->checkStatus($CONF['USLOGLOC']) AND ($now - $this->U->bad_logins_start <= $this->grace_period) ) return 6; // Login is locked for this account and grace period is not over yet.

         /**
          * At this point we know that USLOGLOC is not set anyways
          * or the grace period is over. We can safely unset it.
          */
         $this->U->clearStatus($CONF['USLOGLOC']);
         $this->U->update($this->U->username);

         /**
          * Now check the password
          */
         if ($CONF['LDAP_YES'] && $loginname!="admin" ) 
         {
            /**
             * You need to have PHP LDAP libraries installed.
             * 
             * The admin user is always logged in against the local database.
             * In case the LDAP does not work an admin login must still be possible.
             */
            $retcode = $this->ldapVerify($loginpwd);
         }
         else  
         {
            /**
             * Otherwise use TCPRO authentication
             */
            $retcode = $this->tcproVerify($loginpwd);
         } 
         if ($retcode != 0) return $retcode;

         /**
          * Successful login!
          * Set up the tc cookie and save the uname so TeamCal can get it.
          */
         $secret = crypt($loginname, $this->salt);
         $value = $loginname . ":" . $secret;
         setcookie("teamcal", "");
         setcookie("teamcal", $value, time() + intval($this->C->readConfig("cookieLifetime")));
         $this->U->bad_logins = 0; // Reset the "bad login" counter.
         $this->U->bad_logins_start = "";
         $this->U->clearStatus($CONF['USLOGLOC']);
         $this->U->last_login = date("YmdHis");
         $this->U->update($this->U->username);
         return 0;
      }

      // ---------------------------------------------------------------------
      /**
       * Logs the current user out and clears the 'teamcal' cookie
       */
      function logout() 
      {
         /**
          * The following lines are added for backwards compatibility for 
          * pre 4.1.0 PHP versions
          */
         if (!isset ($_COOKIE)) 
         {
            global $HTTP_COOKIE_VARS;
            $_COOKIE = $HTTP_COOKIE_VARS;
         }

         setcookie('teamcal', '');
         setcookie("teamcal", "", time() - intval($this->C->readConfig("cookieLifetime")));
         /**
          * If the 'teamcal' cookie is set and not empty clear it to log out
          */
         if (isset ($_COOKIE['teamcal'])) 
         {
            if ($_COOKIE['teamcal'] != "") 
            {
               // $array = explode(":", $_COOKIE['teamcal']);
               setcookie("teamcal", "", time() - intval($this->C->readConfig("cookieLifetime")));
               //if ( $this->loglevel && ULLOGIN ){
               //    $this->user->findByName($array[0]);
               //    $this->user->log ('',"<b>Login</b>: User ".$this->user->username." logged out");
               //}
            }
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Displays the "not logged in" error message
       */
      function notLoggedIn() 
      {
         echo "<html><head></head><body>You're not logged in to TeamCal Pro.</body></html>";
      }

      // ---------------------------------------------------------------------
      /**
       * Displays the "not provileged" error message
       */
      function notPrivleged() 
      {
         echo "<html><head></head><body>You do not have sufficient rights to perform this operation.</body></html>";
      }
   }
}
?>
