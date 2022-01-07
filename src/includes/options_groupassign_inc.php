<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_groupassign_inc.php
 *
 * Displays the options bar for the groupassign page
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

//echo "<script type=\"text/javascript\">alert(\"Debug: ".$_POST['optPeriod']."\");</script>";

?>
<!-- Search -->
&nbsp;&nbsp;<?=$LANG['user_search']?>&nbsp;
<input name="searchuser" id="searchuser" size="30" type="text" class="text" value="<?=$searchuser?>">
<input name="btn_usrSearch" type="submit" class="button" value="<?=$LANG['btn_search']?>">
<input name="btn_usrReset" type="submit" class="button" value="<?=$LANG['btn_reset']?>">
