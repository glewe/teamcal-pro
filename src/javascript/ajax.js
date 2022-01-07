/* ============================================================================
 * AJAX.JS
 * ----------------------------------------------------------------------------
 * Application: TeamCal Pro
 * Author:      George Lewe
 * Copyright:   (c) 2004-2007 by George Lewe (www.lewe.com)
 *              All rights reserved.
 * ----------------------------------------------------------------------------
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the 
 * Free Software Foundation. A copy has been distributed with TeamCal Pro
 * named gpl.txt.
 * 
 * This program is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License 
 * for more details (http://www.gnu.org)
 * ============================================================================
 */

/* ============================================================================
 * getXMLHttpRequest(where) 
 * Initialize Ajax, create xmlHttp object
 * where = DOM object ID where the output goes
 */
function getXMLHttpRequest(where) {
   var xmlHttp;
   try {
      /*
       * Real browsers: Firefox, Opera 8.0+, Safari
       */
      xmlHttp=new XMLHttpRequest();
   }
   catch (e) {
      /*
       * Bad browser: IE
       */
      try {
         xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
      }
      catch (e) {
         try {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
         }
         catch (e) {
            alert("Your browser does not support AJAX!");
            return false;
         }
      }
   }   
   xmlHttp.output=where;
   return xmlHttp;
}

/* ============================================================================
 * stateChanged(obj)
 * ----------------------------------------------------------------------------
 * Callback function for xmlHttp object
 * ----------------------------------------------------------------------------
 * obj    = xmlHttp object
 */
function stateChanged(obj) {
   document.getElementById(obj.output).innerHTML="<img src=\"img/ajax-loader.gif\" alt=\"\" title=\"Processing...\">";
   if (obj.readyState == 4) {
      document.getElementById(obj.output).innerHTML=xmlHttp.responseText;
      delete obj;
   }
}

/* ============================================================================
 * ajaxCheckPath()
 * ----------------------------------------------------------------------------
 * Used from installation.php
 * ----------------------------------------------------------------------------
 * reldir = relativ dir to server root
 * url    = URL
 * where  = DOM object ID where the output goes
 */
function ajaxCheckPath(reldir, url, where) {

   var url="helpers/ajax_checkpath_helper.php?reldir="+reldir+"&url="+url;

   xmlHttp=getXMLHttpRequest(where);
   if (xmlHttp==false) {
      alert("No Ajax possible!");
      return;
   }
   if (xmlHttp.overrideMimeType) { xmlHttp.overrideMimeType('text/xml'); }

   xmlHttp.callback = function () { stateChanged(xmlHttp); };
   xmlHttp.onreadystatechange = xmlHttp.callback;
   xmlHttp.open("POST", url, true);
   xmlHttp.send(null);
}

/* ============================================================================
 * ajaxCheckDB()
 * ----------------------------------------------------------------------------
 * Used from installation.php
 * ----------------------------------------------------------------------------
 * server = mySQL server name
 * user   = mySQL user name
 * pass   = mySQL password
 * where  = DOM object ID where the output goes
 */
function ajaxCheckDB(server, user, pass, db, prefix, where) {

   var url="helpers/ajax_checkdb_helper.php?server="+server+"&user="+user+"&pass="+pass+"&db="+db+"&prefix="+prefix;

   xmlHttp=getXMLHttpRequest(where);
   if (xmlHttp==false) {
      alert("No Ajax possible!");
      return;
   }
   if (xmlHttp.overrideMimeType) { xmlHttp.overrideMimeType('text/xml'); }

   xmlHttp.callback = function () { stateChanged(xmlHttp); };
   xmlHttp.onreadystatechange = xmlHttp.callback;
   xmlHttp.open("POST", url, true);
   xmlHttp.send(null);
}
