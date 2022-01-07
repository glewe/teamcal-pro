<?php
/**
 * installation.php
 *
 * Installation page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe
 * @copyright Copyright (c) 2004-2007 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: \");</script>";

/**
 * Reads a value out of config.tcpro.php
 *
 * @param string $var Parameter name to read
 * @param string $file File to scan (defaults to config.default.php)
 * @return string Value
 */
function readConfig($var='',$file='config.default.php') {
   $value="";
   $handle = fopen($file,"r");
   if ($handle) {
      while (!feof($handle)) {
         $buffer = fgets($handle, 4096);
         if (strpos($buffer, "'".$var."'")==6) {
            $pos1=strpos($buffer,'"');
            $pos2=strrpos($buffer,'"');
            $value=trim(substr($buffer,$pos1+1,$pos2-($pos1+1)));
            //echo $buffer."|$equalpos|$colonpos<br>";
            //echo $value."<br>";
         }
      }
      fclose($handle);
   }
   return $value;
}

/**
 * Writes a value into config.tcpro.php
 *
 * @param string $var Parameter name to read
 * @param string $newvalue New value for the parameter
 */
function writeConfig($var='',$newvalue='') {
   $newbuffer="";
   $handle = fopen("config.tcpro.php","r");
   if ($handle) {
      while (!feof($handle)) {
         $buffer = fgets($handle, 4096);
         if (strpos($buffer, "'".$var."'")==6) {
            $pos1=strpos($buffer,'"');
            $pos2=strrpos($buffer,'"');
            $newbuffer.=substr_replace($buffer,$newvalue."\"",$pos1+1,$pos2-($pos1));
            //echo "======> ".$newbuffer."<br>";
         }
         else {
            $newbuffer.=$buffer;
         }
      }
      fclose($handle);
      $handle = fopen("config.tcpro.php","w");
      fwrite($handle,$newbuffer);
      fclose($handle);
   }
}

/**
 * Text messages
 */
$LANG = array (
   'btn_install'              => 'Install',
   'btn_test'                 => 'Test',
   'btn_delete'               => 'Delete',
   'btn_help'                 => 'Help',
   'inst_title'               => 'TeamCal Pro Installation',
   'inst_conf_exists'         => 'Configuration Exists',
   'inst_conf_exists_title'   => 'Configuration Exists',
   'inst_conf_exists_comment' => 'You already have an existing configuration file "config.tcpro.php" '.
                                 'in your TeamCal Pro directory. If you run this installation again it will ' .
                                 'be overwritten. Also, if you use the same database settings as your ' .
                                 'current installation, the database will be ovewritten as well.',
   'inst_reg_globals'         => 'Register Globals',
   'inst_reg_globals_title'   => 'Register Globals',
   'inst_reg_globals_comment' => 'TeamCal has found that your PHP environment variable \'register_globals\' is set to \'on\'. ' .
                                 'It is highly recommended to switch this setting off since it represents a security weakness. ' .
                                 'If you are managing your webserver yourself edit your PHP.INI file and look for the setting ' .
                                 '\'register_globals=On\'. Change this line to \'register_globals=Off\'. ' .
                                 'If you have no access to the PHP.INI file create a file named \'.htaccess\' in your TeamCal ' .
                                 'directory containing the line \'php_value register_globals 0\'. If that leads to an internal ' .
                                 'server error try creating a file called PHP.INI in your TeamCal directory containing the line ' .
                                 '\'register_globals=off\'.',
   'inst_application'         => 'Application Settings',
   'inst_app_reldir'          => 'Application Root Directory',
   'inst_app_reldir_comment'  => 'Specify the absolute directory of your TeamCal Pro copy relative to your server root directoy, '.
                                 'including a leading and trailing slash (e.g. /tcpro/).',
   'inst_app_url'             => 'Application URL',
   'inst_app_url_comment'     => 'Specify the unique resource locator (URL) of your TeamCal Pro copy (e.g. http://www.lewe.com/tcpro).',
   'inst_database'            => 'Database Settings',
   'inst_db_server'           => 'Database Server',
   'inst_db_server_comment'   => 'Specify the URL of the database server.',
   'inst_db_name'             => 'Database Name',
   'inst_db_name_comment'     => 'Specify the name of the database.',
   'inst_db_user'             => 'Database User',
   'inst_db_user_comment'     => 'Specify the username to log in to your database.',
   'inst_db_password'         => 'Database Password',
   'inst_db_password_comment' => 'Specify the password to log in to your database.',
   'inst_db_prefix'           => 'Database Table Prefix',
   'inst_db_prefix_comment'   => 'Specify a prefix for your TeamCal Pro database tables.',
   'inst_data'                => 'Sample Data',
   'inst_db_data'             => 'Sample data',
   'inst_db_data_comment'     => 'Check whether you want a set of sample data loaded or not. Select "Use existing data" if your database '.
                                 'already exists and you want to use the existing data. <br>Attention! "Use existing data" only works if your existing '.
                                 'data set is compatible with the version you are installing. Find details in Upgradeinfo.txt.',
   'inst_db_data_sample'      => 'Sample data',
   'inst_db_data_empty'       => 'Basic data only',
   'inst_db_data_none'        => 'Use existing data',
   'inst_lic'                 => 'License Agreement',
   'inst_lic_title'           => 'License Agreement',
   'inst_lic_comment'         => 'TeamCal Pro is a free open source application. However, if you want to use it you mast accept the '.
                                 'license agreements.',
   'inst_lic_gnu'             => 'I accept the General Pulic License',
   'inst_lic_tcpro'           => 'I accept the TeamCal Pro License',
   'inst_lic_error'           => 'Before you can start the installation you must accept both licenses!',
   'inst_inst'                => 'Start Installation'
);


