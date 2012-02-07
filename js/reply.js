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
    var caution = 'One reply a time please! Replace the previous one?';
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
