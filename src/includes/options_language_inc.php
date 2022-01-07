<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * options_language_inc.php
 *
 * Displays the language drop down list in the options bar
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */
?>
<!-- Language Drop Down -->
<?=$LANG['nav_language']?>&nbsp;
<select id="user_lang" name="user_lang" class="select">
<?php
$selectedLang=$CONF['options']['lang'];
$array = getLanguages();
foreach( $array as $langfile ) { ?>
   <option value="<?=$langfile?>" <?=($langfile==$selectedLang)?' SELECTED':''?>><?=$langfile?></option>
<?php } ?>
</select>
