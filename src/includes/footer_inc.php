<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * footer_inc.php
 *
 * Included by every page to displays the HTML footer
 *
 * @package TeamCalPro
 * @version 3.6.020
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */

/**
 * Includes
 */
unset($CONF);
require ("config.tcpro.php");
?>


<!-- FOOTER =============================================================== -->
<div id="footer">
   <div id="footer-content">
      <span class="copyright">
         <?=html_entity_decode($C->readConfig("appFooterCpy"))?><br>
         <?=$CONF['app_footer_pwd']?><br>
         <a href="http://validator.w3.org/check?uri=referer"><img class="noprint" border="0" src="img/valid-html401.gif" alt="Valid HTML 4.01!" title="Valid HTML 4.01!"></a>&nbsp;
         <a href="http://jigsaw.w3.org/css-validator/"><img class="noprint" style="border:0;width:80px;height:15px" src="img/valid-css.gif" alt="Valid CSS!" title="Valid CSS!"></a>
      </span>
   </div>
</div>
<?php if ($C->readConfig("googleAnalytics") AND $C->readConfig("googleAnalyticsID")) { ?>
<!-- Google Analytics -->
<script type="text/javascript">
   var _gaq = _gaq || [];
   _gaq.push(['_setAccount', '<?=$C->readConfig("googleAnalyticsID")?>']);
   _gaq.push(['_trackPageview']);

   (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
   })();
</script>
<?php } ?>

</body>
</html>
