/*
* Metafluidics Create Device scripts
*
* Handles form elements of the Create Device page
*/

(function(window, document, $) {

  'use strict';

  var $addTagButton = $('.add-tag button');
  var $submitButton = $('#submit-device');
  var $draftButton = $('#submit-draft');
  var $isDraftInput = $('#is-draft');

  // add tag to list on "add tag" button click
  $addTagButton.on('click', function(e){
    e.preventDefault();

    var $tagNameInput = $(this).siblings('input');
    var tagName = $tagNameInput.val();
    var $tagsList = $(this).closest('.postbox').find('ul');
    var $tagsName = $(this).closest('label.tags').attr('for');

    if ( tagName ) {
      $tagsList.append('<li><label><input type="checkbox" value="' + tagName + '" checked="checked" name="' + $tagsName + '[]" /> <span>' + tagName + '</span></label></li>');
    }

    $tagNameInput.val('');
  });

  $submitButton.on('click', function(){
    // to remove required, remove from this array
    var inputsToCheck = ['#device-name','#device-description','#device-thumbnail','#device-design-files'];

    var valid = true;
    $('.postbox').removeClass('field-missing');
    $('#alert-message').removeClass('field-missing');

    $(inputsToCheck).each(function(i, id){
      if ( !$(id).val() ) {
        $(id).closest('.postbox').addClass('field-missing');
        valid = false;
      }
    });

    if ( valid ) {
      return true;
    }
    else {
      $('#alert-message').addClass('field-missing').text('You are missing some items. Scroll up and add them!');
      return false;
    }
  });

  $draftButton.on('click', function(){
    // set hidden input for draft and submit
    $isDraftInput.val('true');

    return true;
  });

}(window, document, window.jQuery));
