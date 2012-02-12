/**
 * V2Press global scripts.
 * Version: 0.0.1
 *
 * @since 0.0.2
 */

jQuery(document).ready(function(){
  
  // Hotkeys
  vpKeys = new HotKey();
  vpKeys.add( 'm', function(){ showMarkdownHelper(); });
  vpKeys.add( 'n', function(){ createNewTopic(); });
  vpKeys.add( 'b', function(){ bookmarkTopic(); });
  vpKeys.add( 'B', function(){ unBookmarkTopic(); });
  vpKeys.add( 'f', function(){ followUser(); });
  vpKeys.add( 'F', function(){ unFollowUser(); });
  vpKeys.add( 'h', function(){ showHotkeysHelper(); });
});

/* Reply to */
function replyTo( cmtId ) {
  var $respondArea = jQuery('#comment');
  var $parent = jQuery('#comment_parent');
  var $replyTo = jQuery( '.fn', '#comment-' + cmtId ).text();

  var mention = '@' + $replyTo + ' ';
  var oldContent = $respondArea.val();
  var newContent = '';

  if ('0' == $parent.val()) {
    $parent.val( cmtId );

    if ( oldContent.length > 0 ) {
      if ( oldContent != mention ) {
        newContent += mention + oldContent;
      } else {
        newContent += oldContent;
      }
    } else {
      newContent += mention;
    }

  } else {
    var caution = globalL10n.replyConfirm;
    if(confirm(caution)) {
      $parent.val(cmtId);
      newContent += mention;
    } else {
      newContent += oldContent;
    }
  }

  $respondArea.focus();
  $respondArea.val( newContent );
}

/* Facebox Settings */
jQuery.facebox.settings.loadingImage = globalL10n.stylesheetURI + '/images/loading.gif';
jQuery.facebox.settings.closeImage = globalL10n.stylesheetURI + '/images/facebox-close.png';

/* Show the Markdown helper block hotkey callback */
function showMarkdownHelper() {
  var $helper = jQuery('#markdown-helper');
  if ( 0 < $helper.length )
    jQuery("#facebox .cheatsheet:visible").length ? jQuery.facebox.close() : jQuery.facebox({div: "#markdown-helper"}, "cheatsheet");
}

/* Create new topic hotkey callback */
function createNewTopic() {
  var $link = jQuery('.create-new-topic-link>a');
  var $url;
  
  if ( $link.length > 0 ) {
    $url = $link[0].href;
  } else {
    $url = globalL10n.newTopicURL;
  }
  
  if ( $url != null )
    redirectTo( $url );
}

/* Bookmark topic hotkey callback */
function bookmarkTopic() {
  var $link = jQuery('.favorite-topic>.add');
  var $url;
  
  if ( $link.length > 0 )
    $url = $link[0].href;
    
  if ( $url != null )
    redirectTo( $url );
}

/* unBookmark topic hotkey callback */
function unBookmarkTopic() {
  var $link = jQuery('.favorite-topic>.remove');
  var $url;
  
  if ( $link.length > 0 )
    $url = $link[0].href;

  if ( $url != null )
    redirectTo( $url );
}

/* Follow user hotkey callback */
function followUser() {
  var $link = jQuery('.follow-user>.add');
  var $url;
  
  if ( $link.length > 0 )
    $url = $link[0].href;
    
  if ( $url != null )
    redirectTo( $url );
}

/* unFollow user hotkey callback */
function unFollowUser() {
  $url = jQuery('.follow-user>.remove')[0].href;
  if ( $url != null )
    redirectTo( $url );
}

/* Show hotkeys helper hotkey callback */
function showHotkeysHelper() {
  var $helper = jQuery('#hotkeys-helper');
  if ( 0 < $helper.length )
    jQuery("#facebox .hotkeys:visible").length ? jQuery.facebox.close() : jQuery.facebox({div: "#hotkeys-helper"}, "hotkeys");
}

/* Helper functions */
function redirectTo( href ) {
  /* fix IE */
	if ( jQuery.browser.msie ) {
	  var a = document.createElement('a');
	  a.style.display = 'none';
	  a.href = href;
	  document.body.appendChild(a);
	  a.click();
	} else {
	  location.href = href;
	}
}

/* from http://la.ma.la/blog/diary_200511041713.htm - hotkey.js */
function HotKey(element){
	this.target = element || document;
	this._keyfunc = {};
	this.init();
}
HotKey.kc2char = function(kc){
	var between = function(a,b){
		return a <= kc && kc <= b
	}
	var _32_40 = "space pageup pagedown end home left up right down".split(" ");
	var kt = {
		8  : "back",
		9  : "tab"  ,
		13 : "enter",
		16 : "shift",
		17 : "ctrl",
		46 : "delete"
	};
	return (
		between(65,90)  ? String.fromCharCode(kc+32) : // a-z
		between(48,57)  ? String.fromCharCode(kc) :    // 0-9
		between(96,105) ? String.fromCharCode(kc-48) : // num 0-9
		between(32,40)  ? _32_40[kc-32] :
		kt.hasOwnProperty(kc) ? kt[kc] :
		null
	)
}
HotKey.prototype.ignore = /input|textarea/i;
HotKey.prototype.init = function(){
	var self = this;
	var listener = function(e){
		self.onkeydown(e)
	};
	if(this.target.addEventListener){
		this.target.addEventListener("keydown", listener, true);
	}else{
		this.target.attachEvent("onkeydown", listener)
	}
}
HotKey.prototype.onkeydown = function(e){
	var tag = (e.target || e.srcElement).tagName;
	if(this.ignore.test(tag)) return;
	var input = HotKey.kc2char(e.keyCode);

	if(e.shiftKey && input.length == 1){
		input = input.toUpperCase()
	}
	if(e.altKey) input = "A" + input;
	if(e.ctrlKey) input = "C" + input;

	if(this._keyfunc.hasOwnProperty(input)){
		this._keyfunc[input].call(this,e)
	}
}
HotKey.prototype.sendKey = function(key){
	this._keyfunc[key] && this._keyfunc[key]()
}
HotKey.prototype.add = function(key,func){
	if(key.constructor == Array){
		for(var i=0;i<key.length;i++)
			this._keyfunc[key[i]] = func;
	}else{
		this._keyfunc[key] = func;
	}
}
HotKey.prototype.remove = function(key){
	if(key.constructor == Array){
		for(var i=0;i<key.length;i++)
			this._keyfunc[key[i]] = function () {};
	}else{
		this._keyfunc[key] = function () {};
	}
}