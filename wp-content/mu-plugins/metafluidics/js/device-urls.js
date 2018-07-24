/*
* Metafluidics Device Post Type URL Meta Scripts
*
* JavaScript for wp-admin functionality when editing and adding
* new Device posts url meta fields
*/

(function(window, document, $) {

  'use strict';

  var $urlInputs = $('.url-input');
  var $urlDelete = $('.url-delete');
  var $urlAdd = $('.url-add');

  // when delete clicked, remove that list item
  var deleteClicked = function(e){
    e.preventDefault();
    var $list = $(this).closest('ul');
    $(this).parent('li').remove();

    if ( $list.find('li').length > 0 ) {
      $urlInputs.trigger('change');
    }
    else {
      $list.siblings('.url-value').val('');
    }
  };

  // when add clicked, add new list item
  var addClicked = function(e){
    e.preventDefault();
    var $list = $(this).closest('.postbox').find('ul');

    var $newItem = $('<li><input type="text" class="url-input name" placeholder="Title" /><input type="text" class="url-input url" placeholder="URL" /> <button href="#" class="url-delete">X</button></li>');

    $list.append($newItem);

    $urlInputs = $('.url-input');
    $urlDelete = $('.url-delete');
    $urlInputs.on('change', inputChanged);
    $urlDelete.on('click', deleteClicked);
  };

  // when input value changes, update meta field input value
  var inputChanged = function(){
    var $container = $(this).closest('.postbox');
    var $listItems = $container.find('li');
    var $hiddenInput = $container.find('.url-value');
    var urlArray = [];

    // for each complete li of inputs, push to array
    $listItems.each(function( i, item ){
      var name = $(item).find('.name').val();
      var url = $(item).find('.url').val();

      if ( name !== '' && url !== '' ) {
        urlArray.push( { name:name, url:url } );
      }
    });

    $hiddenInput.val( JSON.stringify( urlArray ) );
  };

  // bind events
  $urlInputs.on('change', inputChanged);
  $urlDelete.on('click', deleteClicked);
  $urlAdd.on('click', addClicked);

}(window, document, window.jQuery));
