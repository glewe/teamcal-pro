// ===========================================================================
//  TCPRO.JS
//  --------------------------------------------------------------------------
//  Application: TeamCal Pro
//  Purpose:     JavaScript Routines for TeamCal Pro
//  Author:      George Lewe
//  Copyright:   (c) 2004 by Lewe dataVisual, Germany
//               All rights reserved.
//
// ===========================================================================


//  --------------------------------------------------------------------------
//  Opens a popup browser window
//
function openPopup(page,winname,param)
{
   myPopup = window.open(page,winname,param);
}

//  --------------------------------------------------------------------------
//  Closes a popup browser window after reloading the opener window
//
function closeme()
{
   opener.location.reload(true);
   self.close();
}

//  --------------------------------------------------------------------------
//  Opens a confirmation dialog
//
function confirmSubmit(text)
{
   var agree=confirm(text);
   if (agree)
      return true ;
   else
      return false ;
}

//  --------------------------------------------------------------------------
//  Opens an information dialog
//
function infoPopup(text)
{
   alert(text);
}

//--------------------------------------------------------------------------
// Show Fast Edit
//
//
function toggleFastEdit(year, month, day, users) {

   for (var i=0; i<users.length; i++) {
      var viewDiv = document.getElementById("view-"+users[i]+"_"+year+"_"+month+"_"+day);
      var editDiv = document.getElementById("edit-"+users[i]+"_"+year+"_"+month+"_"+day);
      if (viewDiv.style.display=='block') {
         viewDiv.style.display = 'none';
         editDiv.style.display = 'block';
      }
      else {
         viewDiv.style.display = 'block';
         editDiv.style.display = 'none';
      }
   }   
}

//  --------------------------------------------------------------------------
//  Show / Hide a div section
//
//  <img id="divid.img" class="noprint" alt="Toggle" title="Toggle section..." src="hide_section.gif" border="0" onclick="togglediv('divid');">
//  <div id="divid" style="display: block;">
//     This will be hidden/collapsed
//   </div>
//
//  Note: <div> must be properly placed
//
var divToggle = true;
function togglediv(myDiv) {
   var section = document.getElementById(myDiv);
   var button = document.getElementById(myDiv+".img");
   if (section.style.display == 'block') {
      divToggle = false;
      section.style.display = 'none';
      button.src='themes/tcpro/img/show_section.gif';
      button.title='Expand section...';
   }
   else {
      if (section.style.display == 'none') {
         divToggle = true;
         section.style.display = 'block';
         button.src='themes/tcpro/img/hide_section.gif';
         button.title='Collapse section...';
      }
   }
}

//  --------------------------------------------------------------------------
//  Show / Hide table rows
//
//  <img id="trid.img" class="noprint" alt="Toggle" title="Toggle section..." src="hide_section.gif" border="0" onclick="toggletr('trid',#rows);">
//  <table>
//     <tr id="trid-1">
//        <td>This row be hidden/collapsed</td>
//    </tr>
//     <tr id="trid-2">
//        <td>This row be hidden/collapsed</td>
//    </tr>
//    ...
//  </table>
//
function toggletr(trID,rows) {
   var button = document.getElementById(trID+".img");
   var row = document.getElementById(trID+"-1");
   if (row.style.display == '') {
      button.src='themes/tcpro/img/show_section.gif';
      button.title='Expand section...';
   }
   else {
      button.src='themes/tcpro/img/hide_section.gif';
      button.title='Hide section...';
   }
   for (var i=1;i<=rows;i++) {
      var row = document.getElementById(trID+"-"+i.toString());
      if (row.style.display == '') row.style.display = 'none';
      else row.style.display = '';
   }
}

//  --------------------------------------------------------------------------
//  Enable/Disable a group entry on the Edit Profile page
//
function toggleGrp(GroupName) {
   var membership = eval("document.forms.userprofile.X"+GroupName+".checked");
   var ismember   = eval("document.forms.userprofile.M"+GroupName+"[0]");
   var ismanager  = eval("document.forms.userprofile.M"+GroupName+"[1]");
   if (membership) {
      ismember.checked=true;
      ismember.disabled=false;
      ismanager.disabled=false;
   }else{
      ismember.checked=false;
      ismanager.checked=false;
      ismember.disabled=true;
      ismanager.disabled=true;
   }
}

//  --------------------------------------------------------------------------
//  Marks a changed radio button set
//
function changedRadio(e) {
   document.getElementById(e).value = "true";
}
