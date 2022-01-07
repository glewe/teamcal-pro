<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * header_html_inc.php
 *
 * Included on each page holding the HTML header information.
 * You may not to alter or remove these header information nor their
 * corresponding $CONF variables. Giving credits is a matter of good
 * manners. However, you may add your own information to it if you like.
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--
===============================================================================
TEAMCAL PRO
___________________________________________________________________________

Application: <?=$CONF['app_name']." ".$CONF['app_version']."\n"?>
Date:        <?=$CONF['app_version_date']."\n"?>
Author:      <?=$CONF['app_author']."\n"?>
Copyright:   <?=$CONF['app_copyright_html']."\n"?>
             All rights reserved.
___________________________________________________________________________

<?php echo $CONF['app_license_html']; ?>

===============================================================================
-->
<?php
/**
 * Includes
 */
require_once( $CONF['app_root']."models/config_model.php" );
require_once( $CONF['app_root']."models/login_model.php" );
require_once( $CONF['app_root']."models/styles_model.php" );
require_once( $CONF['app_root']."models/user_option_model.php" );
$C = new Config_model;
$L = new Login_model;
$S = new Styles_model;
$UO = new User_option_model;

/**
 * HELP FILE
 * If there is a manual document in that language make it the default help
 * file. If not take the english one:
 */
if (file_exists($CONF['app_root'] . "manual/" . $CONF['options']['lang'] . ".manual.php")) {
   $CONF['help_file'] = $CONF['options']['lang'] . ".manual.php";
}
else {
   $CONF['help_file'] = "english.manual.php";
}

/**
 * Select the theme to use
 */
if ($thisuser=$L->checkLogin()) {
   /**
    * A user is logged in
    */
   if ($C->readConfig("allowUserTheme")) {
      /**
       * User theme selection is allowed. If none is found set it to 'default'
       * and load default theme
       */
      if (!$theme=$UO->find($thisuser,"deftheme")) {
         $UO->create($thisuser,"deftheme","default");
         $theme = $C->readConfig("theme");
      }
      else {
         /**
          * If user theme selection is set it to 'default', use it.
          */
         if ($theme=="default") $theme = $C->readConfig("theme");
      }
   }
   else {
      /**
       * User theme selection not allowed. Use default theme
       */
      $theme = $C->readConfig("theme");
   }
}
else {
   /**
    * No user logged in. Use default theme
    */
   $theme = $C->readConfig("theme");
}

/**
 * If by now nothing is in $theme set it to 'tcpro'
 */
if (!strlen($theme)) {
   $theme="tcpro";
   $C->saveConfig("theme","tcpro");
}
if (!$S->getStyle($theme)) createCSS($theme);
?>
<html>
   <head>
      <title><?=$CONF['app_name'].' '.$CONF['html_title']?></title>
      <meta http-equiv="Pragma" content="no-cache">
      <meta http-equiv="Cache-Control" content="no-cache, must-revalidate, max_age=0">
      <meta http-equiv="Expires" content="0">
      <meta http-equiv="Content-type" content="text/html;charset=<?=($mycharset=$C->readConfig("charset"))?$mycharset:"UTF-8";?>">
      <meta http-equiv="Content-Style-Type" content="text/css">
      <meta name="copyright" content="<?=$CONF['app_copyright_html']?>">
      <meta name="keywords" content="Lewe TeamCal Pro">
      <meta name="description" content="Lewe TeamCal Pro calendar">
      <script type="text/javascript" src="javascript/tcpro.js"></script>
      <script type="text/javascript" src="javascript/ajax.js"></script>
      <script type="text/javascript" src="javascript/JSCookMenu.js"></script>
      <script type="text/javascript" src="javascript/JSCookMenu/ThemeOffice/theme.js"></script>
   <?php if ($C->readConfig("jQueryCDN")) { ?>
   <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css">
   <?php } else { ?>
   <script type="text/javascript" src="javascript/jQuery/jquery-1.9.1.js"></script>
      <script type="text/javascript" src="javascript/jQuery/jquery-ui-1.10.1.custom.min.js"></script>
      <link rel="stylesheet" href="javascript/jQuery/themes/<?=$C->readConfig("jqtheme")?>/jquery-ui.css">
   <?php } ?>
   <link rel="stylesheet" media="screen" type="text/css" href="javascript/colorpicker/css/colorpicker.css">
      <script type="text/javascript" src="javascript/colorpicker/js/colorpicker.js"></script>
      <script type="text/javascript" src="javascript/tipsy/jquery.tipsy.js"></script>
      <link rel="shortcut icon" href="themes/<?=$theme?>/img/favicon.ico">
      <link type="text/css" rel="stylesheet" href="themes/<?=$theme?>/css/menu.css">
      
<!--
===============================================================================
This following stylesheet was created automatically and saved to/read from the database.
If you want to change styles, edit the core stylesheet file
"themes/<?php print $theme;?>/default.css"
Then navigate to the TeamCal Configuration page and click [Apply]. Applying the configuration
will always rebuild the stylesheet in the database based on the core file.
-->
<style type="text/css" media="screen">
<?php print $S->getStyle($theme);?>
</style>

<!--
===============================================================================
This following stylesheet was created automatically and saved to/read from the database.
If you want to change styles, edit the core stylesheet file
"themes/<?php print $theme;?>/default.css"
Then navigate to the TeamCal Configuration page and click [Apply]. Applying the configuration
will always rebuild the stylesheet in the database based on the core file.
-->
<style type="text/css" media="print">
<?php print $S->getStyle($theme."_print");?>
</style>

      <!-- jQuery Tooltip -->
      <script type="text/javascript">$(function() { $( document ).tooltip({ position: { my: "center bottom-20", at: "center top", using: function( position, feedback ) { $( this ).css( position ); $( "<div>" ) .addClass( "arrow" ) .addClass( feedback.vertical ) .addClass( feedback.horizontal ) .appendTo( this ); } } }); });</script>

   </head>
   
   <body>