/**
 * Try to find out where we are
 */
$pos1=strpos($_SERVER['SCRIPT_NAME'],'/');
$pos2=strrpos($_SERVER['SCRIPT_NAME'],'/');
$appRoot=substr($_SERVER['SCRIPT_NAME'],0,$pos2-($pos1-1 ));
$appURL="http://".$_SERVER['HTTP_HOST']."/".substr($appRoot,1,strlen($appRoot)-2);


/**
 * Install
 */
if ( isset($_POST['btn_install']) ) {

   if ( isset($_POST['chkLicGpl']) && isset($_POST['chkLicTcpro']) ) {
      /**
       * Write values to config.tcpro.php
       */
      if (file_exists("config.tcpro.php")) unlink("config.tcpro.php");
      copy("config.default.php","config.tcpro.php");
      writeConfig("app_root",stripslashes($_POST['txt_instAppRelDir']));
      writeConfig("app_url",stripslashes($_POST['txt_instAppURL']));
      writeConfig("db_server",stripslashes($_POST['txt_instDbServer']));
      writeConfig("db_name",stripslashes($_POST['txt_instDbName']));
      writeConfig("db_user",stripslashes($_POST['txt_instDbUser']));
      writeConfig("db_pass",stripslashes($_POST['txt_instDbPassword']));
      writeConfig("db_table_prefix",stripslashes($_POST['txt_instDbPrefix']));

      /**
       * Connect to database
       */
      $dberror=false;
      if (!$db_handle = @mysql_connect(stripslashes($_POST['txt_instDbServer']), $_POST['txt_instDbUser'], $_POST['txt_instDbPassword'])) {
         echo "<script type=\"text/javascript\">alert(\"ERROR: Couldn't connect to database server.\");</script>";
         $dberror=true;
      }
      if (!@mysql_select_db($_POST['txt_instDbName'], $db_handle)) {
         echo "<script type=\"text/javascript\">alert(\"ERROR: Couldn't select database.\");</script>";
         $dberror=true;
      }

      /**
       * Load sample data
       */
      if (!$dberror) {
         $file_content = "";
         if ($_POST['opt_data']=="sample")
            $file_content = file("sql/sample.sql");
         else
            $file_content = file("sql/empty.sql");

         if ($file_content) {
            $file_content = str_replace("my_",stripslashes($_POST['txt_instDbPrefix']),$file_content);
            $query = "";
            foreach($file_content as $sql_line) {
               $tsl = trim($sql_line);
               if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
                  $query .= $sql_line;
                  if(preg_match("/;\s*$/", $sql_line)) {
                     $result = mysql_query($query);
                     if (!$result) die("Database error: ".mysql_error());
                     $query = "";
                  }
               }
            }
         }
         header( 'Location: index.php' );
      }
   }
   else {
      echo "<script type=\"text/javascript\">alert(\"".$LANG['inst_lic_error']."\");</script>";
   }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>Lewe TeamCal Pro Installation</title>
   <meta http-equiv="Content-type" content="text/html;charset=iso-8859-1">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <script type="text/javascript" src="javascript/tcpro.js"></script>
   <script type="text/javascript" src="javascript/ajax.js"></script>
   <link type="text/css" rel="stylesheet" href="themes/tcpro/css/default.css">
