/*
* Metafluidics Search & Sort page scripts
*
* Handles the tag cloud and search input of the site.
*/

(function(window, document, $) {

  'use strict';

  var maxTags = 5;
  var $sortSelect = $('#sort-devices');

  // show/hide more tags
  var $tags = $('#call-to-search li');
  var $seeMoreLink = $('.see-more-tags');
  var $hideMoreLink = $('.hide-more-tags');

  $seeMoreLink.click(function(e){
    $tags.removeClass('hidden');
    $seeMoreLink.addClass('hidden');
    $hideMoreLink.removeClass('hidden');
  });

  $hideMoreLink.click(function(e){
    $tags.each(function(i, element){
      if ( i > maxTags - 1 ) {
        $(element).addClass('hidden');
      }
    });

    $seeMoreLink.removeClass('hidden');
    $hideMoreLink.addClass('hidden');
  });

  // sort devices
  $sortSelect.on('change', function() {
    $('#sorting').submit();
  });

}(window, document, window.jQuery ));
