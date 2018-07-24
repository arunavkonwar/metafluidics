/*
* Metafluidics Device Post Type Media Scripts
*
* JavaScript for wp-admin functionality when editing and adding
* new Device posts media meta fields
*/

(function(window, document, $, wp) {

  'use strict';

  var frame;
  var $uploadItemsLink = $('.upload-items');
  var $deleteItemLink = $( '.delete-item');
  var frameOptions = {
    title: 'Upload Media for this Device',
    button: { text: 'Attach Media to this Device.' },
    multiple: true
  };

  // open media modal on "upload images" link click
  $uploadItemsLink.on('click', function(e){
    e.preventDefault();

    var $metaBox = $(this).closest('.postbox');
    var $uploadedItems = $metaBox.find('.uploaded-items');
    var $uploadInput = $metaBox.find('.upload-input');
    var showListItems;
    var metaID = ( $metaBox.attr('id') ).replace(/_/g, '-');

    switch ( metaID ) {
      case 'metafluidics-device-images':
        frameOptions.title = 'Upload Images for this Device';
        frameOptions.button = { text: 'Attach Images to this Device.' };
        frameOptions.multiple = true;
        showListItems = false;
        break;
      case 'metafluidics-device-design-files':
        frameOptions.title = 'Upload Design Files for this Device';
        frameOptions.button = { text: 'Attach Design Files to this Device.' };
        frameOptions.multiple = true;
        showListItems = true;
        break;
      case 'metafluidics-device-software':
        frameOptions.title = 'Upload Software for this Device';
        frameOptions.button = { text: 'Attach Software to this Device.' };
        frameOptions.multiple = true;
        showListItems = true;
        break;
      case 'metafluidics-device-bill-of-materials':
        frameOptions.title = 'Upload Bill of Materials for this Device';
        frameOptions.button = { text: 'Attach Bill of Materials to this Device.' };
        frameOptions.multiple = true;
        showListItems = true;
        break;
      case 'metafluidics-device-build-instructions':
        frameOptions.title = 'Upload Build Instructions for this Device';
        frameOptions.button = { text: 'Attach Build Instructions to this Device.' };
        frameOptions.multiple = true;
        showListItems = true;
        break;
      case 'metafluidics-device-data':
        frameOptions.title = 'Upload Data for this Device';
        frameOptions.button = { text: 'Attach Data to this Device.' };
        frameOptions.multiple = true;
        showListItems = true;
        break;
      case 'metafluidics-device-thumbnail':
        frameOptions.title = 'Upload Thumbnail for this Device';
        frameOptions.button = { text: 'Attach Thumbnail to this Device.' };
        frameOptions.multiple = false;
        showListItems = false;
        break;
      default:
        break;
    }

    frame = wp.media({
      title: frameOptions.title,
      button: {
        text: frameOptions.button.text,
      },
      multiple: frameOptions.multiple
    });

    frame.on('select', function() {
      var selection = frame.state().get('selection');
      selection.map( function(attachment){
        attachment = attachment.toJSON();

        // show list items unless images
        if ( showListItems ) {
          $uploadedItems.append('<li class="uploaded-item" id="#attachment-' + attachment.id + '"><a href="' + attachment.url + '" />' + attachment.name + ' <a class="delete-item" href="#">[X]</a></li>');
        }
        else {
          $uploadedItems.append('<p class="uploaded-item" id="#attachment-' + attachment.id + '"><img src="' + attachment.url + '" /><a class="delete-item" href="#">[X]</a></p>');
        }
      });

      $deleteItemLink = $( '.delete-item');
      $deleteItemLink.bind('click', bindDeleteItem);

      var attachmentArray = [];
      selection.models.forEach(function(element, index, array) {
        attachmentArray.push(element.id);
      });

      // update hidden input with new attachment id array
      var $currentVal = $uploadInput.val();
      if ( $currentVal ) {
        $currentVal += ',';
      }

      $uploadInput.val($currentVal + attachmentArray.join());
    });

    frame.open();
  });

  var bindDeleteItem = function(e) {
    e.preventDefault();

    var $metaBox = $(this).closest('.postbox');
    var $uploadInput = $metaBox.find('.upload-input');
    var preIDSubstring = 12;

    // remove image from gallery
    var deletedId = $(this).closest('.uploaded-item').attr('id');
    deletedId = deletedId.substring(preIDSubstring);

    $(this).parent().remove();

    // update hidden input with new attachment id array
    var attachmentArray = $uploadInput.val().split(',');

    var indexOfDeletedItem = attachmentArray.indexOf(deletedId);
    if ( indexOfDeletedItem !== -1 ) {
      attachmentArray.splice(indexOfDeletedItem, 1);
    }

    $uploadInput.val(attachmentArray.join());
  };

  // bind events to existing attachment delete links
  $deleteItemLink.bind('click', bindDeleteItem);

}(window, document, window.jQuery, window.wp));
