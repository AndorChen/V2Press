function replyTo( cmtId ) {     
  var $respondArea = jQuery('#comment');
    
  var $replyTo = jQuery( '.fn', '#comment-' + cmtId ).text();  
    
  var mention = '@' + $replyTo + ' ';
  var oldContent = $respondArea.val();
  var newContent = '';
    
  if ( oldContent.length > 0 ) {
    if ( oldContent != mention ) {
      newContent += mention + oldContent;
    } else {
      newContent += oldContent;
    }
  } else {
    newContent += mention;
  }
    
  $respondArea.focus();
  $respondArea.val( newContent );
}