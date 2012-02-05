/**
 * jQuery.HTML5 placeholder - Placeholder plugin for input fields
 * Written by Ludo Helder (ludo DOT helder AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2010/11/18
 *
 * @author Ludo Helder
 * @version 1.0.1
 *
 **/
jQuery(function(){
  var d = "placeholder" in document.createElement("input");
  if (!d){ 
	  jQuery("input[placeholder]").each(function(){
		  jQuery(this).val(element.attr("placeholder")).addClass('placeholder');
	  }).bind('focus',function(){
		  if (jQuery(this).val() == element.attr('placeholder')){
			  jQuery(this).val('').removeClass('placeholder');
		  }
	  }).bind('blur',function(){
		  if (jQuery(this).val() == ''){
			  jQuery(this).val(element.attr("placeholder")).addClass('placeholder');
		  }
	  });
  }
});