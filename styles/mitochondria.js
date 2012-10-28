 function avastAvatars() {
	if (document.bleenform.avastavatars.checked) {
		setActiveStyleSheet("nullAvatars");
	}
	else {
		setActiveStyleSheet("default");
	}
 }

 // alistapart stuff
 function setActiveStyleSheet(title) {
	var i, a, main;
	for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
		if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
		a.disabled = true;
			if(a.getAttribute("title") == title) a.disabled = false;
		}
	}
 }

 function getActiveStyleSheet() {
	var i, a;
	for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
		if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) return a.getAttribute("title");
	}
	return null;
 }

 function getPreferredStyleSheet() {
	var i, a;
	for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
		if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("rel").indexOf("alt") == -1 && a.getAttribute("title")) return a.getAttribute("title");
	}
	return null;
 }

 function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
 }

 function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
 }

 window.onload = function(e) {
	var cookie = readCookie("style");
	var title = cookie ? cookie : getPreferredStyleSheet();
	setActiveStyleSheet(title);
 }

 window.onunload = function(e) {
	var title = getActiveStyleSheet();
	createCookie("style", title, 365);
 }

 var cookie = readCookie("style");
 var title = cookie ? cookie : getPreferredStyleSheet();
 setActiveStyleSheet(title);

 // pilfered from: megnut, stuffeddog, massless, placenamehere
 // http://www.massless.org/mozedit/ (no longer online i guess)
 // http://placenamehere.com/photographica/js_textareas.html
 // modified for 1142

 function mouseover(el) { el.className = "raised"; }
 function mouseout(el)  { el.className = "raised";  }
 function mousedown(el) { el.className = "pressed"; }
 function mouseup(el)   { el.className = "raised"; }

 function tagit(text1, text2) {
	// grab the textarea off the dom tree
	var ta = document.frmComment.txtComment;
		
	if (document.selection) { //IE win
	// code ripped/modified from Meg Hourihan 
	// http://www.oreillynet.com/pub/a/javascript/2001/12/21/js_toolbar.html
	
		var str = document.selection.createRange().text;
		ta.focus();
		var sel = document.selection.createRange();
		sel.text = text1 + str + text2;
	} else if (ta.selectionStart | ta.selectionStart == 0) {
	// Mozzzzzzila relies on builds post bug #88049
	// work around Mozilla Bug #190382

		if (ta.selectionEnd > ta.value.length) { ta.selectionEnd = ta.value.length; }
		// decide where to add it and then add it

			var firstPos = ta.selectionStart;
			var secondPos = ta.selectionEnd+text1.length;
                	// cause we're inserting one at a time

			ta.value=ta.value.slice(0,firstPos)+text1+ta.value.slice(firstPos);
			ta.value=ta.value.slice(0,secondPos)+text2+ta.value.slice(secondPos);
			// reset selection & focus... after the first tag and before the second 

			ta.selectionStart = firstPos+text1.length;
			ta.selectionEnd = secondPos;
			ta.focus();
		}	
	}

 function tagger(action) {
	// decide what you're addding

	var startTag = "";
	var endTag = "";
	switch (action) {
		case "b":	
			startTag = "<b>";
			endTag = "<\/b>";
			break;
		case "i":	
			startTag = "<i>";
			endTag = "<\/i>";
			break;

		case "a":
			var mysite = prompt("Please enter the site you'd like to link", "http://");
			var mytitle = prompt("Please enter the title for the link", mysite);
			startTag = "<a href=\"" + mysite + "\" title=\"" + mytitle + "\">";
			endTag = "<\/a>";
			break;
		}

	tagit(startTag,endTag);
	return false;
 }