</head>
<body>

   <table class="header">
       <tr>
           <td class="header-left"><img src="themes/tcpro/img/logo.gif" width="264" height="55" alt=""></td>
           <td class="header-right"></td>
       </tr>
       <tr>
           <td class="header-subtitle" colspan="2">Version <?=htmlspecialchars(readConfig('app_version','config.version.php'));?></td>
       </tr>
   </table>
   <br>

   <table width="100%">
      <tr>
         <td valign="top">

            <form class="form" name="form-config" method="POST" action="<?=$_SERVER['PHP_SELF']?>">

            <table class="dlg">
               <tr>
                  <td class="dlg-header">
                     <table style="border-collapse: collapse; border: 0px; width: 100%;">
                        <tr>
                           <td style="font-size: 9pt;"><img src="themes/tcpro/img/ico_installation.png" alt="" width="16" height="16" align="top">&nbsp;<?=$LANG['inst_title']?></td>
                        </tr>
                     </table>
                  </td>
                  <td class="dlg-header" style="text-align: right;">
                     <div align="right">
                        <a href="javascript:window.open('<?=readConfig('app_help_root','config.version.php')?>Installation').void();">
                        <img title="Installation Help..." align="middle" alt="" src="themes/tcpro/img/ico_help.png" width="16" height="16" border="0"></a>
                     </div>
                  </td>
               </tr>

               <!-- CONFIG CHECK -->
               <?php if (file_exists('config.tcpro.php')) { ?>
                  <?php $style="2"; ?>
                  <tr>
                     <td class="dlg-caption-red" colspan="2" style="text-align: left;"><?=$LANG['inst_conf_exists']?></td>
                  </tr>

                  <!-- Configuration Exists -->
                  <?php if ($style=="1") $style="2"; else $style="1"; ?>
                  <tr>
                     <td colspan="2" class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                        <span class="config-key"><?=$LANG['inst_conf_exists_title']?></span><br>
                        <span class="config-comment-red"><?=$LANG['inst_conf_exists_comment']?></span>
                     </td>
                  </tr>
               <?php } ?>

               <!-- REGISTER GLOBALS -->
               <?php if (ini_get('register_globals')) { ?>
                  <?php $style="2"; ?>
                  <tr>
                     <td class="dlg-caption-red" colspan="2" style="text-align: left;"><?=$LANG['inst_reg_globals']?></td>
                  </tr>

                  <!-- Register Globals -->
                  <?php if ($style=="1") $style="2"; else $style="1"; ?>
                  <tr>
                     <td colspan="2" class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                        <span class="config-key"><?=$LANG['inst_reg_globals_title']?></span><br>
                        <span class="config-comment-red"><?=$LANG['inst_reg_globals_comment']?></span>
                     </td>
                  </tr>
               <?php } ?>

               <!-- APPLICATION -->
               <?php $style="2"; ?>
               <tr>
                  <td class="dlg-caption" colspan="2" style="text-align: left;"><?=$LANG['inst_application']?></td>
               </tr>

               <!-- Relative Directory -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_app_reldir']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_app_reldir_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <input class="text" name="txt_instAppRelDir" id="txt_instAppRelDir" type="text" size="50" value="<?=$appRoot?>">
                  </td>
               </tr>

               <!-- URL -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_app_url']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_app_url_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <input class="text" name="txt_instAppURL" id="txt_instAppURL" type="text" size="50" value="<?=$appURL?>">
                  </td>
               </tr>

               <tr>
                  <td class="dlg-menu" style="text-align: left;">
                     <script type="text/javascript">
                     function checkPath(){
                        var myAppRelDir = document.getElementById('txt_instAppRelDir');
                        var myAppURL = document.getElementById('txt_instAppURL');
                        ajaxCheckPath(myAppRelDir.value, myAppURL.value, 'app_result');
                     }
                     </script>
                     <input name="btn_test_path" type="button" class="button" style="width: 100px;" value="<?=$LANG['btn_test']?>" onclick="checkPath()">
                  </td>
                  <td class="dlg-menu" style="text-align: left; font-size: 8pt;">
                     <span id="app_result"></span>
                  </td>
               </tr>

               <!-- DATABASE -->
               <?php $style="2"; ?>
               <tr>
                  <td class="dlg-caption" colspan="2" style="text-align: left;"><?=$LANG['inst_database']?></td>
               </tr>

               <!-- DB Server -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_db_server']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_db_server_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <input class="text" name="txt_instDbServer" id="txt_instDbServer" type="text" size="50" value="<?=htmlspecialchars(readConfig('db_server'));?>">
                  </td>
               </tr>

               <!-- DB Name -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_db_name']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_db_name_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <input class="text" name="txt_instDbName" id="txt_instDbName" type="text" size="50" value="<?=htmlspecialchars(readConfig('db_name'));?>">
                  </td>
               </tr>

               <!-- DB User -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_db_user']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_db_user_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <input class="text" name="txt_instDbUser" id="txt_instDbUser" type="text" size="50" value="<?=htmlspecialchars(readConfig('db_user'));?>">
                  </td>
               </tr>

               <!-- DB Password -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_db_password']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_db_password_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <input class="text" name="txt_instDbPassword" id="txt_instDbPassword" type="text" size="50" value="<?=htmlspecialchars(readConfig('db_pass'));?>">
                  </td>
               </tr>

               <!-- DB TablePrefix -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_db_prefix']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_db_prefix_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <input class="text" name="txt_instDbPrefix" id="txt_instDbPrefix" type="text" size="50" value="<?=htmlspecialchars(readConfig('db_table_prefix'));?>">
                  </td>
               </tr>

               <tr>
                  <td class="dlg-menu" style="text-align: left;">
                     <script type="text/javascript">
                     function checkDB(){
                        var myDbServer = document.getElementById('txt_instDbServer');
                        var myDbUser = document.getElementById('txt_instDbUser');
                        var myDbPass = document.getElementById('txt_instDbPassword');
                        var myDbName = document.getElementById('txt_instDbName');
                        var myDbPrefix = document.getElementById('txt_instDbPrefix');
                        ajaxCheckDB(myDbServer.value, myDbUser.value, myDbPass.value, myDbName.value, myDbPrefix.value, 'checkDbOutput');
                     }
                     </script>
                     <input name="btn_test_db" type="button" class="button" style="width: 100px;" value="<?=$LANG['btn_test']?>" onclick="checkDB()">
                  </td>
                  <td class="dlg-menu" style="text-align: left; font-size: 8pt;">
                     <span id="checkDbOutput"></span>
                  </td>
               </tr>

               <!-- SAMPLE DATA -->
               <?php $style="2"; ?>
               <tr>
                  <td class="dlg-caption" colspan="2" style="text-align: left;"><?=$LANG['inst_data']?></td>
               </tr>

               <!-- Sample data -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_db_data']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_db_data_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <table>
                        <tr><td><input name="opt_data" type="radio" value="sample" CHECKED></td><td style="vertical-align: bottom;"><?=$LANG['inst_db_data_sample']?></td></tr>
                        <tr><td><input name="opt_data" type="radio" value="empty"></td><td style="vertical-align: bottom;"><?=$LANG['inst_db_data_empty']?></td></tr>
                        <tr><td><input name="opt_data" type="radio" value="none"></td><td style="vertical-align: bottom;"><?=$LANG['inst_db_data_none']?></td></tr>
                     </table>
                  </td>
               </tr>

               <!-- LICENSE -->
               <?php $style="2"; ?>
               <tr>
                  <td class="dlg-caption" colspan="2" style="text-align: left;"><?=$LANG['inst_lic']?></td>
               </tr>

               <!-- License Agreement -->
               <?php if ($style=="1") $style="2"; else $style="1"; ?>
               <tr>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 60%;">
                     <span class="config-key"><?=$LANG['inst_lic_title']?></span><br>
                     <span class="config-comment"><?=$LANG['inst_lic_comment']?></span>
                  </td>
                  <td class="config-row<?=$style?>" style="text-align: left; width: 40%;">
                     <table>
                        <tr>
                           <td><input name="chkLicGpl" id="chkLicGpl" type="checkbox" value="chkLicGpl"></td>
                           <td style="vertical-align: middle;"><?=$LANG['inst_lic_gnu']?>&nbsp;<input class="button" type="button" onclick="javascript:window.open('<?=readConfig('app_help_root','config.version.php')?>TeamCal+Pro+License').void();" value="View"></td>
                        </tr>
                        <tr>
                           <td><input name="chkLicTcpro" id="chkLicTcpro" type="checkbox" value="chkLicTcpro"></td>
                           <td style="vertical-align: middle;"><?=$LANG['inst_lic_tcpro']?>&nbsp;<input class="button" type="button" onclick="javascript:window.open('<?=readConfig('app_help_root','config.version.php')?>TeamCal+Pro+License').void();" value="View"></td>
                        </tr>
                     </table>
                  </td>
               </tr>

               <!-- INSTALL -->
               <?php $style="2"; ?>
               <tr>
                  <td class="dlg-caption" colspan="2" style="text-align: left;"><?=$LANG['inst_inst']?></td>
               </tr>

               <tr>
                  <td class="dlg-menu" colspan="2" style="text-align: left;">
                     <script type="text/javascript">
                     function checkLicense(){
                        var myButton = document.getElementById('btn_install');
                        var myLicGpl = document.getElementById('chkLicGpl');
                        var myLicTcpro = document.getElementById('chkLicTcpro');
                        if ( !myLicGpl.checked || !myLicTcpro.checked ) {
                           alert("<?=$LANG['inst_lic_error']?>");
                        }
                     }
                     </script>
                     <input name="btn_install" id="btn_install" type="submit" class="button" style="width: 100px;" value="<?=$LANG['btn_install']?>" onmouseover="checkLicense();">&nbsp;
                     <input name="btn_help" type="button" class="button" onclick="javascript:window.open('<?=readConfig('app_help_root','config.version.php')?>Installation').void();" value="<?=$LANG['btn_help']?>">
                  </td>
               </tr>

            </table>
            </form>
         </td>
      </tr>
   </table>

   <?php
   $ver  = readConfig('app_version','config.version.php');
   $year = readConfig('app_year','config.version.php');
   ?>
   <hr size="1">
   <div align="center">
      <span class="copyright">
         Copyright &copy; <?=$year?> by <a href="http://www.lewe.com" class="copyright" target="_blank">Lewe.com</a>.<br>
         Powered by TeamCal Pro <?=$ver?> &copy; 2004-<?=$year?> by <a href="http://www.lewe.com" class="copyright" target="_blank">George Lewe</a><br>
         <a href="http://validator.w3.org/check?uri=referer"><img border="0" src="img/valid-html401.gif" alt="Valid HTML 4.01!" title="Valid HTML 4.01!" height="15" width="80"></a>&nbsp;
         <a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:80px;height:15px" src="img/valid-css.gif" alt="Valid CSS!" title="Valid CSS!"></a>
      </span>
   </div>

</body>
</html>
