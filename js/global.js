/**
 * V2Press global scripts.
 * Version: 0.0.1
 *
 * @since 0.0.2
 */

jQuery(document).ready(function(){
  vpKeys = new HotKey();
  vpKeys.add( 'm', function(){ showMarkdownHelper(); });
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
jQuery.facebox.settings.loadingImage = 'wp-content/themes/v2press/images/loading.gif';
jQuery.facebox.settings.closeImage = 'wp-content/themes/v2press/images/facebox-close.png';

/* Show the Markdown helper block */
function showMarkdownHelper() {
  var $helper = jQuery('#markdown-helper');
  if ( 0 < $helper.length )
    jQuery("#facebox .cheatsheet:visible").length ? jQuery.facebox.close() : jQuery.facebox({div: "#markdown-helper"}, "cheatsheet");
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