<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * statistic_model.php
 *
 * Contains the class dealing with the statistics display
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
if (!class_exists("Statistic_model")) 
{
   /**
    * Object and methods to manage the statics display
    * @package TeamCalPro
    */
   class Statistic_model 
   {
      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Statistic_model() 
      {
         global $CONF;
         global $LANG;
         unset($CONF);
         require ("config.tcpro.php");
      }

      // ---------------------------------------------------------------------
      /**
       * Draws a horizontal bar graph
       *
       * @param array $legend Array holding the legend names for each value
       * @param array $value Array holding the corresponding values
       * @param integer $width Width of main table
       * @param integer $gwidth Width of graph table within main table
       * @param string $header Title text
       * @param string $footer Footer text
       * @return string HTML text of the graph
       */
      function barGraphH($legend, $value, $width, $gwidth, $barcolor, $header='', $footer='') 
      {
         global $theme;
         $str = '';
         $str1 = '';
         $str2 = '';
         $str3 = '';

         switch ($barcolor)
         {
            case 'red':
               $top='ff0000'; $bottom='dd0000';
               break;
               
            case 'green':
               $top='00dd00'; $bottom='00aa00';
               break;
            
            case 'blue':
               $top='0000ff'; $bottom='0000cc';
               break;
            
            case 'gray':
               $top='dddddd'; $bottom='aaaaaa';
               break;
            
            case 'orange':
               $top='ffbb00'; $bottom='cc8800';
               break;
            
            case 'cyan':
               $top='00dddd'; $bottom='00aaaa';
               break;
            
            default:
               $top='dddddd'; $bottom='aaaaaa';
               break;
         }
         
         $bcolor = 'background-image: -ms-linear-gradient(top, #'.$top.' 0%, #'.$bottom.' 100%); 
                    background-image: -moz-linear-gradient(top, #'.$top.' 0%, #'.$bottom.' 100%); 
                    background-image: -o-linear-gradient(top, #'.$top.' 0%, #'.$bottom.' 100%); 
                    background-image: -webkit-linear-gradient(top, #'.$top.' 0%, #'.$bottom.' 100%); 
                    background-image: linear-gradient(top, #'.$top.' 0%, #'.$bottom.' 100%);';

         /**
          * Initialize the styles
          */
         $style_table_main = "background-color:#E4E1DC; border: 1px #606680 solid; border-collapse: collapse; margin: 0px; padding: 0px; width: ".$width."px;";
         $style_td_main_header = "font-weight: bold; height: 30px; padding-left: 4px; padding-right: 4px; text-align: center; vertical-align: middle; width: 100%; white-space: nowrap; ";
         $style_td_main_footer = "font-size: 10px; color: #666666; padding-left: 4px; padding-right: 4px; text-align: center; vertical-align: middle; width: 100%; white-space: nowrap; ";
         $style_td_main_graph = "text-align: center; width: 100%; ";
         $style_td_main_bottom = "height: 10px; ";
         $style_table_graph = "background-color:#FFFFFF; background-image: url(themes/".$theme."/img/bg_graphHTable.png); border-collapse: collapse; margin: 0px; padding: 0px; width: ".$gwidth."px;";
         $style_td_legend = "background-color:#E4E1DC; color: #000000; font-size: 10px; padding: 4px; text-align: right; vertical-align: middle; white-space: nowrap;";
         $style_td_legend_mark = "background-color:#E4E1DC; color: #000000; border-top: 1px solid #000000; width: 4px;";
         $style_td_bar_bg = "color: #000000; border-left: 1px solid #000000; padding-top:4px; padding-bottom:4px; text-align: left; vertical-align: middle; width: 100%";
         $style_table_bar = "border-collapse: collapse; margin: 0px; padding: 0px; ";
         $style_td_bar = $bcolor." border: 1px solid #999999; color: #000000; text-align: center; vertical-align: middle; width: 100%; ";
         $style_td_x_axis = "background-color:#E4E1DC; color: #000000; border-top: 1px solid #000000; text-align: right; vertical-align: middle; ";
         $style_table_x_axis = "border-collapse: collapse; margin: 0px; padding: 0px; width: 100%;";
         $style_td_x_legend = "background-color:#E4E1DC; color: #000000; font-size: 10px; text-align: right; vertical-align: top; width: 10%; ";
         
         /**
          * Count the counts
          */
         $cnt_legend = count($legend);
         $cnt_value = count($value);
         $cnt_max = !empty ($legend) ? min($cnt_legend, $cnt_value) : $cnt_value;
         
         /**
          * Find the max value
          */
         for ($count=0, $max_value=0; $count<$cnt_max; $max_value=max($value[$count], $max_value), $count++);
         
         /**
          * Compute the width of one virtual unit
          */
         $unit_width = !empty ($max_value) ? (0.92*($gwidth/$max_value)) : 0;
         
         /**
          * Begin the main table
          */
         $str =
         "\n\n\n" .
         "<!-- HORIZONTAL GRAPH: " . $header . " -->" .
         "\n" .
         "<table style=\"".$style_table_main."\">\n" .
         "   <tr>\n" .
         "      <td style=\"".$style_td_main_header."\">".$header."</td>\n" .
         "   </tr>\n" .
         "   <tr>\n" .
         "      <td style=\"".$style_td_main_graph."\"><div align=\"center\">\n" .
         "         <table style=\"".$style_table_graph."\">\n"
         ;
         
         /**
          * Now draw one table row for each legend/value set
          */
         for ($count = 0; $count < $cnt_max; $count++) {
            $bar_width = round($value[$count] * $unit_width);
            if ($value[$count]) {
               // Value greater 0
               $number = ($value[$count]>=1000) ? substr(($tmp=$value[$count]/1000),0,(strpos($tmp,".")+2))."k" : $value[$count];
               $str .=
               "            <tr>\n" .
               "               <td style=\"".$style_td_legend."\">".$legend[$count]."</td>\n" .
               "               <td style=\"".$style_td_bar_bg."\">\n" .
               "                  <table style=\"".$style_table_bar." width: ".$bar_width."px;\">\n" .
               "                     <tr>\n" .
               "                        <td style=\"".$style_td_bar."\">" . $number . "</td>\n" .
               "                     </tr>\n" .
               "                  </table>\n" .
               "               </td>\n" .
               "            </tr>\n"
               ;
            }
            else {
               // Value 0
               $str .=
               "            <tr>\n" .
               "               <td style=\"".$style_td_legend."\">".$legend[$count]."</td>\n" .
               "               <td style=\"".$style_td_bar_bg."\">&nbsp;".$value[$count]."</td>\n" .
               "            </tr>\n"
               ;
            }
         }
         
         /**
          * Draw the x-axis
          */
         $str .=
         "            <tr>\n" .
         "               <td style=\"".$style_td_x_legend."\"></td>\n" .
         "               <td style=\"".$style_td_x_axis."\">";

         $str1 =
         "                  <table style=\"".$style_table_x_axis."\">\n" .
         "                     <tr>\n";

         $currvalue=0;
         for ($count=1; $count<=10; $count++) {
            if ( (round(($max_value/10)*$count))>$currvalue) {
               $str2 = "                        <td style=\"".$style_td_x_legend."\">".round(($max_value/10)*$count)."</td>\n";
               $currvalue = round(($max_value/10)*$count);
            }
         }

         $str3 =
         "                     </tr>\n" .
         "                  </table>\n";

         if (strlen($str2)) $str .= $str1.$str2.$str3;

         $str .=
         "               </td>\n" .
         "            </tr>\n"
         ;
         
         $str .=
         "         </table>\n" .
         "      </div></td>\n" .
         "   </tr>\n" .
         "   <tr>\n" .
         "      <td style=\"".$style_td_main_footer."\">".$footer."</td>\n" .
         "   </tr>\n" .
         "</table>\n" .
         "\n\n\n"
         ;

         return $str;
      }

      // ---------------------------------------------------------------------
      /**
       * Draws a vertical bar graph
       *
       * @param array $legend Array holding the legend names for each value
       * @param array $value Array holding the corresponding values
       * @param integer $height Height of main table
       * @param integer $gheight Height of graph table within main table
       * @param string $header Title text
       * @param string $footer Footer text
       * @param integer $width Width of main table in pixel
       * @return string HTML text of the graph
       */
      function barGraphV($legend, $value, $height, $gheight, $barcolor, $header='', $footer='', $width='') 
      {
         global $theme;
         
         switch ($barcolor)
         {
            case 'red':
               $left='ff0000'; $right='cc0000';
               break;
                
            case 'green':
               $left='00dd00'; $right='00aa00';
               break;
         
            case 'blue':
               $left='0000ff'; $right='0000cc';
               break;
         
            case 'gray':
               $left='dddddd'; $right='aaaaaa';
               break;
         
            case 'orange':
               $left='ffbb00'; $right='cc8800';
               break;
         
            case 'cyan':
               $left='00dddd'; $right='00aaaa';
               break;
         
            default:
               $left='dddddd'; $right='aaaaaa';
               break;
         }
          
         $bcolor = 'background-image: -ms-linear-gradient(left, #'.$left.' 0%, #'.$right.' 100%); 
                    background-image: -moz-linear-gradient(left, #'.$left.' 0%, #'.$right.' 100%); 
                    background-image: -o-linear-gradient(left, #'.$left.' 0%, #'.$right.' 100%); 
                    background-image: -webkit-linear-gradient(left, #'.$left.' 0%, #'.$right.' 100%); 
                    background-image: linear-gradient(left, #'.$left.' 0%, #'.$right.' 100%);';
         
         /**
          * Initialize the styles
          */
         if (!$width) $width="100%"; else $width.="px";
         $style_table_main = "background-color:#E4E1DC; border: 1px #606680 solid; border-collapse: collapse; margin: 0px; padding: 0px; height: ".$height."px; width: ".$width.";";
         $style_td_main_header = "font-weight: bold; height: 30px; padding-left: 4px; padding-right: 4px; text-align: center; vertical-align: middle; width: 100%; white-space: nowrap; ";
         $style_td_main_footer = "font-size: 10px; color: #666666; padding-left: 4px; padding-right: 4px; text-align: center; vertical-align: middle; width: 100%; white-space: nowrap;";
         $style_td_main_graph = "text-align: center; width: 100%; padding-left: 10px; padding-right: 10px; ";
         $style_td_main_bottom = "height: 10px; ";
         $style_table_graph = "background-color:#FFFFFF; background-image: url(themes/".$theme."/img/bg_graphVTable.png); border-collapse: collapse; margin: 0px; padding: 0px; height: ".$gheight."px; width: 100%; ";
         $style_td_legend = "background-color:#E4E1DC; color: #000000; font-size: 10px; padding: 4px; text-align: center; vertical-align: top; white-space: nowrap; ";
         $style_td_legend_mark = "background-color:#E4E1DC; color: #000000; border-top: 1px solid #000000; border-right: 1px solid #000000; height: 4px;";
         $style_td_bar_bg = "color: #000000; border-left: 1px solid #000000; padding-left:4px; padding-right:4px; text-align: center; vertical-align: bottom; height: 100%; ";
         $style_table_bar = "border-collapse: collapse; margin: 0px; padding: 0px; width: 90%;";
         $style_td_bar = $bcolor." border: 1px solid #999999; color: #000000; text-align: center; vertical-align: middle; width: 100%; ";
         $style_td_y_axis = "background-color:#E4E1DC; height: 100%; ";
         $style_table_y_axis = "border-collapse: collapse; margin: 0px; padding: 0px; height: 100%; width: 100%;";
         $style_td_y_legend = "background-color:#E4E1DC; color: #000000; font-size: 10px; text-align: center; vertical-align: top; height: 10%; ";
         /**
          * Count the counts
          */
         $cnt_legend = count($legend);
         $cnt_value = count($value);
         $cnt_max = !empty ($legend) ? min($cnt_legend, $cnt_value) : $cnt_value;
         /**
          * Find the max value
          */
         for ($count=0, $max_value=0; $count<$cnt_max; $max_value=max($value[$count], $max_value), $count++);
         /**
          * Compute the height of one virtual unit
          */
         $unit_height = !empty ($max_value) ? (0.92*($gheight/$max_value)) : 0;
         /**
          * Begin the main table
          */
         $str =
         "\n\n\n" .
         "<!-- VERTICAL GRAPH: " . $header . " -->" .
         "\n" .
         "<table style=\"".$style_table_main."\">\n" .
         "   <tr>\n" .
         "      <td style=\"".$style_td_main_header."\">".$header."</td>\n" .
         "   </tr>\n" .
         "   <tr>\n" .
         "      <td style=\"".$style_td_main_graph."\">\n" .
         "         <div align=\"center\">\n" .
         "         <table style=\"".$style_table_graph."\">\n" .
         "            <tr>\n"
         ;
         /**
          * The first column is a vertical legend
          */
         $str .=
         "               <td style=\"".$style_td_y_axis." width: ".(100/($cnt_max+1))."%;\">\n" .
         "                  <table style=\"".$style_table_y_axis."\">\n";
         for ($count=10; $count>0; $count--) {
            $str .=
            "                     <tr>\n" .
            "                        <td style=\"".$style_td_y_legend."\">" . round(($max_value/10)*$count) . "</td>\n" .
            "                     </tr>\n";
         }
         $str .=
         "                  </table>\n" .
         "               </td>\n"
         ;
         /**
          * Now draw one column containing a table for each legend/value set
          */
         for ($count = 0; $count < $cnt_max; $count++) {
            $bar_height = round($value[$count] * $unit_height);
            if ($value[$count]) {
               // Value greater 0
               $number = ($value[$count]>=1000) ? substr(($tmp=$value[$count]/1000),0,(strpos($tmp,".")+2))."k" : $value[$count];
               $str .=
               "               <td style=\"".$style_td_bar_bg." width: ".(100/($cnt_max+1))."%;\">\n" .
               "                  <div align=\"center\">\n" .
               "                  <table style=\"".$style_table_bar." height: ".$bar_height."px;\">\n" .
               "                     <tr>\n" .
               "                        <td style=\"".$style_td_bar."\">" . $number . "</td>\n" .
               "                     </tr>\n" .
               "                  </table>\n" .
               "                  </div>\n" .
               "               </td>\n"
               ;
            }
            else {
               // Value 0
               $str .=
               "               <td style=\"".$style_td_bar_bg." width: ".(100/($cnt_max+1))."%;\">0</td>\n";
            }
         }
         $str .= "            </tr>\n";
         
         /**
          * The legend line
          */
         $str .= "            <tr>\n";
         $str .= "               <td style=\"".$style_td_legend."\"></td>\n";
         for ($count = 0; $count < $cnt_max; $count++) {
            $str .= "               <td style=\"".$style_td_legend."\">".$legend[$count]."</td>\n";
         }
         $str .= "            </tr>\n";
         
         /**
          * End of main table
          */
         $str .=
         "         </table>\n" .
         "         </div>" .
         "      </td>\n" .
         "   </tr>\n" .
         "   <tr>\n" .
         "      <td style=\"".$style_td_main_footer."\">".$footer."</td>\n" .
         "   </tr>\n" .
         "</table>\n" .
         "\n\n\n"
         ;

         return $str;
      }
   }
}
?>
