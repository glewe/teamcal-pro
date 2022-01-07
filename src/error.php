<?php
/**
 * error.php
 *
 * Displays an error message
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

/**
 * Includes
 */
require_once ("config.tcpro.php");
require_once ("helpers/global_helper.php");
getOptions();
require_once ("languages/".$CONF['options']['lang'].".tcpro.php");

?>
<div id="content">
   <div id="content-content">
      <table class="dlg">
          <tr>
              <td class="err-header"><?=$LANG['err_title']?></td>
          </tr>
          <tr>
              <td class="err-body">
                 <p class="erraction"><?=$err_short?></p>
                 <p class="errortext"><?=$err_long?></p>
                 <?php if (!$L->checkLogin()) { ?>
                 <p class="errortext">
                    <?=$LANG['err_not_authorized_login']?><br>
                    <br>
                    <input name="btn_login" type="button" class="button" onclick="window.location.href='login.php?target=<?=substr($err_module, strrpos($err_module, '/') + 1);?>';" value="<?=$LANG['btn_login']?>">
                 </p>
                 <?php } ?>
                 <br>
                 <hr size="1">
                 <p><span class="module">Module: <?=$err_module?></span></p>
              </td>
          </tr>
         <tr>
           <td class="dlg-menu" style="text-align: left;">
              <?php if ($err_btn_close) { ?>
              <input name="btn_close" type="button" class="button" onclick="javascript:window.close();" value="<?=$LANG['btn_close']?>">
              <?php } ?>
           </td>
         </tr>
      </table>
   </div>
</div>
