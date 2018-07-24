/*
* Metafluidics Single Device page scripts
*
* Handles the image carousel on the single Device post page
*/

(function(window, document, $, ajaxurl) {

  'use strict';

  // device image carousel
  var $carousel = {
    thumbnails : $('.carousel-thumbnails li'),
    originals: $('.carousel-originals li')
  };

  $carousel.originals.addClass('hidden');
  $carousel.originals.eq(0).removeClass('hidden');

  $carousel.thumbnails.click(function(e){
    var targetClass = $(this).attr('id');
    $carousel.originals.addClass('hidden');
    $carousel.originals.filter('.' + targetClass).removeClass('hidden');
    return false;
  });

  // downloads
  var $downloads = $('.download');

  $downloads.click(function(e){
    var $this = $(this);

    if ( !$this.hasClass('downloading') ) {
      // allow download to happen
      $this.addClass('downloading');
      $this.trigger('click');
    }
    else {
      // if download happened, just update count and text
      e.preventDefault();
      $this.removeClass('downloading');

      var $downloadText = $this.find('.download-count');
      var downloadData = {
        action: 'metafluidics_downloads_update',
        postID : $this.data('post'),
        parentID : $this.data('parent')
      };

      // send post id and like status to AJAX
      $.post( ajaxurl, downloadData, function( response ) {
        var json = JSON.parse(response);

        // should have number 'downloads'
        // update count
        $downloadText.html( json.downloads );

        // increment total downloads
        var $downloadsTotal = $('.total-downloads');
        $downloadsTotal.html( parseInt($downloadsTotal.text(), 10) + 1 );
      });
    }
  });

}(window, document, window.jQuery, window.ajaxurl));
